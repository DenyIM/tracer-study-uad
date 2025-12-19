@extends('admin.views.layouts.app')

@section('title', 'Detail Alumni')
@section('page-title', 'Detail Alumni')

@section('content')
<div class="card dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Detail Data Alumni</h5>
        <div>
            <a href="{{ route('admin.users.edit', 1) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil me-2"></i> Edit
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center mb-4">
                <img src="https://ui-avatars.com/api/?name=Ahmad+Setiawan&background=0d6efd&color=fff&size=150" 
                     alt="Ahmad Setiawan" class="rounded-circle mb-3" style="width: 150px; height: 150px;">
                <h4 class="mb-1">Ahmad Setiawan</h4>
                <p class="text-muted">20180801234</p>
                <span class="badge bg-success">Email Terverifikasi</span>
            </div>
            
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <div class="form-control-plaintext">ahmad@email.com</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Program Studi</label>
                        <div class="form-control-plaintext">Teknik Informatika</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tanggal Lahir</label>
                        <div class="form-control-plaintext">15 Mei 1999 (24 tahun)</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tahun Lulus</label>
                        <div class="form-control-plaintext">2022</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tanggal Lulus</label>
                        <div class="form-control-plaintext">15 Juli 2022</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">No. Telepon</label>
                        <div class="form-control-plaintext">081234567890</div>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">NPWP</label>
                        <div class="form-control-plaintext">-</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Terakhir Login</label>
                        <div class="form-control-plaintext">2 hari yang lalu</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tanggal Daftar</label>
                        <div class="form-control-plaintext">15 Januari 2023 (10 bulan lalu)</div>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-bold">Alamat</label>
                        <div class="form-control-plaintext">Jl. Merdeka No. 123, Jakarta Pusat</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection