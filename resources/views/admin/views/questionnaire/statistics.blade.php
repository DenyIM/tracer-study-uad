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
                    <i class="fas fa-folder display-4 text-primary"></i>
                    <h3 class="mt-3">{{ $categories->count() }}</h3>
                    <p class="text-muted mb-0">Total Kategori</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle display-4 text-success"></i>
                    <h3 class="mt-3">{{ $totalAlumniCompleted }}</h3>
                    <p class="text-muted mb-0">Alumni Selesai</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <i class="fas fa-question-circle display-4 text-warning"></i>
                    <h3 class="mt-3">{{ $totalQuestions }}</h3>
                    <p class="text-muted mb-0">Total Pertanyaan</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <i class="fas fa-check-square display-4 text-info"></i>
                    <h3 class="mt-3">{{ $totalAnswers }}</h3>
                    <p class="text-muted mb-0">Total Jawaban</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Statistik per Kategori</h5>
                    <div>
                        <a href="{{ route('admin.questionnaire.categories') }}" class="btn btn-sm btn-primary me-2">
                            <i class="fas fa-folder me-1"></i> Kelola Kategori
                        </a>
                        <a href="{{ route('admin.questionnaire.export') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-download me-1"></i> Export Semua Data
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($categories->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-bar-chart display-4 text-muted"></i>
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
                                        <th width="100">Aksi</th>
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
                                                        <i class="{{ $category->icon }} me-2"></i>
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
                                                        class="btn btn-sm btn-primary" title="Lihat Kuesioner">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.questionnaire.export', $category->id) }}"
                                                        class="btn btn-sm btn-success" title="Export Data">
                                                        <i class="fas fa-download"></i>
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
@endsection
