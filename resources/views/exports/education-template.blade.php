<table>
    <thead>
        <tr>
            <th style="background-color: #4a5568; color: white; font-weight: bold">employee_number</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">institution_name</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">major</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">degree</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">start_date</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">end_date</th>
        </tr>
    </thead>
    <tbody>
        <!-- BARIS CONTOH - HAPUS SEBELUM UPLOAD -->
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP001</td>
            <td>CONTOH: Universitas Indonesia</td>
            <td>CONTOH: Teknik Informatika</td>
            <td>CONTOH: S1</td>
            <td>CONTOH: 2015-08-15</td>
            <td>CONTOH: 2019-07-20</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP001</td>
            <td>CONTOH: Institut Teknologi Bandung</td>
            <td>CONTOH: Sistem Informasi</td>
            <td>CONTOH: S2</td>
            <td>CONTOH: 2020-01-10</td>
            <td>CONTOH: 2022-06-15</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP002</td>
            <td>CONTOH: Universitas Gadjah Mada</td>
            <td>CONTOH: Manajemen</td>
            <td>CONTOH: S1</td>
            <td>CONTOH: 2016-09-01</td>
            <td>CONTOH: 2020-08-30</td>
        </tr>
        <!-- ISI DATA ANDA MULAI DARI BARIS INI -->

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
                    Anda hanya dapat menginput data education untuk karyawan di company Anda: {{ $companyName }}
                </td>
            </tr>
        @else
            <tr>
                <td colspan="6" style="background-color: #fef3c7; color: #92400e; padding: 8px">
                    Anda dapat menginput data education untuk semua karyawan dari semua company.
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
                Hapus 3 baris yang bertuliskan "CONTOH:" di atas sebelum upload file ini!
            </td>
        </tr>

        <tr>
            <td colspan="6" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; font-weight: bold; padding: 8px">
                📋 ATURAN PENGISIAN DATA
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                1. employee_number: Nomor karyawan yang sudah terdaftar
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                2. institution_name: Nama institusi pendidikan (wajib diisi)
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                3. major: Jurusan/program studi (wajib diisi)
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                4. degree: Jenjang pendidikan (contoh: SMA, SMK, D3, D4, S1, S2, S3)
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                5. start_date: Tanggal mulai (format: YYYY-MM-DD, contoh: 2015-08-15)
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                6. end_date: Tanggal selesai (format: YYYY-MM-DD, contoh: 2019-07-20)
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                7. Satu karyawan bisa memiliki MULTIPLE baris education (untuk pendidikan berbeda)
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                8. end_date harus lebih besar atau sama dengan start_date
            </td>
        </tr>
    </tbody>
</table>
