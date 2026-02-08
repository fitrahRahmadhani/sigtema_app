<div class="space-y-4 p-2">
    {{-- Breadcrumbs --}}
    <flux:breadcrumbs>
        <flux:breadcrumbs.item href="{{ route('dashboard') }}">Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Bulk Upload</flux:breadcrumbs.item>
        <flux:breadcrumbs.item>Technical Competencies</flux:breadcrumbs.item>
    </flux:breadcrumbs>

    {{-- Content --}}
    <div class="h-full w-full rounded-xl bg-white p-6 md:p-10">
        {{-- Header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4">
                <div
                    class="color-gradient flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br shadow-sm"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="h-6 w-6 text-white"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3V15"
                        />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 md:text-3xl">Bulk Upload Technical Competencies</h1>
                    <p class="mt-1 text-sm text-gray-500">
                        Import multiple technical competency records from Excel file
                    </p>
                </div>
            </div>
            <button
                wire:click="downloadTemplate"
                class="flex shrink-0 items-center gap-2 rounded-lg bg-green-500 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-600"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                    ></path>
                </svg>
                Download Template
            </button>
        </div>

        {{-- Upload Section --}}
        @if (! $showPreview && empty($importResults))
            {{-- Important Notice --}}
            <div class="mb-6 rounded-lg border-l-4 border-red-500 bg-red-50 p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path
                                fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="mb-2 font-semibold text-red-800">PENTING SEBELUM UPLOAD:</p>
                        <ul class="space-y-1 text-sm text-red-700">
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>
                                    <strong>Hapus 4 baris yang bertuliskan "CONTOH:"</strong>
                                    di template
                                </span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>
                                    <strong>employee_number HARUS sudah terdaftar</strong>
                                    dalam sistem
                                </span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Satu karyawan bisa memiliki multiple baris technical competency</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Format year: YYYY (contoh: {{ date('Y') }})</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>Tahun harus antara 1900 dan {{ date('Y') }}</span>
                            </li>
                            <li class="flex items-start">
                                <span class="mr-2">•</span>
                                <span>
                                    <strong>Semua kolom wajib diisi</strong>
                                    (tidak boleh kosong)
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Processing Indicator --}}
            <div wire:loading wire:target="uploadFile" class="mb-6 w-full">
                <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                    <div class="flex items-center">
                        <svg class="mr-3 h-5 w-5 animate-spin text-blue-500" fill="none" viewBox="0 0 24 24">
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle>
                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path>
                        </svg>
                        <div>
                            <p class="font-medium text-blue-800">Processing your file...</p>
                            <p class="text-sm text-blue-600">Validating data and checking employee numbers</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upload Area --}}
            <div
                class="rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 p-8 text-center transition hover:border-blue-400 hover:bg-blue-50"
            >
                <div class="flex flex-col items-center justify-center">
                    <svg class="mb-3 h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                        />
                    </svg>

                    <label for="file-upload" class="cursor-pointer">
                        <span class="text-lg font-medium text-blue-600 hover:text-blue-500">Pilih file Excel</span>
                        <input id="file-upload" type="file" wire:model="excelFile" accept=".xlsx,.xls" class="hidden" />
                    </label>
                    <p class="mt-1 text-xs text-gray-400">XLSX, XLS (Max 10MB)</p>

                    {{-- Upload Progress --}}
                    <div wire:loading wire:target="excelFile" class="mt-4 w-full max-w-md">
                        <div class="mb-2 flex items-center justify-between text-sm">
                            <span class="text-gray-600">Uploading file...</span>
                            <span class="text-gray-500">Please wait</span>
                        </div>
                        <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200">
                            <div class="h-full animate-pulse bg-blue-500" style="width: 100%"></div>
                        </div>
                    </div>

                    @error('excelFile')
                        <div class="mt-3 rounded bg-red-100 p-2 text-sm text-red-600">
                            {{ $message }}
                        </div>
                    @enderror

                    @if ($excelFile)
                        <div class="mt-4 flex items-center rounded-lg bg-white px-4 py-2 shadow">
                            <svg class="mr-2 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span class="text-sm font-medium text-gray-700">
                                {{ $excelFile->getClientOriginalName() }}
                            </span>
                        </div>
                    @endif
                </div>

                @if ($excelFile)
                    <div class="mt-6">
                        <button
                            wire:click="uploadFile"
                            wire:loading.attr="disabled"
                            wire:target="uploadFile"
                            class="inline-flex items-center rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <div wire:loading.remove wire:target="uploadFile" class="flex items-center">
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                Upload & Preview
                            </div>
                            <div wire:loading wire:target="uploadFile" class="flex items-center">
                                <svg class="mr-2 inline h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                                Processing file...
                            </div>
                        </button>
                    </div>
                @endif
            </div>
        @endif

        {{-- Preview Section --}}
        @if ($showPreview)
            <div class="mb-6">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">
                        Preview Data
                        <span class="ml-2 rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800">
                            {{ count($uploadedData) }} records
                        </span>
                    </h2>
                </div>

                {{-- Import Progress Indicator --}}
                <div wire:loading wire:target="confirmImport" class="mb-6 w-full">
                    <div class="rounded-lg border border-green-200 bg-green-50 p-4">
                        <div class="flex items-center">
                            <svg class="mr-3 h-5 w-5 animate-spin text-green-500" fill="none" viewBox="0 0 24 24">
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            <div>
                                <p class="font-medium text-green-800">Importing data to database...</p>
                                <p class="text-sm text-green-600">Please wait, do not close this page</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if (! empty($employeeNotFound))
                    <div class="mb-4 rounded-lg border border-red-300 bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="font-semibold text-red-800">Employee Number Tidak Ditemukan!</h3>
                                <p class="mt-1 text-sm text-red-700">
                                    Terdapat {{ count($employeeNotFound) }} employee_number yang tidak ditemukan dalam
                                    database:
                                </p>
                                <div class="mt-2 rounded border border-red-200 bg-white p-3">
                                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-4">
                                        @foreach ($employeeNotFound as $empNumber)
                                            <span
                                                class="rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-800"
                                            >
                                                {{ $empNumber }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                <p class="mt-2 text-sm text-red-700">
                                    Data dengan employee_number yang tidak ditemukan tidak akan diproses. Pastikan
                                    employee_number sudah terdaftar sebelum melanjutkan import.
                                </p>
                                <div class="mt-3">
                                    <button
                                        wire:click="resetUpload"
                                        class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-white transition hover:bg-red-700"
                                    >
                                        Upload Ulang dengan Data yang Benar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (empty($uploadedData))
                    <div class="rounded-lg border border-yellow-300 bg-yellow-50 p-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="font-semibold text-yellow-800">Tidak ada data yang bisa diproses.</p>
                                <p class="mt-2 text-sm text-yellow-700">Kemungkinan penyebab:</p>
                                <ul class="mt-2 list-inside list-disc space-y-1 text-sm text-yellow-700">
                                    <li>Anda belum menghapus baris yang bertuliskan "CONTOH:"</li>
                                    <li>Semua baris adalah data informasi/instruksi</li>
                                    <li>Employee number tidak ditemukan</li>
                                    <li>Data tidak memenuhi validasi</li>
                                    <li>Tahun tidak valid (harus antara 1900 dan {{ date('Y') }})</li>
                                    <li>Kolom wajib ada yang kosong</li>
                                </ul>

                                <div class="mt-4">
                                    <button
                                        wire:click="resetUpload"
                                        class="inline-flex items-center rounded-lg bg-gray-600 px-4 py-2 text-white transition hover:bg-gray-700"
                                    >
                                        Try Again
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Duplicate Handling --}}
                    <div class="mb-4 rounded-lg border border-yellow-300 bg-yellow-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="font-semibold text-yellow-800">Informasi Data Duplicate</h3>
                                <p class="mt-1 text-sm text-yellow-700">
                                    Jika ditemukan data technical competency yang sama untuk karyawan yang sama dengan
                                    nama kompetensi dan tahun yang sama, sistem akan:
                                </p>
                                <div class="mt-3 space-y-2">
                                    <label
                                        class="flex cursor-pointer items-center rounded-lg border border-yellow-200 bg-white p-3 transition hover:bg-yellow-50"
                                    >
                                        <input
                                            type="radio"
                                            wire:model.live="duplicateAction"
                                            value="skip"
                                            class="mr-3 h-4 w-4 text-blue-600"
                                        />
                                        <span class="text-sm font-medium text-gray-700">
                                            Skip data duplicate (biarkan data lama)
                                        </span>
                                    </label>
                                    <label
                                        class="flex cursor-pointer items-center rounded-lg border border-yellow-200 bg-white p-3 transition hover:bg-yellow-50"
                                    >
                                        <input
                                            type="radio"
                                            wire:model.live="duplicateAction"
                                            value="update"
                                            class="mr-3 h-4 w-4 text-blue-600"
                                        />
                                        <span class="text-sm font-medium text-gray-700">
                                            Update data existing (timpa dengan data baru)
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Preview Table - Show All Data --}}
                    <div class="h-screen overflow-x-auto overflow-y-scroll rounded-lg border border-gray-200 shadow">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        No
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        Employee
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        Competency Name
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        Issued By
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium tracking-wider text-gray-500 uppercase"
                                    >
                                        Year
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($uploadedData as $index => $data)
                                    <tr class="transition hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-500">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap">
                                            <div class="font-medium text-gray-900">
                                                {{ $data['employee_number'] }}
                                            </div>
                                            <div class="text-gray-500">{{ $data['employee_name'] }}</div>
                                            @if ($isSuperadmin)
                                                <div class="text-xs text-gray-400">{{ $data['company_name'] }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-700">
                                            {{ $data['name'] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-700">
                                            {{ $data['issued_by'] }}
                                        </td>
                                        <td class="px-4 py-3 text-sm whitespace-nowrap text-gray-700">
                                            {{ $data['year'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="mt-6 flex gap-3">
                        @if (empty($employeeNotFound))
                            <button
                                wire:click="confirmImport"
                                wire:loading.attr="disabled"
                                wire:target="confirmImport"
                                class="inline-flex items-center rounded-lg bg-green-600 px-6 py-3 text-white transition hover:bg-green-700 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                <span wire:loading.remove wire:target="confirmImport" class="flex items-center">
                                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 13l4 4L19 7"
                                        />
                                    </svg>
                                    Confirm Import
                                </span>
                                <span wire:loading wire:target="confirmImport" class="flex items-center">
                                    <svg class="mr-2 inline h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle
                                            class="opacity-25"
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                        ></circle>
                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                        ></path>
                                    </svg>
                                    Importing...
                                </span>
                            </button>
                        @else
                            <button
                                disabled
                                class="inline-flex cursor-not-allowed items-center rounded-lg bg-gray-400 px-6 py-3 text-white"
                            >
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                    />
                                </svg>
                                Tidak dapat import karena ada employee yang tidak ditemukan
                            </button>
                        @endif
                        <div wire:loading.remove wire:target="confirmImport">
                            <button
                                wire:click="resetUpload"
                                class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-gray-700 transition hover:bg-gray-50"
                            >
                                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                    />
                                </svg>
                                <span>Start Over</span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Results Section --}}
        @if (! empty($importResults))
            <div class="mb-6">
                <div class="rounded-lg border border-green-200 bg-green-50 p-6">
                    <div class="flex items-center">
                        <svg class="h-8 w-8 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-green-800">Import Completed Successfully!</h3>
                            <p class="text-sm text-green-600">Your technical competency data has been imported</p>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-4 md:grid-cols-4">
                        <div class="rounded-lg bg-white p-4 text-center shadow">
                            <div class="text-3xl font-bold text-green-600">{{ $importResults['success'] }}</div>
                            <div class="mt-1 text-sm text-gray-600">Success</div>
                        </div>
                        <div class="rounded-lg bg-white p-4 text-center shadow">
                            <div class="text-3xl font-bold text-yellow-600">{{ $importResults['skipped'] }}</div>
                            <div class="mt-1 text-sm text-gray-600">Skipped</div>
                        </div>
                        <div class="rounded-lg bg-white p-4 text-center shadow">
                            <div class="text-3xl font-bold text-red-600">{{ $importResults['failed'] }}</div>
                            <div class="mt-1 text-sm text-gray-600">Failed</div>
                        </div>
                        <div class="rounded-lg bg-white p-4 text-center shadow">
                            <div class="text-3xl font-bold text-orange-600">
                                {{ $importResults['employee_not_found'] }}
                            </div>
                            <div class="mt-1 text-sm text-gray-600">Employee Not Found</div>
                        </div>
                    </div>

                    @if ($importResults['employee_not_found'] > 0)
                        <div class="mt-4 rounded-lg border border-orange-200 bg-orange-50 p-4">
                            <div class="flex items-center">
                                <svg class="mr-2 h-5 w-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <span class="text-sm text-orange-800">
                                    {{ $importResults['employee_not_found'] }} data tidak diproses karena
                                    employee_number tidak ditemukan. Pastikan employee sudah terdaftar sebelum import
                                    ulang.
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-6 flex gap-3">
                    <button
                        wire:click="resetUpload"
                        class="flex items-center rounded-lg bg-blue-600 px-6 py-3 text-white transition hover:bg-blue-700"
                    >
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                            />
                        </svg>
                        <span>Upload Another File</span>
                    </button>

                    <a
                        href="{{ route('dashboard') }}"
                        class="flex items-center rounded-lg border border-gray-300 bg-white px-6 py-3 text-gray-700 transition hover:bg-gray-50"
                    >
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                            />
                        </svg>
                        <span>Back to Dashboard</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
