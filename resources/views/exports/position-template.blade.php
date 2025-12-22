<table>
    <thead>
        <tr>
            <th style="background-color: #4a5568; color: white; font-weight: bold">company_id</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">employee_number</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">position_code</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">name</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">job_grade</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">band</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">work_unit</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">directorate</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">department</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">group</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">komp</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">biro</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">section</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">location</th>
        </tr>
    </thead>
    <tbody>
        <!-- BARIS CONTOH - HAPUS SEBELUM UPLOAD -->
        <tr style="background-color: #fed7d7">
            <td>CONTOH: {{ $companyId ?? '1' }}</td>
            <td>CONTOH: EMP001</td>
            <td>CONTOH: POS001</td>
            <td>CONTOH: Software Engineer</td>
            <td>CONTOH: 12</td>
            <td>CONTOH: Band 3</td>
            <td>CONTOH: Software Development</td>
            <td>CONTOH: Technology Directorate</td>
            <td>CONTOH: IT Department</td>
            <td>CONTOH: Engineering Group</td>
            <td>CONTOH: KOMP-001</td>
            <td>CONTOH: Application Development</td>
            <td>CONTOH: Backend Section</td>
            <td>CONTOH: Jakarta Head Office</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td>CONTOH: {{ $companyId ?? '1' }}</td>
            <td>CONTOH: EMP002</td>
            <td>CONTOH: POS002</td>
            <td>CONTOH: Product Manager</td>
            <td>CONTOH: 14</td>
            <td>CONTOH: Band 4</td>
            <td>CONTOH: Product Management</td>
            <td>CONTOH: Business Directorate</td>
            <td>CONTOH: Product Department</td>
            <td>CONTOH: Management Group</td>
            <td>CONTOH: KOMP-002</td>
            <td>CONTOH: Digital Products</td>
            <td>CONTOH: E-commerce Section</td>
            <td>CONTOH: Bandung Branch</td>
        </tr>
        <!-- ISI DATA ANDA MULAI DARI BARIS INI -->

        <tr>
            <td colspan="14" style="height: 10px"></td>
        </tr>

        @if ($isSuperadmin)
            <tr>
                <td colspan="14" style="background-color: #fef3c7; color: #92400e; font-weight: bold; padding: 8px">
                    ⚠️ INFORMASI UNTUK SUPERADMIN
                </td>
            </tr>
            <tr>
                <td colspan="14" style="background-color: #fef3c7; color: #92400e; padding: 8px">
                    Anda harus mengisi company_id secara manual. Berikut daftar Company ID yang tersedia:
                </td>
            </tr>
            @foreach (\App\Models\Company::where('is_active', true)->get() as $company)
                <tr>
                    <td colspan="14" style="background-color: #fef3c7; color: #92400e; padding: 4px 8px">
                        • ID: {{ $company->id }} = {{ $company->name }}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="14" style="background-color: #d1fae5; color: #065f46; font-weight: bold; padding: 8px">
                    ✅ INFORMASI COMPANY
                </td>
            </tr>
            <tr>
                <td colspan="14" style="background-color: #d1fae5; color: #065f46; padding: 8px">
                    Company ID sudah otomatis terisi: {{ $companyId }} - {{ $companyName }}
                </td>
            </tr>
            <tr>
                <td colspan="14" style="background-color: #d1fae5; color: #065f46; padding: 8px">
                    Anda tidak perlu mengisi kolom company_id. Langsung isi data position Anda saja.
                </td>
            </tr>
        @endif

        <tr>
            <td colspan="14" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="14" style="background-color: #fee2e2; color: #991b1b; font-weight: bold; padding: 8px">
                ❗ PENTING - HAPUS BARIS CONTOH
            </td>
        </tr>
        <tr>
            <td colspan="14" style="background-color: #fee2e2; color: #991b1b; padding: 8px">
                Hapus 2 baris yang bertuliskan "CONTOH:" di atas sebelum upload file ini!
            </td>
        </tr>

        <tr>
            <td colspan="14" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="14" style="background-color: #dbeafe; color: #1e40af; font-weight: bold; padding: 8px">
                📋 ATURAN PENGISIAN DATA
            </td>
        </tr>
        <tr>
            <td colspan="14" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                1. company_id: ID perusahaan (untuk superadmin) atau otomatis (untuk non-superadmin)
            </td>
        </tr>
        <tr>
            <td colspan="14" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                2. employee_number: Nomor karyawan (boleh kosong jika posisi belum terisi)
            </td>
        </tr>
        <tr>
            <td colspan="14" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                3. position_code: Kode posisi harus UNIK dalam satu company (wajib diisi)
            </td>
        </tr>
        <tr>
            <td colspan="14" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                4. name: Nama jabatan/posisi (wajib diisi)
            </td>
        </tr>
        <tr>
            <td colspan="14" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                5. job_grade: Grade/job grade posisi (wajib diisi)
            </td>
        </tr>
        <tr>
            <td colspan="14" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                6. band: Band classification (pilih: Band 1, Band 2, Band 3, Band 4, Band 5) (boleh kosong)
            </td>
        </tr>
        <tr>
            <td colspan="14" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                7. work_unit: Unit kerja (boleh kosong)
            </td>
        </tr>
        <tr>
            <td colspan="14" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                8. directorate: Direktorat (boleh kosong)
            </td>
        </tr>
        <tr>
            <td colspan="14" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                9. department: Departemen (boleh kosong)
            </td>
        </tr>
        <tr>
            <td colspan="14" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                10. group: Grup (boleh kosong)
            </td>
        </tr>
        <tr>
            <td colspan="14" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                11. komp: KOMP (boleh kosong)
            </td>
        </tr>
        <tr>
            <td colspan="14" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                12. biro: Biro (boleh kosong)
            </td>
        </tr>
        <tr>
            <td colspan="14" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                13. section: Seksi (boleh kosong)
            </td>
        </tr>
        <tr>
            <td colspan="14" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                14. location: Lokasi kerja (boleh kosong)
            </td>
        </tr>
        <tr>
            <td colspan="14" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                <strong>Note:</strong>
                Kolom dengan "boleh kosong" bisa dikosongkan atau diisi sesuai kebutuhan.
            </td>
        </tr>
    </tbody>
</table>
