@extends('admin.views.layouts.app')

@section('title', 'Preview Laporan PDF')
@section('page-title', 'Preview Laporan PDF')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Preview Laporan PDF</h5>
                    <div class="alert alert-info mt-2">
                        <i class="bi bi-info-circle me-2"></i>
                        Ini adalah preview laporan. Data akan sama dengan yang ada di PDF.
                    </div>
                </div>
                <div class="card-body">
                    <!-- Include the PDF content -->
                    @include('admin.views.questionnaire.export-pdf-content')
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.questionnaire.export.form') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Kembali ke Form
                        </a>
                        <div>
                            <a href="{{ route('admin.questionnaire.export.pdf', array_merge(request()->all(), ['action' => 'preview'])) }}"
                                class="btn btn-info" target="_blank">
                                <i class="bi bi-eye me-2"></i> Preview di Tab Baru
                            </a>
                            <a href="{{ route('admin.questionnaire.export.pdf', array_merge(request()->all(), ['action' => 'download'])) }}"
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
        /* Style untuk preview di browser */
        .preview-container {
            background-color: white;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
            max-height: 800px;
            overflow-y: auto;
        }

        .preview-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2c3e50;
        }
    </style>
@endsection
