<table>
    <thead>
        <tr>
            <th style="background-color: #4a5568; color: white; font-weight: bold">employee_number</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">aspect_name</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">score</th>
        </tr>
    </thead>
    <tbody>
        <!-- BARIS CONTOH - HAPUS SEBELUM UPLOAD -->
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP001</td>
            <td>CONTOH: ADAPTABILITY</td>
            <td>3</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP001</td>
            <td>CONTOH: EXECUTION</td>
            <td>3</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP002</td>
            <td>CONTOH: COACHING</td>
            <td>4</td>
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
                    Anda hanya dapat menginput data psychogram untuk karyawan di company Anda: {{ $companyName }}
                </td>
            </tr>
        @else
            <tr>
                <td colspan="3" style="background-color: #fef3c7; color: #92400e; padding: 8px">
                    Anda dapat menginput data psychogram untuk semua karyawan dari semua company.
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
                Hapus 3 baris yang bertuliskan "CONTOH:" di atas sebelum upload file ini!
            </td>
        </tr>

        <tr>
            <td colspan="3" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; font-weight: bold; padding: 8px">
                📋 ATURAN PENGISIAN DATA
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                1. employee_number: Kode karyawan yang sudah terdaftar (wajib diisi)
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                2. aspect_name: Nama aspek psychogram (wajib diisi)
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                3. score: Nilai psychogram antara 0-100 (wajib diisi)
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                4. Gunakan aspect_name yang tersedia untuk company Anda:
            </td>
        </tr>

        @foreach ($aspects as $aspect)
            <tr>
                <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                    &nbsp;&nbsp;&nbsp;&nbsp;• {{ $aspect->aspect_name }} ({{ $aspect->aspect_code }})
                    @if (is_null($aspect->company_id))
                        <span style="color: #059669; font-size: 12px">[Global]</span>
                    @endif
                </td>
            </tr>
        @endforeach

        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                5. Satu karyawan bisa memiliki MULTIPLE baris psychogram (untuk aspek berbeda)
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                6. Data duplikat (employee_number + aspect_name sama) akan di-skip atau di-update sesuai pilihan
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                7. Format score: angka bulat antara 0 sampai 100
            </td>
        </tr>
    </tbody>
</table>
