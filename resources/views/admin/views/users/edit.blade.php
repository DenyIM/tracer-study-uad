@extends('admin.views.layouts.app')

@section('title', 'Edit Data Alumni')
@section('page-title', 'Edit Alumni')

@section('content')
<div class="card dashboard-card">
    <div class="card-header">
        <h5 class="mb-0">Form Edit Data Alumni</h5>
    </div>
    
    <div class="card-body">
        <form action="{{ route('admin.users.update', 1) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="fullname" value="Ahmad Setiawan" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" name="email" value="ahmad@email.com" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIM <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nim" value="20180801234" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Program Studi <span class="text-danger">*</span></label>
                    <select class="form-select" name="study_program" required>
                        <option value="">Pilih program studi</option>
                        <option value="Teknik Informatika" selected>Teknik Informatika</option>
                        <option value="Sistem Informasi">Sistem Informasi</option>
                        <option value="Manajemen">Manajemen</option>
                        <option value="Akuntansi">Akuntansi</option>
                    </select>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" name="date_of_birth" value="1999-05-15">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. Telepon</label>
                    <input type="text" class="form-control" name="phone" value="081234567890">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tahun Lulus <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="graduation_year" value="2022" min="2000" max="2025" required>
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Lulus</label>
                    <input type="date" class="form-control" name="graduation_date" value="2022-07-15">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">NPWP</label>
                    <input type="text" class="form-control" name="npwp" placeholder="Masukkan NPWP">
                </div>
                
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status Email</label>
                    <div class="border rounded p-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="email_verified" id="emailVerified" checked>
                            <label class="form-check-label" for="emailVerified">
                                Email Terverifikasi
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="col-12 mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control" name="address" rows="3" placeholder="Masukkan alamat"></textarea>
                </div>
                
                <div class="col-12">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">
                            <i class="bi bi-x-circle me-2"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-2"></i> Update Data
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection