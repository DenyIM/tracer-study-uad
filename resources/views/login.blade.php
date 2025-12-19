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
    </style>
</head>
<body>
    <img src="{{ asset('logo-tracer-study.png') }}" alt="Logo Tracer Study" class="login-logo">
    <div class="login-container">
        <div class="login-card" data-aos="zoom-in" data-aos-duration="600">
            <div class="login-header">    
                <h4 class="mb-0">LOGIN</h4>
            </div>
            
            <div class="login-body">
                <form>
                    <!-- Email Input -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Masukkan email Anda" required>
                    </div>
                    
                    <!-- Password Input -->
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Masukkan password Anda" required>
                        
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    
                    <!-- Login Button -->
                    <button type="submit" class="btn btn-login mb-3">Login</button>
                    
                    <!-- Divider -->
                    <div class="divider">
                        <span>Atau</span>
                    </div>
                    
                    <!-- Google Login -->
                    <button id="google-login" type="button" class="btn btn-google mb-4">
                        <i class="fab fa-google text-danger"></i>
                        Lanjutkan dengan Akun Google
                    </button>
                    
                    <!-- Links -->
                    <div class="login-links">
                        <div class="mb-2">
                            <span>Anda belum Daftar?</span>
                            <a href="/homepage-register"> Daftar di sini!</a>
                        </div>
                        <div>
                            <span>Lupa password?</span>
                            <a href="/lupa-pass">Klik disini!</a>
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

        // Form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            // Tambahkan logika login di sini
            console.log('Login attempt');
            window.location.href = '/nav-kuesioner';
        });

        document.getElementById('google-login').addEventListener('click', function() {
            // Tampilkan loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengalihkan...';
                this.disabled = true;
                
                // Alihkan ke halaman utama
                setTimeout(() => {
                    window.location.href = '/nav-kuesioner';
                }, 1000);
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
    </script>
</body>
</html>