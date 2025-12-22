@extends('admin.views.layouts.app')

@section('title', 'Detail Alumni')
@section('page-title', 'Detail Alumni')

@section('content')
    <div class="card dashboard-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Data Alumni</h5>
            <div>
                <a href="{{ route('admin.views.users.alumni.edit', $alumni->id) }}" class="btn btn-warning me-2">
                    <i class="bi bi-pencil me-2"></i> Edit
                </a>
                <a href="{{ route('admin.views.users.alumni.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($alumni->fullname) }}&background=0d6efd&color=fff&size=150"
                        alt="{{ $alumni->fullname }}" class="rounded-circle mb-3" style="width: 150px; height: 150px;">
                    <h4 class="mb-1">{{ $alumni->fullname }}</h4>
                    <p class="text-muted">{{ $alumni->nim }}</p>
                    @if ($alumni->user->email_verified_at)
                        <span class="badge bg-success">Email Terverifikasi</span>
                    @endif
                </div>

                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <div class="form-control-plaintext">{{ $alumni->user->email }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Program Studi</label>
                            <div class="form-control-plaintext">{{ $alumni->study_program }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Lahir</label>
                            <div class="form-control-plaintext">
                                {{ $alumni->formatted_date_of_birth ?? '-' }}
                                @if ($alumni->age)
                                    ({{ $alumni->age }} tahun)
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tahun Lulus</label>
                            <div class="form-control-plaintext">
                                {{ $alumni->graduation_date ? $alumni->graduation_date->format('Y') : '-' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Lulus</label>
                            <div class="form-control-plaintext">{{ $alumni->formatted_graduation_date ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">No. Telepon</label>
                            <div class="form-control-plaintext">{{ $alumni->phone ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">NPWP</label>
                            <div class="form-control-plaintext">{{ $alumni->npwp ?? '-' }}</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Usia Saat Lulus</label>
                            <div class="form-control-plaintext">
                                {{ $alumni->graduation_age ? $alumni->graduation_age . ' tahun' : '-' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Terakhir Login</label>
                            <div class="form-control-plaintext">
                                {{ $alumni->user->last_login_at ? $alumni->user->last_login_at->diffForHumans() : 'Belum pernah' }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Daftar</label>
                            <div class="form-control-plaintext">
                                {{ $alumni->created_at->format('d F Y') }}
                                ({{ $alumni->created_at->diffForHumans() }})
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email Terverifikasi</label>
                            <div class="form-control-plaintext">
                                {{ $alumni->user->email_verified_at ? $alumni->user->email_verified_at->format('d F Y H:i') : 'Belum' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
