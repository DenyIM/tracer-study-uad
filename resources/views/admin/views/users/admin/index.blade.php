@extends('admin.views.layouts.app')

@section('title', 'Manajemen Administrator')
@section('page-title', 'Daftar Administrator')

@section('content')
    <div class="card dashboard-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Daftar Administrator</h5>
                <p class="text-muted mb-0">Total {{ $admins->total() }} admin terdaftar</p>
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    @if (auth()->user()->canCreateAdmin() && auth()->user()->canDeleteAdmin())
                        Anda memiliki akses penuh (System Administrator)
                    @elseif(auth()->user()->canCreateAdmin())
                        Anda dapat melihat, menambah, dan mengedit data (Super Admin)
                    @else
                        Anda hanya dapat melihat data
                    @endif
                </small>
            </div>
            <div>
                @if (auth()->user()->canCreateAdmin())
                    <a href="{{ route('admin.views.users.admin.create') }}" class="btn btn-success me-2">
                        <i class="bi bi-plus-circle me-2"></i> Tambah Admin
                    </a>
                @else
                    <button class="btn btn-success me-2" disabled data-bs-toggle="tooltip" data-bs-placement="bottom"
                        title="Hanya System Administrator dan Super Admin yang dapat menambah admin">
                        <i class="bi bi-plus-circle me-2"></i> Tambah Admin
                    </button>
                @endif
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exportModal">
                    <i class="bi bi-download me-2"></i> Ekspor
                </button>
            </div>
        </div>

        <div class="card-body">
            <!-- Filter Section -->
            <div class="filter-section mb-4">
                <form method="GET" action="{{ route('admin.views.users.admin.index') }}">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Search Nama/Email</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nama atau email...">
                                <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Jabatan</label>
                            <select name="job_title" class="form-select">
                                <option value="">Semua Jabatan</option>
                                <option value="System Administrator"
                                    {{ request('job_title') == 'System Administrator' ? 'selected' : '' }}>
                                    System Administrator
                                </option>
                                <option value="Super Admin" {{ request('job_title') == 'Super Admin' ? 'selected' : '' }}>
                                    Super Admin
                                </option>
                                <option value="Admin Akademik"
                                    {{ request('job_title') == 'Admin Akademik' ? 'selected' : '' }}>
                                    Admin Akademik
                                </option>
                                <option value="Admin Keuangan"
                                    {{ request('job_title') == 'Admin Keuangan' ? 'selected' : '' }}>
                                    Admin Keuangan
                                </option>
                                <option value="Admin Alumni" {{ request('job_title') == 'Admin Alumni' ? 'selected' : '' }}>
                                    Admin Alumni
                                </option>
                                <option value="Staff" {{ request('job_title') == 'Staff' ? 'selected' : '' }}>
                                    Staff
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>
                                    Terverifikasi
                                </option>
                                <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>
                                    Belum Verifikasi
                                </option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100 me-2">
                                <i class="bi bi-filter me-2"></i> Filter
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            @if (request()->hasAny(['search', 'job_title', 'status']))
                                <a href="{{ route('admin.views.users.admin.index') }}" class="btn btn-sm btn-secondary">
                                    <i class="bi bi-x-circle me-1"></i> Reset Filter
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Administrator Table -->
            <div class="data-table">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Admin</th>
                                <th>No. Telepon</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>Tanggal Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admins as $index => $admin)
                                <tr>
                                    <td>{{ $admins->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($admin->user->pp_url)
                                                <img src="{{ asset('storage/' . $admin->user->pp_url) }}"
                                                    alt="{{ $admin->fullname }}" class="profile-img me-2"
                                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($admin->fullname) }}&background=0d6efd&color=fff'">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->fullname) }}&background=0d6efd&color=fff"
                                                    alt="{{ $admin->fullname }}" class="profile-img me-2">
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $admin->fullname }}</div>
                                                <small class="text-muted d-block">
                                                    <i class="bi bi-envelope me-1"></i>{{ $admin->user->email }}
                                                </small>
                                                @if ($admin->user->last_login_at)
                                                    <small class="text-muted">
                                                        <i class="bi bi-clock-history me-1"></i>
                                                        Login: {{ $admin->user->last_login_at->diffForHumans() }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-telephone me-2 text-muted"></i>
                                            {{ $admin->phone ?? '-' }}
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match ($admin->job_title) {
                                                'System Administrator' => 'bg-danger',
                                                'Super Admin' => 'bg-danger',
                                                'Admin Akademik' => 'bg-info',
                                                'Admin Keuangan' => 'bg-warning',
                                                'Admin Alumni' => 'bg-success',
                                                'Staff' => 'bg-secondary',
                                                default => 'bg-dark',
                                            };

                                            $iconClass = match ($admin->job_title) {
                                                'System Administrator' => 'bi-shield-check',
                                                'Super Admin' => 'bi-shield-check',
                                                'Admin Akademik' => 'bi-mortarboard',
                                                'Admin Keuangan' => 'bi-cash-coin',
                                                'Admin Alumni' => 'bi-people',
                                                'Staff' => 'bi-person',
                                                default => 'bi-person',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} px-3 py-2">
                                            <i class="bi {{ $iconClass }} me-1"></i>
                                            {{ $admin->job_title }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($admin->user->email_verified_at)
                                            <span class="badge bg-success px-3 py-2">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Terverifikasi
                                            </span>
                                        @else
                                            <span class="badge bg-warning px-3 py-2">
                                                <i class="bi bi-clock me-1"></i>
                                                Belum Verifikasi
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <small class="text-muted">{{ $admin->created_at->format('d/m/Y') }}</small>
                                            <small class="text-muted">{{ $admin->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <!-- View Button (Always visible) -->
                                            <a href="{{ route('admin.views.users.admin.show', $admin->id) }}"
                                                class="btn btn-action btn-view me-1" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <!-- Edit Button (Only for System Administrator & Super Admin) -->
                                            @if (auth()->user()->canEditAdmin())
                                                <a href="{{ route('admin.views.users.admin.edit', $admin->id) }}"
                                                    class="btn btn-action btn-edit me-1" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @else
                                                <button class="btn btn-action btn-secondary me-1" disabled
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Hanya System Administrator dan Super Admin yang dapat mengedit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            @endif

                                            <!-- Delete Button (Only for System Administrator, and can't delete self) -->
                                            @if ($admin->user_id === auth()->id())
                                                <button class="btn btn-action btn-secondary" disabled
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Tidak dapat menghapus akun sendiri">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @elseif(auth()->user()->canDeleteAdmin())
                                                <form action="{{ route('admin.views.users.admin.destroy', $admin->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-action btn-delete"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus"
                                                        onclick="confirmDelete(this)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-action btn-secondary" disabled
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Hanya System Administrator yang dapat menghapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-people display-4"></i>
                                            <p class="mt-2">Belum ada data admin</p>
                                            @if (auth()->user()->canCreateAdmin())
                                                <a href="{{ route('admin.views.users.admin.create') }}"
                                                    class="btn btn-primary mt-2">
                                                    <i class="bi bi-plus-circle me-2"></i> Tambah Admin Pertama
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if ($admins->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan {{ $admins->firstItem() }} sampai {{ $admins->lastItem() }} dari
                        {{ $admins->total() }} data
                    </div>
                    <nav>
                        {{ $admins->withQueryString()->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            @endif

            <!-- Permission Info -->
            <div class="alert alert-info mt-3">
                <div class="d-flex">
                    <i class="bi bi-info-circle me-2 fs-4"></i>
                    <div>
                        <h6 class="alert-heading mb-1">Informasi Hak Akses</h6>
                        <p class="mb-0">
                            <strong>System Administrator:</strong> Dapat melihat, menambah, mengedit, dan menghapus semua
                            data admin.<br>
                            <strong>Super Admin:</strong> Dapat melihat, menambah, dan mengedit data admin (tidak bisa
                            menghapus).<br>
                            <strong>Admin lainnya (Akademik, Keuangan, Alumni, Staff):</strong> Hanya dapat melihat data
                            admin.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">
                        <i class="bi bi-download me-2"></i> Ekspor Data Admin
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.views.users.admin.export') }}" method="GET">
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
                            <label class="form-label">Jabatan</label>
                            <select name="job_title" class="form-select">
                                <option value="">Semua Jabatan</option>
                                <option value="System Administrator">System Administrator</option>
                                <option value="Super Admin">Super Admin</option>
                                <option value="Admin Akademik">Admin Akademik</option>
                                <option value="Admin Keuangan">Admin Keuangan</option>
                                <option value="Admin Alumni">Admin Alumni</option>
                                <option value="Staff">Staff</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="verified">Terverifikasi</option>
                                <option value="unverified">Belum Verifikasi</option>
                            </select>
                        </div>
                        <!-- Tambahkan hidden fields untuk filter yang aktif -->
                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                    </div>
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

        .badge {
            font-size: 0.85rem;
            font-weight: 500;
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

        .btn-action:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05) !important;
        }

        .table td {
            vertical-align: middle;
            padding: 12px 8px;
        }

        .alert-info {
            border-left: 4px solid #0dcaf0;
            background-color: rgba(13, 202, 240, 0.05);
        }

        .filter-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        function confirmDelete(button) {
            const adminName = button.closest('tr').querySelector('.fw-bold').textContent;
            if (confirm(`Apakah Anda yakin ingin menghapus admin "${adminName}"?`)) {
                button.closest('form').submit();
            }
        }

        function clearSearch() {
            const searchInput = document.querySelector('input[name="search"]');
            searchInput.value = '';
            searchInput.form.submit();
        }
    </script>
@endpush
