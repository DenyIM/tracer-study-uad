@extends('admin.views.layouts.app')

@section('title', 'Manajemen Administrator')
@section('page-title', 'Daftar Administrator')

@section('content')
<div class="card dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Daftar Administrator</h5>
            <p class="text-muted mb-0">Total 5 administrator aktif</p>
        </div>
        <a href="{{ route('admin.users.create-admin') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i> Tambah Admin
        </a>
    </div>
    
    <div class="card-body">
        <!-- Admin Cards -->
        <div class="row">
            <!-- Admin Card 1 -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="https://ui-avatars.com/api/?name=Super+Admin&background=0d6efd&color=fff&size=100" 
                             alt="Super Admin" class="rounded-circle mb-3" style="width: 80px; height: 80px;">
                        <h5 class="card-title">Super Admin</h5>
                        <p class="text-muted">superadmin@alumnisys.ac.id</p>
                        <div class="mb-3">
                            <span class="badge bg-primary">Super Administrator</span>
                        </div>
                        <p class="card-text small text-muted">
                            Hak akses penuh, dapat mengelola semua fitur sistem
                        </p>
                        <div class="d-flex justify-content-center">
                            <a href="#" class="btn btn-action btn-view me-2">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="#" class="btn btn-action btn-edit me-2">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="#" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-action btn-delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Admin Card 2 -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="https://ui-avatars.com/api/?name=Admin+Survey&background=20c997&color=fff&size=100" 
                             alt="Admin Survey" class="rounded-circle mb-3" style="width: 80px; height: 80px;">
                        <h5 class="card-title">Admin Survey</h5>
                        <p class="text-muted">survey@alumnisys.ac.id</p>
                        <div class="mb-3">
                            <span class="badge bg-success">Admin Survei</span>
                        </div>
                        <p class="card-text small text-muted">
                            Mengelola survei, kuesioner, dan hasil respons alumni
                        </p>
                        <div class="d-flex justify-content-center">
                            <a href="#" class="btn btn-action btn-view me-2">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="#" class="btn btn-action btn-edit me-2">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="#" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-action btn-delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Admin Card 3 -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="https://ui-avatars.com/api/?name=Admin+Data&background=fd7e14&color=fff&size=100" 
                             alt="Admin Data" class="rounded-circle mb-3" style="width: 80px; height: 80px;">
                        <h5 class="card-title">Admin Data</h5>
                        <p class="text-muted">data@alumnisys.ac.id</p>
                        <div class="mb-3">
                            <span class="badge bg-warning">Admin Data</span>
                        </div>
                        <p class="card-text small text-muted">
                            Mengelola data alumni, export/import data, dan backup
                        </p>
                        <div class="d-flex justify-content-center">
                            <a href="#" class="btn btn-action btn-view me-2">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="#" class="btn btn-action btn-edit me-2">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="#" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-action btn-delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Admin Table for smaller screens -->
        <div class="data-table d-md-none">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Super+Admin&background=0d6efd&color=fff" 
                                     alt="Super Admin" class="profile-img me-2">
                                <div>
                                    <div class="fw-bold">Super Admin</div>
                                    <small class="text-muted">superadmin@alumnisys.ac.id</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary">Super Admin</span>
                        </td>
                        <td>
                            <a href="#" class="btn btn-action btn-view me-1">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="#" class="btn btn-action btn-edit me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-action btn-delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Admin+Survey&background=20c997&color=fff" 
                                     alt="Admin Survey" class="profile-img me-2">
                                <div>
                                    <div class="fw-bold">Admin Survey</div>
                                    <small class="text-muted">survey@alumnisys.ac.id</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-success">Admin Survei</span>
                        </td>
                        <td>
                            <a href="#" class="btn btn-action btn-view me-1">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="#" class="btn btn-action btn-edit me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-action btn-delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection