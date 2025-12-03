<?php

namespace App\Imports;

use App\Models\PositionHistory;
use App\Models\Employee;
use App\Models\Company;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PositionHistoriesImport implements ToCollection, WithHeadingRow
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
                $positionName = $this->cleanValue($row['position_name'] ?? '');
                $workUnit = $this->cleanValue($row['work_unit'] ?? '');
                $company = $this->cleanValue($row['company'] ?? '');

                // Skip example/info rows
                if ($this->shouldSkipRow($rowData, $employeeNumber, $positionName, $workUnit, $company)) {
                    continue;
                }

                // Skip if essential fields are empty
                if (empty($employeeNumber) || empty($positionName) || empty($workUnit) || empty($company)) {
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
                    $this->results['failed']++;
                    continue;
                }

                // Validate date order
                if ($endDate < $startDate) {
                    $this->results['failed']++;
                    continue;
                }

                // Prepare position history data
                $positionHistoryData = [
                    'employee_id' => $employee->id,
                    'position_name' => $positionName,
                    'work_unit' => $workUnit,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'company' => $company,
                ];

                // Validate data
                $validator = Validator::make($positionHistoryData, [
                    'employee_id' => 'required|exists:employees,id',
                    'position_name' => 'required|string|max:255',
                    'work_unit' => 'required|string|max:255',
                    'start_date' => 'required|date',
                    'end_date' => 'required|date|after_or_equal:start_date',
                    'company' => 'required|string|max:255',
                ]);

                if ($validator->fails()) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                // Check for duplicate (same position history for same employee with same details)
                $existingPositionHistory = PositionHistory::where('employee_id', $employee->id)
                    ->where('position_name', $positionName)
                    ->where('work_unit', $workUnit)
                    ->where('company', $company)
                    ->where('start_date', $startDate)
                    ->first();

                $isDuplicate = !is_null($existingPositionHistory);

                // Handle import mode
                if ($this->isImportMode()) {
                    if ($isDuplicate) {
                        if ($this->duplicateAction === 'update') {
                            $existingPositionHistory->update($positionHistoryData);
                            $this->results['success']++;
                        } else {
                            $this->results['skipped']++;
                        }
                    } else {
                        PositionHistory::create($positionHistoryData);
                        $this->results['success']++;
                    }
                }

                // Add to preview data
                if (!$this->isImportMode()) {
                    $previewData = $positionHistoryData;
                    $previewData['employee_number'] = $employee->employee_number;
                    $previewData['employee_name'] = $employee->name;
                    $previewData['current_company'] = $employee->company->name ?? 'N/A';
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

    private function shouldSkipRow($rowData, $employeeNumber, $positionName, $workUnit, $company)
    {
        $rawEmployeeNumber = strtoupper(trim($rowData['employee_number'] ?? ''));
        $rawPositionName = strtoupper(trim($rowData['position_name'] ?? ''));
        $rawWorkUnit = strtoupper(trim($rowData['work_unit'] ?? ''));
        $rawCompany = strtoupper(trim($rowData['company'] ?? ''));

        if (
            str_starts_with($rawEmployeeNumber, 'CONTOH:') ||
            str_starts_with($rawPositionName, 'CONTOH:') ||
            str_starts_with($rawWorkUnit, 'CONTOH:') ||
            str_starts_with($rawCompany, 'CONTOH:')
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
