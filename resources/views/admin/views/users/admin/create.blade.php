@extends('admin.views.layouts.app')

@section('title', 'Tambah Administrator')
@section('page-title', 'Tambah Administrator Baru')

@section('content')
    @if (!auth()->user()->canCreateAdmin())
        <div class="alert alert-danger">
            <div class="d-flex align-items-center">
                <i class="bi bi-shield-exclamation me-2 fs-4"></i>
                <div>
                    <h5 class="alert-heading mb-1">Akses Ditolak!</h5>
                    <p class="mb-0">
                        Anda tidak memiliki izin untuk menambah admin baru.
                        Hanya <strong>System Administrator</strong> dan <strong>Super Admin</strong> yang dapat menambah
                        admin.
                        <br>
                        <small class="text-muted">Jabatan Anda: <strong>{{ auth()->user()->job_title }}</strong></small>
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('admin.views.users.admin.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
            </a>
        </div>
    @else
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">Form Tambah Administrator</h5>
                <p class="text-muted mb-0 small">Hanya System Administrator dan Super Admin yang dapat menambah
                    administrator baru</p>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.views.users.admin.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                                name="fullname" value="{{ old('fullname') }}" required>
                            @error('fullname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Nama lengkap administrator baru</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Email aktif untuk login</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" id="passwordInput" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                    <i class="bi bi-eye" id="passwordIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Minimal 8 karakter. Password akan digunakan untuk
                                login</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password_confirmation"
                                    id="confirmPasswordInput" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="toggleConfirmPassword()">
                                    <i class="bi bi-eye" id="confirmPasswordIcon"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">Ulangi password untuk konfirmasi</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"
                                value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Nomor telepon aktif</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <select class="form-select @error('job_title') is-invalid @enderror" name="job_title" required>
                                <option value="">Pilih Jabatan</option>
                                <option value="System Administrator"
                                    {{ old('job_title') == 'System Administrator' ? 'selected' : '' }}>
                                    System Administrator
                                </option>
                                <option value="Super Admin" {{ old('job_title') == 'Super Admin' ? 'selected' : '' }}>
                                    Super Admin
                                </option>
                                <option value="Admin Akademik"
                                    {{ old('job_title') == 'Admin Akademik' ? 'selected' : '' }}>
                                    Admin Akademik
                                </option>
                                <option value="Admin Keuangan"
                                    {{ old('job_title') == 'Admin Keuangan' ? 'selected' : '' }}>
                                    Admin Keuangan
                                </option>
                                <option value="Admin Alumni" {{ old('job_title') == 'Admin Alumni' ? 'selected' : '' }}>
                                    Admin Alumni
                                </option>
                                <option value="Staff" {{ old('job_title') == 'Staff' ? 'selected' : '' }}>
                                    Staff
                                </option>
                            </select>
                            @error('job_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <span class="fw-medium text-primary">System Administrator:</span> Hak akses
                                penuh<br>
                                <span class="fw-medium text-warning">Super Admin:</span> Baca dan edit (tidak
                                hapus)<br>
                                <span class="fw-medium text-secondary">Admin lainnya:</span> Hanya baca data
                            </small>
                        </div>

                        <div class="col-12 mb-4">
                            <div class="alert alert-info">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <div>
                                        <h6 class="alert-heading mb-1">Informasi Penting</h6>
                                        <p class="mb-0">
                                            1. Email akan diverifikasi otomatis<br>
                                            2. Admin dapat login segera setelah dibuat<br>
                                            3. Password harus diberikan ke admin baru<br>
                                            4. Jabatan menentukan hak akses admin
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div>
                                    <a href="{{ route('admin.views.users.admin.index') }}" class="btn btn-secondary me-2">
                                        <i class="bi bi-x-circle me-2"></i> Batal
                                    </a>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-warning me-2" onclick="generatePassword()">
                                        <i class="bi bi-key me-2"></i> Generate Password
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i> Simpan Admin Baru
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card dashboard-card mt-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb me-2"></i>
                    Tips Menambahkan Admin
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <div class="d-flex">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <div>
                                <small class="fw-medium">Email harus unik</small>
                                <small class="text-muted d-block">Tidak boleh sama dengan admin lain</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="d-flex">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <div>
                                <small class="fw-medium">Password kuat</small>
                                <small class="text-muted d-block">Gunakan kombinasi huruf, angka, simbol</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="d-flex">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <div>
                                <small class="fw-medium">Jabatan sesuai</small>
                                <small class="text-muted d-block">Tentukan hak akses dengan tepat</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <div class="d-flex">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            <div>
                                <small class="fw-medium">Data valid</small>
                                <small class="text-muted d-block">Pastikan semua data benar</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .input-group .btn-outline-secondary {
            border-color: #ced4da;
            transition: all 0.3s;
        }

        .input-group .btn-outline-secondary:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
        }

        .alert-info {
            border-left: 4px solid #0dcaf0;
            background-color: rgba(13, 202, 240, 0.05);
        }

        .card.bg-light {
            background-color: #f8f9fa !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const passwordIcon = document.getElementById('passwordIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('bi-eye');
                passwordIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('bi-eye-slash');
                passwordIcon.classList.add('bi-eye');
            }
        }

        function toggleConfirmPassword() {
            const confirmInput = document.getElementById('confirmPasswordInput');
            const confirmIcon = document.getElementById('confirmPasswordIcon');

            if (confirmInput.type === 'password') {
                confirmInput.type = 'text';
                confirmIcon.classList.remove('bi-eye');
                confirmIcon.classList.add('bi-eye-slash');
            } else {
                confirmInput.type = 'password';
                confirmIcon.classList.remove('bi-eye-slash');
                confirmIcon.classList.add('bi-eye');
            }
        }

        function generatePassword() {
            const length = 12;
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
            let password = "";

            for (let i = 0; i < length; i++) {
                password += charset.charAt(Math.floor(Math.random() * charset.length));
            }

            document.getElementById('passwordInput').value = password;
            document.getElementById('confirmPasswordInput').value = password;

            // Tampilkan password
            document.getElementById('passwordInput').type = 'text';
            document.getElementById('confirmPasswordInput').type = 'text';
            document.getElementById('passwordIcon').classList.remove('bi-eye');
            document.getElementById('passwordIcon').classList.add('bi-eye-slash');
            document.getElementById('confirmPasswordIcon').classList.remove('bi-eye');
            document.getElementById('confirmPasswordIcon').classList.add('bi-eye-slash');

            // Tampilkan alert
            alert(`Password berhasil digenerate:\n\n${password}\n\nSalin password ini dan berikan ke admin baru.`);
        }
    </script>
@endpush
