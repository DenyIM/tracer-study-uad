@extends('admin.views.layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card dashboard-card">
            <div class="card-body text-center">
                <i class="bi bi-people-fill display-4 text-primary"></i>
                <h3 class="mt-3">125</h3>
                <p class="text-muted mb-0">Total Alumni</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card dashboard-card">
            <div class="card-body text-center">
                <i class="bi bi-check-circle-fill display-4 text-success"></i>
                <h3 class="mt-3">98</h3>
                <p class="text-muted mb-0">Email Terverifikasi</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card dashboard-card">
            <div class="card-body text-center">
                <i class="bi bi-clock-fill display-4 text-warning"></i>
                <h3 class="mt-3">27</h3>
                <p class="text-muted mb-0">Belum Verifikasi</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card dashboard-card">
            <div class="card-body text-center">
                <i class="bi bi-shield-check display-4 text-info"></i>
                <h3 class="mt-3">5</h3>
                <p class="text-muted mb-0">Total Admin</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary w-100">
                            <i class="bi bi-people me-2"></i> Kelola Alumni
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('admin.users.admins') }}" class="btn btn-info w-100">
                            <i class="bi bi-shield-check me-2"></i> Kelola Admin
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="{{ route('admin.users.create-admin') }}" class="btn btn-success w-100">
                            <i class="bi bi-person-plus me-2"></i> Tambah Admin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection