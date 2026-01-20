<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kode Verifikasi Reset Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f7fa;
        }

        .container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #003366, #004080);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
        }

        .header p {
            margin: 5px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .content {
            padding: 30px;
        }

        .greeting {
            margin-bottom: 20px;
        }

        .greeting h3 {
            color: #003366;
            margin: 0 0 10px 0;
        }

        .message {
            margin-bottom: 25px;
            color: #444;
            font-size: 15px;
        }

        .otp-container {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border: 2px solid #003366;
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            margin: 25px 0;
            position: relative;
            overflow: hidden;
        }

        .otp-container::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #003366, #3b82f6);
        }

        .otp-container p {
            margin: 0 0 10px 0;
            color: #003366;
            font-weight: 600;
            font-size: 16px;
        }

        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #003366;
            letter-spacing: 10px;
            margin: 15px 0;
            padding: 10px;
            background: white;
            border-radius: 8px;
            display: inline-block;
            min-width: 250px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .timer {
            color: #dc3545;
            font-weight: 600;
            margin-top: 10px;
            font-size: 14px;
        }

        .warning-box {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border: 1px solid #ffc107;
            border-left: 4px solid #ffc107;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }

        .warning-box strong {
            color: #856404;
        }

        .warning-box p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #856404;
        }

        .instructions {
            background: #e7f3ff;
            border: 1px solid #b3d7ff;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }

        .instructions h4 {
            color: #003366;
            margin: 0 0 10px 0;
            font-size: 16px;
        }

        .instructions ol {
            margin: 0;
            padding-left: 20px;
            color: #444;
            font-size: 14px;
        }

        .instructions li {
            margin-bottom: 8px;
        }

        .signature {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
        }

        .signature p {
            margin: 5px 0;
            color: #444;
        }

        .signature strong {
            color: #003366;
        }

        .footer {
            background: #003366;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }

        .footer p {
            margin: 5px 0;
            opacity: 0.8;
        }

        .contact {
            margin-top: 10px;
            font-size: 11px;
            opacity: 0.7;
        }

        .logo-placeholder {
            display: inline-block;
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        @media (max-width: 480px) {
            .content {
                padding: 20px;
            }

            .otp-code {
                font-size: 28px;
                letter-spacing: 8px;
                min-width: 200px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>SISTEM TRACER STUDY UAD</h2>
            <p>Reset Password - Universitas Ahmad Dahlan</p>
        </div>

        <div class="content">
            <div class="greeting">
                <h3>Halo {{ $userName ?? 'Alumni UAD' }},</h3>
            </div>

            <div class="message">
                <p>Kami menerima permintaan untuk mereset password akun Tracer Study Anda.</p>
                <p>Untuk melanjutkan proses reset password, gunakan kode verifikasi berikut:</p>
            </div>

            <div class="otp-container">
                <p>KODE VERIFIKASI RESET PASSWORD</p>
                <div class="otp-code">{{ $verificationCode }}</div>
                <div class="timer">⏰ Kode ini berlaku selama 5 menit</div>
            </div>

            <div class="warning-box">
                <strong>⚠️ PERINGATAN KEAMANAN:</strong>
                <p>Jangan bagikan kode ini kepada siapapun. Tim Tracer Study UAD tidak akan pernah meminta kode
                    verifikasi Anda melalui telepon, email, atau media lainnya.</p>
            </div>

            <div class="instructions">
                <h4>Langkah-langkah Reset Password:</h4>
                <ol>
                    <li>Masukkan kode verifikasi di atas pada halaman reset password</li>
                    <li>Buat password baru yang kuat (minimal 8 karakter)</li>
                    <li>Konfirmasi password baru Anda</li>
                    <li>Klik tombol "Reset Password" untuk menyelesaikan proses</li>
                </ol>
            </div>

            <div class="signature">
                <p>Jika Anda <strong>tidak meminta</strong> reset password, segera abaikan email ini dan pastikan akun
                    Anda aman.</p>
                <p>Salam hormat,</p>
                <p><strong>Tim Tracer Study</strong><br>
                    Universitas Ahmad Dahlan</p>
            </div>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} Universitas Ahmad Dahlan. All rights reserved.</p>
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>

</html>
