@extends('admin.views.layouts.app')

@section('title', 'Detail Administrator')
@section('page-title', 'Detail Administrator')

@section('content')
    <div class="card dashboard-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Data Administrator</h5>
            <div>
                <!-- Edit Button (Only for Super Admin & Admin Sistem) -->
                @if (auth()->user()->canEditAdmin())
                    <a href="{{ route('admin.views.users.admin.edit', $admin->id) }}" class="btn btn-warning me-2">
                        <i class="bi bi-pencil me-2"></i> Edit
                    </a>
                @else
                    <button class="btn btn-warning me-2" disabled data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Hanya Super Admin dan Admin Sistem yang dapat mengedit">
                        <i class="bi bi-pencil me-2"></i> Edit
                    </button>
                @endif

                <a href="{{ route('admin.views.users.admin.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center mb-4">
                    <div class="position-relative d-inline-block mb-3">
                        @if ($admin->user->pp_url)
                            <img src="{{ asset('storage/' . $admin->user->pp_url) }}" alt="{{ $admin->fullname }}"
                                class="rounded-circle profile-img-show"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($admin->fullname) }}&background=0d6efd&color=fff&size=150'">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->fullname) }}&background=0d6efd&color=fff&size=150"
                                alt="{{ $admin->fullname }}" class="rounded-circle profile-img-show">
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $admin->fullname }}</h4>
                    <p class="text-muted mb-3">{{ $admin->user->email }}</p>

                    @php
                        $badgeClass = match ($admin->job_title) {
                            'Super Admin' => 'bg-danger',
                            'Admin Sistem' => 'bg-primary',
                            'Admin Akademik' => 'bg-info',
                            'Admin Keuangan' => 'bg-warning',
                            'Admin Alumni' => 'bg-success',
                            default => 'bg-secondary',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }} fs-6 px-4 py-2">
                        <i class="bi bi-person-badge me-2"></i>{{ $admin->job_title }}
                    </span>
                </div>

                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card info-card mb-3">
                                <div class="card-body">
                                    <label class="form-label fw-bold text-primary mb-2">
                                        <i class="bi bi-envelope me-2"></i>Email
                                    </label>
                                    <div class="form-control-plaintext p-3 bg-light rounded">
                                        {{ $admin->user->email }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card info-card mb-3">
                                <div class="card-body">
                                    <label class="form-label fw-bold text-primary mb-2">
                                        <i class="bi bi-telephone me-2"></i>No. Telepon
                                    </label>
                                    <div class="form-control-plaintext p-3 bg-light rounded">
                                        {{ $admin->phone ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card info-card mb-3">
                                <div class="card-body">
                                    <label class="form-label fw-bold text-primary mb-2">
                                        <i class="bi bi-shield-check me-2"></i>Status Akun
                                    </label>
                                    <div class="form-control-plaintext p-3 bg-light rounded">
                                        @if ($admin->user->email_verified_at)
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="bi bi-check-circle me-2"></i>
                                                Terverifikasi
                                            </span>
                                        @else
                                            <span class="badge bg-warning px-3 py-2">
                                                <i class="bi bi-clock me-2"></i>
                                                Belum Verifikasi
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card info-card mb-3">
                                <div class="card-body">
                                    <label class="form-label fw-bold text-primary mb-2">
                                        <i class="bi bi-calendar-date me-2"></i>Tanggal Bergabung
                                    </label>
                                    <div class="form-control-plaintext p-3 bg-light rounded">
                                        <i class="bi bi-calendar3 me-2"></i>
                                        {{ $admin->created_at->format('d F Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card info-card mb-3">
                                <div class="card-body">
                                    <label class="form-label fw-bold text-primary mb-2">
                                        <i class="bi bi-clock-history me-2"></i>Terakhir Diperbarui
                                    </label>
                                    <div class="form-control-plaintext p-3 bg-light rounded">
                                        <i class="bi bi-arrow-clockwise me-2"></i>
                                        {{ $admin->updated_at->format('d F Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card info-card mb-3">
                                <div class="card-body">
                                    <label class="form-label fw-bold text-primary mb-2">
                                        <i class="bi bi-door-closed me-2"></i>Login Terakhir
                                    </label>
                                    <div class="form-control-plaintext p-3 bg-light rounded">
                                        @if ($admin->user->last_login_at)
                                            <i class="bi bi-box-arrow-in-right me-2"></i>
                                            {{ $admin->user->last_login_at->format('d F Y H:i') }}
                                        @else
                                            <i class="bi bi-question-circle me-2"></i>
                                            Belum pernah login
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .profile-img-show {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 5px solid #0d6efd;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);
            transition: all 0.3s;
        }

        .profile-img-show:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.3);
        }

        .info-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            height: 100%;
        }

        .info-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
        }

        .info-card .card-body {
            padding: 20px;
        }

        .info-card .form-control-plaintext {
            background-color: #f8f9fa;
            border-radius: 8px;
            min-height: 54px;
            display: flex;
            align-items: center;
            font-size: 1rem;
        }

        .badge.fs-6 {
            font-size: 1rem !important;
            padding: 8px 16px;
        }
    </style>
@endpush
