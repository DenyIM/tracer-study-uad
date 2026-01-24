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
                    <form id="pdfExportForm" method="GET">
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
                                <input type="date" name="start_date" class="form-control"
                                    value="{{ date('Y-m-d', strtotime('-1 month')) }}">
                                <small class="text-muted">Default: 1 bulan lalu</small>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tanggal Akhir</label>
                                <input type="date" name="end_date" class="form-control" value="{{ date('Y-m-d') }}">
                                <small class="text-muted">Default: hari ini</small>
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
                                <li>Analisis grafik tracer study</li>
                                {{-- <li>Kesimpulan dan rekomendasi</li> --}}
                            </ul>
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Tips:</strong>
                            <ul class="mb-0 mt-1">
                                <li>Biarkan tanggal kosong untuk semua data</li>
                                <li>Tanggal mulai tidak boleh lebih besar dari tanggal akhir</li>
                                <li>Filter tanggal membantu mempersempit data yang ditampilkan</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.views.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i> Kembali
                            </a>

                            <div>
                                <!-- Button dengan onclick JavaScript -->
                                <button type="button" onclick="exportPDF('preview')" class="btn btn-info me-2">
                                    <i class="bi bi-eye me-2"></i> Preview
                                </button>

                                <button type="button" onclick="exportPDF('download')" class="btn btn-primary">
                                    <i class="bi bi-download me-2"></i> Download PDF
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Data Statistics -->
            {{-- <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-bar-chart me-2"></i> Preview Statistik</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="display-6 text-primary">{{ $totalAlumni ?? 0 }}</div>
                            <div class="text-muted small">Total Alumni</div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="display-6 text-success">{{ $totalAnswers ?? 0 }}</div>
                            <div class="text-muted small">Total Jawaban</div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="display-6 text-info">{{ $responseRate ?? 0 }}%</div>
                            <div class="text-muted small">Response Rate</div>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="display-6 text-warning">{{ $categories->count() ?? 0 }}</div>
                            <div class="text-muted small">Kategori</div>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Data terakhir diperbarui: {{ now()->format('d M Y H:i') }}
                        </small>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function exportPDF(actionType) {
            const form = document.getElementById('pdfExportForm');
            if (!form) {
                alert('Form tidak ditemukan!');
                return;
            }

            // Validasi form dulu
            if (!validateForm()) {
                return;
            }

            // Buat form baru
            const newForm = document.createElement('form');
            newForm.method = 'GET';
            newForm.action = "{{ route('admin.questionnaire.export.pdf') }}";

            // Set target berdasarkan action
            if (actionType === 'preview') {
                newForm.target = '_blank';
            }

            // Copy semua input dari form asli
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.name) {
                    const clone = input.cloneNode(true);
                    newForm.appendChild(clone);
                }
            });

            // Tambahkan action parameter
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = actionType;
            newForm.appendChild(actionInput);

            // Submit form
            document.body.appendChild(newForm);
            newForm.submit();
            document.body.removeChild(newForm);

            // Show loading
            showLoading(actionType);
        }

        function validateForm() {
            const form = document.getElementById('pdfExportForm');
            const startDate = form.querySelector('[name="start_date"]').value;
            const endDate = form.querySelector('[name="end_date"]').value;

            // Validasi tanggal
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);

                if (start > end) {
                    alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir!');
                    return false;
                }
            }

            return true;
        }

        function showLoading(actionType) {
            const buttons = document.querySelectorAll('button[onclick*="exportPDF"]');
            buttons.forEach(btn => {
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i> Memproses...';
                btn.disabled = true;

                // Reset setelah 5 detik
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }, 5000);
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        /* Date input styling */
        input[type="date"] {
            position: relative;
        }

        input[type="date"]:invalid {
            border-color: #dc3545;
        }

        input[type="date"]:valid {
            border-color: #198754;
        }

        /* Small text helper */
        .text-muted.small {
            font-size: 0.8rem;
        }

        /* Preview stats */
        .display-6 {
            font-size: 2.5rem;
            font-weight: bold;
        }
    </style>
@endpush
