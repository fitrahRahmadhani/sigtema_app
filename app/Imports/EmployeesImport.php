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

    public function collection(Collection $rows)
    {
        $user = Auth::user();
        $processedData = [];

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

                // Get company ID
                $companyId = $this->getCompanyId($row, $user);
                if (!$companyId) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
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
                    'password' => $this->getPassword($this->cleanValue($row['password'] ?? null)),
                    'personal_grade' => $this->cleanValue($row['personal_grade'] ?? ''),
                    'band' => $this->cleanValue($row['band'] ?? ''),
                    'hire_date' => $this->parseDate($this->cleanValue($row['hire_date'] ?? null)),
                    'profile_path' => $this->cleanValue($row['profile_path'] ?? null),
                ];

                // Validate data
                $validator = Validator::make($employeeData, [
                    'company_id' => 'required|exists:companies,id',
                    'employee_number' => 'required|string|max:255',
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|max:255',
                    'phone' => 'nullable|string|max:20',
                    'birth_date' => 'nullable|date',
                    'gender' => 'required|in:Male,Female',
                    'personal_grade' => 'required|string|max:255',
                    'band' => 'required|string|max:255',
                    'hire_date' => 'nullable|date',
                    'profile_path' => 'nullable|string|max:255',
                ]);

                if ($validator->fails()) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                // Check for duplicate
                $existingEmployee = Employee::where('employee_number', $employeeData['employee_number'])
                    ->where('company_id', $employeeData['company_id'])
                    ->first();

                $isDuplicate = !is_null($existingEmployee);

                if ($isDuplicate && !in_array($employeeData['employee_number'], $this->duplicates)) {
                    $this->duplicates[] = $employeeData['employee_number'];
                }

                // Handle import mode
                if ($this->isImportMode()) {
                    if ($isDuplicate) {
                        if ($this->duplicateAction === 'update') {
                            $updateData = $employeeData;
                            unset($updateData['slug'], $updateData['password']);
                            $existingEmployee->update($updateData);
                            $this->results['updated']++;
                        } else {
                            $this->results['skipped']++;
                        }
                    } else {
                        Employee::create($employeeData);
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

        if (!$this->isImportMode()) {
            $this->data = $processedData;
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

        if (Company::where('id', $companyId)->where('is_active', true)->exists()) {
            return $companyId;
        }

        return null;
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

    private function getPassword($password)
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
