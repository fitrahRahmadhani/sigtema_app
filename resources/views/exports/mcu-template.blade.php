<table>
    <thead>
        <tr>
            <th style="background-color: #4a5568; color: white; font-weight: bold">employee_number</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">result</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">year</th>
        </tr>
    </thead>
    <tbody>
        <!-- BARIS CONTOH - HAPUS SEBELUM UPLOAD -->
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP001</td>
            <td>CONTOH: K1</td>
            <td>CONTOH: {{ $currentYear }}</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP001</td>
            <td>CONTOH: K2</td>
            <td>CONTOH: {{ $currentYear - 1 }}</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP002</td>
            <td>CONTOH: K1</td>
            <td>CONTOH: {{ $currentYear }}</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP002</td>
            <td>CONTOH: K1</td>
            <td>CONTOH: {{ $currentYear - 1 }}</td>
        </tr>

        <!-- ISI DATA ANDA MULAI DARI BARIS INI -->
        <tr>
            <td colspan="3" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="3" style="background-color: #fef3c7; color: #92400e; font-weight: bold; padding: 8px">
                ⚠️ INFORMASI PENTING
            </td>
        </tr>

        @if (! $isSuperadmin)
            <tr>
                <td colspan="3" style="background-color: #fef3c7; color: #92400e; padding: 8px">
                    Anda hanya dapat menginput data MCU untuk karyawan di company Anda: {{ $companyName }}
                </td>
            </tr>
        @else
            <tr>
                <td colspan="3" style="background-color: #fef3c7; color: #92400e; padding: 8px">
                    Anda dapat menginput data MCU untuk semua karyawan dari semua company.
                </td>
            </tr>
        @endif

        <tr>
            <td colspan="3" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="3" style="background-color: #fee2e2; color: #991b1b; font-weight: bold; padding: 8px">
                ❗ PENTING - HAPUS BARIS CONTOH
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #fee2e2; color: #991b1b; padding: 8px">
                Hapus 4 baris yang bertuliskan "CONTOH:" di atas sebelum upload file ini!
            </td>
        </tr>

        <tr>
            <td colspan="3" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; font-weight: bold; padding: 8px">
                📋 ATURAN PENGISIAN DATA MCU
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                1. employee_number: Nomor karyawan yang sudah terdaftar (WAJIB SESUAI dengan daftar di atas)
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                2. result: Hasil MCU (contoh: Fit, Fit with Notes, Unfit, Pending, dll)
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                3. year: Tahun MCU (format: YYYY, contoh: {{ $currentYear }})
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                4. Satu karyawan bisa memiliki MULTIPLE baris MCU (untuk tahun yang berbeda)
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                5. Untuk karyawan yang sama di tahun yang sama, data akan dianggap duplicate
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                6. year harus antara 2000 dan {{ date('Y') + 1 }}
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                7.
                <strong>Semua kolom wajib diisi</strong>
                (tidak boleh kosong)
            </td>
        </tr>
    </tbody>
</table>
