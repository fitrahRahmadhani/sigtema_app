<table>
    <thead>
        <tr>
            <th style="background-color: #4a5568; color: white; font-weight: bold">name</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">email</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">password</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">company_id</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">band</th>
            @if ($isSuperadmin)
                <th style="background-color: #4a5568; color: white; font-weight: bold">roles</th>
            @endif
        </tr>
    </thead>
    <tbody>
        {{-- BARIS CONTOH - HAPUS SEBELUM UPLOAD --}}
        @if ($isSuperadmin)
            <tr style="background-color: #fed7d7">
                <td>CONTOH: John Doe</td>
                <td>CONTOH: john.doe@example.com</td>
                <td>CONTOH: Password@123</td>
                <td>CONTOH: 1</td>
                <td>CONTOH: 3</td>
                <td>CONTOH: admin,editor</td>
            </tr>
            <tr style="background-color: #fed7d7">
                <td>CONTOH: Jane Smith</td>
                <td>CONTOH: jane.smith@example.com</td>
                <td>CONTOH: Secure#Pass1</td>
                <td>CONTOH: 2</td>
                <td>CONTOH:</td>
                <td>CONTOH:</td>
            </tr>
        @else
            <tr style="background-color: #fed7d7">
                <td>CONTOH: John Doe</td>
                <td>CONTOH: john.doe@example.com</td>
                <td>CONTOH: Password@123</td>
                <td>CONTOH: {{ $companyId }}</td>
                <td>CONTOH: 3</td>
            </tr>
            <tr style="background-color: #fed7d7">
                <td>CONTOH: Jane Smith</td>
                <td>CONTOH: jane.smith@example.com</td>
                <td>CONTOH: Secure#Pass1</td>
                <td>CONTOH: {{ $companyId }}</td>
                <td>CONTOH:</td>
            </tr>
        @endif

        {{-- ISI DATA ANDA MULAI DARI BARIS INI --}}
        <tr>
            <td colspan="{{ $isSuperadmin ? 6 : 5 }}" style="height: 10px"></td>
        </tr>

        <tr>
            <td
                colspan="{{ $isSuperadmin ? 6 : 5 }}"
                style="background-color: #fef3c7; color: #92400e; font-weight: bold; padding: 8px"
            >
                ⚠️ INFORMASI PENTING
            </td>
        </tr>

        @if (! $isSuperadmin)
            <tr>
                <td colspan="5" style="background-color: #fef3c7; color: #92400e; padding: 8px">
                    Anda hanya dapat menginput user untuk company Anda:
                    <strong>{{ $companyName }} (ID: {{ $companyId }})</strong>
                    . Isi kolom company_id dengan angka
                    <strong>{{ $companyId }}</strong>
                    .
                </td>
            </tr>
            <tr>
                <td colspan="5" style="background-color: #fef3c7; color: #92400e; padding: 8px">
                    Kolom
                    <strong>roles</strong>
                    tidak tersedia untuk non-superadmin. Role hanya bisa diatur oleh superadmin.
                </td>
            </tr>
        @else
            <tr>
                <td colspan="6" style="background-color: #fef3c7; color: #92400e; padding: 8px">
                    Sebagai superadmin, Anda dapat mengisi company_id dan roles sesuai kebutuhan. Lihat daftar referensi
                    di bawah.
                </td>
            </tr>
        @endif

        <tr>
            <td colspan="{{ $isSuperadmin ? 6 : 5 }}" style="height: 10px"></td>
        </tr>

        <tr>
            <td
                colspan="{{ $isSuperadmin ? 6 : 5 }}"
                style="background-color: #fee2e2; color: #991b1b; font-weight: bold; padding: 8px"
            >
                ❗ PENTING - HAPUS BARIS CONTOH
            </td>
        </tr>
        <tr>
            <td colspan="{{ $isSuperadmin ? 6 : 5 }}" style="background-color: #fee2e2; color: #991b1b; padding: 8px">
                Hapus 2 baris yang bertuliskan "CONTOH:" di atas sebelum upload file ini!
            </td>
        </tr>

        <tr>
            <td colspan="{{ $isSuperadmin ? 6 : 5 }}" style="height: 10px"></td>
        </tr>

        <tr>
            <td
                colspan="{{ $isSuperadmin ? 6 : 5 }}"
                style="background-color: #dbeafe; color: #1e40af; font-weight: bold; padding: 8px"
            >
                📋 ATURAN PENGISIAN DATA USER
            </td>
        </tr>
        <tr>
            <td colspan="{{ $isSuperadmin ? 6 : 5 }}" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                1. name: Nama lengkap user —
                <strong>wajib diisi</strong>
            </td>
        </tr>
        <tr>
            <td colspan="{{ $isSuperadmin ? 6 : 5 }}" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                2. email: Alamat email unik —
                <strong>wajib diisi</strong>
                , tidak boleh sama dengan user yang sudah ada
            </td>
        </tr>
        <tr>
            <td colspan="{{ $isSuperadmin ? 6 : 5 }}" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                3. password: Password user —
                <strong>wajib diisi</strong>
                , minimal 12 karakter, harus mengandung huruf besar, huruf kecil, angka, dan simbol (contoh:
                Password@123)
            </td>
        </tr>
        <tr>
            <td colspan="{{ $isSuperadmin ? 6 : 5 }}" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                4. company_id: ID company — boleh dikosongkan. Lihat daftar company di bawah
            </td>
        </tr>
        <tr>
            <td colspan="{{ $isSuperadmin ? 6 : 5 }}" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                5. band: Level band user (1–5) — boleh dikosongkan
            </td>
        </tr>
        @if ($isSuperadmin)
            <tr>
                <td colspan="6" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                    6. roles: Nama role dipisah koma — boleh dikosongkan (contoh:
                    <strong>admin,editor</strong>
                    ). Nama role harus sesuai dengan daftar role di bawah
                </td>
            </tr>
        @endif

        <tr>
            <td colspan="{{ $isSuperadmin ? 6 : 5 }}" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                {{ $isSuperadmin ? '7' : '6' }}. Jika email sudah terdaftar, data bisa di-skip atau di-update
                tergantung pilihan saat import
            </td>
        </tr>

        {{-- Daftar company (superadmin only) --}}
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

        {{-- Daftar roles (superadmin only) --}}
        @if ($isSuperadmin && $roles->isNotEmpty())
            <tr>
                <td colspan="6" style="height: 10px"></td>
            </tr>
            <tr>
                <td colspan="6" style="background-color: #ede9fe; color: #4c1d95; font-weight: bold; padding: 8px">
                    📋 DAFTAR ROLE (Referensi kolom roles)
                </td>
            </tr>
            <tr>
                <td style="background-color: #ede9fe; color: #4c1d95; font-weight: bold; padding: 8px">ID</td>
                <td colspan="5" style="background-color: #ede9fe; color: #4c1d95; font-weight: bold; padding: 8px">
                    Nama Role
                </td>
            </tr>
            @foreach ($roles as $role)
                <tr>
                    <td style="background-color: #f5f3ff; color: #4c1d95; padding: 6px 8px">{{ $role->id }}</td>
                    <td colspan="5" style="background-color: #f5f3ff; color: #4c1d95; padding: 6px 8px">
                        {{ $role->name }}
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
