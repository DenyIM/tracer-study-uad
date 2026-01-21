@extends('admin.views.layouts.app')

@section('title', 'Tambah Alumni')
@section('page-title', 'Tambah Alumni Baru')

@section('content')
    <div class="card dashboard-card">
        <div class="card-header">
            <h5 class="mb-0">Form Tambah Alumni</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.views.users.alumni.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">Informasi Akun</h6>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h6 class="mb-3">Informasi Pribadi</h6>

                        <div class="mb-3">
                            <label for="fullname" class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control @error('fullname') is-invalid @enderror"
                                id="fullname" name="fullname" value="{{ old('fullname') }}" required>
                            @error('fullname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nim" class="form-label">NIM *</label>
                            <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim"
                                name="nim" value="{{ old('nim') }}" required>
                            @error('nim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon *</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                                name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="study_program" class="form-label">Program Studi *</label>
                            <select class="form-select @error('study_program') is-invalid @enderror" id="study_program"
                                name="study_program" required>
                                <option value="">Pilih Program Studi</option>
                                <option value="Informatika" {{ old('study_program') == 'Informatika' ? 'selected' : '' }}>
                                    Informatika
                                </option>
                                <option value="Sistem Informasi"
                                    {{ old('study_program') == 'Sistem Informasi' ? 'selected' : '' }}>
                                    Sistem Informasi
                                </option>
                                <option value="Manajemen" {{ old('study_program') == 'Manajemen' ? 'selected' : '' }}>
                                    Manajemen
                                </option>
                                <option value="Akuntansi" {{ old('study_program') == 'Akuntansi' ? 'selected' : '' }}>
                                    Akuntansi
                                </option>
                            </select>
                            @error('study_program')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="graduation_date" class="form-label">Tanggal Lulus *</label>
                            <input type="date" class="form-control @error('graduation_date') is-invalid @enderror"
                                id="graduation_date" name="graduation_date" value="{{ old('graduation_date') }}" required>
                            @error('graduation_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="points" class="form-label">Points</label>
                            <input type="number" class="form-control @error('points') is-invalid @enderror" id="points"
                                name="points" value="{{ old('points') }}" min="0" placeholder="Masukkan points">
                            @error('points')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="npwp" class="form-label">NPWP</label>
                            <input type="text" class="form-control @error('npwp') is-invalid @enderror" id="npwp"
                                name="npwp" value="{{ old('npwp') }}" placeholder="12.345.678.9-012.345">
                            @error('npwp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Password default untuk akun baru adalah: <strong>password123</strong>.
                    Alumni dapat mengubah password setelah login pertama kali.
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.views.users.alumni.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-2"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i> Simpan Alumni
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
