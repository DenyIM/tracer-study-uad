<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            max-width: 420px;
        }
        
        .forgot-password-header {
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
        
        .login-logo {
            display: block;
            width: 180px;
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
        }
        
        .verification-input:focus {
            border-color: var(--primary-blue);
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 51, 102, 0.1);
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
    </style>
</head>
<body>
    <img src="{{ asset('logo-tracer-study.png') }}" alt="Logo Tracer Study" class="login-logo">
    
    <div class="forgot-password-container">
        <div class="forgot-password-card" data-aos="zoom-in" data-aos-duration="600">
            <div class="forgot-password-header">    
                <button class="back-button" id="backButton">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h4 class="mb-0">RESET PASSWORD</h4>
            </div>
            
            <div class="forgot-password-body">
                <div class="step-indicator">
                    <div class="step active">1</div>
                    <div class="step-line"></div>
                    <div class="step">2</div>
                    <div class="step-line"></div>
                    <div class="step">3</div>
                </div>
                
                <div class="instruction-box" id="instructionBox">
                    <p><i class="fas fa-info-circle me-2"></i>Masukkan alamat email yang terdaftar. Kami akan mengirimkan kode verifikasi untuk mereset password Anda.</p>
                </div>
                
                <div class="success-message" id="successMessage" style="display: none;">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h5>Email Terkirim!</h5>
                    <p>Kami telah mengirimkan kode verifikasi ke email Anda. Silakan periksa inbox Anda dan masukkan kode di bawah ini.</p>
                </div>
                
                <div id="step1">
                    <form id="emailForm">
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Masukkan email terdaftar Anda" required>
                            <div class="form-text">Pastikan email yang Anda masukkan sudah terdaftar di sistem.</div>
                        </div>
                        
                        <button type="submit" class="btn btn-submit" id="sendCodeBtn">
                            <i class="fas fa-paper-plane me-2"></i> Kirim Kode Verifikasi
                        </button>
                    </form>
                </div>
                
                <div id="step2" style="display: none;">
                    <form id="verificationForm">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Kode Verifikasi</label>
                            <div class="verification-code-inputs">
                                <input type="text" class="verification-input" maxlength="1" data-index="1" required>
                                <input type="text" class="verification-input" maxlength="1" data-index="2" required>
                                <input type="text" class="verification-input" maxlength="1" data-index="3" required>
                                <input type="text" class="verification-input" maxlength="1" data-index="4" required>
                                <input type="text" class="verification-input" maxlength="1" data-index="5" required>
                                <input type="text" class="verification-input" maxlength="1" data-index="6" required>
                            </div>
                            <div class="timer" id="timer">
                                Kode akan kadaluarsa dalam: <span id="countdown">05:00</span>
                            </div>
                            <div class="form-text mt-2">Masukkan 6-digit kode yang telah dikirim ke email Anda.</div>
                        </div>
                        
                        <button type="submit" class="btn btn-submit" id="verifyCodeBtn">
                            <i class="fas fa-check-circle me-2"></i> Verifikasi Kode
                        </button>
                        
                        <div class="text-center mt-3">
                            <a href="#" id="resendCodeLink">Tidak menerima kode? Kirim ulang</a>
                        </div>
                    </form>
                </div>
                
                <div id="step3" style="display: none;">
                    <form id="newPasswordForm">
                        <div class="mb-3">
                            <label for="newPassword" class="form-label fw-semibold">Password Baru</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="newPassword" placeholder="Masukkan password baru" required>
                                <button type="button" class="password-toggle" id="toggleNewPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">Minimal 8 karakter dengan kombinasi huruf dan angka.</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirmPassword" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="confirmPassword" placeholder="Ulangi password baru" required>
                                <button type="button" class="password-toggle" id="toggleConfirmPassword">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">Pastikan password yang dimasukkan sama.</div>
                        </div>
                        
                        <button type="submit" class="btn btn-submit" id="resetPasswordBtn">
                            <i class="fas fa-key me-2"></i> Reset Password
                        </button>
                    </form>
                </div>
                
                <div class="forgot-password-links">
                    <div>
                        <span>Ingat password Anda?</span>
                        <a href="login.html"> Kembali ke Login</a>
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
        AOS.init({
            duration: 600,
            once: true
        });

        // Variabel global
        let currentStep = 1;
        let countdownInterval;
        let timeLeft = 300; // 5 menit dalam detik

        // Elemen DOM
        const steps = document.querySelectorAll('.step');
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const step3 = document.getElementById('step3');
        const instructionBox = document.getElementById('instructionBox');
        const successMessage = document.getElementById('successMessage');
        const timerElement = document.getElementById('countdown');
        const emailForm = document.getElementById('emailForm');
        const verificationForm = document.getElementById('verificationForm');
        const newPasswordForm = document.getElementById('newPasswordForm');
        const verificationInputs = document.querySelectorAll('.verification-input');

        // Fungsi untuk memformat waktu
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        // Fungsi untuk memulai countdown
        function startCountdown() {
            clearInterval(countdownInterval);
            timeLeft = 300;
            timerElement.textContent = formatTime(timeLeft);
            
            countdownInterval = setInterval(() => {
                timeLeft--;
                timerElement.textContent = formatTime(timeLeft);
                
                if (timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    document.getElementById('verifyCodeBtn').disabled = true;
                    timerElement.textContent = "Kode kadaluarsa";
                    timerElement.style.color = "#ef4444";
                }
            }, 1000);
        }

        // Fungsi untuk reset countdown
        function resetCountdown() {
            clearInterval(countdownInterval);
            timeLeft = 300;
            timerElement.textContent = formatTime(timeLeft);
            timerElement.style.color = "";
            document.getElementById('verifyCodeBtn').disabled = false;
            startCountdown();
        }

        // Fungsi untuk berpindah step
        function goToStep(step) {
            // Sembunyikan semua step
            step1.style.display = 'none';
            step2.style.display = 'none';
            step3.style.display = 'none';
            
            // Reset semua step indicator
            steps.forEach(s => s.classList.remove('active'));
            
            // Tampilkan step yang dipilih
            switch(step) {
                case 1:
                    step1.style.display = 'block';
                    steps[0].classList.add('active');
                    instructionBox.style.display = 'block';
                    successMessage.style.display = 'none';
                    break;
                case 2:
                    step2.style.display = 'block';
                    steps[0].classList.add('active');
                    steps[1].classList.add('active');
                    instructionBox.style.display = 'none';
                    successMessage.style.display = 'block';
                    startCountdown();
                    break;
                case 3:
                    step3.style.display = 'block';
                    steps.forEach(s => s.classList.add('active'));
                    instructionBox.style.display = 'none';
                    successMessage.style.display = 'none';
                    break;
            }
            
            currentStep = step;
        }

        // Back button functionality
        document.getElementById('backButton').addEventListener('click', function() {
            if (currentStep > 1) {
                goToStep(currentStep - 1);
            } else {
                window.location.href = 'login.html';
            }
        });

        // Toggle password visibility
        document.getElementById('toggleNewPassword').addEventListener('click', function() {
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

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
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

        // Verification code input handling
        verificationInputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                // Hanya menerima angka
                this.value = this.value.replace(/\D/g, '');
                
                // Jika ada input dan bukan input terakhir, pindah ke input berikutnya
                if (this.value !== '' && index < verificationInputs.length - 1) {
                    verificationInputs[index + 1].focus();
                }
            });
            
            input.addEventListener('keydown', function(e) {
                // Jika tekan backspace dan input kosong, pindah ke input sebelumnya
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    verificationInputs[index - 1].focus();
                }
            });
        });

        // Form submission handling
        emailForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            
            // Validasi email sederhana
            if (!email || !email.includes('@')) {
                alert('Harap masukkan alamat email yang valid.');
                return;
            }
            
            // Simulasi pengiriman email
            console.log('Mengirim kode verifikasi ke:', email);
            
            // Tampilkan loading state
            const sendCodeBtn = document.getElementById('sendCodeBtn');
            const originalText = sendCodeBtn.innerHTML;
            sendCodeBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengirim...';
            sendCodeBtn.disabled = true;
            
            // Simulasi delay pengiriman
            setTimeout(() => {
                sendCodeBtn.innerHTML = originalText;
                sendCodeBtn.disabled = false;
                
                // Pindah ke step 2
                goToStep(2);
            }, 1500);
        });

        verificationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Kumpulkan kode verifikasi
            let verificationCode = '';
            verificationInputs.forEach(input => {
                verificationCode += input.value;
            });
            
            if (verificationCode.length !== 6) {
                alert('Harap masukkan 6-digit kode verifikasi.');
                return;
            }
            
            // Tampilkan loading state
            const verifyCodeBtn = document.getElementById('verifyCodeBtn');
            const originalText = verifyCodeBtn.innerHTML;
            verifyCodeBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memverifikasi...';
            verifyCodeBtn.disabled = true;
            
            // Simulasi verifikasi
            setTimeout(() => {
                // Untuk demo, kita asumsikan kode 123456 adalah valid
                if (verificationCode === '123456') {
                    // Pindah ke step 3
                    goToStep(3);
                } else {
                    alert('Kode verifikasi tidak valid. Coba lagi.');
                    verificationInputs.forEach(input => {
                        input.value = '';
                    });
                    verificationInputs[0].focus();
                }
                
                verifyCodeBtn.innerHTML = originalText;
                verifyCodeBtn.disabled = false;
            }, 1000);
        });

        newPasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            // Validasi password
            if (newPassword.length < 8) {
                alert('Password minimal 8 karakter.');
                return;
            }
            
            if (newPassword !== confirmPassword) {
                alert('Password dan konfirmasi password tidak cocok.');
                return;
            }
            
            // Tampilkan loading state
            const resetPasswordBtn = document.getElementById('resetPasswordBtn');
            const originalText = resetPasswordBtn.innerHTML;
            resetPasswordBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';
            resetPasswordBtn.disabled = true;
            
            // Simulasi reset password
            setTimeout(() => {
                alert('Password berhasil direset! Silakan login dengan password baru Anda.');
                window.location.href = 'login.html';
            }, 1500);
        });

        // Resend code link
        document.getElementById('resendCodeLink').addEventListener('click', function(e) {
            e.preventDefault();
            
            // Tampilkan loading state
            const link = this;
            const originalText = link.textContent;
            link.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengirim ulang...';
            link.style.pointerEvents = 'none';
            
            // Simulasi pengiriman ulang
            setTimeout(() => {
                link.innerHTML = originalText;
                link.style.pointerEvents = 'auto';
                
                // Reset countdown
                resetCountdown();
                
                alert('Kode verifikasi telah dikirim ulang ke email Anda.');
            }, 1000);
        });

        // Initialize step 1
        goToStep(1);
    </script>
</body>
</html>