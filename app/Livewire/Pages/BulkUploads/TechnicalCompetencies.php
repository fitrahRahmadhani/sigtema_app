<?php

namespace App\Livewire\Pages\BulkUploads;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TechnicalCompetencyTemplateExport;
use App\Imports\TechnicalCompetenciesImport;
use Illuminate\Support\Facades\Auth;
use Masmerise\Toaster\Toaster;

#[Layout('components.layouts.app')]
#[Title('Bulk Upload Technical Competencies')]
class TechnicalCompetencies extends Component
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
    public $showDetailedErrors = false;

    public function mount()
    {
        $user = Auth::user();
        $this->isSuperadmin = is_null($user->company_id);
    }

    public function downloadTemplate()
    {
        return Excel::download(new TechnicalCompetencyTemplateExport, 'technical-competency-template.xlsx');
    }

    public function uploadFile()
    {
        $this->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls|max:10240',
        ]);

        try {
            $this->isProcessing = true;
            $this->uploadErrors = [];
            $this->showDetailedErrors = false;

            // Create import instance for preview
            $import = new TechnicalCompetenciesImport();

            // Process the file
            Excel::import($import, $this->excelFile->getRealPath());

            // Get the processed data
            $this->uploadedData = $import->getData();
            $this->employeeNotFound = $import->getEmployeeNotFound();
            $this->uploadErrors = $import->getErrors();

            if (empty($this->uploadedData) && empty($this->employeeNotFound)) {
                if (!empty($this->uploadErrors)) {
                    Toaster::error('Tidak ada data valid yang ditemukan. Silakan periksa detail error.');
                    $this->showDetailedErrors = true;
                } else {
                    Toaster::warning('Tidak ada data valid yang ditemukan. Pastikan Anda sudah menghapus baris contoh dan data telah diisi dengan benar.');
                }
                $this->isProcessing = false;
                return;
            }

            $this->showPreview = true;

            $message = count($this->uploadedData) . ' data siap untuk diimport.';

            if (!empty($this->employeeNotFound)) {
                $message .= ' Ditemukan ' . count($this->employeeNotFound) . ' employee_number yang tidak ditemukan.';
            }

            if (!empty($this->uploadErrors)) {
                $message .= ' Ditemukan ' . count($this->uploadErrors) . ' error.';
            }

            Toaster::success($message);
            $this->isProcessing = false;
        } catch (\Exception $e) {
            $this->isProcessing = false;
            $this->uploadErrors[] = 'Error processing file: ' . $e->getMessage();
            Toaster::error('Error processing file. Silakan periksa detail error.');
            $this->showDetailedErrors = true;
        }
    }

    public function confirmImport()
    {
        if (!empty($this->employeeNotFound)) {
            Toaster::warning('Terdapat employee_number yang tidak ditemukan. Periksa kembali data Anda.');
            return;
        }

        if (empty($this->uploadedData)) {
            Toaster::warning('Tidak ada data untuk diimport.');
            return;
        }

        try {
            $this->isProcessing = true;

            // Create new import instance with duplicate action
            $import = new TechnicalCompetenciesImport();
            $import->setDuplicateAction($this->duplicateAction);

            // Process the import
            Excel::import($import, $this->excelFile->getRealPath());

            // Get results
            $results = $import->getResults();
            $this->importResults = $results;
            $this->uploadErrors = $import->getErrors();

            // Hide preview
            $this->showPreview = false;

            // Build success message
            $parts = [];
            if ($results['success'] > 0) {
                $parts[] = "{$results['success']} data berhasil diimport";
            }
            if ($results['skipped'] > 0) {
                $parts[] = "{$results['skipped']} data diskip (duplikat)";
            }
            if ($results['failed'] > 0) {
                $parts[] = "{$results['failed']} data gagal";
            }
            if ($results['employee_not_found'] > 0) {
                $parts[] = "{$results['employee_not_found']} employee tidak ditemukan";
            }

            if (empty($parts)) {
                Toaster::info('Tidak ada data yang diproses.');
            } else {
                $message = 'Import selesai! ' . implode(', ', $parts) . '.';

                if ($results['failed'] > 0 || $results['employee_not_found'] > 0) {
                    Toaster::warning($message);
                    $this->showDetailedErrors = true;
                } else {
                    Toaster::success($message);
                }
            }

            $this->isProcessing = false;
        } catch (\Exception $e) {
            $this->isProcessing = false;
            $this->uploadErrors[] = 'Error during import: ' . $e->getMessage();
            Toaster::error('Error during import. Silakan periksa detail error.');
            $this->showDetailedErrors = true;
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
            'isProcessing',
            'showDetailedErrors'
        ]);

        $this->duplicateAction = 'skip';
    }

    public function toggleErrorDetails()
    {
        $this->showDetailedErrors = !$this->showDetailedErrors;
    }

    public function render()
    {
        return view('livewire.pages.bulk-uploads.technical-competencies');
    }
}
