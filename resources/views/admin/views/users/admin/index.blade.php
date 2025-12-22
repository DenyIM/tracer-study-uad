@extends('admin.views.layouts.app')

@section('title', 'Manajemen Administrator')
@section('page-title', 'Daftar Administrator')

@section('content')
<div class="card dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Daftar Administrator</h5>
            <p class="text-muted mb-0">Total {{ $admins->count() }} admin terdaftar</p>
        </div>
        <div>
            <a href="{{ route('admin.views.admins.create') }}" class="btn btn-success me-2">
                <i class="bi bi-plus-circle me-2"></i> Tambah Admin
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Administrator Table -->
        <div class="data-table">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Admin</th>
                        <th>Email</th>
                        <th>No. Telepon</th>
                        <th>Jabatan</th>
                        <th>Tanggal Bergabung</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $index => $admin)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->fullname) }}&background=0d6efd&color=fff" 
                                     alt="{{ $admin->fullname }}" class="profile-img me-2">
                                <div>
                                    <div class="fw-bold">{{ $admin->fullname }}</div>
                                    <small class="text-muted">{{ $admin->user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $admin->user->email }}</td>
                        <td>{{ $admin->phone ?? '-' }}</td>
                        <td>{{ $admin->job_title }}</td>
                        <td>
                            <small>{{ $admin->created_at->format('d/m/Y') }}</small>
                        </td>
                        <td>
                            <a href="{{ route('admin.views.admins.show', $admin->id) }}" class="btn btn-action btn-view me-1">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.views.admins.edit', $admin->id) }}" class="btn btn-action btn-edit me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.views.admins.destroy', $admin->id) }}" method="POST" class="d-inline">
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
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-people display-4"></i>
                                <p class="mt-2">Belum ada data admin</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination (jika diperlukan) -->
        @if($admins->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Menampilkan {{ $admins->firstItem() }} sampai {{ $admins->lastItem() }} dari {{ $admins->total() }} data
            </div>
            <nav>
                {{ $admins->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>
@endsection