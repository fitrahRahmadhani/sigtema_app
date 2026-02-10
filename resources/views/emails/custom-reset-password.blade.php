<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Reset Password</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
                font-family: 'Fira Sans', Arial, Helvetica, sans-serif;
            }
            table {
                border-collapse: collapse;
            }
            a {
                color: #417cd6;
                text-decoration: none;
            }
        </style>
    </head>

    <body>
        <table width="100%" bgcolor="#f4f4f4" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center">
                    {{-- Container --}}
                    <table
                        width="600"
                        bgcolor="#ffffff"
                        cellpadding="0"
                        cellspacing="0"
                        role="presentation"
                        style="margin-top: 40px; border-radius: 8px; overflow: hidden"
                    >
                        {{-- Logo --}}
                        <tr>
                            <td align="center" style="padding: 36px 40px">
                                <img
                                    src="https://sigtema.id/assets/logo/sigtema-logo-primary.png"
                                    width="230"
                                    alt="SIGTEMA"
                                    style="display: block; border: 0; max-width: 100%"
                                />
                            </td>
                        </tr>

                        {{-- Title --}}
                        <tr>
                            <td align="center" bgcolor="#fafafa" style="padding: 25px 40px 10px">
                                <h1
                                    style="
                                        margin: 0;
                                        font-size: 24px;
                                        line-height: 1.3;
                                        color: #434343;
                                        font-weight: 700;
                                    "
                                >
                                    Reset Password
                                </h1>
                            </td>
                        </tr>

                        {{-- Content --}}
                        <tr>
                            <td
                                bgcolor="#fafafa"
                                style="padding: 10px 40px; font-size: 15px; line-height: 1.4; color: #333"
                            >
                                <p>Halo {{ $user->name ?? 'Pengguna' }},</p>
                                <p>Kami menerima permintaan untuk mengatur ulang password pada akun Anda.</p>
                                <p>Silakan klik tombol di bawah ini untuk melanjutkan proses reset password:</p>
                            </td>
                        </tr>

                        {{-- Button --}}
                        <tr>
                            <td bgcolor="#fafafa" align="center" style="padding: 20px 40px">
                                <a
                                    href="{{ $url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    style="
                                        display: inline-block;
                                        background-color: #721d21;
                                        color: #ffffff;
                                        padding: 12px 24px;
                                        border-radius: 12px;
                                        font-size: 16px;
                                        font-weight: 500;
                                        width: 60%;
                                        text-align: center;
                                    "
                                >
                                    Reset Password
                                </a>
                            </td>
                        </tr>

                        {{-- Info --}}
                        <tr>
                            <td
                                bgcolor="#fafafa"
                                style="padding: 10px 40px; font-size: 15px; line-height: 1.4; color: #333"
                            >
                                <p>
                                    Link ini hanya berlaku selama
                                    <strong>{{ $expire }} menit</strong>
                                    demi keamanan akun Anda.
                                </p>
                                <p>
                                    Jika Anda tidak merasa melakukan permintaan reset password, abaikan email ini. Tidak
                                    ada perubahan yang akan terjadi pada akun Anda.
                                </p>
                                <p>Terima kasih,</p>
                                <p><strong>Tim SIGTEMA</strong></p>
                            </td>
                        </tr>

                        {{-- Divider --}}
                        <tr>
                            <td bgcolor="#fafafa" style="padding: 10px 40px">
                                <hr style="border: none; border-top: 2px solid #d9d9d9" />
                            </td>
                        </tr>

                        {{-- Fallback Link --}}
                        <tr>
                            <td
                                bgcolor="#fafafa"
                                style="padding: 10px 40px 40px; font-size: 15px; line-height: 1.4; color: #333"
                            >
                                <p>
                                    Apabila Anda mengalami kendala saat mengklik tombol di atas, silakan salin dan
                                    tempel tautan berikut ke browser Anda:
                                </p>
                                <p>
                                    <a
                                        href="{{ $url }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        style="word-break: break-all"
                                    >
                                        {{ $url }}
                                    </a>
                                </p>
                            </td>
                        </tr>
                    </table>

                    {{-- Footer --}}
                    <table
                        width="600"
                        bgcolor="#1b1b1b"
                        cellpadding="0"
                        cellspacing="0"
                        role="presentation"
                        style="margin-bottom: 40px"
                    >
                        <tr>
                            <td align="center" style="padding: 30px; font-size: 14px; line-height: 1.4; color: #d8d8d8">
                                Unit of Opr HC, GA &amp; Asset Management
                                <br />
                                <strong>SIGTEMA</strong>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
