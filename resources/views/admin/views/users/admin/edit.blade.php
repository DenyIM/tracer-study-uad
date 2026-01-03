@extends('admin.views.layouts.app')

@section('title', 'Edit Administrator')
@section('page-title', 'Edit Data Administrator')

@section('content')
    <div class="card dashboard-card">
        <div class="card-header">
            <h5 class="mb-0">Form Edit Data Administrator</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.views.users.admin.update', $admin->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('fullname') is-invalid @enderror" name="fullname"
                            value="{{ old('fullname', $admin->fullname) }}" required>
                        @error('fullname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email', $admin->user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password (Kosongkan jika tidak diubah)</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="password_confirmation">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"
                            value="{{ old('phone', $admin->phone) }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <select class="form-select @error('job_title') is-invalid @enderror" name="job_title" required>
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
                            <option value="Staff" {{ old('job_title', $admin->job_title) == 'Staff' ? 'selected' : '' }}>
                                Staff
                            </option>
                        </select>
                        @error('job_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.views.users.admin.index') }}" class="btn btn-secondary me-2">
                                <i class="bi bi-x-circle me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i> Update Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
