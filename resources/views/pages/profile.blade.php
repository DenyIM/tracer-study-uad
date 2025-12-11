<x-app-layout>
    @section('title', 'Profile')
    
    <div class="main-content">
        <div class="profile-header-section" data-aos="fade-down">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-5 fw-bold mb-3">Profil Saya</h1>
                        <p class="lead mb-0">Kelola informasi profil dan pengaturan akun Anda</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="hero-icon">
                            <i class="fas fa-user-circle" style="font-size: 6rem; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container profile-container">
            <div class="profile-card" data-aos="fade-up">
                <div class="profile-card-header">
                    <h3 class="mb-0"><i class="fas fa-user me-2"></i> Informasi Profil</h3>
                </div>
                
                <div class="profile-card-body">
                    <div class="profile-avatar-section">
                        <div class="profile-avatar-container">
                            <div class="profile-avatar-large" id="profileAvatar">DI</div>
                            <button class="change-photo-btn" onclick="document.getElementById('photoUpload').click()">
                                <i class="fas fa-camera"></i>
                            </button>
                            <input type="file" id="photoUpload" class="file-upload" accept="image/*" onchange="handlePhotoUpload(event)">
                        </div>
                        <h3 class="mb-2" id="profileName">Deny Iqbal</h3><br>
                        
                        <div class="profile-stats">
                            <div class="stat-card stat-rank">
                                <div class="stat-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="stat-value" id="rankValue">#24</div>
                                <div class="stat-label">Ranking</div>
                            </div>
                            
                            <div class="stat-card stat-points">
                                <div class="stat-icon">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div class="stat-value" id="pointsValue">15.250</div>
                                <div class="stat-label">Points</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="profile-info-grid" id="profileInfoGrid">
                        <!-- Data akan diisi dengan js -->
                    </div>
                    
                    <div class="action-buttons" id="actionButtons">
                        <button class="btn-edit" onclick="enableEditMode()">
                            <i class="fas fa-edit me-2"></i> Edit Profil
                        </button>
                    </div>

                    <div class="settings-section">
                        <h4 class="mb-4"><i class="fas fa-cog me-2"></i> Pengaturan Akun</h4>
                        
                        <div class="settings-grid">
                            <div class="setting-item" onclick="openPasswordModal()">
                                <div class="setting-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <h4 class="setting-title">Ganti Password</h4>
                                <p class="setting-description">
                                    Perbarui kata sandi Anda untuk menjaga keamanan akun
                                </p>
                            </div>
                            
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
            </div>
        </div>
    </div>

    @push('styles')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/profile.js') }}"></script>
    @endpush
</x-app-layout>



