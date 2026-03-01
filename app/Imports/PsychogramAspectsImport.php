<?php

namespace App\Imports;

use App\Models\PsychogramAspect;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PsychogramAspectsImport implements ToCollection, WithHeadingRow
{
    private $data = [];
    private $errors = [];
    private $companyNotFound = [];
    private $duplicateAction = null;
    private $results = [
        'success'           => 0,
        'skipped'           => 0,
        'failed'            => 0,
        'company_not_found' => 0,
    ];

    public function collection(Collection $rows)
    {
        $user          = Auth::user();
        $userCompanyId = $user->company_id; // null = superadmin
        $isSuperadmin  = is_null($userCompanyId);
        $processedData = [];

        foreach ($rows as $index => $row) {
            try {
                $rowData = $row->toArray();

                // Clean the data
                $companyIdRaw = $this->cleanValue($row['company_id'] ?? '');
                $aspectCode   = $this->cleanValue($row['aspect_code']  ?? '');
                $aspectName   = $this->cleanValue($row['aspect_name']  ?? '');
                $description  = $this->cleanValue($row['description']  ?? null);
                $isActive     = $this->cleanValue($row['is_active']    ?? '1');
                $sortOrder    = $this->cleanValue($row['sort_order']   ?? '0');

                // Skip example/info rows
                if ($this->shouldSkipRow($rowData, $aspectCode, $aspectName)) {
                    continue;
                }

                // Skip if required fields are empty
                if (empty($companyIdRaw) || empty($aspectCode) || empty($aspectName)) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                // Validate company_id is numeric
                if (!is_numeric($companyIdRaw)) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                $companyId = (int) $companyIdRaw;

                // Non-superadmin can only use their own company_id
                if (!$isSuperadmin && $companyId !== (int) $userCompanyId) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                // Check company exists
                if (!\App\Models\Company::where('id', $companyId)->exists()) {
                    if ($this->isImportMode()) {
                        $this->results['company_not_found']++;
                    }
                    if (!in_array($companyId, $this->companyNotFound)) {
                        $this->companyNotFound[] = $companyId;
                    }
                    continue;
                }

                // Normalise is_active → boolean integer
                $isActiveParsed = $this->parseBoolean($isActive);

                // Normalise sort_order → integer
                $sortOrderParsed = is_numeric($sortOrder) ? (int) $sortOrder : 0;

                // Prepare aspect data
                $aspectData = [
                    'company_id'  => $companyId,
                    'aspect_code' => strtoupper(trim($aspectCode)),
                    'aspect_name' => $aspectName,
                    'description' => $description ?: null,
                    'is_active'   => $isActiveParsed,
                    'sort_order'  => $sortOrderParsed,
                ];

                // Validate
                $validator = Validator::make($aspectData, [
                    'company_id'  => 'required|integer|exists:companies,id',
                    'aspect_code' => 'required|string|max:255',
                    'aspect_name' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'is_active'   => 'required|boolean',
                    'sort_order'  => 'required|integer',
                ]);

                if ($validator->fails()) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                    }
                    continue;
                }

                // Check for duplicate (same aspect_code + company_id)
                $existingAspect = PsychogramAspect::where('aspect_code', $aspectData['aspect_code'])
                    ->where('company_id', $companyId)
                    ->first();

                $isDuplicate = !is_null($existingAspect);

                // Handle import mode
                if ($this->isImportMode()) {
                    if ($isDuplicate) {
                        if ($this->duplicateAction === 'update') {
                            $existingAspect->update($aspectData);
                            $this->results['success']++;
                        } else {
                            $this->results['skipped']++;
                        }
                    } else {
                        PsychogramAspect::create($aspectData);
                        $this->results['success']++;
                    }
                }

                // Add to preview data
                if (!$this->isImportMode()) {
                    $previewData                 = $aspectData;
                    $previewData['company_name'] = \App\Models\Company::find($companyId)?->name ?? 'N/A';
                    $previewData['is_duplicate'] = $isDuplicate;
                    $processedData[]             = $previewData;
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

    private function shouldSkipRow(array $rowData, ?string $aspectCode, ?string $aspectName): bool
    {
        $rawCode      = strtoupper(trim($rowData['aspect_code']  ?? ''));
        $rawName      = strtoupper(trim($rowData['aspect_name']  ?? ''));
        $rawCompanyId = strtoupper(trim($rowData['company_id']   ?? ''));

        if (
            str_starts_with($rawCode, 'CONTOH:') ||
            str_starts_with($rawName, 'CONTOH:') ||
            str_starts_with($rawCompanyId, 'CONTOH:')
        ) {
            return true;
        }

        $firstColumn = strtolower((string) $aspectCode);

        if (
            str_contains($firstColumn, 'informasi') ||
            str_contains($firstColumn, 'penting') ||
            str_contains($firstColumn, 'aturan') ||
            str_contains($firstColumn, 'aspect_code') ||
            empty($firstColumn)
        ) {
            return true;
        }

        return false;
    }

    private function cleanValue(mixed $value): ?string
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

    private function parseBoolean(?string $value): int
    {
        if (is_null($value)) {
            return 1;
        }

        $lower = strtolower(trim($value));

        if (in_array($lower, ['0', 'false', 'no', 'tidak', 'inactive', 'non-active'], true)) {
            return 0;
        }

        return 1;
    }

    private function isImportMode(): bool
    {
        return !is_null($this->duplicateAction);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getCompanyNotFound(): array
    {
        return array_unique($this->companyNotFound);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function setDuplicateAction(string $action): static
    {
        $this->duplicateAction = in_array($action, ['skip', 'update']) ? $action : 'skip';
        return $this;
    }

    public function getResults(): array
    {
        return $this->results;
    }
}
