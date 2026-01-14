<div class="profile-card" data-aos="fade-up">
    <div class="profile-card-header">
        <h3 class="mb-0"><i class="fas fa-user me-2"></i> Informasi Profil</h3>
    </div>

    <div class="profile-card-body">
        <div class="profile-avatar-section">
            <div class="profile-avatar-container">
                <div class="profile-avatar-large" id="profileAvatar"
                    style="{{ $user->pp_url ? 'background-image: url(' . asset('storage/' . $user->pp_url) . ');' : '' }}">
                    @if (!$user->pp_url)
                        {{ strtoupper(substr($alumni->fullname ?? 'US', 0, 2)) }}
                    @endif
                </div>
                <button class="change-photo-btn" onclick="document.getElementById('photoUpload').click()"
                    data-upload-url="{{ route('profile.photo.upload') }}">
                    <i class="fas fa-camera"></i>
                </button>
                <input type="file" id="photoUpload" class="file-upload" accept="image/*"
                    onchange="uploadProfilePhoto(event)">
            </div>
            <h3 class="mb-2" id="profileName">{{ $alumni->fullname ?? 'Alumni' }}</h3>
            <p class="text-muted">{{ $alumni->study_program ?? 'Program Studi' }}
                {{ $alumni->graduation_date ? date('Y', strtotime($alumni->graduation_date)) : '' }}</p>

            <div class="profile-stats">
                <div class="stat-card stat-rank">
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-value" id="rankValue">#{{ $ranking }}</div>
                    <div class="stat-label">Ranking</div>
                </div>

                <div class="stat-card stat-points">
                    <div class="stat-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <div class="stat-value" id="pointsValue">{{ number_format($totalPoints) }}</div>
                    <div class="stat-label">Points</div>
                </div>
            </div>
        </div>

        <form id="profileForm" action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="profile-info-grid" id="profileInfoGrid">
                <div class="info-item">
                    <span class="info-label">Nama Lengkap *</span>
                    <div class="info-value editable-field" data-field="fullname">
                        <span class="field-value">{{ $alumni->fullname ?? '-' }}</span>
                        <input type="text" class="info-input d-none" name="fullname"
                            value="{{ $alumni->fullname ?? '' }}" data-original="{{ $alumni->fullname ?? '' }}"
                            required>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">NIM *</span>
                    <div class="info-value editable-field" data-field="nim">
                        <span class="field-value">{{ $alumni->nim ?? '-' }}</span>
                        <input type="text" class="info-input d-none" name="nim" value="{{ $alumni->nim ?? '' }}"
                            data-original="{{ $alumni->nim ?? '' }}" required>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Jurusan/Prodi *</span>
                    <div class="info-value editable-field" data-field="study_program">
                        <span class="field-value">{{ $alumni->study_program ?? '-' }}</span>
                        <input type="text" class="info-input d-none" name="study_program"
                            value="{{ $alumni->study_program ?? '' }}" required
                            data-original="{{ $alumni->study_program ?? '' }}">
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Tahun Lulus *</span>
                    <div class="info-value editable-field" data-field="graduation_year">
                        <span
                            class="field-value">{{ $alumni->graduation_date ? date('Y', strtotime($alumni->graduation_date)) : '-' }}</span>
                        <input type="number" class="info-input d-none" name="graduation_year"
                            value="{{ $alumni->graduation_date ? date('Y', strtotime($alumni->graduation_date)) : date('Y') }}"
                            min="2000" max="{{ date('Y') + 5 }}" required
                            data-original="{{ $alumni->graduation_date ? date('Y', strtotime($alumni->graduation_date)) : '' }}">
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Email</span>
                    <div class="info-value">
                        <span class="field-value">{{ auth()->user()->email }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Nomor HP/WhatsApp *</span>
                    <div class="info-value editable-field" data-field="phone">
                        <span class="field-value">{{ $alumni->phone ?? '-' }}</span>
                        <input type="text" class="info-input d-none" name="phone"
                            value="{{ $alumni->phone ?? '' }}" data-original="{{ $alumni->phone ?? '' }}" required>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">NPWP</span>
                    <div class="info-value editable-field" data-field="npwp">
                        <span class="field-value">{{ $alumni->npwp ?? '-' }}</span>
                        <input type="text" class="info-input d-none" name="npwp"
                            value="{{ $alumni->npwp ?? '' }}" data-original="{{ $alumni->npwp ?? '' }}">
                    </div>
                </div>
            </div>

            <div class="action-buttons" id="actionButtons">
                <button type="button" class="btn-edit" onclick="enableEditMode()">
                    <i class="fas fa-edit me-2"></i> Edit Profil
                </button>
                <button type="button" class="btn-save d-none" onclick="saveProfileChanges()" id="saveButton">
                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                </button>
                <button type="button" class="btn-cancel d-none" onclick="cancelEdit()" id="cancelButton">
                    <i class="fas fa-times me-2"></i> Batal
                </button>
            </div>
        </form>
    </div>
</div>
