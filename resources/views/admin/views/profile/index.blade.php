@extends('admin.views.layouts.app')

@section('title', 'Profil Admin')
@section('page-title', 'Profil Saya')

@section('content')
    <div class="row">
        <!-- Profile Information -->
        <div class="col-md-4">
            <div class="card dashboard-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informasi Profil</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-camera"></i> Edit Foto
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#uploadPhotoModal">
                                    <i class="bi bi-upload me-2"></i> Upload Foto Baru
                                </a>
                            </li>
                            @if ($admin->user->pp_url)
                                <li>
                                    <a class="dropdown-item text-danger" href="#" onclick="deletePhoto()">
                                        <i class="bi bi-trash me-2"></i> Hapus Foto
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <img src="{{ $admin->profile_photo_url }}" alt="{{ $admin->fullname }}" class="profile-img-circle"
                            id="profilePhoto"
                            style="width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 4px solid #0d6efd;">
                        <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2" style="cursor: pointer;"
                            data-bs-toggle="modal" data-bs-target="#uploadPhotoModal">
                            <i class="bi bi-camera text-white"></i>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $admin->fullname }}</h4>
                    <p class="text-muted mb-2">{{ $admin->user->email }}</p>
                    <span class="badge bg-primary fs-6">{{ $admin->job_title }}</span>

                    <div class="mt-4 text-start">
                        <div class="info-item">
                            <i class="bi bi-telephone me-2 text-primary"></i>
                            <span>{{ $admin->phone ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-calendar me-2 text-primary"></i>
                            <span>Bergabung: {{ $admin->created_at->format('d F Y') }}</span>
                        </div>
                        <div class="info-item">
                            <i class="bi bi-clock-history me-2 text-primary"></i>
                            <span>Login terakhir:
                                {{ $admin->user->last_login_at ? $admin->user->last_login_at->format('d/m/Y H:i') : 'Belum pernah' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile & Change Password -->
        <div class="col-md-8">
            <!-- Edit Profile Form -->
            <div class="card dashboard-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Edit Profil</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
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
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email', $admin->user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" value="{{ old('phone', $admin->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <select class="form-select @error('job_title') is-invalid @enderror" name="job_title"
                                    required>
                                    <option value="">Pilih Jabatan</option>
                                    <option value="Super Admin"
                                        {{ old('job_title', $admin->job_title) == 'Super Admin' ? 'selected' : '' }}>Super
                                        Admin</option>
                                    <option value="Admin Sistem"
                                        {{ old('job_title', $admin->job_title) == 'Admin Sistem' ? 'selected' : '' }}>Admin
                                        Sistem</option>
                                    <option value="Admin Akademik"
                                        {{ old('job_title', $admin->job_title) == 'Admin Akademik' ? 'selected' : '' }}>
                                        Admin Akademik</option>
                                    <option value="Admin Keuangan"
                                        {{ old('job_title', $admin->job_title) == 'Admin Keuangan' ? 'selected' : '' }}>
                                        Admin Keuangan</option>
                                    <option value="Admin Alumni"
                                        {{ old('job_title', $admin->job_title) == 'Admin Alumni' ? 'selected' : '' }}>Admin
                                        Alumni</option>
                                    <option value="Staff"
                                        {{ old('job_title', $admin->job_title) == 'Staff' ? 'selected' : '' }}>Staff
                                    </option>
                                </select>
                                @error('job_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Form -->
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="mb-0">Ganti Password</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.profile.change-password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                                <input type="password"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password Baru <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konfirmasi Password Baru <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-key me-2"></i> Ganti Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Photo Modal -->
    <div class="modal fade" id="uploadPhotoModal" tabindex="-1" aria-labelledby="uploadPhotoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadPhotoModalLabel">Upload Foto Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.profile.upload-photo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">Pilih Foto</label>
                            <input type="file" class="form-control" id="profile_photo" name="profile_photo"
                                accept="image/*" required>
                            <div class="form-text">
                                Format: JPEG, PNG, JPG, GIF. Maksimal: 2MB.
                            </div>
                        </div>

                        <div class="text-center">
                            <img id="photoPreview" src="#" alt="Preview" class="img-thumbnail d-none"
                                style="max-width: 200px; max-height: 200px;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Upload Foto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .profile-img-circle {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #0d6efd;
            transition: all 0.3s;
        }

        .profile-img-circle:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(13, 110, 253, 0.3);
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
            padding: 8px;
            border-radius: 8px;
            transition: background-color 0.2s;
        }

        .info-item:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        .info-item i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
            font-size: 1.1rem;
        }

        .position-relative .position-absolute {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .position-relative .position-absolute:hover {
            background-color: #0b5ed7 !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Photo preview
        document.getElementById('profile_photo').addEventListener('change', function(e) {
            const preview = document.getElementById('photoPreview');
            const file = e.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }

                reader.readAsDataURL(file);
            } else {
                preview.classList.add('d-none');
            }
        });

        // Delete photo confirmation
        function deletePhoto() {
            if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
                fetch('{{ route('admin.profile.delete-photo') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert('Error: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        location.reload();
                    });
            }
        }
    </script>
@endpush
