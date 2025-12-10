<?php

namespace App\Imports;

use App\Models\PsychogramAspect;
use App\Models\PsychogramResult;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PsychogramImport implements ToCollection, WithHeadingRow
{
    private $data = [];
    private $errors = [];
    private $employeeNotFound = [];
    private $aspectNotFound = [];
    private $duplicateAction = null;
    private $results = [
        'success' => 0,
        'skipped' => 0,
        'failed' => 0,
        'employee_not_found' => 0,
        'aspect_not_found' => 0
    ];

    public function collection(Collection $rows)
    {
        $user = Auth::user();
        $processedData = [];
        $employeeCache = [];
        $aspectCache = [];

        foreach ($rows as $index => $row) {
            try {
                $rowData = $row->toArray();

                // Clean the data
                $employeeCode = $this->cleanValue($row['employee_number'] ?? '');
                $aspectName = $this->cleanValue($row['aspect_name'] ?? '');
                $score = $this->cleanValue($row['score'] ?? '');

                // Skip example/info rows
                if ($this->shouldSkipRow($rowData, $employeeCode, $aspectName)) {
                    continue;
                }

                // Skip if essential fields are empty
                if (empty($employeeCode) || empty($aspectName) || $score === '') {
                    $this->results['failed']++;
                    continue;
                }

                // Validate score
                if (!is_numeric($score) || $score < 0 || $score > 100) {
                    $this->results['failed']++;
                    continue;
                }

                // Get employee data
                $employee = $this->getEmployee($employeeCode, $user, $employeeCache);

                if (!$employee) {
                    if ($this->isImportMode()) {
                        $this->results['employee_not_found']++;
                    }
                    if (!in_array($employeeCode, $this->employeeNotFound)) {
                        $this->employeeNotFound[] = $employeeCode;
                    }
                    continue;
                }

                // Get aspect data
                $aspect = $this->getAspect($aspectName, $user, $employee->company_id, $aspectCache);
                if (!$aspect) {
                    if ($this->isImportMode()) {
                        $this->results['aspect_not_found']++;
                    }
                    if (!in_array($aspectName, $this->aspectNotFound)) {
                        $this->aspectNotFound[] = $aspectName;
                    }
                    continue;
                }

                // Prepare psychogram data
                $psychogramData = [
                    'employee_id' => $employee->id,
                    'aspect_id' => $aspect->id,
                    'score' => (int) $score,
                ];

                // Validate data
                $validator = Validator::make($psychogramData, [
                    'employee_id' => 'required|exists:employees,id',
                    'aspect_id' => 'required|exists:psychogram_aspects,id',
                    'score' => 'required|integer|min:0|max:100',
                ]);

                if ($validator->fails()) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                // Check for duplicate (same aspect for same employee)
                $existingPsychogram = PsychogramResult::where('employee_id', $employee->id)
                    ->where('aspect_id', $aspect->id)
                    ->first();

                $isDuplicate = !is_null($existingPsychogram);

                // Handle import mode
                if ($this->isImportMode()) {
                    if ($isDuplicate) {
                        if ($this->duplicateAction === 'update') {
                            $existingPsychogram->update($psychogramData);
                            $this->results['success']++;
                        } else {
                            $this->results['skipped']++;
                        }
                    } else {
                        PsychogramResult::create($psychogramData);
                        $this->results['success']++;
                    }
                }

                // Add to preview data
                if (!$this->isImportMode()) {
                    $previewData = $psychogramData;
                    $previewData['employee_number'] = $employee->employee_number;
                    $previewData['employee_name'] = $employee->name;
                    $previewData['company_name'] = $employee->company->name ?? 'N/A';
                    $previewData['aspect_name'] = $aspect->aspect_name;
                    $previewData['aspect_code'] = $aspect->aspect_code;
                    $previewData['score'] = $score;
                    $previewData['is_duplicate'] = $isDuplicate;
                    $previewData['existing_score'] = $isDuplicate ? $existingPsychogram->score : null;
                    $previewData['aspect_type'] = is_null($aspect->company_id) ? 'Global' : 'Company';

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

    private function shouldSkipRow($rowData, $employeeCode, $aspectName)
    {
        $rawEmployeeCode = strtoupper(trim($rowData['employee_number'] ?? ''));
        $rawAspectName = strtoupper(trim($rowData['aspect_name'] ?? ''));

        if (
            str_starts_with($rawEmployeeCode, 'CONTOH:') ||
            str_starts_with($rawAspectName, 'CONTOH:')
        ) {
            return true;
        }

        $firstColumn = strtolower($employeeCode);

        if (
            str_contains($firstColumn, 'informasi') ||
            str_contains($firstColumn, 'penting') ||
            str_contains($firstColumn, 'aturan') ||
            str_contains($firstColumn, 'employee code') ||
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

    private function getEmployee($employeeCode, $user, &$employeeCache)
    {
        // Check cache first
        if (isset($employeeCache[$employeeCode])) {
            return $employeeCache[$employeeCode];
        }

        $employee = null;

        if (!is_null($user->company_id)) {
            // Non-superadmin: hanya bisa akses employee di company mereka
            $employee = Employee::with('company')
                ->where('employee_number', $employeeCode)
                ->where('company_id', $user->company_id)
                ->first();
        } else {
            // Superadmin: bisa akses semua employee
            $employee = Employee::with('company')
                ->where('employee_number', $employeeCode)
                ->first();
        }

        // Cache the result
        if ($employee) {
            $employeeCache[$employeeCode] = $employee;
        }

        return $employee;
    }

    private function getAspect($aspectName, $user, $employeeCompanyId, &$aspectCache)
    {
        $cacheKey = $aspectName . '_' . $user->company_id . '_' . $employeeCompanyId;

        if (isset($aspectCache[$cacheKey])) {
            return $aspectCache[$cacheKey];
        }

        $aspect = null;

        // Cari aspect berdasarkan aspect_name atau aspect_code
        // Untuk non-superadmin: hanya bisa pakai global atau aspects dari company mereka
        // Untuk superadmin: bisa pakai global atau aspects dari employee's company
        if (!is_null($user->company_id)) {
            $aspect = PsychogramAspect::active()
                ->where(function ($query) use ($aspectName, $user) {
                    $query->whereNull('company_id')
                        ->orWhere('company_id', $user->company_id);
                })
                ->where(function ($query) use ($aspectName) {
                    $query->where('aspect_name', $aspectName)
                        ->orWhere('aspect_code', $aspectName);
                })
                ->first();
        } else {
            $aspect = PsychogramAspect::active()
                ->where(function ($query) use ($aspectName, $employeeCompanyId) {
                    $query->whereNull('company_id')
                        ->orWhere('company_id', $employeeCompanyId);
                })
                ->where(function ($query) use ($aspectName) {
                    $query->where('aspect_name', $aspectName)
                        ->orWhere('aspect_code', $aspectName);
                })
                ->first();
        }

        // Cache the result
        if ($aspect) {
            $aspectCache[$cacheKey] = $aspect;
        }

        return $aspect;
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

    public function getAspectNotFound()
    {
        return array_unique($this->aspectNotFound);
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
