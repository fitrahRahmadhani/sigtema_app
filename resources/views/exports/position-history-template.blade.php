<table>
    <thead>
        <tr>
            <th style="background-color: #4a5568; color: white; font-weight: bold">employee_number</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">position_name</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">work_unit</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">start_date</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">end_date</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">company</th>
        </tr>
    </thead>
    <tbody>
        {{-- BARIS CONTOH - HAPUS SEBELUM UPLOAD --}}
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP001</td>
            <td>CONTOH: Software Engineer</td>
            <td>CONTOH: Technology Department</td>
            <td>CONTOH: 2020-01-15</td>
            <td>CONTOH: 2021-12-31</td>
            <td>CONTOH: PT ABC Technology</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP001</td>
            <td>CONTOH: Senior Software Engineer</td>
            <td>CONTOH: Product Development</td>
            <td>CONTOH: 2022-01-01</td>
            <td>CONTOH: 2023-06-30</td>
            <td>CONTOH: PT ABC Technology</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP001</td>
            <td>CONTOH: Tech Lead</td>
            <td>CONTOH: Engineering</td>
            <td>CONTOH: 2023-07-01</td>
            <td>CONTOH: {{ date('Y-m-d') }}</td>
            <td>CONTOH: PT ABC Technology</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP002</td>
            <td>CONTOH: Sales Executive</td>
            <td>CONTOH: Marketing Department</td>
            <td>CONTOH: 2019-03-01</td>
            <td>CONTOH: 2021-02-28</td>
            <td>CONTOH: PT XYZ Marketing</td>
        </tr>
        {{-- ISI DATA ANDA MULAI DARI BARIS INI --}}

        <tr>
            <td colspan="6" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="6" style="background-color: #fef3c7; color: #92400e; font-weight: bold; padding: 8px">
                ⚠️ INFORMASI PENTING
            </td>
        </tr>

        @if (! $isSuperadmin)
            <tr>
                <td colspan="6" style="background-color: #fef3c7; color: #92400e; padding: 8px">
                    Anda hanya dapat menginput data position history untuk karyawan di company Anda: {{ $companyName }}
                </td>
            </tr>
        @else
            <tr>
                <td colspan="6" style="background-color: #fef3c7; color: #92400e; padding: 8px">
                    Anda dapat menginput data position history untuk semua karyawan dari semua company.
                </td>
            </tr>
        @endif

        <tr>
            <td colspan="6" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="6" style="background-color: #fee2e2; color: #991b1b; font-weight: bold; padding: 8px">
                ❗ PENTING - HAPUS BARIS CONTOH
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #fee2e2; color: #991b1b; padding: 8px">
                Hapus 4 baris yang bertuliskan "CONTOH:" di atas sebelum upload file ini!
            </td>
        </tr>

        <tr>
            <td colspan="6" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; font-weight: bold; padding: 8px">
                📋 ATURAN PENGISIAN DATA POSITION HISTORY
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                1. employee_number: Nomor karyawan yang sudah terdaftar
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                2. position_name: Nama jabatan/posisi (contoh: Software Engineer, Manager, Supervisor)
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                3. work_unit: Unit kerja/departemen (contoh: Technology Department, Marketing, HR)
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                4. start_date: Tanggal mulai jabatan (format: YYYY-MM-DD, contoh: 2020-01-15)
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                5. end_date: Tanggal selesai jabatan (format: YYYY-MM-DD, contoh: 2021-12-31)
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                6. company: Nama perusahaan (untuk history karyawan di perusahaan sebelumnya)
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                7. Satu karyawan bisa memiliki MULTIPLE baris position history (riwayat jabatan)
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                8. end_date harus lebih besar atau sama dengan start_date
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                9. Untuk jabatan yang masih berjalan, gunakan tanggal hari ini sebagai end_date
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                10.
                <strong>Semua kolom wajib diisi</strong>
                (tidak boleh kosong)
            </td>
        </tr>
    </tbody>
</table>
