<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Tracer Study UAD</title>
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

        .login-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 420px;
        }

        .login-header {
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

        .login-body {
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

        .invalid-feedback {
            display: none;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .btn-login {
            background: var(--primary-blue);
            border: none;
            color: white;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-login:hover {
            background: var(--secondary-blue);
            transform: translateY(-2px);
        }

        .btn-google {
            background: white;
            border: 2px solid #e2e8f0;
            color: #374151;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-google:hover {
            border-color: var(--primary-blue);
            transform: translateY(-2px);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: #6b7280;
            margin: 1.5rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }

        .divider span {
            padding: 0 1rem;
        }

        .login-links {
            text-align: center;
            margin-top: 1.5rem;
        }

        .login-links a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
        }

        .login-links a:hover {
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

        .form-check-input:checked {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 1rem;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .email-help {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.25rem;
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

        /* Menghilangkan toggle built-in browser untuk password */
        input[type="password"]::-webkit-textfield-decoration-container {
            display: none !important;
        }

        input[type="password"]::-webkit-caps-lock-indicator {
            display: none !important;
        }

        input[type="password"]::-webkit-credentials-auto-fill-button {
            display: none !important;
            visibility: hidden;
            pointer-events: none;
            position: absolute;
            right: 0;
        }

        /* Untuk Chrome, Safari, Edge */
        input[type="password"]::-webkit-reveal-password-button,
        input[type="password"]::-webkit-reveal-password {
            display: none !important;
            -webkit-appearance: none;
        }

        /* Untuk Firefox */
        input[type="password"][type="password"]::-ms-reveal,
        input[type="password"][type="password"]::-ms-clear {
            display: none !important;
        }

        /* Umum */
        input[type="password"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* Responsif untuk mobile */
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

            .login-card {
                margin: 0 15px;
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

    <div class="login-container">
        <div class="login-card" data-aos="zoom-in" data-aos-duration="600">
            <div class="login-header">
                <h4 class="mb-0">LOGIN</h4>
            </div>

            <div class="login-body">
                <!-- Error Message -->
                @if (session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <!-- Email Input -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ old('email') }}" placeholder="namanim@webmail.uad.ac.id" required>
                        <div class="email-help">
                            Gunakan Email yang telah disediakan UAD!
                            {{-- Admin: nama@domain.uad.ac.id --}}
                        </div>
                        @error('email')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="password-container">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Masukkan password Anda" required>
                            <button type="button" class="password-toggle" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn btn-login mb-3">Login</button>

                    <!-- Divider -->
                    <div class="divider">
                        <span>Atau</span>
                    </div>

                    <!-- Google Login -->
                    <a href="{{ route('google.redirect') }}" class="btn btn-google mb-4">
                        <i class="fab fa-google text-danger"></i>
                        Lanjutkan dengan Akun Google
                    </a>

                    <!-- Links -->
                    <div class="login-links">
                        <div class="mb-2">
                            <span>Anda belum Daftar?</span>
                            <a href="{{ route('register') }}"> Daftar di sini!</a>
                        </div>
                        <div>
                            <span>Lupa password?</span>
                            <a href="{{ route('lupa-pass') }}">Klik disini!</a>
                        </div>
                    </div>
                </form>
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

        // Validasi format email UAD (client-side)
        function validateUADEmail(email) {
            // Format alumni: @webmail.uad.ac.id
            const alumniPattern = /^[a-zA-Z0-9]+@webmail\.uad\.ac\.id$/;
            // Format admin: @*.uad.ac.id (di mana * bisa berupa apa saja)
            const adminPattern = /^[a-zA-Z0-9]+@[a-zA-Z0-9.-]+\.uad\.ac\.id$/;

            return alumniPattern.test(email) || adminPattern.test(email);
        }

        // Toggle Password Visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
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

        // Validasi client-side sebelum submit
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;

            if (email && !validateUADEmail(email)) {
                e.preventDefault();
                alert(
                    'Email harus menggunakan format UAD:\n' +
                    '- Alumni: namanim@webmail.uad.ac.id\n' +
                    // '- Admin: nama@domain.uad.ac.id\n\n' +
                    'Contoh Alumni: alumni1234567890@webmail.uad.ac.id'
                    // 'Contoh Admin: admin@fti.uad.ac.id'
                );
                document.getElementById('email').focus();
                return false;
            }

            // Tampilkan loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';
            submitBtn.disabled = true;

            return true;
        });

        // Solusi JavaScript tambahan untuk menghilangkan toggle built-in
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInputs = document.querySelectorAll('input[type="password"]');

            passwordInputs.forEach(input => {
                // Atur attribute untuk mencegah autocomplete yang memicu toggle
                input.setAttribute('autocomplete', 'current-password');
                input.setAttribute('autocapitalize', 'off');
                input.setAttribute('autocorrect', 'off');
            });

            // Validasi email saat input
            const emailInput = document.getElementById('email');
            if (emailInput) {
                emailInput.addEventListener('input', function(e) {
                    const email = e.target.value;

                    if (email && !validateUADEmail(email)) {
                        e.target.classList.add('is-invalid');
                    } else {
                        e.target.classList.remove('is-invalid');
                        e.target.classList.add('is-valid');
                    }
                });
            }
        });
    </script>
</body>

</html>
