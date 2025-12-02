<?php

namespace App\Imports;

use App\Models\Education;
use App\Models\Employee;
use App\Models\Company;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EducationsImport implements ToCollection, WithHeadingRow
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
                $institutionName = $this->cleanValue($row['institution_name'] ?? '');
                $major = $this->cleanValue($row['major'] ?? '');
                $degree = $this->cleanValue($row['degree'] ?? '');

                // Skip example/info rows
                if ($this->shouldSkipRow($rowData, $employeeNumber, $institutionName, $major, $degree)) {
                    continue;
                }

                // Skip if essential fields are empty
                if (empty($employeeNumber) || empty($institutionName) || empty($major) || empty($degree)) {
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

                // Parse dates
                $startDate = $this->parseDate($this->cleanValue($row['start_date'] ?? null));
                $endDate = $this->parseDate($this->cleanValue($row['end_date'] ?? null));

                // Validate dates
                if (!$startDate || !$endDate) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                // Validate date order
                if ($endDate < $startDate) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                // Prepare education data
                $educationData = [
                    'employee_id' => $employee->id,
                    'institution_name' => $institutionName,
                    'major' => $major,
                    'degree' => $degree,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ];

                // Validate data
                $validator = Validator::make($educationData, [
                    'employee_id' => 'required|exists:employees,id',
                    'institution_name' => 'required|string|max:255',
                    'major' => 'required|string|max:255',
                    'degree' => 'required|string|max:100',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                ]);

                if ($validator->fails()) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                // Check for duplicate (same education for same employee)
                $existingEducation = Education::where('employee_id', $employee->id)
                    ->where('institution_name', $institutionName)
                    ->where('major', $major)
                    ->where('degree', $degree)
                    ->first();

                $isDuplicate = !is_null($existingEducation);

                // Handle import mode
                if ($this->isImportMode()) {
                    if ($isDuplicate) {
                        if ($this->duplicateAction === 'update') {
                            $existingEducation->update($educationData);
                            $this->results['success']++;
                        } else {
                            $this->results['skipped']++;
                        }
                    } else {
                        Education::create($educationData);
                        $this->results['success']++;
                    }
                }

                // Add to preview data
                if (!$this->isImportMode()) {
                    $previewData = $educationData;
                    $previewData['employee_number'] = $employee->employee_number;
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

    private function shouldSkipRow($rowData, $employeeNumber, $institutionName, $major, $degree)
    {
        $rawEmployeeNumber = strtoupper(trim($rowData['employee_number'] ?? ''));
        $rawInstitutionName = strtoupper(trim($rowData['institution_name'] ?? ''));
        $rawMajor = strtoupper(trim($rowData['major'] ?? ''));
        $rawDegree = strtoupper(trim($rowData['degree'] ?? ''));

        if (
            str_starts_with($rawEmployeeNumber, 'CONTOH:') ||
            str_starts_with($rawInstitutionName, 'CONTOH:') ||
            str_starts_with($rawMajor, 'CONTOH:') ||
            str_starts_with($rawDegree, 'CONTOH:')
        ) {
            return true;
        }

        $firstColumn = strtolower($employeeNumber);

        if (
            str_contains($firstColumn, 'informasi') ||
            str_contains($firstColumn, 'penting') ||
            str_contains($firstColumn, 'aturan') ||
            str_contains($firstColumn, 'employee number') ||
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

    private function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        try {
            if (is_numeric($date)) {
                // Handle Excel serial date
                $unixDate = ($date - 25569) * 86400;
                return Carbon::createFromTimestamp($unixDate)->format('Y-m-d');
            }

            // Try multiple date formats
            $formats = [
                'Y-m-d',
                'd/m/Y',
                'm/d/Y',
                'd-m-Y',
                'm-d-Y',
                'Y/m/d',
            ];

            foreach ($formats as $format) {
                try {
                    $parsedDate = Carbon::createFromFormat($format, $date);
                    if ($parsedDate) {
                        return $parsedDate->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            // Try Carbon parse as fallback
            return Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
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
