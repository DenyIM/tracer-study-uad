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
                    <form id="pdfExportForm" action="{{ route('admin.questionnaire.export.pdf') }}" method="GET">
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
                                <button type="button" id="previewBtn" class="btn btn-info">
                                    <i class="bi bi-eye me-2"></i> Preview
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-download me-2"></i> Download PDF
                                </button>
                            </div>
                        </div>
                    </form>

                    @push('scripts')
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const previewBtn = document.getElementById('previewBtn');
                                const form = document.getElementById('pdfExportForm');

                                console.log('Preview button:', previewBtn);
                                console.log('Form element:', form);

                                if (previewBtn && form) {
                                    previewBtn.addEventListener('click', function(e) {
                                        e.preventDefault();
                                        generatePreview();
                                    });
                                } else {
                                    console.error('Element tidak ditemukan!');
                                }
                            });

                            function generatePreview() {
                                try {
                                    const form = document.getElementById('pdfExportForm');

                                    if (!form) {
                                        throw new Error('Form dengan ID "pdfExportForm" tidak ditemukan!');
                                    }

                                    // Ambil semua nilai form
                                    const formData = new FormData(form);
                                    const params = new URLSearchParams();

                                    // Tambahkan semua parameter
                                    for (const [key, value] of formData.entries()) {
                                        if (value) {
                                            params.append(key, value);
                                        }
                                    }

                                    // Tambahkan action=preview
                                    params.append('action', 'preview');

                                    // Build URL
                                    const baseUrl = "{{ route('admin.questionnaire.export.pdf') }}";
                                    const previewUrl = `${baseUrl}?${params.toString()}`;

                                    console.log('Generated URL:', previewUrl);

                                    // Buka di tab baru
                                    window.open(previewUrl, '_blank', 'noopener,noreferrer');

                                } catch (error) {
                                    console.error('Error:', error);
                                    // Tampilkan toast error
                                    showToast('Error: ' + error.message, 'error');
                                }
                            }

                            // Toast notification function
                            function showToast(message, type = 'info') {
                                // Cek jika Bootstrap Toast tersedia
                                if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                                    // Buat toast element
                                    const toastHtml = `
            <div class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : 'info'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi ${type === 'error' ? 'bi-exclamation-triangle' : 'bi-info-circle'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

                                    // Buat container jika belum ada
                                    let toastContainer = document.getElementById('toastContainer');
                                    if (!toastContainer) {
                                        toastContainer = document.createElement('div');
                                        toastContainer.id = 'toastContainer';
                                        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                                        document.body.appendChild(toastContainer);
                                    }

                                    // Tambahkan toast
                                    toastContainer.insertAdjacentHTML('beforeend', toastHtml);

                                    // Inisialisasi dan tampilkan toast
                                    const toastEl = toastContainer.lastElementChild;
                                    const toast = new bootstrap.Toast(toastEl);
                                    toast.show();

                                    // Hapus setelah 5 detik
                                    setTimeout(() => {
                                        if (toastEl.parentNode) {
                                            toastEl.parentNode.removeChild(toastEl);
                                        }
                                    }, 5000);

                                } else {
                                    // Fallback ke alert jika Bootstrap tidak tersedia
                                    alert(message);
                                }
                            }
                        </script>
                    @endpush
                </div>
            </div>
        </div>
    </div>
@endsection
