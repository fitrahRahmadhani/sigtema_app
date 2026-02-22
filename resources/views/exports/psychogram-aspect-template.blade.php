<table>
    <thead>
        <tr>
            <th style="background-color: #4a5568; color: white; font-weight: bold">company_id</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">aspect_code</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">aspect_name</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">description</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">is_active</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">sort_order</th>
        </tr>
    </thead>
    <tbody>
        {{-- BARIS CONTOH - HAPUS SEBELUM UPLOAD --}}
        @if ($isSuperadmin)
            <tr style="background-color: #fed7d7">
                <td>CONTOH: 1</td>
                <td>CONTOH: INT</td>
                <td>CONTOH: Intelligence</td>
                <td>CONTOH: Kemampuan intelektual umum</td>
                <td>CONTOH: 1</td>
                <td>CONTOH: 1</td>
            </tr>
            <tr style="background-color: #fed7d7">
                <td>CONTOH: 1</td>
                <td>CONTOH: EMO</td>
                <td>CONTOH: Emotional Stability</td>
                <td>CONTOH: Kestabilan emosional</td>
                <td>CONTOH: 1</td>
                <td>CONTOH: 2</td>
            </tr>
            <tr style="background-color: #fed7d7">
                <td>CONTOH: 2</td>
                <td>CONTOH: SOC</td>
                <td>CONTOH: Social Adaptability</td>
                <td>CONTOH:</td>
                <td>CONTOH: 0</td>
                <td>CONTOH: 3</td>
            </tr>
        @else
            <tr style="background-color: #fed7d7">
                <td>CONTOH: {{ $companyId }}</td>
                <td>CONTOH: INT</td>
                <td>CONTOH: Intelligence</td>
                <td>CONTOH: Kemampuan intelektual umum</td>
                <td>CONTOH: 1</td>
                <td>CONTOH: 1</td>
            </tr>
            <tr style="background-color: #fed7d7">
                <td>CONTOH: {{ $companyId }}</td>
                <td>CONTOH: EMO</td>
                <td>CONTOH: Emotional Stability</td>
                <td>CONTOH: Kestabilan emosional</td>
                <td>CONTOH: 1</td>
                <td>CONTOH: 2</td>
            </tr>
            <tr style="background-color: #fed7d7">
                <td>CONTOH: {{ $companyId }}</td>
                <td>CONTOH: SOC</td>
                <td>CONTOH: Social Adaptability</td>
                <td>CONTOH:</td>
                <td>CONTOH: 0</td>
                <td>CONTOH: 3</td>
            </tr>
        @endif

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
                    Anda hanya dapat menginput data untuk company Anda:
                    <strong>{{ $companyName }} (ID: {{ $companyId }})</strong>
                    . Isi kolom company_id dengan angka
                    <strong>{{ $companyId }}</strong>
                    .
                </td>
            </tr>
        @else
            <tr>
                <td colspan="6" style="background-color: #fef3c7; color: #92400e; padding: 8px">
                    Sebagai superadmin, Anda dapat mengisi company_id sesuai company yang dituju. Lihat daftar company
                    di bawah.
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
                📋 ATURAN PENGISIAN DATA PSYCHOGRAM ASPECT
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                1. company_id: ID company yang terdaftar —
                <strong>wajib diisi</strong>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                2. aspect_code: Kode unik aspek (contoh: INT, EMO, SOC) —
                <strong>wajib diisi, tidak boleh duplikat dalam company yang sama</strong>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                3. aspect_name: Nama aspek psikogram (contoh: Intelligence) —
                <strong>wajib diisi</strong>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                4. description: Deskripsi aspek — boleh dikosongkan
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                5. is_active: Status aktif — isi dengan
                <strong>1</strong>
                (aktif) atau
                <strong>0</strong>
                (tidak aktif). Default: 1
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                6. sort_order: Urutan tampil (angka bulat, contoh: 1, 2, 3) — Default: 0
            </td>
        </tr>
        <tr>
            <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                7.
                <strong>company_id + aspect_code bersifat unik</strong>
                — kombinasi yang sama akan dianggap duplikat
            </td>
        </tr>

        {{-- Daftar company (hanya untuk superadmin) --}}
        @if ($isSuperadmin && $companies->isNotEmpty())
            <tr>
                <td colspan="6" style="height: 10px"></td>
            </tr>
            <tr>
                <td colspan="6" style="background-color: #d1fae5; color: #065f46; font-weight: bold; padding: 8px">
                    📋 DAFTAR COMPANY (Referensi company_id)
                </td>
            </tr>
            <tr>
                <td style="background-color: #d1fae5; color: #065f46; font-weight: bold; padding: 8px">ID</td>
                <td colspan="5" style="background-color: #d1fae5; color: #065f46; font-weight: bold; padding: 8px">
                    Nama Company
                </td>
            </tr>
            @foreach ($companies as $company)
                <tr>
                    <td style="background-color: #ecfdf5; color: #065f46; padding: 6px 8px">{{ $company->id }}</td>
                    <td colspan="5" style="background-color: #ecfdf5; color: #065f46; padding: 6px 8px">
                        {{ $company->name }}
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
