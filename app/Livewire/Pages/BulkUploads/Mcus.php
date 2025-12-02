<?php

namespace App\Livewire\Pages\BulkUploads;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\McuTemplateExport;
use App\Imports\McusImport;
use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Bulk Upload MCU')]
class Mcus extends Component
{
    use WithFileUploads;

    public $excelFile;
    public $uploadedData = [];
    public $employeeNotFound = [];
    public $showPreview = false;
    public $importResults = [];
    public $isSuperadmin = false;
    public $duplicateAction = 'skip';
    public $uploadErrors = [];
    public $isProcessing = false;

    public function mount()
    {
        $user = Auth::user();
        $this->isSuperadmin = is_null($user->company_id);
    }

    public function downloadTemplate()
    {
        return Excel::download(new McuTemplateExport, 'mcu-template.xlsx');
    }

    public function uploadFile()
    {
        $this->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $this->isProcessing = true;

            // Create import instance for preview
            $import = new McusImport();

            // Process the file
            Excel::import($import, $this->excelFile->getRealPath());

            // Get the processed data
            $this->uploadedData = $import->getData();
            $this->employeeNotFound = $import->getEmployeeNotFound();
            $this->uploadErrors = $import->getErrors();

            if (empty($this->uploadedData) && empty($this->employeeNotFound)) {
                Toaster::warning('Tidak ada data valid yang ditemukan. Pastikan Anda sudah menghapus baris contoh dan data telah diisi dengan benar.');
                $this->isProcessing = false;
                return;
            }

            $this->showPreview = true;

            $message = count($this->uploadedData) . ' data siap untuk diimport.';
            if (!empty($this->employeeNotFound)) {
                $message .= ' Ditemukan ' . count($this->employeeNotFound) . ' employee_number yang tidak ditemukan.';
            }

            Toaster::success($message);
            $this->isProcessing = false;
        } catch (\Exception $e) {
            $this->isProcessing = false;
            Toaster::error('Error processing file: ' . $e->getMessage());
        }
    }

    public function confirmImport()
    {
        if (!empty($this->employeeNotFound)) {
            Toaster::warning('Terdapat employee_number yang tidak ditemukan. Periksa kembali data Anda.');
            return;
        }

        try {
            $this->isProcessing = true;

            // Create new import instance with duplicate action
            $import = new McusImport();
            $import->setDuplicateAction($this->duplicateAction);

            // Process the import
            Excel::import($import, $this->excelFile->getRealPath());

            // Get results
            $results = $import->getResults();
            $this->importResults = $results;

            // Hide preview
            $this->showPreview = false;

            // Build success message
            $parts = [];
            if ($results['success'] > 0) {
                $parts[] = "{$results['success']} data berhasil diimport";
            }
            if ($results['skipped'] > 0) {
                $parts[] = "{$results['skipped']} data diskip";
            }
            if ($results['failed'] > 0) {
                $parts[] = "{$results['failed']} data gagal";
            }
            if ($results['employee_not_found'] > 0) {
                $parts[] = "{$results['employee_not_found']} data dengan employee tidak ditemukan";
            }

            if (empty($parts)) {
                Toaster::info('Tidak ada data yang diproses.');
            } else {
                Toaster::success('Import selesai! ' . implode(', ', $parts) . '.');
            }

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
            'employeeNotFound',
            'showPreview',
            'importResults',
            'uploadErrors',
            'isProcessing'
        ]);

        $this->duplicateAction = 'skip';
    }

    public function render()
    {
        return view('livewire.pages.bulk-uploads.mcus');
    }
}
