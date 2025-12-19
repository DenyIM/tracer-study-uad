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
                <form>
                    <!-- Nama Lengkap -->
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" placeholder="Masukkan nama lengkap Anda" required>
                    </div>
                    
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Masukkan email Anda" required>
                    </div>

                    <!-- NIM -->
                    <div class="mb-3">
                        <label for="nim" class="form-label fw-semibold">NIM</label>
                        <input type="text" class="form-control" id="nim" placeholder="Masukkan NIM Anda" required>
                    </div>
                    
                    <!-- Program Studi -->
                    <div class="mb-3">
                        <label for="prodi" class="form-label fw-semibold">Program Studi</label>
                        <select class="form-select" id="prodi" required>
                            <option value="" selected disabled>Pilih Program Studi</option>
                            <option value="Teknik Informatika">Teknik Informatika</option>
                            <option value="Sistem Informasi">Sistem Informasi</option>
                            <option value="Manajemen">Manajemen</option>
                            <option value="Akuntansi">Akuntansi</option>
                            <option value="Psikologi">Psikologi</option>
                            <option value="Kedokteran">Kedokteran</option>
                            <option value="Farmasi">Farmasi</option>
                            <option value="Hukum">Hukum</option>
                            <option value="Komunikasi">Ilmu Komunikasi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <!-- Tanggal Lulus -->
                    <div class="mb-3">
                        <label for="tanggal_lulus" class="form-label fw-semibold">Tanggal Lulus</label>
                        <input type="date" class="form-control" id="tanggal_lulus" required>
                    </div>

                    <!-- NPWP -->
                    <div class="mb-3">
                        <label for="npwp" class="form-label fw-semibold">NPWP (Opsional)</label>
                        <input type="text" class="form-control" id="npwp" placeholder="Masukkan NPWP Anda">
                    </div>

                    <!-- No HP -->
                    <div class="mb-3">
                        <label for="no_hp" class="form-label fw-semibold">No HP</label>
                        <input type="tel" class="form-control" id="no_hp" placeholder="Masukkan nomor HP Anda" required>
                    </div>
                    
                    <!-- Password -->
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Masukkan password Anda" required>
                        <button type="button" class="password-toggle" id="togglePassword"></button>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-3 position-relative">
                        <label for="confirmPassword" class="form-label fw-semibold">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="confirmPassword" placeholder="Konfirmasi password Anda" required>
                        <button type="button" class="password-toggle" id="toggleConfirmPassword"></button>
                    </div>
                    
                    <!-- Terms Agreement -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="agreeTerms" required>
                        <label class="form-check-label" for="agreeTerms">
                            Saya menyetujui <a href="#" class="text-primary">syarat dan ketentuan</a> yang berlaku
                        </label>
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
                            <a href="/homepage-login"> Login di sini!</a>
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
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password !== confirmPassword) {
                alert('Password dan Konfirmasi Password tidak cocok!');
                return;
            }
            
            // Jika validasi berhasil, lanjutkan dengan proses pendaftaran
            console.log('Registration attempt');
            // Tambahkan logika pendaftaran di sini

            window.location.href = '/go-to-kuesioner1';
        });

        // Format NPWP input
        document.getElementById('npwp').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                value = value.match(/.{1,15}/g).join('.');
            }
            e.target.value = value;
        });

        // Format No HP input
        document.getElementById('no_hp').addEventListener('input', function(e) {
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
                    window.location.href = '/go-to-register-form';
                }, 1000);
        });
    </script>
</body>
</html>