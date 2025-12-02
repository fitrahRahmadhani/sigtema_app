<?php

namespace App\Imports;

use App\Models\Position;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PositionsImport implements ToCollection, WithHeadingRow
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
                $positionCode = $this->cleanValue($row['position_code'] ?? '');
                $name = $this->cleanValue($row['name'] ?? '');
                $jobGrade = $this->cleanValue($row['job_grade'] ?? '');
                $companyIdRaw = $this->cleanValue($row['company_id'] ?? '');
                $employeeNumber = $this->cleanValue($row['employee_number'] ?? '');

                // Skip example/info rows
                if ($this->shouldSkipRow($rowData, $positionCode, $name, $jobGrade, $companyIdRaw)) {
                    continue;
                }

                // Skip if essential fields are empty
                if (empty($positionCode) || empty($name) || empty($jobGrade)) {
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

                // Get employee ID if employee number provided
                $employeeId = null;
                if (!empty($employeeNumber)) {
                    $employee = Employee::where('employee_number', $employeeNumber)
                        ->where('company_id', $companyId)
                        ->first();
                    $employeeId = $employee ? $employee->id : null;
                }

                // Prepare position data
                $positionData = [
                    'company_id' => $companyId,
                    'employee_id' => $employeeId,
                    'slug' => Str::uuid(),
                    'position_code' => $positionCode,
                    'name' => $name,
                    'directorate' => $this->cleanValue($row['directorate'] ?? null),
                    'department' => $this->cleanValue($row['department'] ?? null),
                    'biro' => $this->cleanValue($row['biro'] ?? null),
                    'section' => $this->cleanValue($row['section'] ?? null),
                    'location' => $this->cleanValue($row['location'] ?? null),
                    'job_grade' => $jobGrade,
                ];

                // Validate data
                $validator = Validator::make($positionData, [
                    'company_id' => 'required|exists:companies,id',
                    'employee_id' => 'nullable|exists:employees,id',
                    'position_code' => 'required|string|max:255',
                    'name' => 'required|string|max:255',
                    'directorate' => 'nullable|string|max:255',
                    'department' => 'nullable|string|max:255',
                    'biro' => 'nullable|string|max:255',
                    'section' => 'nullable|string|max:255',
                    'location' => 'nullable|string|max:255',
                    'job_grade' => 'required|string|max:255',
                ]);

                if ($validator->fails()) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                // Check for duplicate (position_code within same company)
                $existingPosition = Position::where('position_code', $positionData['position_code'])
                    ->where('company_id', $positionData['company_id'])
                    ->first();

                $isDuplicate = !is_null($existingPosition);

                if ($isDuplicate && !in_array($positionData['position_code'], $this->duplicates)) {
                    $this->duplicates[] = $positionData['position_code'];
                }

                // Handle import mode
                if ($this->isImportMode()) {
                    if ($isDuplicate) {
                        if ($this->duplicateAction === 'update') {
                            $updateData = $positionData;
                            unset($updateData['slug']);

                            // Only update if there are changes
                            if (
                                $existingPosition->employee_id != $updateData['employee_id'] ||
                                $existingPosition->name != $updateData['name'] ||
                                $existingPosition->directorate != $updateData['directorate'] ||
                                $existingPosition->department != $updateData['department'] ||
                                $existingPosition->biro != $updateData['biro'] ||
                                $existingPosition->section != $updateData['section'] ||
                                $existingPosition->location != $updateData['location'] ||
                                $existingPosition->job_grade != $updateData['job_grade']
                            ) {

                                $existingPosition->update($updateData);
                                $this->results['updated']++;
                            } else {
                                $this->results['skipped']++;
                            }
                        } else {
                            $this->results['skipped']++;
                        }
                    } else {
                        Position::create($positionData);
                        $this->results['success']++;
                    }
                }

                // Add to preview data
                if (!$this->isImportMode()) {
                    $previewData = $positionData;
                    $previewData['employee_name'] = $employee ? $employee->name : null;
                    unset($previewData['slug']);
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

    private function shouldSkipRow($rowData, $positionCode, $name, $jobGrade, $companyIdRaw)
    {
        $rawPositionCode = strtoupper(trim($rowData['position_code'] ?? ''));
        $rawName = strtoupper(trim($rowData['name'] ?? ''));
        $rawJobGrade = strtoupper(trim($rowData['job_grade'] ?? ''));
        $rawCompanyId = strtoupper(trim($rowData['company_id'] ?? ''));

        if (
            str_starts_with($rawPositionCode, 'CONTOH:') ||
            str_starts_with($rawName, 'CONTOH:') ||
            str_starts_with($rawJobGrade, 'CONTOH:') ||
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
