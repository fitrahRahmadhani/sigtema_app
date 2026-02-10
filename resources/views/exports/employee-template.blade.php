<table>
    <thead>
        <tr>
            <th style="background-color: #4a5568; color: white; font-weight: bold">company_id</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">employee_number</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">name</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">email</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">phone</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">birth_date</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">gender</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">password</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">personal_grade</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">band</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">hire_date</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">profile_path</th>
        </tr>
    </thead>
    <tbody>
        {{-- BARIS CONTOH - HAPUS SEBELUM UPLOAD --}}
        <tr style="background-color: #fed7d7">
            <td>CONTOH: {{ $companyId ?? '1' }}</td>
            <td>CONTOH: EMP001</td>
            <td>CONTOH: John Doe</td>
            <td>CONTOH: john@example.com</td>
            <td>CONTOH: 08123456789</td>
            <td>CONTOH: 1990-01-15</td>
            <td>CONTOH: Male</td>
            <td>CONTOH: (kosongkan)</td>
            <td>CONTOH: 12</td>
            <td>CONTOH: 1</td>
            <td>CONTOH: 2024-01-01</td>
            <td>CONTOH: (kosongkan)</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td>CONTOH: {{ $companyId ?? '1' }}</td>
            <td>CONTOH: EMP002</td>
            <td>CONTOH: Jane Smith</td>
            <td>CONTOH: jane@example.com</td>
            <td>CONTOH: 08123456780</td>
            <td>CONTOH: 1992-05-20</td>
            <td>CONTOH: Female</td>
            <td>CONTOH: mypassword</td>
            <td>CONTOH: 10</td>
            <td>CONTOH: 2</td>
            <td>CONTOH: 2024-02-01</td>
            <td>CONTOH: (kosongkan)</td>
        </tr>
        {{-- ISI DATA ANDA MULAI DARI BARIS INI --}}

        <tr>
            <td colspan="12" style="height: 10px"></td>
        </tr>

        @if ($isSuperadmin)
            <tr>
                <td colspan="12" style="background-color: #fef3c7; color: #92400e; font-weight: bold; padding: 8px">
                    ⚠️ INFORMASI UNTUK SUPERADMIN
                </td>
            </tr>
            <tr>
                <td colspan="12" style="background-color: #fef3c7; color: #92400e; padding: 8px">
                    Anda harus mengisi company_id secara manual. Berikut daftar Company ID yang tersedia:
                </td>
            </tr>
            @foreach (\App\Models\Company::where('is_active', true)->get() as $company)
                <tr>
                    <td colspan="12" style="background-color: #fef3c7; color: #92400e; padding: 4px 8px">
                        • ID: {{ $company->id }} = {{ $company->name }}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="12" style="background-color: #d1fae5; color: #065f46; font-weight: bold; padding: 8px">
                    ✅ INFORMASI COMPANY
                </td>
            </tr>
            <tr>
                <td colspan="12" style="background-color: #d1fae5; color: #065f46; padding: 8px">
                    Company ID sudah otomatis terisi: {{ $companyId }} - {{ $companyName }}
                </td>
            </tr>
            <tr>
                <td colspan="12" style="background-color: #d1fae5; color: #065f46; padding: 8px">
                    Gunakan company id tersebut untuk mengisi kolom employee_id.
                </td>
            </tr>
        @endif

        <tr>
            <td colspan="12" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="12" style="background-color: #fee2e2; color: #991b1b; font-weight: bold; padding: 8px">
                ❗ PENTING - HAPUS BARIS CONTOH
            </td>
        </tr>
        <tr>
            <td colspan="12" style="background-color: #fee2e2; color: #991b1b; padding: 8px">
                Hapus 2 baris yang bertuliskan "CONTOH:" di atas sebelum upload file ini!
            </td>
        </tr>

        <tr>
            <td colspan="12" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="12" style="background-color: #dbeafe; color: #1e40af; font-weight: bold; padding: 8px">
                📋 ATURAN PENGISIAN DATA
            </td>
        </tr>
        <tr>
            <td colspan="12" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                1. employee_number: Harus UNIK, tidak boleh sama dengan employee lain
            </td>
        </tr>
        <tr>
            <td colspan="12" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                2. name: Nama lengkap karyawan (wajib diisi)
            </td>
        </tr>
        <tr>
            <td colspan="12" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                3. email: Format email yang valid, contoh: nama @domain.com(wajib diisi)
            </td>
        </tr>
        <tr>
            <td colspan="12" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                4. phone: Nomor telepon, boleh kosong
            </td>
        </tr>
        <tr>
            <td colspan="12" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                5. birth_date: Format YYYY-MM-DD, contoh: 1990-01-15 (boleh kosong)
            </td>
        </tr>
        <tr>
            <td colspan="12" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                6. gender: Tulis "Male" atau "Female" (wajib diisi)
            </td>
        </tr>
        <tr>
            <td colspan="12" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                7. password: Kosongkan untuk password otomatis "password123", atau isi password custom
            </td>
        </tr>
        <tr>
            <td colspan="12" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                8. personal_grade: Grade karyawan (wajib diisi)
            </td>
        </tr>
        <tr>
            <td colspan="12" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                9. band: Band karyawan (wajib diisi)
            </td>
        </tr>
        <tr>
            <td colspan="12" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                10. hire_date: Tanggal masuk kerja, format YYYY-MM-DD (boleh kosong)
            </td>
        </tr>
        <tr>
            <td colspan="12" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                11. profile_path: Path foto profil (boleh kosong)
            </td>
        </tr>
    </tbody>
</table>
