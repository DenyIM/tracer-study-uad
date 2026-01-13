{{-- resources/views/admin/questionnaire/statistics.blade.php --}}
@extends('admin.views.layouts.app')

@section('title', 'Statistik Kuesioner')
@section('page-title', 'Statistik Kuesioner')

@section('content')
    <div class="row">
        <!-- Statistic Cards -->
        <div class="col-md-3 mb-4">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <i class="bi bi-folder display-4 text-primary"></i>
                    <h3 class="mt-3">{{ $categories->count() }}</h3>
                    <p class="text-muted mb-0">Total Kategori</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <i class="bi bi-check-circle display-4 text-success"></i>
                    <h3 class="mt-3">{{ $totalAlumniCompleted }}</h3>
                    <p class="text-muted mb-0">Alumni Selesai</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <i class="bi bi-question-circle display-4 text-warning"></i>
                    <h3 class="mt-3">{{ $totalQuestions }}</h3>
                    <p class="text-muted mb-0">Total Pertanyaan</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <i class="bi bi-check2-square display-4 text-info"></i>
                    <h3 class="mt-3">{{ $totalAnswers }}</h3>
                    <p class="text-muted mb-0">Total Jawaban</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="mb-0">Statistik per Kategori</h5>
                </div>
                <div class="card-body">
                    @if ($categories->isEmpty())
                        <div class="text-center py-4">
                            <i class="bi bi-bar-chart display-4 text-muted"></i>
                            <p class="mt-2 text-muted">Belum ada data statistik</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Kuesioner</th>
                                        <th>Alumni Terdaftar</th>
                                        <th>Selesai</th>
                                        <th>Progress</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        @php
                                            $alumniCount = $category->alumniStatuses_count ?? 0;
                                            $completedCount = \App\Models\StatusQuestionnaire::where(
                                                'category_id',
                                                $category->id,
                                            )
                                                ->where('status', 'completed')
                                                ->count();
                                            $progress =
                                                $alumniCount > 0 ? round(($completedCount / $alumniCount) * 100) : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($category->icon)
                                                        <i class="bi {{ $category->icon }} me-2"></i>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $category->name }}</div>
                                                        <small class="text-muted">{{ $category->slug }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-info">{{ $category->questionnaires_count ?? 0 }}</span>
                                            </td>
                                            <td>{{ $alumniCount }}</td>
                                            <td>{{ $completedCount }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: {{ $progress }}%"
                                                            aria-valuenow="{{ $progress }}" aria-valuemin="0"
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <small>{{ $progress }}%</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <a href="{{ route('admin.questionnaire.questionnaires', $category->id) }}"
                                                        class="btn btn-action btn-view">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.questionnaire.export', $category->id) }}"
                                                        class="btn btn-action btn-view" title="Export Data">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.questionnaire.categories') }}" class="btn btn-primary w-100">
                                <i class="bi bi-folder me-2"></i> Kelola Kategori
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.questionnaire.export') }}" class="btn btn-success w-100">
                                <i class="bi bi-download me-2"></i> Export Semua Data
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#" class="btn btn-info w-100" data-bs-toggle="modal" data-bs-target="#helpModal">
                                <i class="bi bi-question-circle me-2"></i> Panduan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="helpModalLabel">Panduan Manajemen Kuesioner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>Struktur Kuesioner:</h6>
                    <ol>
                        <li><strong>Kategori</strong>: Kelompok utama (Bekerja, Wirausaha, Pendidikan, dll)</li>
                        <li><strong>Kuesioner/Bagian</strong>: Sub-bagian dalam kategori (Bagian Umum, Bagian 1, Bagian 2,
                            dll)</li>
                        <li><strong>Pertanyaan</strong>: Item pertanyaan dalam setiap bagian</li>
                    </ol>

                    <h6>Urutan Pengerjaan:</h6>
                    <ul>
                        <li>Alumni memilih 1 kategori</li>
                        <li>Mengisi Bagian Umum terlebih dahulu</li>
                        <li>Mengisi bagian spesifik secara berurutan</li>
                    </ul>

                    <h6>Tipe Pertanyaan yang Tersedia:</h6>
                    <ul>
                        <li><strong>Text</strong>: Input teks singkat</li>
                        <li><strong>Text Area</strong>: Input teks panjang</li>
                        <li><strong>Number</strong>: Input angka</li>
                        <li><strong>Date</strong>: Input tanggal</li>
                        <li><strong>Radio</strong>: Pilihan tunggal</li>
                        <li><strong>Dropdown</strong>: Pilihan dropdown</li>
                        <li><strong>Checkbox</strong>: Pilihan ganda</li>
                        <li><strong>Skala Likert</strong>: Skala 1-5</li>
                        <li><strong>Radio/Checkbox per Baris</strong>: Untuk matriks pertanyaan</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection
