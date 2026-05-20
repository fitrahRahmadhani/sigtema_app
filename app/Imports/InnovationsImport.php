<?php

namespace App\Imports;

use App\Models\Innovation;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InnovationsImport implements ToCollection, WithHeadingRow
{
    private $data = [];
    private $errors = [];
    private $employeeNotFound = [];
    private $duplicateAction = null;
    private $results = [
        'success' => 0,
        'skipped' => 0,
        'failed' => 0,
        'employee_not_found' => 0
    ];

    public function collection(Collection $rows)
    {
        $user = Auth::user();
        $processedData = [];
        $employeeCache = [];

        foreach ($rows as $index => $row) {
            try {
                $rowData = $row->toArray();

                // Clean the data
                $employeeNumber = $this->cleanValue($row['employee_number'] ?? '');
                $title = $this->cleanValue($row['title'] ?? '');
                $team = $this->cleanValue($row['team'] ?? '');
                $category = $this->cleanValue($row['category'] ?? '');
                $theme = $this->cleanValue($row['theme'] ?? '');
                $event = $this->cleanValue($row['event'] ?? '');
                $replication = $this->cleanValue($row['replication'] ?? '');
                $status = $this->cleanValue($row['status'] ?? '');
                $achievement = $this->cleanValue($row['achievement'] ?? '');
                $isActive = $this->cleanValue($row['is_active'] ?? '1');

                // Skip example/info rows
                if ($this->shouldSkipRow($rowData, $employeeNumber, $title)) {
                    continue;
                }

                // Skip if essential fields are empty
                if (empty($employeeNumber) || empty($title)) {
                    $this->results['failed']++;
                    continue;
                }

                // Get employee data
                $employee = $this->getEmployee($employeeNumber, $user, $employeeCache);

                if (!$employee) {
                    if ($this->isImportMode()) {
                        $this->results['employee_not_found']++;
                    }
                    if (!in_array($employeeNumber, $this->employeeNotFound)) {
                        $this->employeeNotFound[] = $employeeNumber;
                    }
                    continue;
                }

                // Map replication value
                $replicationValue = $this->mapReplication($replication);

                // Prepare Innovation data
                $innovationData = [
                    'employee_id' => $employee->id,
                    'employee_number' => $employeeNumber,
                    'title' => $title,
                    'team' => $team ?: null,
                    'category' => $category ?: null,
                    'theme' => $theme ?: null,
                    'event' => $event ?: null,
                    'replication' => $replicationValue,
                    'status' => $status ?: null,
                    'achievement' => $achievement ?: null,
                    'source' => 'internal',
                    'is_active' => $this->mapIsActive($isActive),
                ];

                // Validate data
                $validator = Validator::make($innovationData, [
                    'employee_id' => 'required|exists:employees,id',
                    'employee_number' => 'nullable|string|max:100',
                    'title' => 'required|string|max:500',
                    'team' => 'nullable|string|max:255',
                    'category' => 'nullable|string|max:255',
                    'theme' => 'nullable|string|max:255',
                    'event' => 'nullable|string|max:255',
                    'replication' => 'nullable|string',
                    'status' => 'nullable|string|max:100',
                    'achievement' => 'nullable|string|max:500',
                    'source' => 'nullable|string',
                    'is_active' => 'boolean',
                ]);

                if ($validator->fails()) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                // Check for duplicate (same title for same employee)
                $existingInnovation = Innovation::where('employee_id', $employee->id)
                    ->where('title', $title)
                    ->first();

                $isDuplicate = !is_null($existingInnovation);

                // Handle import mode
                if ($this->isImportMode()) {
                    if ($isDuplicate) {
                        if ($this->duplicateAction === 'update') {
                            $existingInnovation->update($innovationData);
                            $this->results['success']++;
                        } else {
                            $this->results['skipped']++;
                        }
                    } else {
                        Innovation::create($innovationData);
                        $this->results['success']++;
                    }
                }

                // Add to preview data
                if (!$this->isImportMode()) {
                    $previewData = $innovationData;
                    $previewData['employee_name'] = $employee->name;
                    $previewData['company_name'] = $employee->company->name ?? 'N/A';
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

    private function shouldSkipRow($rowData, $employeeNumber, $title)
    {
        $rawEmployeeNumber = strtoupper(trim($rowData['employee_number'] ?? ''));
        $rawTitle = strtoupper(trim($rowData['title'] ?? ''));

        if (
            str_starts_with($rawEmployeeNumber, 'CONTOH:') ||
            str_starts_with($rawTitle, 'CONTOH:')
        ) {
            return true;
        }

        $firstColumn = strtolower($employeeNumber);

        if (
            str_contains($firstColumn, 'informasi') ||
            str_contains($firstColumn, 'penting') ||
            str_contains($firstColumn, 'aturan') ||
            str_contains($firstColumn, 'employee number') ||
            str_contains($firstColumn, 'title') ||
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

    private function mapReplication($value)
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        $value = strtolower(trim($value));

        // Map from human-readable to database values
        if ($value === 'bisa direplikasi') {
            return 'sudah';
        } elseif ($value === 'tidak bisa direplikasi') {
            return 'belum';
        }

        // Return as-is if it doesn't match known values
        return $value;
    }

    private function mapIsActive($value)
    {
        if (is_null($value) || $value === '') {
            return true;
        }

        $value = (string) $value;

        // Convert to boolean
        $value = trim(strtolower($value));

        if (in_array($value, ['1', 'true', 'yes', 'aktif', 'active', 'ya'])) {
            return true;
        }

        return false;
    }

    private function getEmployee($employeeNumber, $user, &$employeeCache)
    {
        // Check cache first
        if (isset($employeeCache[$employeeNumber])) {
            return $employeeCache[$employeeNumber];
        }

        $employee = null;

        if (!is_null($user->company_id)) {
            // Non-superadmin: hanya bisa akses employee di company mereka
            $employee = Employee::with('company')
                ->where('employee_number', $employeeNumber)
                ->where('company_id', $user->company_id)
                ->first();
        } else {
            // Superadmin: bisa akses semua employee
            $employee = Employee::with('company')
                ->where('employee_number', $employeeNumber)
                ->first();
        }

        // Cache the result
        if ($employee) {
            $employeeCache[$employeeNumber] = $employee;
        }

        return $employee;
    }

    private function isImportMode()
    {
        return !is_null($this->duplicateAction);
    }

    public function getData()
    {
        return $this->data;
    }

    public function getEmployeeNotFound()
    {
        return array_unique($this->employeeNotFound);
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