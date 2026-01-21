@extends('admin.views.layouts.app')

@section('title', 'Edit Administrator')
@section('page-title', 'Edit Data Administrator')

@section('content')
    @if (!auth()->user()->canEditAdmin())
        <div class="alert alert-danger">
            <div class="d-flex align-items-center">
                <i class="bi bi-shield-exclamation me-2 fs-4"></i>
                <div>
                    <h5 class="alert-heading mb-1">Akses Ditolak!</h5>
                    <p class="mb-0">
                        Anda tidak memiliki izin untuk mengedit data admin.
                        Hanya <strong>Super Admin</strong> dan <strong>Admin Sistem</strong> yang dapat mengedit data admin.
                        <br>
                        <small class="text-muted">Jabatan Anda: <strong>{{ auth()->user()->job_title }}</strong></small>
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('admin.views.users.admin.show', $admin->id) }}" class="btn btn-primary me-2">
                <i class="bi bi-eye me-2"></i> Lihat Detail
            </a>
            <a href="{{ route('admin.views.users.admin.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
            </a>
        </div>
    @else
        <div class="row">
            <div class="col-md-4">
                <div class="card dashboard-card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Foto Profil</h5>
                        <span class="badge bg-primary">
                            <i class="bi bi-person-badge me-1"></i>
                            {{ $admin->job_title }}
                        </span>
                    </div>
                    <div class="card-body text-center">
                        <div class="position-relative d-inline-block mb-3">
                            @if ($admin->user->pp_url)
                                <img src="{{ asset('storage/' . $admin->user->pp_url) }}" alt="{{ $admin->fullname }}"
                                    class="rounded-circle profile-img-edit"
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($admin->fullname) }}&background=0d6efd&color=fff&size=150'">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->fullname) }}&background=0d6efd&color=fff&size=150"
                                    alt="{{ $admin->fullname }}" class="rounded-circle profile-img-edit">
                            @endif
                        </div>
                        <h5 class="mb-1">{{ $admin->fullname }}</h5>
                        <p class="text-muted mb-3">{{ $admin->user->email }}</p>

                        <form action="{{ route('admin.views.users.admin.upload-photo', $admin->id) }}" method="POST"
                            enctype="multipart/form-data" class="mb-3">
                            @csrf
                            <div class="mb-3">
                                <input type="file" class="form-control form-control-sm" id="profile_photo_edit"
                                    name="profile_photo" accept="image/*" onchange="this.form.submit()">
                                <small class="form-text text-muted">
                                    Klik untuk ganti foto. Format: JPEG, PNG, JPG, GIF. Maks: 2MB
                                </small>
                            </div>
                        </form>

                        @if ($admin->user->pp_url)
                            <form action="{{ route('admin.views.users.admin.delete-photo', $admin->id) }}" method="POST"
                                onsubmit="return confirm('Hapus foto profil admin ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash me-1"></i> Hapus Foto
                                </button>
                            </form>
                        @endif

                        <hr class="my-3">

                        <div class="text-start small">
                            <div class="mb-2">
                                <i class="bi bi-calendar me-2 text-muted"></i>
                                <span class="text-muted">Bergabung:</span>
                                <span class="fw-medium">{{ $admin->created_at->format('d F Y') }}</span>
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-clock-history me-2 text-muted"></i>
                                <span class="text-muted">Login terakhir:</span>
                                <span class="fw-medium">
                                    @if ($admin->user->last_login_at)
                                        {{ $admin->user->last_login_at->format('d/m/Y H:i') }}
                                    @else
                                        Belum pernah
                                    @endif
                                </span>
                            </div>
                            <div>
                                <i class="bi bi-check-circle me-2 text-muted"></i>
                                <span class="text-muted">Status:</span>
                                @if ($admin->user->email_verified_at)
                                    <span class="badge bg-success">Terverifikasi</span>
                                @else
                                    <span class="badge bg-warning">Belum Verifikasi</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card dashboard-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Form Edit Data Administrator</h5>
                        <div>
                            @if ($admin->user_id === auth()->id())
                                <span class="badge bg-info">
                                    <i class="bi bi-person me-1"></i> Akun Anda
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.views.users.admin.update', $admin->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                                        name="fullname" value="{{ old('fullname', $admin->fullname) }}" required>
                                    @error('fullname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Nama lengkap administrator</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email', $admin->user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Email aktif untuk login</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password (Kosongkan jika tidak diubah)</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Minimal 8 karakter. Kosongkan jika tidak ingin
                                        mengubah password</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <input type="password" class="form-control" name="password_confirmation">
                                    <small class="form-text text-muted">Ulangi password baru</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        name="phone" value="{{ old('phone', $admin->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Nomor telepon aktif</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                                    <select class="form-select @error('job_title') is-invalid @enderror" name="job_title"
                                        required>
                                        <option value="">Pilih Jabatan</option>
                                        <option value="Super Admin"
                                            {{ old('job_title', $admin->job_title) == 'Super Admin' ? 'selected' : '' }}>
                                            Super Admin
                                        </option>
                                        <option value="Admin Sistem"
                                            {{ old('job_title', $admin->job_title) == 'Admin Sistem' ? 'selected' : '' }}>
                                            Admin Sistem
                                        </option>
                                        <option value="Admin Akademik"
                                            {{ old('job_title', $admin->job_title) == 'Admin Akademik' ? 'selected' : '' }}>
                                            Admin Akademik
                                        </option>
                                        <option value="Admin Keuangan"
                                            {{ old('job_title', $admin->job_title) == 'Admin Keuangan' ? 'selected' : '' }}>
                                            Admin Keuangan
                                        </option>
                                        <option value="Admin Alumni"
                                            {{ old('job_title', $admin->job_title) == 'Admin Alumni' ? 'selected' : '' }}>
                                            Admin Alumni
                                        </option>
                                        <option value="Staff"
                                            {{ old('job_title', $admin->job_title) == 'Staff' ? 'selected' : '' }}>
                                            Staff
                                        </option>
                                    </select>
                                    @error('job_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Pilih jabatan sesuai tugas</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label d-flex align-items-center justify-content-between">
                                        <span>Status Verifikasi Email</span>
                                        @if (!$admin->user->email_verified_at)
                                            <a href="{{ route('admin.views.users.admin.verify-email', $admin->id) }}"
                                                class="btn btn-sm btn-success"
                                                onclick="return confirm('Verifikasi email admin ini? Admin akan bisa login setelah verifikasi.')">
                                                <i class="bi bi-check-circle me-1"></i> Verifikasi
                                            </a>
                                        @endif
                                    </label>
                                    <div class="form-control-plaintext p-2 bg-light rounded">
                                        @if ($admin->user->email_verified_at)
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                <div>
                                                    <span class="fw-medium">Terverifikasi</span>
                                                    <small class="text-muted d-block">
                                                        {{ $admin->user->email_verified_at->format('d/m/Y H:i') }}
                                                    </small>
                                                </div>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clock-history text-warning me-2"></i>
                                                <div>
                                                    <span class="fw-medium">Belum Terverifikasi</span>
                                                    <small class="text-muted d-block">Admin belum bisa login</small>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Akun Dibuat</label>
                                    <div class="form-control-plaintext p-2 bg-light rounded">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar-plus text-primary me-2"></i>
                                            <div>
                                                <span class="fw-medium">{{ $admin->created_at->format('d F Y') }}</span>
                                                <small class="text-muted d-block">
                                                    {{ $admin->created_at->format('H:i:s') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                        <div>
                                            <a href="{{ route('admin.views.users.admin.show', $admin->id) }}"
                                                class="btn btn-info me-2">
                                                <i class="bi bi-eye me-2"></i> Lihat Detail
                                            </a>
                                            <a href="{{ route('admin.views.users.admin.index') }}"
                                                class="btn btn-secondary me-2">
                                                <i class="bi bi-x-circle me-2"></i> Batal
                                            </a>
                                        </div>
                                        <div>
                                            @if ($admin->user_id === auth()->id())
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-save me-2"></i> Update Profil Saya
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-save me-2"></i> Update Data Admin
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($admin->user_id !== auth()->id() && auth()->user()->canDeleteAdmin())
                    <div class="card dashboard-card mt-4 border-danger">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Zona Bahaya
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-danger mb-0">
                                <div class="d-flex">
                                    <i class="bi bi-exclamation-octagon fs-4 me-2"></i>
                                    <div>
                                        <h6 class="alert-heading">Hapus Administrator</h6>
                                        <p class="mb-2">
                                            Tindakan ini akan menghapus administrator
                                            <strong>"{{ $admin->fullname }}"</strong> secara permanen dari sistem.
                                            <br>
                                            <small class="text-muted">Data yang dihapus tidak dapat dikembalikan.</small>
                                        </p>
                                        <form action="{{ route('admin.views.users.admin.destroy', $admin->id) }}"
                                            method="POST" onsubmit="return confirmDeleteAdmin('{{ $admin->fullname }}')"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash me-1"></i> Hapus Administrator Ini
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .profile-img-edit {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 4px solid #0d6efd;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);
            transition: all 0.3s;
        }

        .profile-img-edit:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.3);
        }

        .form-control-plaintext {
            min-height: 45px;
            padding: 10px 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            display: flex;
            align-items: center;
        }

        .btn-sm {
            padding: 5px 12px;
            font-size: 0.85rem;
        }

        .border-danger {
            border: 2px solid #dc3545;
        }

        .alert-danger {
            border-left: 4px solid #dc3545;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmDeleteAdmin(adminName) {
            return confirm(
                `⚠️ PERINGATAN!\n\nAnda akan menghapus administrator "${adminName}" secara permanen.\n\nTindakan ini tidak dapat dibatalkan. Apakah Anda yakin?`
                );
        }
    </script>
@endpush
