@extends('admin.views.layouts.app')

@section('title', 'Manajemen Alumni')
@section('page-title', 'Daftar Alumni')

@section('content')
    <div class="card dashboard-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Daftar Alumni</h5>
                <p class="text-muted mb-0">Total {{ $alumni->total() }} alumni terdaftar</p>
            </div>
            <div>
                <a href="{{ route('admin.views.users.alumni.create') }}" class="btn btn-success me-2">
                    <i class="bi bi-plus-circle me-2"></i> Tambah Alumni
                </a>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exportAlumniModal">
                    <i class="bi bi-download me-2"></i> Ekspor
                </button>
            </div>
        </div>

        <div class="card-body">
            <!-- Filter Section -->
            <div class="filter-section mb-4">
                <form method="GET" action="{{ route('admin.views.users.alumni.index') }}">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Search Nama/NIM</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nama atau NIM...">
                                <button class="btn btn-outline-secondary" type="button" onclick="clearAlumniSearch()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Program Studi</label>
                            <select name="study_program" class="form-select">
                                <option value="">Semua Program</option>
                                @foreach ($studyPrograms as $program)
                                    <option value="{{ $program }}"
                                        {{ request('study_program') == $program ? 'selected' : '' }}>
                                        {{ $program }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Tahun Lulus</label>
                            <select name="graduation_year" class="form-select">
                                <option value="">Semua Tahun</option>
                                @foreach ($graduationYears as $year)
                                    <option value="{{ $year }}"
                                        {{ request('graduation_year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100 me-2">
                                <i class="bi bi-filter me-2"></i> Filter
                            </button>
                            <a href="{{ route('admin.views.users.alumni.index') }}" class="btn btn-secondary w-100">
                                <i class="bi bi-x-circle me-2"></i> Reset
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Status Email</label>
                            <select name="email_status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="verified" {{ request('email_status') == 'verified' ? 'selected' : '' }}>
                                    Terverifikasi
                                </option>
                                <option value="unverified" {{ request('email_status') == 'unverified' ? 'selected' : '' }}>
                                    Belum Verifikasi
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Points</label>
                            <select name="points_filter" class="form-select">
                                <option value="">Semua Points</option>
                                <option value="has_points"
                                    {{ request('points_filter') == 'has_points' ? 'selected' : '' }}>
                                    Memiliki Points
                                </option>
                                <option value="no_points" {{ request('points_filter') == 'no_points' ? 'selected' : '' }}>
                                    Tidak Ada Points
                                </option>
                                <option value="high_points"
                                    {{ request('points_filter') == 'high_points' ? 'selected' : '' }}>
                                    Points Tinggi (> 100)
                                </option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Alumni Table -->
            <div class="data-table">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Nama Alumni</th>
                                <th>Program Studi</th>
                                <th>Tahun Lulus</th>
                                <th>Points</th>
                                <th>Status Email</th>
                                <th>Terakhir Login</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alumni as $index => $alumniItem)
                                <tr>
                                    <td>{{ $alumni->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($alumniItem->fullname) }}&background=0d6efd&color=fff"
                                                alt="{{ $alumniItem->fullname }}" class="profile-img me-2">
                                            <div>
                                                <div class="fw-bold">{{ $alumniItem->fullname }}</div>
                                                <small class="text-muted">{{ $alumniItem->user->email }}</small>
                                                <!-- TAMPILKAN NIM DI BAWAH EMAIL JIKA MASIH INGIN DILIHAT -->
                                                <small class="text-muted d-block">{{ $alumniItem->nim }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $alumniItem->study_program }}</td>
                                    <td>{{ $alumniItem->graduation_date ? $alumniItem->graduation_date->format('Y') : '-' }}
                                    </td>
                                    <td>
                                        @if ($alumniItem->points)
                                            <span class="badge bg-success">{{ $alumniItem->points }} pts</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($alumniItem->user->email_verified_at)
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="bi bi-check-circle me-1"></i> Terverifikasi
                                            </span>
                                        @else
                                            <span class="badge bg-warning px-3 py-2">
                                                <i class="bi bi-clock me-1"></i> Belum Verifikasi
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($alumniItem->user->last_login_at)
                                            <small>{{ $alumniItem->user->last_login_at->diffForHumans() }}</small>
                                        @else
                                            <small class="text-muted">Belum pernah</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="{{ route('admin.views.users.alumni.show', $alumniItem->id) }}"
                                                class="btn btn-action btn-view">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.views.users.alumni.edit', $alumniItem->id) }}"
                                                class="btn btn-action btn-edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.views.users.alumni.destroy', $alumniItem->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-action btn-delete"
                                                    onclick="confirmDeleteAlumni(this, '{{ $alumniItem->fullname }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-people display-4"></i>
                                            <p class="mt-2">Belum ada data alumni</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if ($alumni->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $alumni->firstItem() }} sampai {{ $alumni->lastItem() }} dari
                        {{ $alumni->total() }} data
                    </div>
                    <nav>
                        {{ $alumni->withQueryString()->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            @endif
        </div>
    </div>

    <!-- Export Alumni Modal -->
    <div class="modal fade" id="exportAlumniModal" tabindex="-1" aria-labelledby="exportAlumniModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportAlumniModalLabel">
                        <i class="bi bi-download me-2"></i> Ekspor Data Alumni
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.views.users.alumni.export') }}" method="GET">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Format Ekspor</label>
                            <select class="form-select" name="format">
                                <option value="csv">CSV (Excel)</option>
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel (XLSX)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Program Studi</label>
                            <select name="study_program" class="form-select">
                                <option value="">Semua Program</option>
                                @foreach ($studyPrograms as $program)
                                    <option value="{{ $program }}">{{ $program }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tahun Lulus</label>
                            <select name="graduation_year" class="form-select">
                                <option value="">Semua Tahun</option>
                                @foreach ($graduationYears as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status Email</label>
                            <select name="email_status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="verified">Terverifikasi</option>
                                <option value="unverified">Belum Verifikasi</option>
                            </select>
                        </div>
                    </div>
                    <!-- Tambahkan hidden fields untuk filter yang aktif -->
                    @if (request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if (request('points_filter'))
                        <input type="hidden" name="points_filter" value="{{ request('points_filter') }}">
                    @endif
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-download me-2"></i> Ekspor Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .profile-img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
        }

        .profile-img:hover {
            border-color: #0d6efd;
            transform: scale(1.1);
        }

        .btn-action {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-action:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .filter-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05) !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function clearAlumniSearch() {
            const searchInput = document.querySelector('input[name="search"]');
            searchInput.value = '';
            searchInput.form.submit();
        }

        function confirmDeleteAlumni(button, alumniName) {
            if (confirm(`Apakah Anda yakin ingin menghapus alumni "${alumniName}"?`)) {
                button.closest('form').submit();
            }
        }
    </script>
@endpush
