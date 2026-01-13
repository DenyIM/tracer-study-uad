{{-- resources/views/admin/views/questionnaire/export-complete-answers-form.blade.php --}}
@extends('admin.views.layouts.app')

@section('title', 'Export Jawaban Lengkap')
@section('page-title', 'Export Semua Jawaban Alumni')

@section('content')
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-download me-2"></i> Export Semua Jawaban Alumni</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.questionnaire.export.complete.pdf') }}" method="GET" target="_blank">
                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Ekspor semua jawaban detail dari alumni.</strong>
                                    Pilih filter sesuai kebutuhan atau biarkan kosong untuk semua data.
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Kategori</label>
                                <select name="category_id" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Kuesioner</label>
                                <select name="questionnaire_id" class="form-select">
                                    <option value="">Semua Kuesioner</option>
                                    @foreach ($questionnaires as $questionnaire)
                                        <option value="{{ $questionnaire->id }}">{{ $questionnaire->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Alumni Tertentu</label>
                                <select name="alumni_id" class="form-select">
                                    <option value="">Semua Alumni</option>
                                    @foreach ($alumni as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->fullname }} ({{ $item->nim ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Tanggal Akhir</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Format Laporan</label>
                                <select name="format" class="form-select">
                                    <option value="detailed">Detail per Alumni</option>
                                    <option value="summary">Ringkasan Matriks</option>
                                </select>
                                <small class="text-muted">
                                    Detail: Satu alumni per halaman<br>
                                    Matriks: Semua alumni dalam tabel
                                </small>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Estimasi Data</label>
                                <div class="card bg-light">
                                    <div class="card-body p-2">
                                        <div class="small">
                                            <i class="bi bi-people"></i> Alumni: {{ $alumni->count() }}<br>
                                            <i class="bi bi-list-check"></i> Kategori: {{ $categories->count() }}<br>
                                            <i class="bi bi-file-text"></i> Kuesioner: {{ $questionnaires->count() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Perhatian:</strong> PDF dengan data lengkap bisa berukuran besar
                                    (tergantung jumlah data). Disarankan menggunakan filter untuk membatasi data.
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('admin.views.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Kembali ke Dashboard
                            </a>

                            <div>
                                <button type="submit" name="action" value="preview" class="btn btn-info me-2">
                                    <i class="bi bi-eye me-2"></i> Preview PDF
                                </button>
                                <button type="submit" name="action" value="download" class="btn btn-danger">
                                    <i class="bi bi-file-pdf me-2"></i> Download PDF Lengkap
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Recent Answers Preview -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i> Jawaban Terbaru</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Alumni</th>
                                    <th>Pertanyaan</th>
                                    <th>Jawaban</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $recentAnswers = \App\Models\AnswerQuestion::with(['alumni', 'question'])
                                        ->orderBy('answered_at', 'desc')
                                        ->limit(5)
                                        ->get();
                                @endphp
                                @foreach ($recentAnswers as $answer)
                                    <tr>
                                        <td>{{ $answer->alumni->fullname ?? '-' }}</td>
                                        <td>{{ Str::limit($answer->question->question_text ?? '-', 50) }}</td>
                                        <td>
                                            @if ($answer->answer)
                                                {{ Str::limit($answer->answer, 30) }}
                                            @elseif($answer->selected_options)
                                                {{ Str::limit(json_decode($answer->selected_options, true)[0] ?? '-', 30) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $answer->answered_at ? $answer->answered_at->format('d/m/Y H:i') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
