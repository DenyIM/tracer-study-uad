<x-app-layout>
    @section('title', 'Kuesioner')

    @php
        // Helper function untuk menentukan status yang aktif
        $status = $statusQuestionnaire->status ?? 'not_started';
        $isCompleted = $status === 'completed';
        $isInProgress = $status === 'in_progress';
        $isNotStarted = $status === 'not_started';

        // Data alumni
        $alumni = Auth::user()->alumni;
        $user = Auth::user();

        // Progress data
        $progressPercentage = $statusQuestionnaire->progress_percentage ?? 0;
        $totalPoints = $statusQuestionnaire->total_points ?? 0;
        $category = $statusQuestionnaire->category ?? null;

        // Progress detail
        $currentQuestionnaire = $statusQuestionnaire->currentQuestionnaire ?? null;
        $progressRecords = $progressRecords ?? collect();

        // Hitung statistik
        $totalAnswered = $totalAnswered ?? 0;
        $totalQuestions = $category->total_questions ?? 0;

        // Ambil kategori lainnya untuk ditampilkan
        $otherCategories = $otherCategories ?? collect();

        // Features data dari DashboardController
        $features = $features ?? [];
        $unlockProgress = $unlockProgress ?? 0;
    @endphp

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12 text-center" data-aos="fade-up">
                <h1 class="fw-bold mb-3" style="color: var(--primary-blue);">Kuesioner Tracer Study</h1>
                <p class="lead mb-4">Lacak perkembangan karir alumni dan berkontribusi untuk pengembangan almamater</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="progress-section p-4 mx-auto" style="max-width: 800px;" data-aos="fade-up"
                    data-aos-delay="100">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold mb-1">{{ $alumni->fullname }}</h5>
                                <p class="text-muted mb-0">{{ $alumni->study_program }}
                                    {{ optional($alumni->graduation_date)->format('Y') }}</p>
                            </div>
                            @if ($totalPoints > 0)
                                <div class="text-end">
                                    <span class="badge bg-primary px-3 py-2">
                                        <i class="fas fa-star me-1"></i> {{ $totalPoints }} Poin
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Kondisi 1: Belum Mengerjakan -->
                    <div id="status-belum-mengerjakan"
                        class="status-section @if (!$isNotStarted) d-none @endif">
                        <div class="text-center py-3">
                            <div class="status-icon mb-3">
                                <div class="icon-wrapper">
                                    <i class="fas fa-clipboard-list fa-3x" style="color: var(--primary-blue);"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-2" style="color: var(--primary-blue);">Siap Memulai Tracer Study?</h4>
                            <p class="mb-3">Mulai isi kuesioner sekarang untuk mengakses berbagai manfaat eksklusif
                                bagi alumni UAD.</p>

                            <div class="status-highlights mb-3">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="highlight-item p-2">
                                            <div class="highlight-icon mb-2">
                                                <i class="fas fa-bolt fa-lg" style="color: var(--accent-yellow);"></i>
                                            </div>
                                            <h6 class="fw-bold mb-1">5 Kategori</h6>
                                            <p class="text-muted mb-0 small">Pilih sesuai status</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="highlight-item p-2">
                                            <div class="highlight-icon mb-2">
                                                <i class="fas fa-star fa-lg" style="color: var(--accent-yellow);"></i>
                                            </div>
                                            <h6 class="fw-bold mb-1">Fitur Eksklusif</h6>
                                            <p class="text-muted mb-0 small">Akses khusus</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="highlight-item p-2">
                                            <div class="highlight-icon mb-2">
                                                <i class="fas fa-users fa-lg" style="color: var(--accent-yellow);"></i>
                                            </div>
                                            <h6 class="fw-bold mb-1">Jaringan Alumni</h6>
                                            <p class="text-muted mb-0 small">Terhubung alumni</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cta-section">
                                <a href="#kategori-section" class="btn btn-primary btn-lg px-4 py-2">
                                    <i class="fas fa-play-circle me-2"></i> Pilih Kategori & Mulai
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Kondisi 2: Sedang Mengerjakan -->
                    <div id="status-sedang-mengerjakan"
                        class="status-section @if (!$isInProgress) d-none @endif">
                        <div class="status-header mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="fw-bold mb-1" style="color: var(--primary-blue);">Kuesioner Aktif</h4>
                                    <p class="text-muted mb-0 small" id="kategori-dikerjakan">
                                        Kategori: <strong>{{ $category->name ?? 'Belum dipilih' }}</strong>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-warning px-3 py-1">
                                        <i class="fas fa-spinner fa-spin me-1"></i> Dalam Progres
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="progress-main mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="fw-bold mb-0">Progress Keseluruhan</h6>
                                </div>
                                <div class="text-end">
                                    <h4 class="fw-bold mb-0" style="color: var(--primary-blue);"
                                        id="progress-percentage">{{ $progressPercentage }}%</h4>
                                </div>
                            </div>

                            <div class="progress-container">
                                <div class="progress" style="height: 16px; border-radius: 8px;">
                                    <div class="progress-bar progress-bar-animated" role="progressbar"
                                        style="width: {{ $progressPercentage }}%; background: linear-gradient(90deg, var(--primary-blue), var(--accent-yellow));"
                                        aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100"
                                        data-progress="{{ $progressPercentage }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($currentQuestionnaire && $progressRecords->isNotEmpty())
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <div class="progress-detail-card p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="detail-icon me-3">
                                                <div class="icon-bg" style="background-color: rgba(0, 51, 102, 0.1);">
                                                    <i class="fas fa-file-alt" style="color: var(--primary-blue);"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                @php
                                                    $currentIndex =
                                                        $progressRecords
                                                            ->where('questionnaire_id', $currentQuestionnaire->id)
                                                            ->keys()
                                                            ->first() + 1;
                                                    $totalQuestionnaires = $progressRecords->count();
                                                @endphp
                                                <h6 class="fw-bold mb-0" id="kuesioner-aktif">
                                                    Kuesioner {{ $currentIndex }} dari {{ $totalQuestionnaires }}
                                                </h6>
                                                <p class="text-muted mb-0 small">{{ $currentQuestionnaire->name }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="progress-detail-card p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="detail-icon me-3">
                                                <div class="icon-bg"
                                                    style="background-color: rgba(40, 167, 69, 0.1);">
                                                    <i class="fas fa-check-circle" style="color: #28a745;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold mb-0" id="pertanyaan-selesai">
                                                    {{ $totalAnswered }} dari {{ $totalQuestions }} pertanyaan
                                                </h6>
                                                <p class="text-muted mb-0 small">Telah terjawab</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="action-buttons">
                            <div class="row g-2">
                                @if ($category && $currentQuestionnaire)
                                    <div class="col-md-6">
                                        <a href="{{ route('questionnaire.fill.questionnaire', [
                                            'category' => $category->slug,
                                            'questionnaire' => $currentQuestionnaire->slug,
                                        ]) }}"
                                            class="btn btn-primary w-100 py-2">
                                            <i class="fas fa-play-circle me-2"></i> Lanjutkan
                                        </a>
                                    </div>
                                @elseif($category)
                                    <div class="col-md-6">
                                        <a href="{{ route('questionnaire.fill.category', ['category' => $category->slug]) }}"
                                            class="btn btn-primary w-100 py-2">
                                            <i class="fas fa-play-circle me-2"></i> Mulai Kuesioner
                                        </a>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <button class="btn btn-outline-primary w-100 py-2 btn-jelajahi-fitur">
                                        <i class="fas fa-gift me-2"></i> Lihat Fitur
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kondisi 3: Selesai Mengerjakan -->
                    <div id="status-selesai-mengerjakan"
                        class="status-section @if (!$isCompleted) d-none @endif">
                        <div class="text-center py-3">
                            <div class="status-icon mb-3">
                                <div class="icon-wrapper">
                                    <i class="fas fa-trophy fa-3x" style="color: var(--accent-yellow);"></i>
                                </div>
                            </div>
                            <h4 class="fw-bold mb-2" style="color: var(--primary-blue);">Kuesioner Selesai!</h4>
                            <p class="mb-3">Terima kasih telah berkontribusi dalam tracer study UAD.</p>

                            <div class="achievement-badges mb-3">
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <div class="badge-item p-2">
                                            <div class="badge-icon mb-2">
                                                <i class="fas fa-check-circle" style="color: #28a745;"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0 small">Kuesioner Selesai</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="badge-item p-2">
                                            <div class="badge-icon mb-2">
                                                <i class="fas fa-star" style="color: var(--accent-yellow);"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0 small">{{ $totalPoints }} Poin</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="badge-item p-2">
                                            <div class="badge-icon mb-2">
                                                <i class="fas fa-users" style="color: var(--primary-blue);"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0 small">Jaringan Alumni</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="badge-item p-2">
                                            <div class="badge-icon mb-2">
                                                <i class="fas fa-unlock-alt" style="color: var(--primary-blue);"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0 small">Akses Penuh</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="action-buttons">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <a href="{{ route('questionnaire.answers.category', ['category' => $category->slug]) }}"
                                            class="btn btn-outline-primary w-100 py-2">
                                            <i class="fas fa-eye me-2"></i> Lihat Hasil
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-primary w-100 py-2 btn-jelajahi-fitur">
                                            <i class="fas fa-gift me-2"></i> Jelajahi Fitur
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Navigation (Demo Only) - Hanya ditampilkan jika ingin demo -->
                    @if (app()->environment('local') || Auth::user()->email == 'test2100018138@webmail.uad.ac.id')
                        <div class="status-navigation mt-3 pt-3 border-top">
                            <div class="text-center">
                                <p class="text-muted mb-2 small">Ubah status untuk melihat tampilan berbeda:</p>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button"
                                        class="btn btn-outline-primary btn-status @if ($isNotStarted) active @endif"
                                        data-status="belum" data-url="{{ route('questionnaire.api.progress') }}">
                                        <i class="fas fa-clock me-1"></i> Belum
                                    </button>
                                    <button type="button"
                                        class="btn btn-outline-primary btn-status @if ($isInProgress) active @endif"
                                        data-status="sedang" data-url="{{ route('questionnaire.api.progress') }}">
                                        <i class="fas fa-spinner me-1"></i> Sedang
                                    </button>
                                    <button type="button"
                                        class="btn btn-outline-primary btn-status @if ($isCompleted) active @endif"
                                        data-status="selesai" data-url="{{ route('questionnaire.api.progress') }}">
                                        <i class="fas fa-check me-1"></i> Selesai
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kategori Section -->
        <div id="kategori-section" class="row mb-4">
            <div class="col-12">
                <h3 class="fw-bold mb-3 text-center" style="color: var(--primary-blue);" data-aos="fade-up">
                    @if ($category)
                        Kategori Anda: {{ $category->name }}
                    @else
                        Pilih Kategori Status Anda
                    @endif
                </h3>

                @if (!$category)
                    <p class="text-center mb-4" data-aos="fade-up">Pilih kategori yang sesuai dengan status Anda saat
                        ini</p>
                @else
                    <p class="text-center mb-4" data-aos="fade-up">Anda telah memilih kategori
                        <strong>{{ $category->name }}</strong>.
                        @if ($otherCategories->isNotEmpty())
                            Berikut kategori lainnya:
                        @endif
                    </p>
                @endif

                <div class="row g-3">
                    @php
                        $allCategories = $otherCategories;
                        if ($category) {
                            $allCategories = $otherCategories->prepend($category);
                        } else {
                            // Jika belum memilih kategori, ambil semua kategori aktif
                            $allCategories = \App\Models\Category::where('is_active', true)->orderBy('order')->get();
                        }

                        $delay = 100;
                    @endphp

                    @foreach ($allCategories as $cat)
                        <div class="col-lg-4 col-md-6 @if ($category && $cat->id == $category->id) col-lg-6 @endif"
                            data-aos="fade-up" data-aos-delay="{{ $delay }}">
                            <a href="@if ($category && $cat->id == $category->id) {{ route('questionnaire.fill.category', ['category' => $cat->slug]) }}
                                 @else
                                    {{ route('questionnaire.categories') }} @endif"
                                class="text-decoration-none">
                                <div
                                    class="category-card p-3 h-100 text-center position-relative 
                                 @if ($category && $cat->id == $category->id) category-active @endif">
                                    <div class="category-icon mx-auto mb-2">
                                        <i class="fas {{ $cat->icon ?? 'fa-folder' }}"></i>
                                    </div>
                                    <h5 class="fw-bold mb-2">{{ $cat->name }}</h5>
                                    <p class="mb-0 small">{{ $cat->description }}</p>
                                    <div class="category-status">
                                        @if ($category && $cat->id == $category->id)
                                            @if ($isInProgress)
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-spinner fa-spin me-1"></i> Sedang
                                                </span>
                                            @elseif($isCompleted)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i> Selesai
                                                </span>
                                            @else
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-arrow-right me-1"></i> Mulai
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-primary">
                                                <i class="fas fa-arrow-right me-1"></i> Mulai
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                        @php $delay += 100; @endphp
                    @endforeach
                </div>

                @if ($category && $otherCategories->isNotEmpty())
                    <div class="text-center mt-4">
                        <p class="text-muted mb-2">
                            <small>
                                <i class="fas fa-info-circle me-1"></i>
                                Anda hanya dapat memilih satu kategori. Untuk mengubah kategori, hubungi admin.
                            </small>
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Fitur Eksklusif Section -->
        <div id="fitur-eksklusif" class="row mb-4">
            <div class="col-12">
                <div class="feature-unlock-section p-4 rounded" data-aos="fade-up">
                    <div class="text-center mb-3">
                        <div class="feature-unlock-icon mx-auto mb-3">
                            <i class="fas fa-lock-open fa-3x" style="color: var(--primary-blue);"></i>
                        </div>
                        <h4 class="fw-bold mb-2" style="color: var(--primary-blue);">Fitur Eksklusif Menanti Anda</h4>
                        <p class="mb-0">Selesaikan kuesioner untuk membuka akses ke fitur-fitur spesial</p>
                    </div>

                    <div class="row g-3">
                        @php
                            $features = [
                                'leaderboard' => [
                                    'name' => 'Leaderboard',
                                    'description' => 'Kumpulkan poin dan bersaing di papan peringkat alumni',
                                    'icon' => 'crown',
                                    'unlocked' => true,
                                    'required_progress' => 0,
                                    'color' => 'var(--accent-yellow)',
                                ],
                                'forum' => [
                                    'name' => 'Forum Diskusi',
                                    'description' => 'Akses event, seminar, dan diskusi eksklusif',
                                    'icon' => 'comments',
                                    'unlocked' => true,
                                    'required_progress' => 0,
                                    'color' => 'var(--primary-blue)',
                                ],
                                'consultation' => [
                                    'name' => 'Konsultasi Karir',
                                    'description' => 'Konsultasi privat dengan mentor berpengalaman',
                                    'icon' => 'chalkboard-teacher',
                                    'unlocked' => $isInProgress || $isCompleted,
                                    'required_progress' => 50,
                                    'color' => 'var(--accent-yellow)',
                                ],
                                'jobs' => [
                                    'name' => 'Lowongan Kerja',
                                    'description' => 'Rekomendasi lowongan eksklusif dari mitra UAD',
                                    'icon' => 'briefcase',
                                    'unlocked' => $isCompleted,
                                    'required_progress' => 100,
                                    'color' => '#6c757d',
                                ],
                            ];

                            $unlockedCount = collect($features)->where('unlocked', true)->count();
                            $totalCount = count($features);
                            $unlockProgress = $totalCount > 0 ? round(($unlockedCount / $totalCount) * 100) : 0;
                        @endphp

                        @foreach ($features as $key => $feature)
                            <div class="col-lg-3 col-md-6">
                                <div
                                    class="feature-card p-3 text-center h-100 @if (!$feature['unlocked']) feature-locked @endif">
                                    @if (!$feature['unlocked'])
                                        <div class="lock-overlay">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    @endif
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-{{ $feature['icon'] }} fa-2x"
                                            style="color: {{ $feature['color'] }};"></i>
                                    </div>
                                    <h5 class="fw-bold mb-2">{{ $feature['name'] }}</h5>
                                    <p class="mb-0 small">{{ $feature['description'] }}</p>
                                    <div class="feature-status mt-2">
                                        @if ($feature['unlocked'])
                                            <span class="badge bg-success small">Terbuka</span>
                                        @else
                                            <span class="badge bg-secondary small">
                                                Progress: {{ $feature['required_progress'] }}%
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="feature-progress mt-4 pt-3 border-top">
                        <div class="text-center">
                            <h6 class="fw-bold mb-2">Progress Pembukaan Fitur</h6>
                            <div class="progress mx-auto" style="max-width: 500px; height: 10px;">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $unlockProgress }}%; background: linear-gradient(90deg, var(--primary-blue), var(--accent-yellow));">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-1"
                                style="max-width: 500px; margin: 0 auto;">
                                <small class="text-muted">{{ $unlockedCount }} dari {{ $totalCount }} fitur</small>
                                <small class="fw-bold" style="color: var(--primary-blue);">{{ $unlockProgress }}%
                                    terbuka</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <div class="p-3 rounded bg-light">
                    <h6 class="fw-bold mb-2" style="color: var(--primary-blue);">
                        <i class="fas fa-question-circle me-2"></i>Punya Pertanyaan?
                    </h6>
                    <p class="mb-0 small">
                        Hubungi kami di
                        <a href="mailto:tracerstudy@uad.ac.id" class="text-decoration-none fw-bold"
                            style="color: var(--primary-blue);">
                            tracerstudy@uad.ac.id
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/main-kuesioner.css') }}">
        <style>
            .category-active {
                border-color: var(--accent-yellow) !important;
                box-shadow: 0 8px 20px rgba(0, 51, 102, 0.15) !important;
            }

            .category-active .category-icon {
                background: linear-gradient(135deg, var(--accent-yellow), var(--primary-blue)) !important;
            }

            .progress-bar-animated {
                background-size: 200% 100% !important;
                animation: progress-bar-stripes 1s linear infinite;
            }

            @keyframes progress-bar-stripes {
                0% {
                    background-position: 200% 0;
                }

                100% {
                    background-position: -200% 0;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('js/main-kuesioner.js') }}"></script>
        <script>
            // Update JavaScript untuk menggunakan data dinamis
            document.addEventListener('DOMContentLoaded', function() {
                // Inisialisasi data untuk JavaScript
                const questionnaireData = {
                    kategori: "{{ $category->name ?? 'Belum dipilih' }}",
                    progress: {{ $progressPercentage }},
                    status: "{{ $status }}",
                    totalPoints: {{ $totalPoints }},
                    isCompleted: {{ $isCompleted ? 'true' : 'false' }},
                    isInProgress: {{ $isInProgress ? 'true' : 'false' }},
                    isNotStarted: {{ $isNotStarted ? 'true' : 'false' }}
                };

                // Simpan data ke window object untuk diakses oleh main-kuesioner.js
                window.questionnaireData = questionnaireData;

                // Update status navigation untuk menghilangkan demo jika sudah ada data nyata
                @if (!app()->environment('local') && Auth::user()->email != 'test2100018138@webmail.uad.ac.id')
                    const statusNav = document.querySelector('.status-navigation');
                    if (statusNav) {
                        statusNav.style.display = 'none';
                    }
                @endif

                // Tambahkan event listener untuk progress update
                @if ($isInProgress)
                    // Auto-update progress setiap 30 detik jika sedang mengerjakan
                    setInterval(() => {
                        fetch("{{ route('questionnaire.api.progress') }}")
                            .then(response => response.json())
                            .then(data => {
                                if (data.success && data.data.overall_progress !==
                                    {{ $progressPercentage }}) {
                                    // Refresh halaman jika progress berubah
                                    location.reload();
                                }
                            })
                            .catch(error => console.error('Error fetching progress:', error));
                    }, 30000);
                @endif
            });
        </script>
    @endpush
</x-app-layout>
