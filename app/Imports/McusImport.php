<?php

namespace App\Imports;

use App\Models\Mcu;
use App\Models\Employee;
use App\Models\Company;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class McusImport implements ToCollection, WithHeadingRow
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
                $result = $this->cleanValue($row['result'] ?? '');
                $year = $this->cleanValue($row['year'] ?? '');

                // Skip example/info rows
                if ($this->shouldSkipRow($rowData, $employeeNumber, $result, $year)) {
                    continue;
                }

                // Skip if essential fields are empty
                if (empty($employeeNumber) || empty($result) || empty($year)) {
                    $this->results['failed']++;
                    continue;
                }

                // Validate year
                if (!is_numeric($year) || strlen($year) !== 4 || $year < 2000 || $year > date('Y') + 1) {
                    $this->results['failed']++;
                    continue;
                }

                $year = (int) $year;

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

                // Prepare MCU data
                $mcuData = [
                    'employee_id' => $employee->id,
                    'result' => $result,
                    'year' => $year,
                ];

                // Validate data
                $validator = Validator::make($mcuData, [
                    'employee_id' => 'required|exists:employees,id',
                    'result' => 'required|string|max:255',
                    'year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
                ]);

                if ($validator->fails()) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                // Check for duplicate (same MCU for same employee and same year)
                $existingMcu = Mcu::where('employee_id', $employee->id)
                    ->where('year', $year)
                    ->first();

                $isDuplicate = !is_null($existingMcu);

                // Handle import mode
                if ($this->isImportMode()) {
                    if ($isDuplicate) {
                        if ($this->duplicateAction === 'update') {
                            $existingMcu->update($mcuData);
                            $this->results['success']++;
                        } else {
                            $this->results['skipped']++;
                        }
                    } else {
                        Mcu::create($mcuData);
                        $this->results['success']++;
                    }
                }

                // Add to preview data
                if (!$this->isImportMode()) {
                    $previewData = $mcuData;
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

    private function shouldSkipRow($rowData, $employeeNumber, $result, $year)
    {
        $rawEmployeeNumber = strtoupper(trim($rowData['employee_number'] ?? ''));
        $rawResult = strtoupper(trim($rowData['result'] ?? ''));
        $rawYear = strtoupper(trim($rowData['year'] ?? ''));

        if (
            str_starts_with($rawEmployeeNumber, 'CONTOH:') ||
            str_starts_with($rawResult, 'CONTOH:') ||
            str_starts_with($rawYear, 'CONTOH:')
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
