@extends('admin.views.layouts.app')

@section('title', 'Detail Administrator')
@section('page-title', 'Detail Administrator')

@section('content')
<div class="card dashboard-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Detail Data Administrator</h5>
        <div>
            <a href="{{ route('admin.views.admins.edit', $admin->id) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil me-2"></i> Edit
            </a>
            <a href="{{ route('admin.views.admins.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center mb-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->fullname) }}&background=0d6efd&color=fff&size=150" 
                     alt="{{ $admin->fullname }}" class="rounded-circle mb-3" style="width: 150px; height: 150px;">
                <h4 class="mb-1">{{ $admin->fullname }}</h4>
                <p class="text-muted">{{ $admin->user->email }}</p>
            </div>
            
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <div class="form-control-plaintext">{{ $admin->user->email }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">No. Telepon</label>
                        <div class="form-control-plaintext">{{ $admin->phone ?? '-' }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Jabatan</label>
                        <div class="form-control-plaintext">{{ $admin->job_title }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tanggal Bergabung</label>
                        <div class="form-control-plaintext">{{ $admin->created_at->format('d F Y') }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Terakhir Diperbarui</label>
                        <div class="form-control-plaintext">{{ $admin->updated_at->format('d F Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection