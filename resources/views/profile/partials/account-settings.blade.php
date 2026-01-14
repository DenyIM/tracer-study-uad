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
                        <input type="password" class="form-control" id="current_password" name="current_password"
                            required placeholder="Masukkan password saat ini">
                        <small class="text-muted">Password saat ini harus sesuai dengan password lama Anda</small>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru *</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required
                            placeholder="Minimal 8 karakter">
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru *</label>
                        <input type="password" class="form-control" id="new_password_confirmation"
                            name="new_password_confirmation" required placeholder="Ketik ulang password baru">
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
