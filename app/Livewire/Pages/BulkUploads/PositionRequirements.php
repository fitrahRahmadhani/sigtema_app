<?php

namespace App\Livewire\Pages\BulkUploads;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Masmerise\Toaster\Toaster;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PositionRequirementSetting;
use App\Imports\PositionRequirementsImport;
use App\Exports\PositionRequirementTemplateExport;

#[Layout('components.layouts.app')]
#[Title('Bulk Upload Position Requirements')]
class PositionRequirements extends Component
{
    use WithFileUploads;

    public $excelFile;
    public $uploadedData = [];
    public $showPreview = false;
    public $importResults = [];
    public $uploadErrors = [];
    public $isProcessing = false;

    public function downloadTemplate()
    {
        return Excel::download(new PositionRequirementTemplateExport, 'position-requirement-template.xlsx');
    }

    public function uploadFile()
    {
        $this->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $this->isProcessing = true;

            // Create import instance untuk PREVIEW MODE
            $import = new PositionRequirementsImport(false); // false = preview mode

            // Process the file
            Excel::import($import, $this->excelFile->getRealPath());

            // Get the processed data
            $this->uploadedData = $import->getData();
            $this->uploadErrors = $import->getErrors();

            Log::info('Preview Mode - Data count: ' . count($this->uploadedData));
            Log::info('Preview Mode - Results:', $import->getResults());

            if (empty($this->uploadedData)) {
                $results = $import->getResults();
                Toaster::warning(
                    'Tidak ada data valid yang ditemukan. ' .
                        'Data ditemukan: ' . ($results['success'] + $results['failed']) . ', ' .
                        'Dilewati: ' . $results['skipped']
                );
                $this->isProcessing = false;
                return;
            }

            $this->showPreview = true;

            $message = count($this->uploadedData) . ' data siap untuk diimport.';
            Toaster::success($message);

            $this->isProcessing = false;
        } catch (\Exception $e) {
            $this->isProcessing = false;
            Log::error('Preview Error: ' . $e->getMessage());
            Toaster::error('Error memproses file: ' . $e->getMessage());
        }
    }

    public function confirmImport()
    {
        try {
            $this->isProcessing = true;

            Log::info('Starting Import Process...');

            // Create new import instance untuk IMPORT MODE
            $import = new PositionRequirementsImport(true); // true = import mode

            // Process the import
            Excel::import($import, $this->excelFile->getRealPath());

            // Get results
            $results = $import->getResults();
            $this->importResults = $results;

            Log::info('Import Mode - Results:', $results);
            Log::info('Import Mode - Errors:', $import->getErrors());

            // Verifikasi data masuk ke database
            $importedCount = PositionRequirementSetting::count();
            Log::info('Total records in database after import: ' . $importedCount);

            // Hide preview
            $this->showPreview = false;

            // Build success message
            $message = "Import selesai! ";
            $message .= "{$results['success']} data berhasil diproses";

            if ($results['failed'] > 0) {
                $message .= ", {$results['failed']} data gagal";
            }

            if ($results['skipped'] > 0) {
                $message .= ", {$results['skipped']} data diskip";
            }

            Toaster::success($message . '.');
            $this->isProcessing = false;
        } catch (\Exception $e) {
            $this->isProcessing = false;
            Log::error('Import Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            Toaster::error('Error saat import: ' . $e->getMessage());
        }
    }

    public function resetUpload()
    {
        $this->reset([
            'excelFile',
            'uploadedData',
            'showPreview',
            'importResults',
            'uploadErrors',
            'isProcessing'
        ]);
    }

    public function render()
    {
        return view('livewire.pages.bulk-uploads.position-requirements');
    }
}
