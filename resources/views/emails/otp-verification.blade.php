<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kode Verifikasi OTP</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #003366;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
            border: 1px solid #dee2e6;
            border-top: none;
        }

        .otp-container {
            background: white;
            border: 2px dashed #003366;
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            margin: 20px 0;
        }

        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #003366;
            letter-spacing: 8px;
            margin: 10px 0;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 12px;
        }

        .logo {
            max-width: 200px;
            margin-bottom: 15px;
        }

        .note {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 10px;
            margin: 15px 0;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Sistem Tracer Study UAD</h2>
        <p>Verifikasi Akun Alumni</p>
    </div>

    <div class="content">
        <h3>Halo {{ $userName ?? 'Pengguna' }},</h3>

        <p>Terima kasih telah mendaftar di Sistem Tracer Study Universitas Ahmad Dahlan.</p>

        <p>Gunakan kode OTP berikut untuk memverifikasi akun Anda:</p>

        <div class="otp-container">
            <p><strong>Kode Verifikasi OTP</strong></p>
            <div class="otp-code">{{ $otpCode }}</div>
            <p><small>Kode ini berlaku selama 5 menit</small></p>
        </div>

        <div class="note">
            <strong>⚠️ Penting:</strong> Jangan bagikan kode OTP ini kepada siapapun, termasuk pihak yang mengaku dari
            UAD.
        </div>

        <p>Jika Anda tidak merasa melakukan pendaftaran, silakan abaikan email ini.</p>

        <p>Salam hormat,<br>
            <strong>Tim Tracer Study UAD</strong>
        </p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} Universitas Ahmad Dahlan. All rights reserved.</p>
        <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
    </div>
</body>

</html>
