@extends('admin.views.layouts.app')

@section('title', 'Manajemen Alumni')
@section('page-title', 'Daftar Alumni')

@section('content')
<div class="card dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Daftar Alumni</h5>
            <p class="text-muted mb-0">Total 125 alumni terdaftar</p>
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
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Program Studi</label>
                        <select name="program_studi" class="form-select">
                            <option value="">Semua Program</option>
                            <option>Teknik Informatika</option>
                            <option>Sistem Informasi</option>
                            <option>Manajemen</option>
                            <option>Akuntansi</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tahun Lulus</label>
                        <select name="tahun_lulus" class="form-select">
                            <option value="">Semua Tahun</option>
                            <option>2023</option>
                            <option>2022</option>
                            <option>2021</option>
                            <option>2020</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status Email</label>
                        <select name="status_email" class="form-select">
                            <option value="">Semua Status</option>
                            <option>Terverifikasi</option>
                            <option>Belum Verifikasi</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-filter me-2"></i> Filter
                        </button>
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
                    <!-- Data Dummy 1 -->
                    <tr>
                        <td>1</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Ahmad+Setiawan&background=0d6efd&color=fff" 
                                     alt="Ahmad Setiawan" class="profile-img me-2">
                                <div>
                                    <div class="fw-bold">Ahmad Setiawan</div>
                                    <small class="text-muted">ahmad@email.com</small>
                                </div>
                            </div>
                        </td>
                        <td>20180801234</td>
                        <td>Teknik Informatika</td>
                        <td>2022</td>
                        <td>
                            <span class="status-badge badge-success">
                                <i class="bi bi-check-circle me-1"></i> Terverifikasi
                            </span>
                        </td>
                        <td>
                            <small>2 hari yang lalu</small>
                        </td>
                        <td>
                            <a href="#" class="btn btn-action btn-view me-1">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', 1) }}" class="btn btn-action btn-edit me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy', 1) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-action btn-delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    
                    <!-- Data Dummy 2 -->
                    <tr>
                        <td>2</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Siti+Nurhaliza&background=20c997&color=fff" 
                                     alt="Siti Nurhaliza" class="profile-img me-2">
                                <div>
                                    <div class="fw-bold">Siti Nurhaliza</div>
                                    <small class="text-muted">siti@email.com</small>
                                </div>
                            </div>
                        </td>
                        <td>20180801235</td>
                        <td>Sistem Informasi</td>
                        <td>2021</td>
                        <td>
                            <span class="status-badge badge-success">
                                <i class="bi bi-check-circle me-1"></i> Terverifikasi
                            </span>
                        </td>
                        <td>
                            <small>1 minggu yang lalu</small>
                        </td>
                        <td>
                            <a href="#" class="btn btn-action btn-view me-1">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', 2) }}" class="btn btn-action btn-edit me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy', 2) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-action btn-delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    
                    <!-- Data Dummy 3 -->
                    <tr>
                        <td>3</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=fd7e14&color=fff" 
                                     alt="Budi Santoso" class="profile-img me-2">
                                <div>
                                    <div class="fw-bold">Budi Santoso</div>
                                    <small class="text-muted">budi@email.com</small>
                                </div>
                            </div>
                        </td>
                        <td>20180801236</td>
                        <td>Manajemen</td>
                        <td>2023</td>
                        <td>
                            <span class="status-badge badge-warning">
                                <i class="bi bi-clock me-1"></i> Belum Verifikasi
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">Belum pernah</small>
                        </td>
                        <td>
                            <a href="#" class="btn btn-action btn-view me-1">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', 3) }}" class="btn btn-action btn-edit me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy', 3) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-action btn-delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    
                    <!-- Data Dummy 4 -->
                    <tr>
                        <td>4</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Dewi+Lestari&background=6f42c1&color=fff" 
                                     alt="Dewi Lestari" class="profile-img me-2">
                                <div>
                                    <div class="fw-bold">Dewi Lestari</div>
                                    <small class="text-muted">dewi@email.com</small>
                                </div>
                            </div>
                        </td>
                        <td>20180801237</td>
                        <td>Akuntansi</td>
                        <td>2020</td>
                        <td>
                            <span class="status-badge badge-success">
                                <i class="bi bi-check-circle me-1"></i> Terverifikasi
                            </span>
                        </td>
                        <td>
                            <small>3 jam yang lalu</small>
                        </td>
                        <td>
                            <a href="#" class="btn btn-action btn-view me-1">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', 4) }}" class="btn btn-action btn-edit me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy', 4) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-action btn-delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    
                    <!-- Data Dummy 5 -->
                    <tr>
                        <td>5</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=Rizky+Pratama&background=dc3545&color=fff" 
                                     alt="Rizky Pratama" class="profile-img me-2">
                                <div>
                                    <div class="fw-bold">Rizky Pratama</div>
                                    <small class="text-muted">rizky@email.com</small>
                                </div>
                            </div>
                        </td>
                        <td>20180801238</td>
                        <td>Teknik Informatika</td>
                        <td>2022</td>
                        <td>
                            <span class="status-badge badge-danger">
                                <i class="bi bi-x-circle me-1"></i> Diblokir
                            </span>
                        </td>
                        <td>
                            <small>1 bulan yang lalu</small>
                        </td>
                        <td>
                            <a href="#" class="btn btn-action btn-view me-1">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.users.edit', 5) }}" class="btn btn-action btn-edit me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.users.destroy', 5) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-action btn-delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Menampilkan 1 sampai 5 dari 125 data
            </div>
            <nav>
                <ul class="pagination mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#">«</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">»</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection