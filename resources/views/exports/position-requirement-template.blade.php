<table>
    <thead>
        <tr>
            <th style="background-color: #4a5568; color: white; font-weight: bold; padding: 8px">position_code</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold; padding: 8px">name</th>
            <th style="background-color: #4a5568; color: white; font-weight: bold; padding: 8px">value</th>
        </tr>
    </thead>
    <tbody>
        {{-- CONTOH DATA - HAPUS BARIS INI SEBELUM UPLOAD --}}
        <tr style="background-color: #fed7d7">
            <td style="padding: 8px">CONTOH: 50212121</td>
            <td style="padding: 8px">CONTOH: Education</td>
            <td style="padding: 8px">CONTOH: Bachelor's Degree in Mechanical Engineering</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td style="padding: 8px">CONTOH: 50212121</td>
            <td style="padding: 8px">CONTOH: Adaptability</td>
            <td style="padding: 8px">CONTOH: 2</td>
        </tr>
        <tr style="background-color: #fed7d7">
            <td style="padding: 8px">CONTOH: 50212121</td>
            <td style="padding: 8px">CONTOH: Certification</td>
            <td style="padding: 8px">CONTOH: PMP Certified</td>
        </tr>
        {{-- AKHIR CONTOH DATA --}}

        {{-- ISI DATA ANDA DI BAWAH INI --}}
        <tr>
            <td colspan="3" style="height: 20px"></td>
        </tr>

        {{-- INSTRUKSI --}}
        <tr>
            <td colspan="3" style="background-color: #fee2e2; color: #991b1b; font-weight: bold; padding: 12px">
                ⚠️ PERHATIAN SEBELUM UPLOAD
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #fee2e2; color: #991b1b; padding: 8px">
                1. HAPUS 3 BARIS CONTOH DI ATAS (yang berwarna merah muda)
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #fee2e2; color: #991b1b; padding: 8px">
                2. Isi data Anda mulai dari baris setelah instruksi ini
            </td>
        </tr>
        <tr>
            <td colspan="3" style="height: 10px"></td>
        </tr>

        {{-- PETUNJUK PENGISIAN --}}
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; font-weight: bold; padding: 12px">
                📋 PETUNJUK PENGISIAN
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                •
                <strong>position_code</strong>
                : Kode position yang valid (wajib diisi)
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                •
                <strong>name</strong>
                : Nama requirement (wajib diisi)
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                •
                <strong>value</strong>
                : Nilai requirement (boleh kosong)
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                • Satu position bisa memiliki banyak requirements
            </td>
        </tr>
        <tr>
            <td colspan="3" style="background-color: #dbeafe; color: #1e40af; padding: 8px">
                • Jika requirement sudah ada, value akan diupdate
            </td>
        </tr>
    </tbody>
</table>
