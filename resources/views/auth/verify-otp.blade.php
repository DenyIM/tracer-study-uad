<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Sistem Informasi Tracer Study UAD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #003366;
            --secondary-blue: #3b82f6;
            --light-blue: #dbeafe;
            --accent-yellow: #fab300;
            --light-yellow: #fef3c7;
        }

        body {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .verify-otp-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .verify-otp-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
        }

        .verify-otp-header {
            background: var(--primary-blue);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .back-button {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-50%) scale(1.1);
        }

        .logo-container {
            text-align: center;
            margin: 2rem 0;
        }

        .verify-otp-logo {
            width: 200px;
            height: auto;
            background: #ffffff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .verify-otp-body {
            padding: 2rem;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 51, 102, 0.25);
        }

        .btn-submit {
            background: var(--primary-blue);
            border: none;
            color: white;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-submit:hover {
            background: var(--secondary-blue);
            transform: translateY(-2px);
        }

        .btn-submit:disabled {
            background: #94a3b8;
            cursor: not-allowed;
            transform: none;
        }

        .info-box {
            background: var(--light-blue);
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary-blue);
            text-align: center;
        }

        .info-box p {
            margin-bottom: 0;
            color: var(--primary-blue);
            font-size: 0.95rem;
        }

        .email-display {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .email-display .email-label {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .email-display .email-value {
            color: var(--primary-blue);
            font-weight: 600;
            font-size: 1.1rem;
            word-break: break-all;
        }

        .verification-code-inputs {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin: 1.5rem 0;
        }

        .verification-input {
            width: 55px;
            height: 55px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            transition: all 0.3s ease;
            background: white;
        }

        .verification-input:focus {
            border-color: var(--primary-blue);
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 51, 102, 0.1);
            transform: scale(1.05);
        }

        .verification-input.filled {
            border-color: var(--primary-blue);
            background-color: var(--light-blue);
        }

        .timer-container {
            text-align: center;
            margin: 1rem 0 2rem 0;
        }

        .timer {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.5rem 1rem;
            background: #f8fafc;
            border-radius: 20px;
            color: var(--primary-blue);
            font-weight: 600;
        }

        .timer.expired {
            color: #ef4444;
        }

        .timer i {
            font-size: 1.2rem;
        }

        .timer span {
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
        }

        .resend-option {
            text-align: center;
            margin-top: 1.5rem;
        }

        .resend-link {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .resend-link:hover {
            color: var(--secondary-blue);
            text-decoration: underline;
        }

        .resend-link:disabled {
            color: #94a3b8;
            cursor: not-allowed;
            text-decoration: none;
        }

        .resend-link i {
            margin-right: 5px;
        }

        .alert-danger {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #7f1d1d;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger i {
            font-size: 1.2rem;
        }

        .alert-success {
            background: #d1fae5;
            border: 1px solid #10b981;
            color: #065f46;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success i {
            font-size: 1.2rem;
        }

        .footer {
            background: var(--primary-blue);
            color: white;
            text-align: center;
            padding: 1.5rem;
            margin-top: auto;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 576px) {
            .verification-code-inputs {
                gap: 8px;
            }

            .verification-input {
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
            }

            .verify-otp-card {
                margin: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="logo-container">
        <img src="{{ asset('logo-tracer-study.png') }}" alt="Logo Tracer Study" class="verify-otp-logo">
    </div>

    <div class="verify-otp-container">
        <div class="verify-otp-card" data-aos="zoom-in" data-aos-duration="600">
            <div class="verify-otp-header">
                <button class="back-button" id="backButton" onclick="window.history.back()">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h4 class="mb-0">VERIFIKASI OTP</h4>
                <p class="mb-0 mt-2" style="font-size: 0.9rem; opacity: 0.9;">Verifikasi Akun Alumni</p>
            </div>

            <div class="verify-otp-body">
                <div class="info-box">
                    <p><i class="fas fa-envelope me-2"></i>Kami telah mengirimkan kode verifikasi 6 digit ke email Anda.
                        Silakan masukkan kode tersebut untuk melanjutkan pendaftaran.</p>
                </div>

                @if (session('email'))
                    <div class="email-display">
                        <div class="email-label">Kode dikirim ke:</div>
                        <div class="email-value">{{ session('email') }}</div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert-success">
                        <i class="fas fa-check-circle"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('otp.verify') }}" id="otpForm">
                    @csrf

                    <input type="hidden" name="email" value="{{ session('email') ?? old('email') }}">

                    <div class="mb-4">
                        <label class="form-label fw-semibold d-block text-center mb-3">Kode Verifikasi (6 Digit)</label>
                        <div class="verification-code-inputs">
                            <input type="text" class="verification-input" name="otp[]" maxlength="1"
                                data-index="1" required autofocus>
                            <input type="text" class="verification-input" name="otp[]" maxlength="1"
                                data-index="2" required>
                            <input type="text" class="verification-input" name="otp[]" maxlength="1"
                                data-index="3" required>
                            <input type="text" class="verification-input" name="otp[]" maxlength="1"
                                data-index="4" required>
                            <input type="text" class="verification-input" name="otp[]" maxlength="1"
                                data-index="5" required>
                            <input type="text" class="verification-input" name="otp[]" maxlength="1"
                                data-index="6" required>
                        </div>

                        <input type="hidden" name="otp_code" id="fullOtp">

                        <div class="timer-container">
                            <div class="timer" id="timer">
                                <i class="fas fa-clock"></i>
                                <span id="countdown">05:00</span>
                            </div>
                        </div>

                        <div class="text-center text-muted mt-2">
                            Kode OTP akan kadaluarsa dalam <span id="minutes">5</span> menit
                        </div>
                    </div>

                    <button type="submit" class="btn btn-submit" id="verifyBtn">
                        <i class="fas fa-check-circle me-2"></i> Verifikasi OTP
                    </button>

                    <div class="resend-option">
                        <p class="text-muted mb-2">Tidak menerima kode?</p>
                        <a href="#" class="resend-link" id="resendLink" data-email="{{ session('email') }}">
                            <i class="fas fa-redo"></i> Kirim ulang kode OTP
                        </a>
                        <div id="resendTimer" class="text-muted small mt-2" style="display: none;">
                            Dapat kirim ulang dalam <span id="resendCountdown">60</span> detik
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('register') }}" class="text-decoration-none">
                            <i class="fas fa-edit me-1"></i> Ganti email pendaftaran
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Tracer Study Universitas Ahmad Dahlan.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 600,
            once: true
        });

        // Variabel global
        let countdownInterval;
        let timeLeft = 300; // 5 menit dalam detik
        let resendTimeLeft = 60; // 60 detik untuk kirim ulang
        let resendInterval;

        // Elemen DOM
        const verificationInputs = document.querySelectorAll('.verification-input');
        const timerElement = document.getElementById('countdown');
        const minutesElement = document.getElementById('minutes');
        const otpForm = document.getElementById('otpForm');
        const fullOtpInput = document.getElementById('fullOtp');
        const verifyBtn = document.getElementById('verifyBtn');
        const resendLink = document.getElementById('resendLink');
        const resendTimer = document.getElementById('resendTimer');
        const resendCountdownElement = document.getElementById('resendCountdown');

        // Fungsi untuk memformat waktu
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        // Fungsi untuk memulai countdown utama
        function startCountdown() {
            clearInterval(countdownInterval);
            timeLeft = 300;
            timerElement.textContent = formatTime(timeLeft);
            minutesElement.textContent = Math.ceil(timeLeft / 60);

            countdownInterval = setInterval(() => {
                timeLeft--;
                timerElement.textContent = formatTime(timeLeft);
                minutesElement.textContent = Math.ceil(timeLeft / 60);

                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    document.getElementById('timer').classList.add('expired');
                    verifyBtn.disabled = true;
                    verifyBtn.innerHTML = '<i class="fas fa-exclamation-circle me-2"></i> OTP Kadaluarsa';

                    // Tampilkan pesan
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert-danger';
                    alertDiv.innerHTML = `
                        <i class="fas fa-exclamation-circle"></i>
                        <div>Kode OTP telah kadaluarsa. Silakan kirim ulang kode baru.</div>
                    `;
                    otpForm.parentNode.insertBefore(alertDiv, otpForm);
                }
            }, 1000);
        }

        // Fungsi untuk memulai countdown kirim ulang
        function startResendCountdown() {
            clearInterval(resendInterval);
            resendTimeLeft = 60;
            resendLink.style.pointerEvents = 'none';
            resendLink.style.opacity = '0.5';
            resendTimer.style.display = 'block';
            resendCountdownElement.textContent = resendTimeLeft;

            resendInterval = setInterval(() => {
                resendTimeLeft--;
                resendCountdownElement.textContent = resendTimeLeft;

                if (resendTimeLeft <= 0) {
                    clearInterval(resendInterval);
                    resendLink.style.pointerEvents = 'auto';
                    resendLink.style.opacity = '1';
                    resendTimer.style.display = 'none';
                }
            }, 1000);
        }

        // Fungsi untuk mengumpulkan kode OTP
        function collectOtp() {
            let otpCode = '';
            verificationInputs.forEach(input => {
                otpCode += input.value;
            });
            fullOtpInput.value = otpCode;
            return otpCode;
        }

        // Handling input OTP
        verificationInputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                // Hanya menerima angka
                this.value = this.value.replace(/\D/g, '');

                // Update kelas untuk styling
                if (this.value !== '') {
                    this.classList.add('filled');
                } else {
                    this.classList.remove('filled');
                }

                // Jika ada input dan bukan input terakhir, pindah ke input berikutnya
                if (this.value !== '' && index < verificationInputs.length - 1) {
                    verificationInputs[index + 1].focus();
                }

                // Kumpulkan OTP
                collectOtp();
            });

            input.addEventListener('keydown', function(e) {
                // Jika tekan backspace dan input kosong, pindah ke input sebelumnya
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    verificationInputs[index - 1].focus();
                }

                // Jika tekan panah kiri, pindah ke input sebelumnya
                if (e.key === 'ArrowLeft' && index > 0) {
                    verificationInputs[index - 1].focus();
                }

                // Jika tekan panah kanan, pindah ke input berikutnya
                if (e.key === 'ArrowRight' && index < verificationInputs.length - 1) {
                    verificationInputs[index + 1].focus();
                }
            });

            // Paste handling
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').replace(/\D/g, '');

                if (pastedData.length === 6) {
                    for (let i = 0; i < 6; i++) {
                        if (verificationInputs[i]) {
                            verificationInputs[i].value = pastedData[i];
                            verificationInputs[i].classList.add('filled');
                        }
                    }
                    verificationInputs[5].focus();
                    collectOtp();
                }
            });
        });

        // Form submission
        otpForm.addEventListener('submit', function(e) {
            const otpCode = collectOtp();

            if (otpCode.length !== 6) {
                e.preventDefault();
                alert('Harap masukkan 6-digit kode OTP yang lengkap.');
                verificationInputs[0].focus();
                return;
            }

            // Tampilkan loading state
            verifyBtn.innerHTML = '<span class="loading-spinner"></span> Memverifikasi...';
            verifyBtn.disabled = true;
        });

        // Kirim ulang OTP
        resendLink.addEventListener('click', function(e) {
            e.preventDefault();

            const email = this.getAttribute('data-email');

            if (!email) {
                alert('Email tidak ditemukan. Silakan kembali ke halaman pendaftaran.');
                return;
            }

            // Tampilkan loading state
            const originalText = resendLink.innerHTML;
            resendLink.innerHTML = '<span class="loading-spinner"></span> Mengirim ulang...';

            // Kirim request AJAX
            fetch('{{ route('otp.resend') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        email: email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reset input OTP
                        verificationInputs.forEach(input => {
                            input.value = '';
                            input.classList.remove('filled');
                        });
                        verificationInputs[0].focus();

                        // Reset dan mulai ulang countdown
                        clearInterval(countdownInterval);
                        startCountdown();

                        // Reset timer OTP kadaluarsa
                        document.getElementById('timer').classList.remove('expired');
                        verifyBtn.disabled = false;
                        verifyBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Verifikasi OTP';

                        // Hapus pesan kadaluarsa jika ada
                        const expiredAlert = document.querySelector('.alert-danger');
                        if (expiredAlert) {
                            expiredAlert.remove();
                        }

                        // Tampilkan pesan sukses
                        const successDiv = document.createElement('div');
                        successDiv.className = 'alert-success';
                        successDiv.innerHTML = `
                        <i class="fas fa-check-circle"></i>
                        <div>Kode OTP baru telah dikirim ke email Anda. Silakan periksa inbox Anda.</div>
                    `;

                        const infoBox = document.querySelector('.info-box');
                        infoBox.parentNode.insertBefore(successDiv, infoBox.nextSibling);

                        // Hapus pesan sukses setelah 5 detik
                        setTimeout(() => {
                            if (successDiv.parentNode) {
                                successDiv.remove();
                            }
                        }, 5000);

                        // Mulai countdown kirim ulang
                        startResendCountdown();

                    } else {
                        alert(data.message || 'Gagal mengirim ulang kode OTP. Silakan coba lagi.');
                    }

                    // Kembalikan teks asli
                    resendLink.innerHTML = originalText;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                    resendLink.innerHTML = originalText;
                });
        });

        // Auto-focus input pertama saat halaman dimuat
        window.addEventListener('load', function() {
            if (verificationInputs[0]) {
                verificationInputs[0].focus();
            }

            // Mulai countdown
            startCountdown();

            // Mulai countdown kirim ulang
            startResendCountdown();
        });

        // Auto-submit jika semua input terisi
        verificationInputs[5].addEventListener('input', function() {
            const otpCode = collectOtp();
            if (otpCode.length === 6) {
                // Auto-submit setelah delay kecil
                setTimeout(() => {
                    verifyBtn.click();
                }, 300);
            }
        });
    </script>
</body>

</html>
