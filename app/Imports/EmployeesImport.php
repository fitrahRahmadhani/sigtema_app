<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Company;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EmployeesImport implements ToCollection, WithHeadingRow
{
    private $data = [];
    private $duplicates = [];
    private $duplicateAction = null;
    private $results = [
        'success' => 0,
        'updated' => 0,
        'skipped' => 0,
        'failed' => 0
    ];
    private $errors = [];
    private $existingEmployees = [];
    private $activeCompanyIds = [];

    public function collection(Collection $rows)
    {
        $user = Auth::user();

        // OPTIMASI 1: Load semua data yang dibutuhkan di awal (eager loading)
        $this->preloadData($user);

        $processedData = [];
        $toInsert = [];
        $toUpdate = [];

        foreach ($rows as $index => $row) {
            try {
                $rowData = $row->toArray();

                // Clean the data
                $employeeNumber = $this->cleanValue($row['employee_number'] ?? '');
                $name = $this->cleanValue($row['name'] ?? '');
                $email = $this->cleanValue($row['email'] ?? '');
                $companyIdRaw = $this->cleanValue($row['company_id'] ?? '');

                // Skip example/info rows
                if ($this->shouldSkipRow($rowData, $employeeNumber, $name, $email, $companyIdRaw)) {
                    continue;
                }

                // Skip if essential fields are empty
                if (empty($employeeNumber) || empty($name) || empty($email)) {
                    continue;
                }

                // Get company ID (now uses cached data)
                $companyId = $this->getCompanyId($row, $user);
                if (!$companyId) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                // OPTIMASI 2: Hanya hash password saat import mode, tidak saat preview
                $passwordHash = null;
                if ($this->isImportMode()) {
                    $passwordHash = $this->getPasswordHash($this->cleanValue($row['password'] ?? null));
                }

                // Prepare employee data
                $employeeData = [
                    'company_id' => $companyId,
                    'slug' => Str::uuid(),
                    'employee_number' => $employeeNumber,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $this->cleanValue($row['phone'] ?? null),
                    'birth_date' => $this->parseDate($this->cleanValue($row['birth_date'] ?? null)),
                    'gender' => $this->validateGender($this->cleanValue($row['gender'] ?? null)),
                    'personal_grade' => $this->cleanValue($row['personal_grade'] ?? ''),
                    'band' => $this->cleanValue($row['band'] ?? ''),
                    'hire_date' => $this->parseDate($this->cleanValue($row['hire_date'] ?? null)),
                    'profile_path' => $this->cleanValue($row['profile_path'] ?? null),
                ];

                if ($this->isImportMode()) {
                    $employeeData['password'] = $passwordHash;
                }

                // Validate data
                $rules = [
                    'company_id' => 'required|exists:companies,id',
                    'employee_number' => 'required|string|max:255',
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|max:255',
                    'phone' => 'nullable|string|max:20',
                    'birth_date' => 'nullable|date',
                    'gender' => 'required|in:Male,Female',
                    'personal_grade' => 'required|string|max:255',
                    'band' => 'required|numeric|min:1|max:5',
                    'hire_date' => 'nullable|date',
                    'profile_path' => 'nullable|string|max:255',
                ];

                $validator = Validator::make($employeeData, $rules);

                if ($validator->fails()) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                // OPTIMASI 3: Check duplicate menggunakan cached data, bukan query
                $duplicateKey = $companyId . '_' . $employeeNumber;
                $isDuplicate = isset($this->existingEmployees[$duplicateKey]);

                if ($isDuplicate && !in_array($employeeNumber, $this->duplicates)) {
                    $this->duplicates[] = $employeeNumber;
                }

                // Handle import mode dengan batch operations
                if ($this->isImportMode()) {
                    if ($isDuplicate) {
                        if ($this->duplicateAction === 'update') {
                            $updateData = $employeeData;
                            unset($updateData['slug'], $updateData['password']);
                            $updateData['id'] = $this->existingEmployees[$duplicateKey];
                            $toUpdate[] = $updateData;
                            $this->results['updated']++;
                        } else {
                            $this->results['skipped']++;
                        }
                    } else {
                        // Tambahkan timestamps
                        $employeeData['created_at'] = now();
                        $employeeData['updated_at'] = now();
                        $toInsert[] = $employeeData;
                        $this->results['success']++;
                    }
                }

                // Add to preview data
                if (!$this->isImportMode()) {
                    $previewData = $employeeData;
                    unset($previewData['password'], $previewData['slug']);
                    $processedData[] = $previewData;
                }
            } catch (\Exception $e) {
                if ($this->isImportMode()) {
                    $this->results['failed']++;
                }
                continue;
            }
        }

        // OPTIMASI 4: Batch insert dan update
        if ($this->isImportMode()) {
            if (!empty($toInsert)) {
                // Insert dalam chunks untuk menghindari query terlalu besar
                foreach (array_chunk($toInsert, 500) as $chunk) {
                    Employee::insert($chunk);
                }
            }

            if (!empty($toUpdate)) {
                // Batch update menggunakan DB transaction
                DB::transaction(function () use ($toUpdate) {
                    foreach ($toUpdate as $updateData) {
                        Employee::where('id', $updateData['id'])->update(
                            collect($updateData)->except('id')->toArray()
                        );
                    }
                });
            }
        }

        if (!$this->isImportMode()) {
            $this->data = $processedData;
        }
    }

    /**
     * OPTIMASI: Preload semua data yang dibutuhkan di awal
     */
    private function preloadData($user)
    {
        // Load active company IDs
        $this->activeCompanyIds = Company::where('is_active', true)
            ->pluck('id')
            ->toArray();

        // Load existing employees untuk check duplicate
        $query = Employee::select('id', 'employee_number', 'company_id');

        // Jika bukan superadmin, hanya load dari company user
        if (!is_null($user->company_id)) {
            $query->where('company_id', $user->company_id);
        }

        $employees = $query->get();

        // Buat lookup table untuk O(1) duplicate check
        foreach ($employees as $emp) {
            $key = $emp->company_id . '_' . $emp->employee_number;
            $this->existingEmployees[$key] = $emp->id;
        }
    }

    private function shouldSkipRow($rowData, $employeeNumber, $name, $email, $companyIdRaw)
    {
        $rawEmployeeNumber = strtoupper(trim($rowData['employee_number'] ?? ''));
        $rawName = strtoupper(trim($rowData['name'] ?? ''));
        $rawEmail = strtoupper(trim($rowData['email'] ?? ''));
        $rawCompanyId = strtoupper(trim($rowData['company_id'] ?? ''));

        if (
            str_starts_with($rawEmployeeNumber, 'CONTOH:') ||
            str_starts_with($rawName, 'CONTOH:') ||
            str_starts_with($rawEmail, 'CONTOH:') ||
            str_starts_with($rawCompanyId, 'CONTOH:')
        ) {
            return true;
        }

        $firstColumn = strtolower($companyIdRaw);

        if (
            str_contains($firstColumn, 'informasi') ||
            str_contains($firstColumn, 'penting') ||
            str_contains($firstColumn, 'aturan') ||
            str_contains($firstColumn, 'company id') ||
            empty($firstColumn)
        ) {
            return true;
        }

        return false;
    }

    private function cleanValue($value)
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        $value = (string) $value;

        $patterns = ['CONTOH: ', 'contoh: ', 'Contoh: ', 'CONTOH:', 'contoh:', 'Contoh:'];
        foreach ($patterns as $pattern) {
            if (str_starts_with($value, $pattern)) {
                $value = substr($value, strlen($pattern));
                break;
            }
        }

        return trim($value);
    }

    private function getCompanyId($row, $user)
    {
        if (!is_null($user->company_id)) {
            return $user->company_id;
        }

        $companyId = $this->cleanValue($row['company_id'] ?? null);

        if (empty($companyId)) {
            return null;
        }

        // OPTIMASI: Gunakan cached data, bukan query
        return in_array($companyId, $this->activeCompanyIds) ? $companyId : null;
    }

    private function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        try {
            if (is_numeric($date)) {
                $unixDate = ($date - 25569) * 86400;
                return \Carbon\Carbon::createFromTimestamp($unixDate)->format('Y-m-d');
            }

            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function validateGender($gender)
    {
        if (empty($gender)) {
            return 'Male';
        }

        $gender = ucfirst(strtolower(trim($gender)));
        return in_array($gender, ['Male', 'Female']) ? $gender : 'Male';
    }

    /**
     * OPTIMASI: Hanya hash saat import mode
     */
    private function getPasswordHash($password)
    {
        if (empty($password)) {
            return Hash::make('password123');
        }
        return Hash::make($password);
    }

    private function isImportMode()
    {
        return !is_null($this->duplicateAction);
    }

    public function getData()
    {
        return $this->data;
    }

    public function getDuplicates()
    {
        return array_unique($this->duplicates);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setDuplicateAction($action)
    {
        $this->duplicateAction = in_array($action, ['skip', 'update']) ? $action : 'skip';
        return $this;
    }

    public function getResults()
    {
        return $this->results;
    }
}
