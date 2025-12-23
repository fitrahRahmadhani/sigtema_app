<?php

namespace App\Livewire\Pages\BulkUploads;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PositionRequirementTemplateExport;
use App\Imports\PositionRequirementsImport;
use Masmerise\Toaster\Toaster;

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

            // Create import instance for preview
            $import = new PositionRequirementsImport();

            // Process the file
            Excel::import($import, $this->excelFile->getRealPath());

            // Get the processed data
            $this->uploadedData = $import->getData();
            $this->uploadErrors = $import->getErrors();

            if (empty($this->uploadedData)) {
                Toaster::warning('Tidak ada data valid yang ditemukan. Pastikan Anda sudah menghapus baris contoh dan data telah diisi dengan benar.');
                $this->isProcessing = false;
                return;
            }

            $this->showPreview = true;

            $message = count($this->uploadedData) . ' data siap untuk diimport.';
            Toaster::success($message);

            $this->isProcessing = false;
        } catch (\Exception $e) {
            $this->isProcessing = false;
            Toaster::error('Error processing file: ' . $e->getMessage());
        }
    }

    public function confirmImport()
    {
        try {
            $this->isProcessing = true;

            // Create new import instance
            $import = new PositionRequirementsImport();

            // Process the import
            Excel::import($import, $this->excelFile->getRealPath());

            // Get results
            $results = $import->getResults();
            $this->importResults = $results;

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
            Toaster::error('Error during import: ' . $e->getMessage());
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
