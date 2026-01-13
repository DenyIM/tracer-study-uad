{{-- resources/views/admin/views/questionnaire/export-pdf-form.blade.php --}}
@extends('admin.views.layouts.app')

@section('title', 'Export PDF')
@section('page-title', 'Export Laporan PDF')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Export Laporan PDF</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.questionnaire.export.pdf') }}" method="GET" target="_blank">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Pilih Kategori</label>
                                <select name="category_id" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Akhir</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Informasi:</strong> Laporan akan berisi:
                            <ul class="mb-0 mt-1">
                                <li>Statistik lengkap kuesioner</li>
                                <li>Data per kategori</li>
                                <li>Pertanyaan paling sering dijawab</li>
                                <li>Top 10 alumni aktif</li>
                                <li>Kesimpulan dan rekomendasi</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.views.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Kembali
                            </a>

                            <div>
                                <button type="submit" name="action" value="preview" class="btn btn-info">
                                    <i class="bi bi-eye me-2"></i> Preview
                                </button>
                                <button type="submit" name="action" value="download" class="btn btn-primary">
                                    <i class="bi bi-download me-2"></i> Download PDF
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
