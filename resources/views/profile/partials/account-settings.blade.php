<div class="profile-card mt-4" data-aos="fade-up" data-aos-delay="100">
    <div class="profile-card-header">
        <h3 class="mb-0"><i class="fas fa-cog me-2"></i> Pengaturan Akun</h3>
    </div>

    <div class="profile-card-body">
        <div class="settings-grid">
            <!-- Ganti Password -->
            <div class="setting-item" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                <div class="setting-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <h4 class="setting-title">Ganti Password</h4>
                <p class="setting-description">
                    Perbarui kata sandi Anda untuk menjaga keamanan akun
                </p>
            </div>

            <!-- Tema Aplikasi -->
            <div class="setting-item">
                <div class="setting-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <h4 class="setting-title">Tema Aplikasi</h4>
                <p class="setting-description">
                    Pilih tema terang atau gelap sesuai preferensi Anda
                </p>
                <div class="theme-toggle">
                    <span>Tema Gelap</span>
                    <label class="toggle-switch">
                        <input type="checkbox" id="themeToggle" onchange="toggleTheme()">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <!-- Pengaturan Notifikasi -->
            <div class="setting-item" onclick="openNotificationModal()">
                <div class="setting-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h4 class="setting-title">Pengaturan Notifikasi</h4>
                <p class="setting-description">
                    Kelola preferensi notifikasi email dan push notification
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ganti Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">
                    <i class="fas fa-lock me-2"></i> Ganti Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="changePasswordForm" action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                <!-- JANGAN GUNAKAN @method('PUT') - GUNAKAN POST SAJA -->
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini *</label>
                        <div class="password-container">
                            <input type="password" class="form-control" id="current_password" name="current_password"
                                required placeholder="Masukkan password saat ini">
                            <button type="button" class="password-toggle" id="toggleCurrentPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <small class="text-muted">Password saat ini harus sesuai dengan password lama Anda</small>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru *</label>
                        <div class="password-container">
                            <input type="password" class="form-control" id="new_password" name="new_password" required
                                placeholder="Minimal 8 karakter">
                            <button type="button" class="password-toggle" id="toggleNewPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru *</label>
                        <div class="password-container">
                            <input type="password" class="form-control" id="new_password_confirmation"
                                name="new_password_confirmation" required placeholder="Ketik ulang password baru">
                            <button type="button" class="password-toggle" id="toggleConfirmNewPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Password baru harus minimal 8 karakter dan berbeda dari password lama
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Ganti Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tambahkan CSS berikut ke dalam file CSS Anda -->
<style>
    /* Container untuk password input dengan toggle */
    .password-container {
        position: relative;
    }

    /* Tombol toggle untuk password */
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

    /* Styling untuk modal */
    .modal-content {
        border-radius: 12px;
        overflow: hidden;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: #003366;
        color: white;
        border-bottom: none;
        padding: 1.5rem;
    }

    .modal-title {
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
    }

    .btn-close {
        filter: invert(1) brightness(2);
    }

    .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #003366;
        box-shadow: 0 0 0 0.2rem rgba(0, 51, 102, 0.25);
    }

    .alert-info {
        background-color: #dbeafe;
        border: none;
        border-radius: 8px;
        color: #1e40af;
    }
</style>

<!-- Tambahkan JavaScript berikut -->
<script>
    // Fungsi untuk toggle password visibility
    function togglePasswordVisibility(inputId, buttonId) {
        const passwordInput = document.getElementById(inputId);
        const toggleButton = document.getElementById(buttonId);

        if (passwordInput && toggleButton) {
            toggleButton.addEventListener('click', function() {
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
        }
    }

    // Inisialisasi toggle password saat modal terbuka
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi untuk setiap field password
        togglePasswordVisibility('current_password', 'toggleCurrentPassword');
        togglePasswordVisibility('new_password', 'toggleNewPassword');
        togglePasswordVisibility('new_password_confirmation', 'toggleConfirmNewPassword');

        // Atur atribut untuk mencegah toggle built-in browser
        const passwordInputs = document.querySelectorAll('input[type="password"]');

        passwordInputs.forEach(input => {
            input.setAttribute('autocomplete', 'new-password');
            input.setAttribute('autocapitalize', 'off');
            input.setAttribute('autocorrect', 'off');
        });

        // Validasi form ganti password
        const changePasswordForm = document.getElementById('changePasswordForm');
        if (changePasswordForm) {
            changePasswordForm.addEventListener('submit', function(e) {
                const currentPassword = document.getElementById('current_password').value;
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('new_password_confirmation').value;

                // Validasi password minimal 8 karakter
                if (newPassword.length < 8) {
                    e.preventDefault();
                    alert('Password baru harus minimal 8 karakter!');
                    document.getElementById('new_password').focus();
                    return;
                }

                // Validasi konfirmasi password
                if (newPassword !== confirmPassword) {
                    e.preventDefault();
                    alert('Password baru dan konfirmasi password tidak cocok!');
                    document.getElementById('new_password_confirmation').focus();
                    return;
                }

                // Validasi password baru tidak sama dengan password lama
                if (newPassword === currentPassword) {
                    e.preventDefault();
                    alert('Password baru harus berbeda dari password lama!');
                    document.getElementById('new_password').focus();
                    return;
                }

                // Tampilkan loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengubah...';
                submitBtn.disabled = true;
            });
        }

        // Reset form saat modal ditutup
        const changePasswordModal = document.getElementById('changePasswordModal');
        if (changePasswordModal) {
            changePasswordModal.addEventListener('hidden.bs.modal', function() {
                // Reset form
                const form = document.getElementById('changePasswordForm');
                if (form) {
                    form.reset();
                }

                // Reset toggle button ke state awal
                const toggleButtons = document.querySelectorAll('.password-toggle i');
                toggleButtons.forEach(icon => {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                });

                // Reset input type ke password
                const passwordInputs = document.querySelectorAll('.password-container input');
                passwordInputs.forEach(input => {
                    input.type = 'password';
                });

                // Reset tombol submit
                const submitBtn = document.querySelector('#changePasswordForm button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = 'Ganti Password';
                    submitBtn.disabled = false;
                }
            });
        }

        // Inisialisasi toggle untuk modal saat dibuka
        if (changePasswordModal) {
            changePasswordModal.addEventListener('shown.bs.modal', function() {
                // Pastikan atribut sudah diatur
                const passwordInputs = this.querySelectorAll('input[type="password"]');
                passwordInputs.forEach(input => {
                    input.setAttribute('autocomplete', 'new-password');
                    input.setAttribute('autocapitalize', 'off');
                    input.setAttribute('autocorrect', 'off');
                });
            });
        }
    });
</script>
