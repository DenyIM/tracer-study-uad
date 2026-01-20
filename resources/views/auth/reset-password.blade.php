<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lupa Password - Sistem Informasi Tracer Study UAD</title>
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
            position: relative;
        }

        /* Tombol Kembali ke Homepage */
        .back-to-home {
            position: absolute;
            left: 20px;
            top: 20px;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid rgba(255, 255, 255, 0.25);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            z-index: 1000;
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .back-to-home:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
            color: var(--light-yellow);
            text-decoration: none;
        }

        .forgot-password-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .forgot-password-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
        }

        .forgot-password-header {
            background: var(--primary-blue);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .login-logo {
            display: block;
            width: 200px;
            height: auto;
            margin: 2rem auto 1rem auto;
            background: #ffffff;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .forgot-password-body {
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

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-control.is-valid {
            border-color: #198754;
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

        .instruction-box {
            background: var(--light-blue);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--primary-blue);
        }

        .instruction-box p {
            margin-bottom: 0;
            color: var(--primary-blue);
            font-size: 0.9rem;
        }

        .success-message {
            background: #d1fae5;
            border: 1px solid #10b981;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            margin-bottom: 1.5rem;
            display: none;
        }

        .success-icon {
            color: #10b981;
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .forgot-password-links {
            text-align: center;
            margin-top: 1.5rem;
        }

        .forgot-password-links a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-password-links a:hover {
            color: var(--secondary-blue);
            text-decoration: underline;
        }

        .footer {
            background: var(--primary-blue);
            color: white;
            text-align: center;
            padding: 1.5rem;
            margin-top: auto;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-bottom: 1.5rem;
        }

        .step {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .step.active {
            background: var(--primary-blue);
            color: white;
            transform: scale(1.1);
        }

        .step-line {
            width: 40px;
            height: 3px;
            background: #e2e8f0;
        }

        /* OTP INPUT STYLING - DIMODIFIKASI UNTUK PASTE */
        .verification-code-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .verification-input {
            width: 50px;
            height: 50px;
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
            transform: scale(1.05);
        }

        .paste-hint {
            text-align: center;
            color: #6c757d;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .paste-hint i {
            color: var(--primary-blue);
        }

        .timer {
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .timer span {
            color: var(--primary-blue);
            font-weight: 600;
        }

        .timer.expired span {
            color: #dc3545;
        }

        /* Perbaikan untuk password input */
        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
            z-index: 10;
            height: 30px;
            width: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-toggle:hover {
            color: #003366;
        }

        /* Padding untuk input password agar tidak tertutup tombol toggle */
        .password-container input[type="password"],
        .password-container input[type="text"] {
            padding-right: 45px !important;
        }

        /* Alert styling */
        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 1rem;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: none;
        }

        /* Loading spinner */
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

        /* Responsif */
        @media (max-width: 768px) {
            .back-to-home {
                left: 10px;
                top: 10px;
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            .login-logo {
                width: 180px;
                margin-top: 3rem;
            }

            .forgot-password-card {
                margin: 0 15px;
            }

            .verification-input {
                width: 40px;
                height: 40px;
                font-size: 1.2rem;
            }
        }

        @media (max-width: 480px) {
            .back-to-home {
                padding: 0.4rem 0.8rem;
                font-size: 0.85rem;
            }

            .back-to-home span {
                display: none;
            }

            .back-to-home i {
                margin-right: 0;
            }

            .login-logo {
                width: 160px;
            }

            .verification-input {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }

            .verification-code-inputs {
                gap: 6px;
            }
        }
    </style>
</head>

<body>
    <!-- Tombol Kembali ke Homepage -->
    <a href="{{ url('/') }}" class="back-to-home">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Homepage</span>
    </a>

    <img src="{{ asset('logo-tracer-study.png') }}" alt="Logo Tracer Study" class="login-logo">

    <div class="forgot-password-container">
        <div class="forgot-password-card" data-aos="zoom-in" data-aos-duration="600">
            <div class="forgot-password-header">
                <h4 class="mb-0">RESET PASSWORD</h4>
            </div>

            <div class="forgot-password-body">
                <!-- Alert untuk menampilkan pesan error/success dari backend -->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="step-indicator">
                    <div class="step active" id="stepIndicator1">1</div>
                    <div class="step-line"></div>
                    <div class="step" id="stepIndicator2">2</div>
                    <div class="step-line"></div>
                    <div class="step" id="stepIndicator3">3</div>
                </div>

                <div class="instruction-box" id="instructionBox">
                    <p><i class="fas fa-info-circle me-2"></i>Masukkan alamat email yang terdaftar. Kami akan
                        mengirimkan kode verifikasi untuk mereset password Anda.</p>
                </div>

                <div class="success-message" id="successMessage">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h5>Email Terkirim!</h5>
                    <p>Kami telah mengirimkan kode verifikasi ke email Anda. Silakan periksa inbox Anda dan masukkan
                        kode di bawah ini.</p>
                </div>

                <!-- Step 1: Input Email -->
                <div id="step1">
                    <form id="emailForm" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email UAD</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}"
                                placeholder="namanim@webmail.uad.ac.id" required>
                            <div class="form-text" id="emailHelp">Format email: namanim@webmail.uad.ac.id</div>
                            <div class="invalid-feedback" id="emailError" style="display: none;">
                                Email harus menggunakan format: namanim@webmail.uad.ac.id
                            </div>
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                            <!-- Pesan error jika email tidak terdaftar -->
                            <div class="text-danger mt-1" id="emailNotRegisteredError" style="display: none;">
                                <i class="fas fa-exclamation-circle me-1"></i> Email ini belum terdaftar di sistem kami.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-submit" id="sendCodeBtn">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Kode Verifikasi
                        </button>
                    </form>
                </div>

                <!-- Step 2: Verifikasi Kode - DIMODIFIKASI UNTUK SUPPORT PASTE -->
                <div id="step2" style="display: none;">
                    <form id="verificationForm" method="POST">
                        @csrf
                        <input type="hidden" id="verificationEmail" name="email">
                        <input type="hidden" name="verification_code" id="fullVerificationCode">

                        <div class="mb-4">
                            <label class="form-label fw-semibold d-block text-center mb-3">Kode Verifikasi (6
                                Digit)</label>

                            <div class="paste-hint">
                                <i class="fas fa-mouse-pointer me-1"></i> Klik kotak pertama lalu paste (Ctrl+V) kode
                                dari email
                            </div>

                            <div class="verification-code-inputs">
                                <input type="text" class="verification-input" maxlength="1" data-index="1"
                                    name="code[]" required autofocus>
                                <input type="text" class="verification-input" maxlength="1" data-index="2"
                                    name="code[]" required>
                                <input type="text" class="verification-input" maxlength="1" data-index="3"
                                    name="code[]" required>
                                <input type="text" class="verification-input" maxlength="1" data-index="4"
                                    name="code[]" required>
                                <input type="text" class="verification-input" maxlength="1" data-index="5"
                                    name="code[]" required>
                                <input type="text" class="verification-input" maxlength="1" data-index="6"
                                    name="code[]" required>
                            </div>

                            <div class="timer" id="timer">
                                Kode akan kadaluarsa dalam: <span id="countdown">05:00</span>
                            </div>
                            <div class="form-text mt-2 text-center">
                                <i class="fas fa-lightbulb me-1"></i> Salin kode dari email lalu paste di kotak di atas
                            </div>
                            <div class="text-danger mt-1" id="codeError" style="display: none;"></div>
                        </div>

                        <button type="submit" class="btn btn-submit" id="verifyCodeBtn">
                            <i class="fas fa-check-circle me-2"></i> Verifikasi Kode
                        </button>

                        <div class="text-center mt-3">
                            <a href="#" id="resendCodeLink">Tidak menerima kode? Kirim ulang</a>
                        </div>
                    </form>
                </div>

                <!-- Step 3: Password Baru -->
                <div id="step3" style="display: none;">
                    <form id="newPasswordForm" method="POST">
                        @csrf
                        <input type="hidden" id="resetEmail" name="email">
                        <input type="hidden" id="resetToken" name="token">

                        <div class="mb-3">
                            <label for="newPassword" class="form-label fw-semibold">Password Baru</label>
                            <div class="password-container">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="newPassword" name="password" placeholder="Masukkan password baru" required>
                                <button type="button" class="password-toggle" id="toggleNewPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">Minimal 8 karakter dengan kombinasi huruf dan angka.</div>
                            <div class="text-danger mt-1" id="passwordError" style="display: none;"></div>
                        </div>

                        <div class="mb-4">
                            <label for="confirmPassword" class="form-label fw-semibold">Konfirmasi Password
                                Baru</label>
                            <div class="password-container">
                                <input type="password" class="form-control" id="confirmPassword"
                                    name="password_confirmation" placeholder="Ulangi password baru" required>
                                <button type="button" class="password-toggle" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">Pastikan password yang dimasukkan sama.</div>
                            <div class="text-danger mt-1" id="confirmPasswordError" style="display: none;"></div>
                        </div>

                        <button type="submit" class="btn btn-submit" id="resetPasswordBtn">
                            <i class="fas fa-key me-2"></i> Reset Password
                        </button>
                    </form>
                </div>

                <div class="forgot-password-links">
                    <div>
                        <span>Ingat password Anda?</span>
                        <a href="{{ route('login') }}"> Kembali ke Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; 2025 Tracer Study Universitas Ahmad Dahlan.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inisialisasi AOS
        AOS.init({
            duration: 600,
            once: true
        });

        // Validasi format email UAD
        function validateUADEmail(email) {
            const emailRegex = /^[a-zA-Z0-9]+@webmail\.uad\.ac\.id$/;
            return emailRegex.test(email);
        }

        // Variabel global
        let currentStep = 1;
        let countdownInterval;
        let timeLeft = 300;
        let userEmail = '';
        let resetToken = '';

        // Elemen DOM
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        const verificationInputs = document.querySelectorAll('.verification-input');

        // Fungsi untuk berpindah step
        function goToStep(step) {
            // Sembunyikan semua step
            document.getElementById('step1').style.display = 'none';
            document.getElementById('step2').style.display = 'none';
            document.getElementById('step3').style.display = 'none';

            // Reset semua step indicator
            document.getElementById('stepIndicator1').classList.remove('active');
            document.getElementById('stepIndicator2').classList.remove('active');
            document.getElementById('stepIndicator3').classList.remove('active');

            // Tampilkan step yang dipilih
            if (step === 1) {
                document.getElementById('step1').style.display = 'block';
                document.getElementById('stepIndicator1').classList.add('active');
                document.getElementById('instructionBox').style.display = 'block';
                document.getElementById('successMessage').style.display = 'none';
            } else if (step === 2) {
                document.getElementById('step2').style.display = 'block';
                document.getElementById('stepIndicator1').classList.add('active');
                document.getElementById('stepIndicator2').classList.add('active');
                document.getElementById('instructionBox').style.display = 'none';
                document.getElementById('successMessage').style.display = 'block';
                startCountdown();
                // Auto-focus input pertama
                setTimeout(() => {
                    if (verificationInputs[0]) {
                        verificationInputs[0].focus();
                    }
                }, 300);
            } else if (step === 3) {
                document.getElementById('step3').style.display = 'block';
                document.getElementById('stepIndicator1').classList.add('active');
                document.getElementById('stepIndicator2').classList.add('active');
                document.getElementById('stepIndicator3').classList.add('active');
                document.getElementById('instructionBox').style.display = 'none';
                document.getElementById('successMessage').style.display = 'none';
            }

            currentStep = step;
        }

        // Fungsi untuk memulai countdown
        function startCountdown() {
            clearInterval(countdownInterval);
            timeLeft = 300;
            const timerElement = document.getElementById('countdown');
            timerElement.textContent = '05:00';
            timerElement.parentElement.classList.remove('expired');

            countdownInterval = setInterval(() => {
                timeLeft--;
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerElement.textContent =
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    document.getElementById('verifyCodeBtn').disabled = true;
                    timerElement.parentElement.classList.add('expired');
                }
            }, 1000);
        }

        // Fungsi untuk reset countdown
        function resetCountdown() {
            clearInterval(countdownInterval);
            timeLeft = 300;
            const timerElement = document.getElementById('countdown');
            timerElement.textContent = '05:00';
            timerElement.parentElement.classList.remove('expired');
            document.getElementById('verifyCodeBtn').disabled = false;
            startCountdown();
        }

        // FUNGSI UTAMA UNTUK PASTE OTP CODE
        // Fungsi untuk mengupdate kode verifikasi lengkap ke hidden input
        function updateVerificationCode() {
            let fullCode = '';
            verificationInputs.forEach(input => {
                fullCode += input.value;
            });
            document.getElementById('fullVerificationCode').value = fullCode;
            return fullCode;
        }

        // Setup paste functionality untuk OTP inputs
        function setupPasteFunctionality() {
            verificationInputs.forEach((input, index) => {
                // Input handling
                input.addEventListener('input', function(e) {
                    // Hanya menerima angka
                    this.value = this.value.replace(/\D/g, '');

                    // Update styling
                    if (this.value !== '') {
                        this.classList.add('filled');
                    } else {
                        this.classList.remove('filled');
                    }

                    // Jika ada input dan bukan input terakhir, pindah ke input berikutnya
                    if (this.value !== '' && index < verificationInputs.length - 1) {
                        verificationInputs[index + 1].focus();
                    }

                    // Update hidden input
                    updateVerificationCode();

                    // Auto-submit jika semua terisi
                    if (updateVerificationCode().length === 6) {
                        setTimeout(() => {
                            document.getElementById('verifyCodeBtn').click();
                        }, 500);
                    }
                });

                // Keydown handling
                input.addEventListener('keydown', function(e) {
                    // Jika tekan backspace dan input kosong, pindah ke input sebelumnya
                    if (e.key === 'Backspace' && this.value === '' && index > 0) {
                        verificationInputs[index - 1].focus();
                        verificationInputs[index - 1].value = '';
                        verificationInputs[index - 1].classList.remove('filled');
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

                // PASTE HANDLING - FITUR UTAMA
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text').replace(/\D/g, '');

                    if (pastedData.length === 6) {
                        // Isi semua input dengan angka yang dipaste
                        for (let i = 0; i < 6; i++) {
                            if (verificationInputs[i]) {
                                verificationInputs[i].value = pastedData[i];
                                verificationInputs[i].classList.add('filled');
                            }
                        }
                        // Fokus ke input terakhir
                        verificationInputs[5].focus();

                        // Update hidden input
                        updateVerificationCode();

                        // Auto-verify setelah paste
                        setTimeout(() => {
                            document.getElementById('verifyCodeBtn').click();
                        }, 300);
                    } else if (pastedData.length > 0) {
                        // Jika paste data tidak 6 digit, isi sebanyak yang bisa
                        for (let i = 0; i < Math.min(pastedData.length, 6); i++) {
                            if (verificationInputs[i]) {
                                verificationInputs[i].value = pastedData[i];
                                verificationInputs[i].classList.add('filled');
                            }
                        }
                        // Fokus ke input setelah yang terakhir diisi
                        const lastIndex = Math.min(pastedData.length, 6) - 1;
                        if (verificationInputs[lastIndex]) {
                            verificationInputs[lastIndex].focus();
                        }

                        // Update hidden input
                        updateVerificationCode();
                    }
                });
            });
        }

        // Panggil setup paste functionality
        setupPasteFunctionality();

        // Toggle password visibility
        document.getElementById('toggleNewPassword')?.addEventListener('click', function() {
            const passwordInput = document.getElementById('newPassword');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        document.getElementById('toggleConfirmPassword')?.addEventListener('click', function() {
            const passwordInput = document.getElementById('confirmPassword');
            const icon = this.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Validasi email saat input
        document.getElementById('email')?.addEventListener('input', function(e) {
            const email = e.target.value;
            const emailError = document.getElementById('emailError');
            const emailNotRegisteredError = document.getElementById('emailNotRegisteredError');

            if (email && !validateUADEmail(email)) {
                e.target.classList.add('is-invalid');
                emailError.style.display = 'block';
                emailNotRegisteredError.style.display = 'none';
            } else {
                e.target.classList.remove('is-invalid');
                e.target.classList.remove('is-valid');
                emailError.style.display = 'none';
                emailNotRegisteredError.style.display = 'none';
            }
        });

        // Form submission - Step 1
        document.getElementById('emailForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const sendCodeBtn = document.getElementById('sendCodeBtn');
            const emailNotRegisteredError = document.getElementById('emailNotRegisteredError');

            // Validasi email UAD
            if (!validateUADEmail(email)) {
                document.getElementById('emailError').style.display = 'block';
                document.getElementById('email').classList.add('is-invalid');
                return;
            }

            // Tampilkan loading state
            const originalText = sendCodeBtn.innerHTML;
            sendCodeBtn.innerHTML = '<span class="loading-spinner"></span> Mengirim...';
            sendCodeBtn.disabled = true;

            try {
                const response = await fetch('{{ route('password.email') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        email: email
                    })
                });

                const data = await response.json();

                if (data.success) {
                    userEmail = email;
                    document.getElementById('verificationEmail').value = email;
                    document.getElementById('resetEmail').value = email;

                    // Clear any previous errors
                    emailNotRegisteredError.style.display = 'none';

                    // Pindah ke step 2
                    goToStep(2);
                } else {
                    if (data.message.includes('tidak terdaftar')) {
                        emailNotRegisteredError.style.display = 'block';
                        document.getElementById('email').classList.add('is-invalid');
                    } else {
                        alert(data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                    }
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
                console.error('Error:', error);
            } finally {
                sendCodeBtn.innerHTML = originalText;
                sendCodeBtn.disabled = false;
            }
        });

        // Form submission - Step 2
        document.getElementById('verificationForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Kumpulkan kode verifikasi
            let verificationCode = updateVerificationCode();

            if (verificationCode.length !== 6) {
                alert('Harap masukkan 6-digit kode verifikasi.');
                verificationInputs[0].focus();
                return;
            }

            // Tampilkan loading state
            const verifyCodeBtn = document.getElementById('verifyCodeBtn');
            const originalText = verifyCodeBtn.innerHTML;
            verifyCodeBtn.innerHTML = '<span class="loading-spinner"></span> Memverifikasi...';
            verifyCodeBtn.disabled = true;

            try {
                const response = await fetch('{{ route('password.verify-code') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        email: userEmail,
                        code: verificationCode
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Simpan token untuk reset password
                    if (data.token) {
                        resetToken = data.token;
                        document.getElementById('resetToken').value = data.token;
                    }

                    // Pindah ke step 3
                    goToStep(3);
                } else {
                    alert(data.message || 'Kode verifikasi tidak valid atau telah kadaluarsa.');
                    // Reset input
                    verificationInputs.forEach(input => {
                        input.value = '';
                        input.classList.remove('filled');
                    });
                    verificationInputs[0].focus();
                    updateVerificationCode();
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
                console.error('Error:', error);
            } finally {
                verifyCodeBtn.innerHTML = originalText;
                verifyCodeBtn.disabled = false;
            }
        });

        // Form submission - Step 3
        document.getElementById('newPasswordForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            // Validasi password
            if (newPassword.length < 8) {
                document.getElementById('passwordError').textContent = 'Password minimal 8 karakter.';
                document.getElementById('passwordError').style.display = 'block';
                return;
            } else {
                document.getElementById('passwordError').style.display = 'none';
            }

            if (newPassword !== confirmPassword) {
                document.getElementById('confirmPasswordError').textContent =
                    'Password dan konfirmasi password tidak cocok.';
                document.getElementById('confirmPasswordError').style.display = 'block';
                return;
            } else {
                document.getElementById('confirmPasswordError').style.display = 'none';
            }

            // Tampilkan loading state
            const resetPasswordBtn = document.getElementById('resetPasswordBtn');
            const originalText = resetPasswordBtn.innerHTML;
            resetPasswordBtn.innerHTML = '<span class="loading-spinner"></span> Memproses...';
            resetPasswordBtn.disabled = true;

            try {
                const response = await fetch('{{ route('password.reset') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        email: userEmail,
                        token: resetToken,
                        password: newPassword,
                        password_confirmation: confirmPassword
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Password berhasil direset! Silakan login dengan password baru Anda.');
                    window.location.href = '{{ route('login') }}';
                } else {
                    alert(data.message || 'Terjadi kesalahan. Silakan coba lagi.');
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
                console.error('Error:', error);
            } finally {
                resetPasswordBtn.innerHTML = originalText;
                resetPasswordBtn.disabled = false;
            }
        });

        // Resend code link
        document.getElementById('resendCodeLink')?.addEventListener('click', async function(e) {
            e.preventDefault();

            if (!userEmail) {
                alert('Email tidak ditemukan. Silakan mulai dari awal.');
                goToStep(1);
                return;
            }

            // Tampilkan loading state
            const link = this;
            const originalText = link.textContent;
            link.innerHTML = '<span class="loading-spinner"></span> Mengirim ulang...';
            link.style.pointerEvents = 'none';

            try {
                const response = await fetch('{{ route('password.resend') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        email: userEmail
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Kode verifikasi telah dikirim ulang ke email Anda.');
                    resetCountdown();

                    // Reset input fields
                    verificationInputs.forEach(input => {
                        input.value = '';
                        input.classList.remove('filled');
                    });
                    verificationInputs[0].focus();
                    updateVerificationCode();
                } else {
                    alert(data.message || 'Gagal mengirim ulang kode. Silakan coba lagi.');
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
                console.error('Error:', error);
            } finally {
                link.innerHTML = originalText;
                link.style.pointerEvents = 'auto';
            }
        });

        // Initialize step 1 saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            goToStep(1);
        });
    </script>
</body>

</html>
