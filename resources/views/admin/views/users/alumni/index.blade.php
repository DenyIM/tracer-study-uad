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
                <a href="#" class="btn btn-success me-2">
                    <i class="bi bi-download me-2"></i> Ekspor
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" action="{{ route('admin.views.users.alumni.index') }}">
                    <div class="row">
                        <div class="col-md-4 mb-3">
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
                        <div class="col-md-4 mb-3">
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
                        <div class="col-md-4 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100 me-2">
                                <i class="bi bi-filter me-2"></i> Filter
                            </button>
                            <a href="{{ route('admin.views.users.alumni.index') }}" class="btn btn-secondary w-100">
                                <i class="bi bi-x-circle me-2"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Alumni Table -->
            <div class="data-table">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Nama Alumni</th>
                            <th>NIM</th>
                            <th>Program Studi</th>
                            <th>Tahun Lulus</th>
                            <th>Status Email</th>
                            <th>Terakhir Login</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alumni as $index => $alumniItem)
                            <tr>
                                <td>{{ ($alumni->currentPage() - 1) * $alumni->perPage() + $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($alumniItem->fullname) }}&background=0d6efd&color=fff"
                                            alt="{{ $alumniItem->fullname }}" class="profile-img me-2">
                                        <div>
                                            <div class="fw-bold">{{ $alumniItem->fullname }}</div>
                                            <small class="text-muted">{{ $alumniItem->user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $alumniItem->nim }}</td>
                                <td>{{ $alumniItem->study_program }}</td>
                                <td>{{ $alumniItem->graduation_date ? $alumniItem->graduation_date->format('Y') : '-' }}
                                </td>
                                <td>
                                    @if ($alumniItem->user->email_verified_at)
                                        <span class="status-badge badge-success">
                                            <i class="bi bi-check-circle me-1"></i> Terverifikasi
                                        </span>
                                    @else
                                        <span class="status-badge badge-warning">
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
                                    <a href="{{ route('admin.views.users.alumni.show', $alumniItem->id) }}"
                                        class="btn btn-action btn-view me-1">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.views.users.alumni.edit', $alumniItem->id) }}"
                                        class="btn btn-action btn-edit me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.views.users.alumni.destroy', $alumniItem->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-action btn-delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
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

            <!-- Pagination -->
            @if ($alumni->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $alumni->firstItem() }} sampai {{ $alumni->lastItem() }} dari
                        {{ $alumni->total() }} data
                    </div>
                    <nav>
                        {{ $alumni->withQueryString()->links() }}
                    </nav>
                </div>
            @endif
        </div>
    </div>
@endsection
