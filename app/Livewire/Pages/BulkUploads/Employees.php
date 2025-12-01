<?php

namespace App\Livewire\Pages\BulkUploads;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeeTemplateExport;
use App\Imports\EmployeesImport;
use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Bulk Upload Employees')]
class Employees extends Component
{
    use WithFileUploads;

    public $excelFile;
    public $uploadedData = [];
    public $duplicates = [];
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
        return Excel::download(new EmployeeTemplateExport, 'employee-template.xlsx');
    }

    public function uploadFile()
    {
        $this->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $this->isProcessing = true;

            // Create import instance for preview
            $import = new EmployeesImport();

            // Process the file
            Excel::import($import, $this->excelFile->getRealPath());

            // Get the processed data
            $this->uploadedData = $import->getData();
            $this->duplicates = $import->getDuplicates();
            $this->uploadErrors = $import->getErrors();

            if (empty($this->uploadedData)) {
                Toaster::warning('Tidak ada data valid yang ditemukan. Pastikan Anda sudah menghapus baris contoh dan data telah diisi dengan benar.');
                $this->isProcessing = false;
                return;
            }

            $this->showPreview = true;

            $message = count($this->uploadedData) . ' data siap untuk diimport.';
            if (!empty($this->duplicates)) {
                $message .= ' Ditemukan ' . count($this->duplicates) . ' data duplicate.';
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
        if (!empty($this->duplicates) && !in_array($this->duplicateAction, ['skip', 'update'])) {
            Toaster::warning('Silakan pilih tindakan untuk data duplicate.');
            return;
        }

        try {
            $this->isProcessing = true;

            // Create new import instance with duplicate action
            $import = new EmployeesImport();
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
                $parts[] = "{$results['success']} data baru berhasil ditambahkan";
            }
            if ($results['updated'] > 0) {
                $parts[] = "{$results['updated']} data berhasil diupdate";
            }
            if ($results['skipped'] > 0) {
                $parts[] = "{$results['skipped']} data diskip";
            }
            if ($results['failed'] > 0) {
                $parts[] = "{$results['failed']} data gagal";
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
            'duplicates',
            'showPreview',
            'importResults',
            'uploadErrors',
            'isProcessing'
        ]);

        $this->duplicateAction = 'skip';
    }

    public function render()
    {
        return view('livewire.pages.bulk-uploads.employees');
    }
}
