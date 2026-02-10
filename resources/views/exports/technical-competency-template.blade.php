<table>
    <thead>
        <tr>
            <th style="background-color: #4a5568; color: white; font-weight: bold">employee_number</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">name</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">issued_by</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">year</th>
        </tr>
    </thead>
    <tbody>
        {{-- BARIS CONTOH - HAPUS SEBELUM UPLOAD --}}
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP001</td>
            <td>CONTOH: AWS Certified Solutions Architect</td>
            <td>CONTOH: Badan Nasional Sertifikasi Profesi</td>
            <td>CONTOH: {{ $currentYear }}</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP001</td>
            <td>CONTOH: Google Cloud Professional Data Engineer</td>
            <td>CONTOH: Badan Nasional Sertifikasi Profesi</td>
            <td>CONTOH: {{ $currentYear - 1 }}</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP002</td>
            <td>CONTOH: Microsoft Certified: Azure Administrator Associate</td>
            <td>CONTOH: MBadan Nasional Sertifikasi Profesi</td>
            <td>CONTOH: {{ $currentYear }}</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP002</td>
            <td>CONTOH: Certified Kubernetes Administrator</td>
            <td>CONTOH: Badan Nasional Sertifikasi Profesi</td>
            <td>CONTOH: {{ $currentYear - 1 }}</td>
        </tr>
        {{-- ISI DATA ANDA MULAI DARI BARIS INI --}}

        <tr>
            <td colspan="4" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="4" style="background-color: #fef3c7; color: #92400e; font-weight: bold; padding: 8px">
                ⚠️ INFORMASI PENTING
            </td>
        </tr>

        @if (! $isSuperadmin)
            <tr>
                <td colspan="4" style="background-color: #fef3c7; color: #92400e; padding: 8px">
                    Anda hanya dapat menginput data technical competency untuk karyawan di company Anda:
                    {{ $companyName }}
                </td>
            </tr>
        @else
            <tr>
                <td colspan="4" style="background-color: #fef3c7; color: #92400e; padding: 8px">
                    Anda dapat menginput data technical competency untuk semua karyawan dari semua company.
                </td>
            </tr>
        @endif

        <tr>
            <td colspan="4" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="4" style="background-color: #fee2e2; color: #991b1b; font-weight: bold; padding: 8px">
                ❗ PENTING - HAPUS BARIS CONTOH
            </td>
        </tr>
        <tr>
            <td colspan="4" style="background-color: #fee2e2; color: #991b1b; padding: 8px">
                Hapus 4 baris yang bertuliskan "CONTOH:" di atas sebelum upload file ini!
            </td>
        </tr>

        <tr>
            <td colspan="4" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="4" style="background-color: #dbeafe; color: #1e40af; font-weight: bold; padding: 8px">
                📋 ATURAN PENGISIAN DATA TECHNICAL COMPETENCY
            </td>
        </tr>
        <tr>
            <td colspan="4" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                1. employee_number: Nomor karyawan yang sudah terdaftar
            </td>
        </tr>
        <tr>
            <td colspan="4" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                2. name: Nama kompetensi/sertifikasi (contoh: Badan Nasional Sertifikasi Profesi, AWS Certified, dll)
            </td>
        </tr>
        <tr>
            <td colspan="4" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                3. issued_by: Lembaga/organisasi yang mengeluarkan sertifikasi
            </td>
        </tr>
        <tr>
            <td colspan="4" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                4. year: Tahun sertifikasi diperoleh (format: YYYY, contoh: {{ $currentYear }})
            </td>
        </tr>
        <tr>
            <td colspan="4" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                5. Satu karyawan bisa memiliki MULTIPLE baris technical competency
            </td>
        </tr>
        <tr>
            <td colspan="4" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                6. Untuk karyawan yang sama dengan nama kompetensi dan tahun yang sama, data akan dianggap duplicate
            </td>
        </tr>
        <tr>
            <td colspan="4" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                7. year harus antara 1900 dan {{ date('Y') }}
            </td>
        </tr>
        <tr>
            <td colspan="4" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                8.
                <strong>Semua kolom wajib diisi</strong>
                (tidak boleh kosong)
            </td>
        </tr>
        <tr>
            <td colspan="4" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                9. Contoh kompetensi teknis: Sertifikasi IT, Sertifikasi Engineering, Sertifikasi Profesional, dll.
            </td>
        </tr>
    </tbody>
</table>
