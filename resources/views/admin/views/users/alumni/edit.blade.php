@extends('admin.views.layouts.app')

@section('title', 'Edit Data Alumni')
@section('page-title', 'Edit Alumni')

@section('content')
    <div class="card dashboard-card">
        <div class="card-header">
            <h5 class="mb-0">Form Edit Data Alumni</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.views.users.alumni.update', $alumni->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('fullname') is-invalid @enderror" name="fullname"
                            value="{{ old('fullname', $alumni->fullname) }}" required>
                        @error('fullname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email', $alumni->user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIM <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nim') is-invalid @enderror" name="nim"
                            value="{{ old('nim', $alumni->nim) }}" required>
                        @error('nim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Program Studi <span class="text-danger">*</span></label>
                        <select class="form-select @error('study_program') is-invalid @enderror" name="study_program"
                            required>
                            <option value="">Pilih program studi</option>
                            @foreach ($studyPrograms as $program)
                                <option value="{{ $program }}"
                                    {{ old('study_program', $alumni->study_program) == $program ? 'selected' : '' }}>
                                    {{ $program }}
                                </option>
                            @endforeach
                        </select>
                        @error('study_program')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ranking</label>
                        <input type="number" class="form-control @error('ranking') is-invalid @enderror" name="ranking"
                            value="{{ old('ranking', $alumni->ranking) }}" min="1" placeholder="Masukkan ranking">
                        @error('ranking')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Points</label>
                        <input type="number" class="form-control @error('points') is-invalid @enderror" name="points"
                            value="{{ old('points', $alumni->points) }}" min="0" placeholder="Masukkan points">
                        @error('points')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"
                            value="{{ old('phone', $alumni->phone) }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Lulus <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('graduation_date') is-invalid @enderror"
                            name="graduation_date"
                            value="{{ old('graduation_date', $alumni->graduation_date ? $alumni->graduation_date->format('Y-m-d') : '') }}"
                            required>
                        @error('graduation_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">NPWP</label>
                        <input type="text" class="form-control @error('npwp') is-invalid @enderror" name="npwp"
                            value="{{ old('npwp', $alumni->npwp) }}" placeholder="Masukkan NPWP">
                        @error('npwp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status Email</label>
                        <div class="border rounded p-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="email_verified" id="emailVerified"
                                    {{ $alumni->user->email_verified_at ? 'checked' : '' }}>
                                <label class="form-check-label" for="emailVerified">
                                    Email Terverifikasi
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.views.users.alumni.index') }}" class="btn btn-secondary me-2">
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
