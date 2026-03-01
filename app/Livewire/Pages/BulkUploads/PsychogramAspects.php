<?php

namespace App\Livewire\Pages\BulkUploads;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PsychogramAspectTemplateExport;
use App\Imports\PsychogramAspectsImport;
use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Bulk Upload Psychogram Aspects')]
class PsychogramAspects extends Component
{
    use WithFileUploads;

    public $excelFile;
    public $uploadedData    = [];
    public $companyNotFound = [];
    public $showPreview     = false;
    public $importResults   = [];
    public $isSuperadmin    = false;
    public $duplicateAction = 'skip';
    public $uploadErrors    = [];
    public $isProcessing    = false;

    public function mount(): void
    {
        $user = Auth::user();
        $this->isSuperadmin = is_null($user->company_id);
    }

    public function downloadTemplate()
    {
        return Excel::download(new PsychogramAspectTemplateExport, 'psychogram-aspect-template.xlsx');
    }

    public function uploadFile(): void
    {
        $this->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $this->isProcessing = true;

            $import = new PsychogramAspectsImport();
            Excel::import($import, $this->excelFile->getRealPath());

            $this->uploadedData    = $import->getData();
            $this->companyNotFound = $import->getCompanyNotFound();
            $this->uploadErrors    = $import->getErrors();

            if (empty($this->uploadedData) && empty($this->companyNotFound)) {
                Toaster::warning('Tidak ada data valid yang ditemukan. Pastikan Anda sudah menghapus baris contoh dan data telah diisi dengan benar.');
                $this->isProcessing = false;
                return;
            }

            $this->showPreview = true;

            $duplicateCount = collect($this->uploadedData)->where('is_duplicate', true)->count();
            $message = count($this->uploadedData) . ' data siap untuk diimport.';
            if ($duplicateCount > 0) {
                $message .= " Ditemukan {$duplicateCount} data yang sudah ada di database (duplikat).";
            }
            if (!empty($this->companyNotFound)) {
                $message .= ' Ditemukan ' . count($this->companyNotFound) . ' company_id yang tidak ditemukan.';
            }

            Toaster::success($message);
            $this->isProcessing = false;
        } catch (\Exception $e) {
            $this->isProcessing = false;
            Toaster::error('Error processing file: ' . $e->getMessage());
        }
    }

    public function confirmImport(): void
    {
        if (!empty($this->companyNotFound)) {
            Toaster::warning('Terdapat company_id yang tidak ditemukan. Periksa kembali data Anda.');
            return;
        }

        try {
            $this->isProcessing = true;

            $import = new PsychogramAspectsImport();
            $import->setDuplicateAction($this->duplicateAction);

            Excel::import($import, $this->excelFile->getRealPath());

            $results             = $import->getResults();
            $this->importResults = $results;
            $this->showPreview   = false;

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
            if ($results['company_not_found'] > 0) {
                $parts[] = "{$results['company_not_found']} data dengan company tidak ditemukan";
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

    public function resetUpload(): void
    {
        $this->reset([
            'excelFile',
            'uploadedData',
            'companyNotFound',
            'showPreview',
            'importResults',
            'uploadErrors',
            'isProcessing',
        ]);

        $this->duplicateAction = 'skip';
    }

    public function render()
    {
        return view('livewire.pages.bulk-uploads.psychogram-aspects');
    }
}
