<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Sistem Informasi Tracer Study UAD</title>
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

        .register-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }

        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
        }

        .register-header {
            background: var(--primary-blue);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .register-logo {
            display: block;
            width: 200px;
            height: auto;
            margin: 2rem auto 1rem auto;
            background: #ffffff;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .register-body {
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

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6b7280;
            cursor: pointer;
        }

        .btn-register {
            background: var(--primary-blue);
            border: none;
            color: white;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-register:hover {
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

        .register-links {
            text-align: center;
            margin-top: 1.5rem;
        }

        .register-links a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
        }

        .register-links a:hover {
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

        .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 51, 102, 0.25);
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

        .text-danger {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .email-help {
            font-size: 0.875rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }

        .invalid-feedback {
            display: none;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>

<body>
    <img src="{{ asset('logo-tracer-study.png') }}" alt="Logo Tracer Study" class="register-logo">

    <div class="register-container">
        <div class="register-card" data-aos="zoom-in" data-aos-duration="600">
            <div class="register-header">
                <h4 class="mb-0">DAFTAR</h4>
            </div>

            <div class="register-body">
                {{-- ERROR MESSAGE --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <!-- Nama Lengkap -->
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="form-control"
                            placeholder="Masukkan nama lengkap Anda" required>
                        @error('nama')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                            id="email" placeholder="namanim@webmail.uad.ac.id" required>
                        <div class="email-help">
                            Format email: namanim@webmail.uad.ac.id
                        </div>
                        <div class="invalid-feedback" id="emailError">
                            Email harus menggunakan format: namanim@webmail.uad.ac.id
                        </div>
                        @error('email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NIM -->
                    <div class="mb-3">
                        <label for="nim" class="form-label fw-semibold">NIM</label>
                        <input type="text" name="nim" value="{{ old('nim') }}" class="form-control"
                            placeholder="Masukkan NIM Anda" required>
                        @error('nim')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Program Studi -->
                    <div class="mb-3">
                        <label for="prodi" class="form-label fw-semibold">Program Studi</label>
                        <select name="prodi" class="form-select" required>
                            <option value="" selected disabled>Pilih Program Studi</option>
                            <option value="Teknik Informatika"
                                {{ old('prodi') == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                            <option value="Sistem Informasi"
                                {{ old('prodi') == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                            <option value="Manajemen" {{ old('prodi') == 'Manajemen' ? 'selected' : '' }}>Manajemen
                            </option>
                            <option value="Akuntansi" {{ old('prodi') == 'Akuntansi' ? 'selected' : '' }}>Akuntansi
                            </option>
                            <option value="Psikologi" {{ old('prodi') == 'Psikologi' ? 'selected' : '' }}>Psikologi
                            </option>
                            <option value="Kedokteran" {{ old('prodi') == 'Kedokteran' ? 'selected' : '' }}>Kedokteran
                            </option>
                            <option value="Farmasi" {{ old('prodi') == 'Farmasi' ? 'selected' : '' }}>Farmasi</option>
                            <option value="Hukum" {{ old('prodi') == 'Hukum' ? 'selected' : '' }}>Hukum</option>
                            <option value="Komunikasi" {{ old('prodi') == 'Komunikasi' ? 'selected' : '' }}>Ilmu
                                Komunikasi</option>
                            <option value="Lainnya" {{ old('prodi') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('prodi')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Lulus -->
                    <div class="mb-3">
                        <label for="tanggal_lulus" class="form-label fw-semibold">Tanggal Lulus</label>
                        <input type="date" name="tanggal_lulus" value="{{ old('tanggal_lulus') }}"
                            class="form-control" required>
                        @error('tanggal_lulus')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NPWP -->
                    <div class="mb-3">
                        <label for="npwp" class="form-label fw-semibold">NPWP (Opsional)</label>
                        <input type="text" name="npwp" value="{{ old('npwp') }}" class="form-control"
                            placeholder="Masukkan NPWP Anda">
                        @error('npwp')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- No HP -->
                    <div class="mb-3">
                        <label for="no_hp" class="form-label fw-semibold">No HP</label>
                        <input type="tel" name="no_hp" value="{{ old('no_hp') }}" class="form-control"
                            placeholder="Masukkan nomor HP Anda" required>
                        @error('no_hp')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" id="password"
                            placeholder="Masukkan password Anda" required>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                        @error('password')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-3 position-relative">
                        <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            id="confirmPassword" placeholder="Konfirmasi password Anda" required>
                        <button type="button" class="password-toggle" id="toggleConfirmPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                        @error('password_confirmation')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Terms Agreement -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="agree_terms" class="form-check-input" id="agreeTerms"
                            {{ old('agree_terms') ? 'checked' : '' }} required>
                        <label class="form-check-label" for="agreeTerms">
                            Saya menyetujui <a href="#" class="text-primary">syarat dan ketentuan</a> yang
                            berlaku
                        </label>
                        @error('agree_terms')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Register Button -->
                    <button type="submit" class="btn btn-register mb-3">Daftar</button>

                    <div class="divider">
                        <span>Atau</span>
                    </div>

                    <!-- Google Register -->
                    <button id="google-register" type="button" class="btn btn-google mb-4">
                        <i class="fab fa-google text-danger"></i>
                        Lanjutkan dengan Akun Google
                    </button>

                    <!-- Links -->
                    <div class="register-links">
                        <div>
                            <span>Sudah Daftar?</span>
                            <a href="{{ route('login') }}"> Login di sini!</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
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
            // Regex untuk format: namanim@webmail.uad.ac.id
            // namanim bisa berupa kombinasi huruf dan angka
            const emailRegex = /^[a-zA-Z0-9]+@webmail\.uad\.ac\.id$/;
            return emailRegex.test(email);
        }

        // Validasi email saat input
        document.getElementById('email').addEventListener('input', function(e) {
            const email = e.target.value;
            const emailError = document.getElementById('emailError');

            if (email && !validateUADEmail(email)) {
                e.target.classList.add('is-invalid');
                emailError.style.display = 'block';
            } else {
                e.target.classList.remove('is-invalid');
                e.target.classList.add('is-valid');
                emailError.style.display = 'none';
            }
        });

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

        // Toggle Confirm Password Visibility
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const icon = this.querySelector('i');

            if (confirmPasswordInput.type === 'password') {
                confirmPasswordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                confirmPasswordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            // Validasi email format UAD
            if (!validateUADEmail(email)) {
                e.preventDefault();
                alert(
                    'Email harus menggunakan format UAD: namanim@webmail.uad.ac.id\nContoh: deny2100018138@webmail.uad.ac.id'
                );
                document.getElementById('email').focus();
                return;
            }

            // Validasi konfirmasi password
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password dan Konfirmasi Password tidak cocok!');
                document.getElementById('confirmPassword').focus();
                return;
            }

            // Validasi password minimal 8 karakter
            if (password.length < 8) {
                e.preventDefault();
                alert('Password minimal harus 8 karakter!');
                document.getElementById('password').focus();
                return;
            }
        });

        // Format NPWP input
        document.querySelector('input[name="npwp"]')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                value = value.match(/.{1,15}/g).join('.');
            }
            e.target.value = value;
        });

        // Format No HP input
        document.querySelector('input[name="no_hp"]')?.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = '62' + value.substring(1);
            }
            e.target.value = value;
        });

        document.getElementById('google-register').addEventListener('click', function() {
            // Tampilkan loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengalihkan...';
            this.disabled = true;

            // Alihkan ke halaman utama
            setTimeout(() => {
                window.location.href = '{{ route('google.redirect') }}';
            }, 1000);
        });

        // Set tanggal maksimal untuk input tanggal
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[name="tanggal_lulus"]').setAttribute('max', today);
    </script>
</body>

</html>
