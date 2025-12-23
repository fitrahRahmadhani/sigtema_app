<?php

namespace App\Imports;

use App\Models\Position;
use App\Models\PositionRequirementSetting;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Masmerise\Toaster\Toaster;

class PositionRequirementsImport implements ToCollection, WithHeadingRow
{
    private $data = [];
    private $results = [
        'success' => 0,
        'failed' => 0,
        'skipped' => 0
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
                $positionIdRaw = $this->cleanValue($row['position_id'] ?? '');
                $name = $this->cleanValue($row['name'] ?? '');
                $value = $this->cleanValue($row['value'] ?? '');

                // Skip example/info rows
                if ($this->shouldSkipRow($rowData, $positionIdRaw, $name)) {
                    continue;
                }

                // Skip if essential fields are empty
                if (empty($positionIdRaw) || empty($name)) {
                    $this->results['skipped']++;
                    continue;
                }

                // Get position ID
                $positionId = $this->getPositionId($positionIdRaw, $user);
                if (!$positionId) {
                    $this->results['failed']++;
                    continue;
                }

                // Get position to get position_code
                $position = Position::find($positionId);
                if (!$position) {
                    $this->results['failed']++;
                    continue;
                }

                // Prepare requirement data
                $requirementData = [
                    'position_id' => $positionId,
                    'position_code' => $position->position_code,
                    'name' => $name,
                    'value' => $value
                ];

                // Validate data
                $validator = Validator::make($requirementData, [
                    'position_id' => 'required|exists:positions,id',
                    'position_code' => 'required|string|max:255',
                    'name' => 'required|string|max:255',
                    'value' => 'nullable|string'
                ]);

                if ($validator->fails()) {
                    $this->results['failed']++;
                    continue;
                }

                // Check for duplicate (same position_id and name combination)
                $existingRequirement = PositionRequirementSetting::where('position_id', $positionId)
                    ->where('name', $name)
                    ->first();

                $isDuplicate = !is_null($existingRequirement);

                // Handle import mode
                if ($this->isImportMode()) {
                    if ($isDuplicate) {
                        // Update existing requirement
                        $existingRequirement->update([
                            'value' => $value
                        ]);
                        $this->results['success']++; // Count as success for update
                    } else {
                        // Create new requirement
                        PositionRequirementSetting::create($requirementData);
                        $this->results['success']++;
                    }
                }

                // Add to preview data
                if (!$this->isImportMode()) {
                    $previewData = $requirementData;
                    $previewData['position_name'] = $position->name;
                    $previewData['position_code_display'] = $position->position_code;
                    $previewData['company_name'] = $position->company->name ?? 'N/A';
                    $previewData['is_duplicate'] = $isDuplicate;
                    $processedData[] = $previewData;
                }
            } catch (\Exception $e) {
                $this->results['failed']++;
                continue;
            }
        }

        if (!$this->isImportMode()) {
            $this->data = $processedData;
        }
    }

    private function shouldSkipRow($rowData, $positionIdRaw, $name)
    {
        $rawPositionId = strtoupper(trim($rowData['position_id'] ?? ''));
        $rawName = strtoupper(trim($rowData['name'] ?? ''));

        if (
            str_starts_with($rawPositionId, 'CONTOH:') ||
            str_starts_with($rawName, 'CONTOH:')
        ) {
            return true;
        }

        $firstColumn = strtolower($positionIdRaw);

        if (
            str_contains($firstColumn, 'informasi') ||
            str_contains($firstColumn, 'penting') ||
            str_contains($firstColumn, 'aturan') ||
            str_contains($firstColumn, 'position id') ||
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

    private function getPositionId($positionIdRaw, $user)
    {
        $positionId = $this->cleanValue($positionIdRaw);

        if (empty($positionId)) {
            return null;
        }

        $position = Position::find($positionId);

        if (!$position) {
            return null;
        }

        // For non-superadmin, check if position belongs to their company
        if (!is_null($user->company_id) && $position->company_id != $user->company_id) {
            return null;
        }

        return $positionId;
    }

    private function isImportMode()
    {
        return request()->has('import');
    }

    public function getData()
    {
        return $this->data;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getResults()
    {
        return $this->results;
    }
}
