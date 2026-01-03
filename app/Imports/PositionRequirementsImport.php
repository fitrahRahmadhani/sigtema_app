<?php

namespace App\Imports;

use App\Models\Position;
use App\Models\PositionRequirementSetting;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PositionRequirementsImport implements ToCollection, WithHeadingRow
{
    private $data = [];
    private $results = [
        'success' => 0,
        'failed' => 0,
        'skipped' => 0
    ];
    private $errors = [];

    // Tambahkan property untuk menentukan mode
    private $importMode = false;

    // Tambahkan constructor untuk set mode
    public function __construct($importMode = false)
    {
        $this->importMode = $importMode;
    }

    public function collection(Collection $rows)
    {
        $user = Auth::user();
        $processedData = [];

        foreach ($rows as $index => $row) {
            try {
                // Ambil data dengan key lowercase karena Excel bisa case insensitive
                $rowArray = $row->toArray();
                $positionCode = $this->getColumnValue($rowArray, 'position_code');
                $name = $this->getColumnValue($rowArray, 'name');
                $value = $this->getColumnValue($rowArray, 'value');

                // Skip row contoh
                if ($this->isExampleRow($positionCode, $name)) {
                    continue;
                }

                // Skip jika kosong
                if (empty($positionCode) || empty($name)) {
                    $this->results['skipped']++;
                    continue;
                }

                // Cari position berdasarkan position_code
                $position = $this->findPosition($positionCode, $user);
                if (!$position) {
                    $this->results['failed']++;
                    $this->errors[] = "Baris " . ($index + 1) . ": Position code '$positionCode' tidak ditemukan";
                    continue;
                }

                // Validasi data
                $validator = Validator::make([
                    'position_id' => $position->id,
                    'position_code' => $position->position_code,
                    'name' => $name,
                    'value' => $value
                ], [
                    'position_id' => 'required|exists:positions,id',
                    'position_code' => 'required|string|max:255',
                    'name' => 'required|string|max:255',
                    'value' => 'nullable|string'
                ]);

                if ($validator->fails()) {
                    $this->results['failed']++;
                    continue;
                }

                // Cek apakah sudah ada
                $existingRequirement = PositionRequirementSetting::where('position_id', $position->id)
                    ->where('name', $name)
                    ->first();

                $isDuplicate = !is_null($existingRequirement);

                // Jika mode import (bukan preview)
                if ($this->importMode) {
                    if ($isDuplicate) {
                        // Update existing
                        $existingRequirement->update(['value' => $value]);
                        $this->results['success']++;
                    } else {
                        // Create new
                        PositionRequirementSetting::create([
                            'position_id' => $position->id,
                            'position_code' => $position->position_code,
                            'name' => $name,
                            'value' => $value
                        ]);
                        $this->results['success']++;
                    }
                } else {
                    // Mode preview
                    $processedData[] = [
                        'position_id' => $position->id,
                        'position_name' => $position->name,
                        'position_code_display' => $position->position_code,
                        'name' => $name,
                        'value' => $value,
                        'company_name' => $position->company->name ?? 'N/A',
                        'is_duplicate' => $isDuplicate
                    ];
                }
            } catch (\Exception $e) {
                $this->results['failed']++;
                $this->errors[] = "Baris " . ($index + 1) . ": " . $e->getMessage();
                continue;
            }
        }

        if (!$this->importMode) {
            $this->data = $processedData;
        }
    }

    private function getColumnValue($rowArray, $columnName)
    {
        // Cari kolom dengan berbagai kemungkinan case
        $keys = array_keys($rowArray);
        foreach ($keys as $key) {
            if (strtolower($key) === strtolower($columnName)) {
                $value = $rowArray[$key];
                return $this->cleanValue($value);
            }
        }
        return null;
    }

    private function cleanValue($value)
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        $value = trim((string) $value);

        // Hapus kata "CONTOH:" jika ada
        if (str_starts_with(strtoupper($value), 'CONTOH:')) {
            $value = substr($value, 7);
        }

        return trim($value);
    }

    private function isExampleRow($positionCode, $name)
    {
        if (empty($positionCode) || empty($name)) {
            return false;
        }

        $positionCode = strtoupper(trim($positionCode));
        $name = strtoupper(trim($name));

        return str_contains($positionCode, 'CONTOH:') ||
            str_contains($name, 'CONTOH:') ||
            str_contains($positionCode, 'PERHATIAN') ||
            str_contains($positionCode, 'PETUNJUK') ||
            str_contains($positionCode, 'ATURAN');
    }

    private function findPosition($positionCode, $user)
    {
        $positionCode = trim($positionCode);

        $position = Position::where('position_code', $positionCode)->first();

        if (!$position) {
            return null;
        }

        // Untuk non-superadmin, cek apakah position milik company mereka
        if (!is_null($user->company_id) && $position->company_id != $user->company_id) {
            return null;
        }

        return $position;
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
