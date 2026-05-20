<table>
    <thead>
        <tr>
            <th style="background-color: #4a5568; color: white; font-weight: bold">employee_number</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">title</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">team</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">category</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">theme</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">event</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">replication</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">status</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">achievement</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold">is_active</th>
        </tr>
    </thead>
    <tbody>
        {{-- BARIS CONTOH - HAPUS SEBELUM UPLOAD --}}
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP001</td>
            <td>CONTOH: Inovasi Penghematan Energi</td>
            <td>CONTOH: Tim IT</td>
            <td>CONTOH: Teknologi</td>
            <td>CONTOH: Digital</td>
            <td>CONTOH: Annual Innovation Award 2024</td>
            <td>CONTOH: Bisa Direplikasi</td>
            <td>CONTOH: Juara 1</td>
            <td>CONTOH: Best of The Best</td>
            <td>CONTOH: 1</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td>CONTOH: EMP002</td>
            <td>CONTOH: Aplikasi Mobile Tracking</td>
            <td>CONTOH: Tim Digital</td>
            <td>CONTOH: Proses</td>
            <td>CONTOH: Inovasi Proses</td>
            <td>CONTOH: Internal Hackathon</td>
            <td>CONTOH: Tidak Bisa Direplikasi</td>
            <td>CONTOH: Peserta</td>
            <td>CONTOH: -</td>
            <td>CONTOH: 1</td>
        </tr>

        {{-- ISI DATA ANDA MULAI DARI BARIS INI --}}
        <tr>
            <td colspan="10" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="10" style="background-color: #fef3c7; color: #92400e; font-weight: bold; padding: 8px">
                ⚠️ INFORMASI PENTING
            </td>
        </tr>

        @if (!$isSuperadmin)
            <tr>
                <td colspan="10" style="background-color: #fef3c7; color: #92400e; padding: 8px">
                    Anda hanya dapat menginput data Innovation untuk karyawan di company Anda: {{ $companyName }}
                </td>
            </tr>
        @else
            <tr>
                <td colspan="10" style="background-color: #fef3c7; color: #92400e; padding: 8px">
                    Anda dapat menginput data Innovation untuk semua karyawan dari semua company.
                </td>
            </tr>
        @endif

        <tr>
            <td colspan="10" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="10" style="background-color: #fee2e2; color: #991b1b; font-weight: bold; padding: 8px">
                ❗ PENTING - HAPUS BARIS CONTOH
            </td>
        </tr>
        <tr>
            <td colspan="10" style="background-color: #fee2e2; color: #991b1b; padding: 8px">
                Hapus 2 baris yang bertuliskan "CONTOH:" di atas sebelum upload file ini!
            </td>
        </tr>

        <tr>
            <td colspan="10" style="height: 10px"></td>
        </tr>

        <tr>
            <td colspan="10" style="background-color: #dbeafe; color: #1e40af; font-weight: bold; padding: 8px">
                📋 ATURAN PENGISIAN DATA INNOVATION
            </td>
        </tr>
        <tr>
            <td colspan="10" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                1. <strong>employee_number</strong>: Nomor karyawan yang sudah terdaftar (wajib)
            </td>
        </tr>
        <tr>
            <td colspan="10" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                2. <strong>title</strong>: Judul inovasi (wajib)
            </td>
        </tr>
        <tr>
            <td colspan="10" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                3. <strong>team</strong>: Nama tim (opsional)
            </td>
        </tr>
        <tr>
            <td colspan="10" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                4. <strong>category</strong>: Kategori inovasi (opsional)
            </td>
        </tr>
        <tr>
            <td colspan="10" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                5. <strong>theme</strong>: Tema inovasi (opsional)
            </td>
        </tr>
        <tr>
            <td colspan="10" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                6. <strong>event</strong>: Nama event/lomba (opsional)
            </td>
        </tr>
        <tr>
            <td colspan="10" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                7. <strong>replication</strong>: "Bisa Direplikasi" atau "Tidak Bisa Direplikasi" (opsional)
            </td>
        </tr>
        <tr>
            <td colspan="10" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                8. <strong>status</strong>: Status inovasi seperti: Peserta, Juara 1, Juara 2, Juara 3
            </td>
        </tr>
        <tr>
            <td colspan="10" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                9. <strong>achievement</strong>: Pencapaian/juara (opsional),  seperti Best of the Best
            </td>
        </tr>
        <tr>
            <td colspan="10" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                10. <strong>is_active</strong>: 1 = Aktif, 0 = Tidak Aktif (default: 1)
            </td>
        </tr>
        <tr>
            <td colspan="10" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                11. Satu employee_number bisa memiliki MULTIPLE baris inovasi (dengan judul berbeda)
            </td>
        </tr>
        <tr>
            <td colspan="10" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                12. Untuk judul inovasi yang sama pada employee yang sama, data akan dianggap duplicate
            </td>
        </tr>
    </tbody>
</table>