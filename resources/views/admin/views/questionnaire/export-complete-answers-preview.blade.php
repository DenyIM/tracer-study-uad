@extends('admin.views.layouts.app')

@section('title', 'Preview Laporan Lengkap')
@section('page-title', 'Preview Laporan Jawaban Lengkap')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Preview Laporan Jawaban Lengkap</h5>
                    <div class="alert alert-info mt-2">
                        <i class="bi bi-info-circle me-2"></i>
                        Ini adalah preview laporan lengkap. Data akan sama dengan yang ada di PDF.
                    </div>
                </div>
                <div class="card-body">
                    <!-- Include the PDF content -->
                    @include('admin.views.questionnaire.export-complete-answers-content')
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.questionnaire.export.complete.form') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Kembali ke Form
                        </a>
                        <div>
                            @php
                                $queryParams = request()->all();
                                $queryParams['action'] = 'preview';
                            @endphp
                            <a href="{{ route('admin.questionnaire.export.complete.pdf', $queryParams) }}"
                                class="btn btn-info" target="_blank">
                                <i class="bi bi-eye me-2"></i> Preview di Tab Baru
                            </a>

                            @php
                                $queryParams['action'] = 'download';
                            @endphp
                            <a href="{{ route('admin.questionnaire.export.complete.pdf', $queryParams) }}"
                                class="btn btn-primary">
                                <i class="bi bi-download me-2"></i> Download PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .preview-container {
            background-color: white;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            max-height: 800px;
            overflow-y: auto;
        }

        .preview-table {
            font-size: 11px;
        }

        .preview-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
    </style>
@endsection
