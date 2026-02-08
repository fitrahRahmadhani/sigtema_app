<?php

namespace App\Imports;

use App\Models\TechnicalCompetency;
use App\Models\Employee;
use App\Models\Company;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TechnicalCompetenciesImport implements ToCollection, WithHeadingRow
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
        $rowNumber = 1; // Start from 1 (after header)

        // Preload all employees untuk performa lebih baik
        $this->preloadEmployees($user, $employeeCache);

        foreach ($rows as $index => $row) {
            $rowNumber++;

            try {
                $rowData = $row->toArray();

                // Clean the data
                $employeeNumber = $this->cleanValue($row['employee_number'] ?? '');
                $name = $this->cleanValue($row['name'] ?? '');
                $issuedBy = $this->cleanValue($row['issued_by'] ?? '');
                $year = $this->cleanValue($row['year'] ?? '');

                // Skip example/info rows (lebih selektif)
                if ($this->shouldSkipRow($employeeNumber)) {
                    continue;
                }

                // Skip if essential fields are empty
                if (empty($employeeNumber) || empty($name) || empty($issuedBy) || empty($year)) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                        $this->errors[] = "Baris {$rowNumber}: Data tidak lengkap (employee_number, name, issued_by, atau year kosong)";
                    }
                    continue;
                }

                // Validate and clean year - lebih fleksibel
                $yearCleaned = preg_replace('/\s+/', '', $year); // Remove all whitespace
                $yearCleaned = preg_replace('/[^0-9]/', '', $yearCleaned); // Keep only numbers

                if (empty($yearCleaned) || strlen($yearCleaned) !== 4) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                        $this->errors[] = "Baris {$rowNumber}: Format tahun tidak valid '{$year}'";
                    }
                    continue;
                }

                $yearInt = (int) $yearCleaned;

                if ($yearInt < 1900 || $yearInt > (int)date('Y')) {
                    if ($this->isImportMode()) {
                        $this->results['failed']++;
                        $this->errors[] = "Baris {$rowNumber}: Tahun di luar rentang valid ({$yearInt})";
                    }
                    continue;
                }

                // Get employee data with cache
                $employee = $this->getEmployee($employeeNumber, $user, $employeeCache);

                if (!$employee) {
                    if ($this->isImportMode()) {
                        $this->results['employee_not_found']++;
                        $this->errors[] = "Baris {$rowNumber}: Employee number '{$employeeNumber}' tidak ditemukan";
                    }
                    if (!in_array($employeeNumber, $this->employeeNotFound)) {
                        $this->employeeNotFound[] = $employeeNumber;
                    }
                    continue;
                }

                // Prepare technical competency data
                $competencyData = [
                    'employee_id' => $employee->id,
                    'name' => $name,
                    'issued_by' => $issuedBy,
                    'year' => $yearInt,
                ];

                // Check for duplicate (same competency for same employee with same name and year)
                $existingCompetency = TechnicalCompetency::where('employee_id', $employee->id)
                    ->where('name', $name)
                    ->where('year', $yearInt)
                    ->first();

                $isDuplicate = !is_null($existingCompetency);

                // Handle import mode
                if ($this->isImportMode()) {
                    if ($isDuplicate) {
                        if ($this->duplicateAction === 'update') {
                            $existingCompetency->update($competencyData);
                            $this->results['success']++;
                        } else {
                            $this->results['skipped']++;
                        }
                    } else {
                        TechnicalCompetency::create($competencyData);
                        $this->results['success']++;
                    }
                } else {
                    // Add to preview data
                    $previewData = $competencyData;
                    $previewData['employee_number'] = $employee->employee_number;
                    $previewData['employee_name'] = $employee->name;
                    $previewData['company_name'] = $employee->company->name ?? 'N/A';
                    $previewData['row_number'] = $rowNumber;
                    $previewData['is_duplicate'] = $isDuplicate;
                    $processedData[] = $previewData;
                }
            } catch (\Exception $e) {
                if ($this->isImportMode()) {
                    $this->results['failed']++;
                    $this->errors[] = "Baris {$rowNumber}: Error - " . $e->getMessage();
                }
                Log::error("TechnicalCompetency Import Error - Row {$rowNumber}: " . $e->getMessage());
                continue;
            }
        }

        if (!$this->isImportMode()) {
            $this->data = $processedData;
        }
    }

    /**
     * Preload semua employees ke cache untuk performa lebih baik
     */
    private function preloadEmployees($user, &$employeeCache)
    {
        $query = Employee::with('company');

        if (!is_null($user->company_id)) {
            $query->where('company_id', $user->company_id);
        }

        $employees = $query->get();

        foreach ($employees as $employee) {
            $employeeCache[$employee->employee_number] = $employee;
        }
    }

    /**
     * Skip row yang merupakan contoh atau info
     * Simplified - hanya check employee_number
     */
    private function shouldSkipRow($employeeNumber)
    {
        if (empty($employeeNumber)) {
            return false; // Biar dihandle di validasi utama
        }

        $employeeNumberUpper = strtoupper(trim($employeeNumber));
        $employeeNumberLower = strtolower(trim($employeeNumber));

        // Skip jika dimulai dengan kata kunci contoh
        if (
            str_starts_with($employeeNumberUpper, 'CONTOH:') ||
            str_starts_with($employeeNumberUpper, 'CONTOH ') ||
            str_starts_with($employeeNumberUpper, 'EXAMPLE:')
        ) {
            return true;
        }

        // Skip jika mengandung kata kunci informasi (hanya di kolom pertama)
        $skipKeywords = [
            'informasi',
            'penting',
            'aturan',
            'employee number',
            'petunjuk',
            'catatan',
            'note:',
            'info:'
        ];

        foreach ($skipKeywords as $keyword) {
            if (str_contains($employeeNumberLower, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Clean value dari prefix CONTOH dan whitespace
     */
    private function cleanValue($value)
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        $value = (string) $value;
        $value = trim($value);

        // Remove CONTOH prefix dengan berbagai variasi
        $patterns = [
            'CONTOH: ',
            'contoh: ',
            'Contoh: ',
            'CONTOH:',
            'contoh:',
            'Contoh:',
            'EXAMPLE: ',
            'example: '
        ];

        foreach ($patterns as $pattern) {
            if (str_starts_with($value, $pattern)) {
                $value = substr($value, strlen($pattern));
                $value = trim($value);
                break;
            }
        }

        return empty($value) ? null : $value;
    }

    /**
     * Get employee dengan cache
     */
    private function getEmployee($employeeNumber, $user, &$employeeCache)
    {
        // Check cache first
        if (isset($employeeCache[$employeeNumber])) {
            return $employeeCache[$employeeNumber];
        }

        // Jika tidak ada di cache, return null
        // Karena sudah dipreload di awal
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
