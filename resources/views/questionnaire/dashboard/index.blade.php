<x-app-layout>
    @section('title', 'Kuesioner Tracer Study')

    @php
        $statusQuestionnaire = $statusQuestionnaire ?? null;
        $showCategorySelection = $showCategorySelection ?? false;
        $categories = $categories ?? collect();
        $alumni = Auth::user()->alumni;
        $user = Auth::user();

        // Data untuk dashboard normal
        if ($statusQuestionnaire) {
            $status = $statusQuestionnaire->status ?? 'not_started';
            $isCompleted = $status === 'completed';
            $isInProgress = $status === 'in_progress';
            $isNotStarted = $status === 'not_started';

            $category = $statusQuestionnaire->category ?? null;
            $progressPercentage = $statusQuestionnaire->progress_percentage ?? 0;
            $totalPoints = $statusQuestionnaire->total_points ?? 0;

            // Statistik
            $stats = $stats ?? [];
            $totalAnswered = $stats['total_questions_answered'] ?? 0;
            $totalQuestions = $stats['total_questions'] ?? 0;
            $sectionsCompleted = $stats['sections_completed'] ?? 0;
            $totalSections = $stats['total_sections'] ?? 0;

            // Data lainnya
            $progressRecords = $progressRecords ?? [];
            $activeQuestionnaire = $activeQuestionnaire ?? null;
            $otherCategories = $otherCategories ?? collect();
            $achievements = $achievements ?? collect();

            // Ambil ranking dari leaderboard (dummy data untuk sekarang)
            $currentRank = $stats['current_rank_number'] ?? 1;
            $totalParticipants = $stats['total_participants'] ?? 100;
        }
    @endphp

    <div class="container py-5" style="min-height: 80vh;">
        @if ($showCategorySelection && $categories->isNotEmpty())
            <!-- KONDISI 1: TAMPILKAN FORM PEMILIHAN KATEGORI -->
            <div class="row mb-5">
                <div class="col-12 text-center" data-aos="fade-up">
                    <h1 class="fw-bold mb-3" style="color: var(--primary-blue);">Kuesioner Tracer Study</h1>
                    <p class="lead mb-4">Pilih kategori status Anda saat ini untuk melanjutkan kuesioner</p>

                    <div class="progress-section p-4 mx-auto" style="max-width: 600px;" data-aos="fade-up"
                        data-aos-delay="100">
                        <div class="mb-3">
                            <h5 class="mb-1">{{ $alumni->fullname }}</h5>
                            <p class="text-muted mb-3">{{ $alumni->study_program }}
                                {{ optional($alumni->graduation_date)->format('Y') }}</p>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold">Progress Kuesioner</span>
                                <span class="fw-bold text-accent">0%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <p class="text-muted mb-0">Belum memilih kategori</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4 text-center" style="color: var(--primary-blue);" data-aos="fade-up">Pilih
                        Kategori Status Anda</h3>
                    <p class="text-center mb-5" data-aos="fade-up">Silakan pilih kategori yang sesuai dengan status Anda
                        saat ini untuk mengisi kuesioner yang relevan</p>

                    <form action="{{ route('questionnaire.categories.store') }}" method="POST" id="categoryForm">
                        @csrf
                        <div class="row g-4">
                            @foreach ($categories as $cat)
                                <!-- Kategori {{ $cat->name }} -->
                                <div class="col-lg-4 col-md-6" data-aos="fade-up"
                                    data-aos-delay="{{ ($loop->index + 1) * 100 }}">
                                    <div class="category-card p-4 h-100 text-center position-relative">
                                        <input type="radio" name="category_id" value="{{ $cat->id }}"
                                            id="category_{{ $cat->id }}" class="d-none category-radio"
                                            {{ old('category_id') == $cat->id ? 'checked' : '' }}>
                                        <label for="category_{{ $cat->id }}"
                                            class="category-label w-100 h-100 d-block">
                                            <div class="category-icon mx-auto">
                                                <i class="fas {{ $cat->icon ?? 'fa-folder' }}"></i>
                                            </div>
                                            <h4 class="fw-bold mb-3">{{ strtoupper($cat->name) }}</h4>
                                            <p class="mb-0">{{ $cat->description }}</p>
                                            <div class="category-status">
                                                <span class="badge bg-primary"><i class="fas fa-arrow-right me-1"></i>
                                                    Pilih</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-warning btn-lg px-5">
                                <i class="fas fa-check-circle me-2"></i> Konfirmasi Pilihan
                            </button>
                            <p class="text-muted small mt-3">
                                <i class="fas fa-info-circle me-1"></i>
                                Anda hanya dapat memilih satu kategori. Hubungi admin jika ingin mengubah.
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Fitur yang Akan Terbuka -->
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4 text-center" style="color: var(--primary-blue);" data-aos="fade-up">Fitur
                        yang Akan Terbuka</h3>
                    <p class="text-center mb-5" data-aos="fade-up">Selesaikan kuesioner bagian 1 sampai 4 sesuai
                        kategori status anda untuk membuka fitur-fitur eksklusif berikut:</p>

                    <div class="row g-4">
                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                            <div class="feature-card p-4 position-relative feature-locked">
                                <div class="lock-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <div class="text-center">
                                    <div class="feature-icon mx-auto">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Bagian 1</h5>
                                    <p class="mb-0">Membuka fitur Leaderboard dan kumpulkan point untuk mendapat
                                        keuntungan eksklusif</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="badge bg-warning"><i class="fas fa-lock me-1"></i> Kuesioner 1</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                            <div class="feature-card p-4 position-relative feature-locked">
                                <div class="lock-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <div class="text-center">
                                    <div class="feature-icon mx-auto">
                                        <i class="fas fa-comments"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Bagian 2</h5>
                                    <p class="mb-0">Dapat mengakses Informasi terkait Event, Seminar, Diskusi dan
                                        lainnya di Forum Tracer Study UAD</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="badge bg-warning"><i class="fas fa-lock me-1"></i> Kuesioner 2</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                            <div class="feature-card p-4 position-relative feature-locked">
                                <div class="lock-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <div class="text-center">
                                    <div class="feature-icon mx-auto">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Bagian 3</h5>
                                    <p class="mb-0">Mengakses layanan Konsultasi terkait rencana karir dan bisnis
                                        dengan para Mentor via Email/WA</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="badge bg-warning"><i class="fas fa-lock me-1"></i> Kuesioner 3</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                            <div class="feature-card p-4 position-relative feature-locked">
                                <div class="lock-icon">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <div class="text-center">
                                    <div class="feature-icon mx-auto">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Bagian 4</h5>
                                    <p class="mb-0">Dapat mengakses informasi terkait Lowongan Kerja yang
                                        direkomendasikan oleh UAD</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="badge bg-warning"><i class="fas fa-lock me-1"></i> Kuesioner 4</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="text-center p-5 rounded"
                        style="background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue)); color: white;"
                        data-aos="fade-up">
                        <h3 class="fw-bold mb-3">Mulai Kuesioner Sekarang</h3>
                        <p class="lead mb-4">Pilih kategori yang sesuai dengan status Anda saat ini</p>
                        <button type="submit" form="categoryForm" class="btn btn-warning btn-lg">
                            <i class="fas fa-rocket me-2"></i> Pilih Kategori & Mulai
                        </button>
                    </div>
                </div>
            </div>
        @elseif(!$statusQuestionnaire)
            <!-- KONDISI 2: BELUM MEMILIH KATEGORI (tampilkan pesan) -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow border-0">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-clipboard-list fa-4x" style="color: var(--primary-blue);"></i>
                            </div>
                            <h3 class="fw-bold mb-3" style="color: var(--primary-blue);">
                                Selamat Datang di Tracer Study UAD!
                            </h3>
                            <p class="lead mb-4">
                                Silakan pilih kategori yang sesuai dengan status Anda saat ini untuk memulai mengisi
                                kuesioner.
                            </p>
                            <a href="{{ route('questionnaire.categories') }}" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-play-circle me-2"></i> Pilih Kategori & Mulai
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- KONDISI 3: SUDAH MEMILIH KATEGORI - TAMPILKAN DASHBOARD NORMAL -->
            <div class="row mb-5">
                <div class="col-12 text-center" data-aos="fade-up">
                    <h1 class="fw-bold mb-3" style="color: var(--primary-blue);">Dashboard Kuesioner</h1>
                    <p class="lead mb-4">Pantau progress pengisian kuesioner Anda</p>

                    <div class="progress-section p-4 mx-auto" style="max-width: 800px;" data-aos="fade-up"
                        data-aos-delay="100">
                        <div class="mb-3">
                            <h5 class="mb-1">{{ $alumni->fullname }}</h5>
                            <p class="text-muted mb-3">{{ $alumni->study_program }}
                                {{ optional($alumni->graduation_date)->format('Y') }}</p>
                        </div>

                        <!-- Status Badge -->
                        <div class="mb-4">
                            @if ($isCompleted)
                                <span class="badge bg-success px-3 py-2 fs-6">
                                    <i class="fas fa-check-circle me-1"></i> Selesai
                                </span>
                            @elseif($isInProgress)
                                <span class="badge bg-warning px-3 py-2 fs-6">
                                    <i class="fas fa-spinner fa-spin me-1"></i> Dalam Proses
                                </span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 fs-6">
                                    <i class="fas fa-clock me-1"></i> Belum Dimulai
                                </span>
                            @endif
                            <span class="badge bg-info px-3 py-2 fs-6 ms-2">
                                <i class="fas {{ $category->icon ?? 'fa-folder' }} me-1"></i> {{ $category->name }}
                            </span>

                            <!-- Tombol Batalkan Kategori -->
                            @if (!$isInProgress && !$isCompleted)
                                <button type="button" class="btn btn-outline-danger btn-sm ms-2"
                                    data-bs-toggle="modal" data-bs-target="#cancelCategoryModal">
                                    <i class="fas fa-times me-1"></i> Batalkan Pilihan
                                </button>
                            @endif
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold">Progress Kuesioner</span>
                                <span class="fw-bold text-accent">{{ $progressPercentage }}%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar"
                                    style="width: {{ $progressPercentage }}%"></div>
                            </div>
                            <div class="text-center mt-3">
                                <p class="text-muted mb-0">
                                    {{ $totalAnswered }} dari {{ $totalQuestions }} pertanyaan terjawab |
                                    {{ $sectionsCompleted }} dari {{ $totalSections }} bagian selesai
                                </p>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-4">
                            @if ($isCompleted)
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <a href="{{ route('questionnaire.answers.category', ['categorySlug' => $category->slug]) }}"
                                            class="btn btn-primary w-100 py-2">
                                            <i class="fas fa-eye me-2"></i> Lihat Hasil Jawaban
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('questionnaire.answers.export', ['categorySlug' => $category->slug]) }}"
                                            class="btn btn-outline-primary w-100 py-2">
                                            <i class="fas fa-file-pdf me-2"></i> Export PDF
                                        </a>
                                    </div>
                                </div>
                            @else
                                @if ($activeQuestionnaire)
                                    <a href="{{ route('questionnaire.fill', ['categorySlug' => $category->slug, 'questionnaireSlug' => $activeQuestionnaire->slug]) }}"
                                        class="btn btn-primary w-100 py-3">
                                        @if ($isInProgress)
                                            <i class="fas fa-play-circle me-2"></i> Lanjutkan Kuesioner
                                        @else
                                            <i class="fas fa-play-circle me-2"></i> Mulai Kuesioner
                                        @endif
                                    </a>
                                @else
                                    <a href="{{ route('questionnaire.fill', ['categorySlug' => $category->slug]) }}"
                                        class="btn btn-primary w-100 py-3">
                                        <i class="fas fa-play-circle me-2"></i> Mulai Kuesioner
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik -->
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4 text-center" style="color: var(--primary-blue);" data-aos="fade-up">
                        Statistik</h3>

                    <div class="row g-4">
                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                            <div class="stats-card p-4 text-center">
                                <div class="stats-icon mx-auto mb-3">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stats-number fw-bold mb-2"
                                    style="color: var(--primary-blue); font-size: 2.5rem;">
                                    {{ $sectionsCompleted }}
                                </div>
                                <div class="stats-label">Bagian Selesai</div>
                                <small class="text-muted">dari {{ $totalSections }} bagian</small>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                            <div class="stats-card p-4 text-center">
                                <div class="stats-icon mx-auto mb-3">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="stats-number fw-bold mb-2"
                                    style="color: var(--accent-yellow); font-size: 2.5rem;">
                                    {{ $totalPoints }}
                                </div>
                                <div class="stats-label">Total Points</div>
                                <small class="text-muted">Terkumpul</small>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                            <div class="stats-card p-4 text-center">
                                <div class="stats-icon mx-auto mb-3">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="stats-number fw-bold mb-2" style="color: #28a745; font-size: 2.5rem;">
                                    {{ $currentRank }}
                                </div>
                                <div class="stats-label">Ranking</div>
                                <small class="text-muted">Dari {{ $totalParticipants }} peserta</small>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                            <div class="stats-card p-4 text-center">
                                <div class="stats-icon mx-auto mb-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stats-number fw-bold mb-2" style="color: #fd7e14; font-size: 2.5rem;">
                                    {{ round($progressPercentage) }}%
                                </div>
                                <div class="stats-label">Progress</div>
                                <small class="text-muted">Penyelesaian</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Urutan Kuesioner (Alur) -->
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4 text-center" style="color: var(--primary-blue);" data-aos="fade-up">
                        Alur Kuesioner {{ $category->name }}
                    </h3>

                    <div class="timeline-container" data-aos="fade-up">
                        <div class="timeline">
                            @php
                                // Tentukan urutan kuesioner berdasarkan kategori
                                $questionnaireTitles = [
                                    'bekerja' => [
                                        'Kuesioner Bagian Umum',
                                        'Bagian 1: Informasi Karir Awal',
                                        'Bagian 2: Identitas Perusahaan & Bidang Kerja',
                                        'Bagian 3: Relevansi Studi & Dukungan Kurikulum',
                                        'Bagian 4: Pengembangan Kompetensi Setelah Lulus',
                                    ],
                                    'wirausaha' => [
                                        'Kuesioner Bagian Umum',
                                        'Bagian 1: Profil Usaha',
                                        'Bagian 2: Proses Memulai Usaha',
                                        'Bagian 3: Relevansi Studi & Keterampilan',
                                        'Bagian 4: Rencana Pengembangan Usaha',
                                    ],
                                    'pendidikan' => [
                                        'Kuesioner Bagian Umum',
                                        'Bagian 1: Jenis Pendidikan Lanjutan',
                                        'Bagian 2: Institusi & Program Studi',
                                        'Bagian 3: Motivasi & Tujuan',
                                        'Bagian 4: Persiapan & Dukungan',
                                    ],
                                    'pencari' => [
                                        'Kuesioner Bagian Umum',
                                        'Bagian 1: Aktivitas Pencarian Kerja',
                                        'Bagian 2: Hambatan & Tantangan',
                                        'Bagian 3: Keterampilan & Persiapan',
                                        'Bagian 4: Harapan & Rencana',
                                    ],
                                    'tidak-kerja' => [
                                        'Kuesioner Bagian Umum',
                                        'Bagian 1: Kondisi Saat Ini',
                                        'Bagian 2: Rencana Ke Depan',
                                        'Bagian 3: Keterampilan yang Dimiliki',
                                        'Bagian 4: Dukungan yang Diperlukan',
                                    ],
                                ];

                                $titles = $questionnaireTitles[$category->slug] ?? [
                                    'Kuesioner Bagian Umum',
                                    'Bagian 1',
                                    'Bagian 2',
                                    'Bagian 3',
                                    'Bagian 4',
                                ];
                            @endphp

                            @foreach ($progressRecords as $record)
                                @php
                                    $isCompleted = $record['progress'] && $record['progress']->status === 'completed';
                                    $isInProgress =
                                        $record['progress'] && $record['progress']->status === 'in_progress';
                                    $titleIndex = $record['is_general'] ? 0 : $record['section_number'];
                                    $title = $titles[$titleIndex] ?? $record['questionnaire']->name;
                                @endphp

                                <div
                                    class="timeline-item {{ $isCompleted ? 'completed' : '' }} {{ $isInProgress ? 'current' : '' }}">
                                    <div class="timeline-marker">
                                        @if ($isCompleted)
                                            <i class="fas fa-check-circle"></i>
                                        @elseif($isInProgress)
                                            <i class="fas fa-spinner fa-spin"></i>
                                        @else
                                            <i class="fas fa-circle"></i>
                                        @endif
                                    </div>
                                    <div class="timeline-content">
                                        <h5 class="fw-bold mb-1">{{ $title }}</h5>
                                        <p class="mb-0 text-muted">
                                            {{ $record['answered_count'] }} dari {{ $record['total_questions'] }}
                                            pertanyaan terjawab
                                        </p>
                                        @if ($record['is_general'])
                                            <span class="badge bg-info mt-1">Wajib untuk semua kategori</span>
                                        @endif
                                    </div>
                                </div>

                                @if (!$loop->last)
                                    <div class="timeline-connector"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fitur yang Akan Terbuka -->
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4 text-center" style="color: var(--primary-blue);" data-aos="fade-up">Fitur
                        yang Akan Terbuka</h3>
                    <p class="text-center mb-5" data-aos="fade-up">Selesaikan kuesioner bagian 1 sampai 4 sesuai
                        kategori status anda untuk membuka fitur-fitur eksklusif berikut:</p>

                    <div class="row g-4">
                        @php
                            // Hitung bagian mana yang sudah selesai
                            $generalCompleted = false;
                            $part1Completed = false;
                            $part2Completed = false;
                            $part3Completed = false;
                            $part4Completed = false;

                            foreach ($progressRecords as $record) {
                                if (
                                    $record['is_general'] &&
                                    $record['progress'] &&
                                    $record['progress']->status === 'completed'
                                ) {
                                    $generalCompleted = true;
                                }

                                if (!$record['is_general']) {
                                    switch ($record['section_number']) {
                                        case 1:
                                            $part1Completed =
                                                $record['progress'] && $record['progress']->status === 'completed';
                                            break;
                                        case 2:
                                            $part2Completed =
                                                $record['progress'] && $record['progress']->status === 'completed';
                                            break;
                                        case 3:
                                            $part3Completed =
                                                $record['progress'] && $record['progress']->status === 'completed';
                                            break;
                                        case 4:
                                            $part4Completed =
                                                $record['progress'] && $record['progress']->status === 'completed';
                                            break;
                                    }
                                }
                            }
                        @endphp

                        <!-- Fitur 1: Terbuka setelah Bagian 1 -->
                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                            <div
                                class="feature-card p-4 position-relative {{ $generalCompleted && $part1Completed ? '' : 'feature-locked' }}">
                                @if (!$generalCompleted || !$part1Completed)
                                    <div class="lock-icon">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                @endif
                                <div class="text-center">
                                    <div
                                        class="feature-icon mx-auto {{ $generalCompleted && $part1Completed ? 'pulse-animation' : '' }}">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Bagian 1</h5>
                                    <p class="mb-0">Membuka fitur Leaderboard dan kumpulkan point untuk mendapat
                                        keuntungan eksklusif</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span
                                        class="badge {{ $generalCompleted && $part1Completed ? 'bg-success' : 'bg-warning' }}">
                                        <i
                                            class="fas {{ $generalCompleted && $part1Completed ? 'fa-check' : 'fa-lock' }} me-1"></i>
                                        {{ $generalCompleted && $part1Completed ? 'Terbuka' : 'Kuesioner 1' }}
                                    </span>
                                </div>
                                @if (!$generalCompleted)
                                    <div class="mt-2">
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Harap
                                            selesaikan kuesioner umum terlebih dahulu</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Fitur 2: Terbuka setelah Bagian 2 -->
                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                            <div
                                class="feature-card p-4 position-relative {{ $generalCompleted && $part1Completed && $part2Completed ? '' : 'feature-locked' }}">
                                @if (!$generalCompleted || !$part1Completed || !$part2Completed)
                                    <div class="lock-icon">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                @endif
                                <div class="text-center">
                                    <div
                                        class="feature-icon mx-auto {{ $generalCompleted && $part1Completed && $part2Completed ? 'pulse-animation' : '' }}">
                                        <i class="fas fa-comments"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Bagian 2</h5>
                                    <p class="mb-0">Dapat mengakses Informasi terkait Event, Seminar, Diskusi dan
                                        lainnya di Forum Tracer Study UAD</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span
                                        class="badge {{ $generalCompleted && $part1Completed && $part2Completed ? 'bg-success' : 'bg-warning' }}">
                                        <i
                                            class="fas {{ $generalCompleted && $part1Completed && $part2Completed ? 'fa-check' : 'fa-lock' }} me-1"></i>
                                        {{ $generalCompleted && $part1Completed && $part2Completed ? 'Terbuka' : 'Kuesioner 2' }}
                                    </span>
                                </div>
                                @if (!$generalCompleted)
                                    <div class="mt-2">
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Harap
                                            selesaikan kuesioner umum terlebih dahulu</small>
                                    </div>
                                @elseif(!$part1Completed)
                                    <div class="mt-2">
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Harap
                                            selesaikan kuesioner bagian 1 terlebih dahulu</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Fitur 3: Terbuka setelah Bagian 3 -->
                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                            <div
                                class="feature-card p-4 position-relative {{ $generalCompleted && $part1Completed && $part2Completed && $part3Completed ? '' : 'feature-locked' }}">
                                @if (!$generalCompleted || !$part1Completed || !$part2Completed || !$part3Completed)
                                    <div class="lock-icon">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                @endif
                                <div class="text-center">
                                    <div
                                        class="feature-icon mx-auto {{ $generalCompleted && $part1Completed && $part2Completed && $part3Completed ? 'pulse-animation' : '' }}">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Bagian 3</h5>
                                    <p class="mb-0">Mengakses layanan Konsultasi terkait rencana karir dan bisnis
                                        dengan para Mentor via Email/WA</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span
                                        class="badge {{ $generalCompleted && $part1Completed && $part2Completed && $part3Completed ? 'bg-success' : 'bg-warning' }}">
                                        <i
                                            class="fas {{ $generalCompleted && $part1Completed && $part2Completed && $part3Completed ? 'fa-check' : 'fa-lock' }} me-1"></i>
                                        {{ $generalCompleted && $part1Completed && $part2Completed && $part3Completed ? 'Terbuka' : 'Kuesioner 3' }}
                                    </span>
                                </div>
                                @if (!$generalCompleted)
                                    <div class="mt-2">
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Harap
                                            selesaikan kuesioner umum terlebih dahulu</small>
                                    </div>
                                @elseif(!$part1Completed)
                                    <div class="mt-2">
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Harap
                                            selesaikan kuesioner bagian 1 terlebih dahulu</small>
                                    </div>
                                @elseif(!$part2Completed)
                                    <div class="mt-2">
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Harap
                                            selesaikan kuesioner bagian 2 terlebih dahulu</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Fitur 4: Terbuka setelah Bagian 4 -->
                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                            <div
                                class="feature-card p-4 position-relative {{ $generalCompleted && $part1Completed && $part2Completed && $part3Completed && $part4Completed ? '' : 'feature-locked' }}">
                                @if (!$generalCompleted || !$part1Completed || !$part2Completed || !$part3Completed || !$part4Completed)
                                    <div class="lock-icon">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                @endif
                                <div class="text-center">
                                    <div
                                        class="feature-icon mx-auto {{ $generalCompleted && $part1Completed && $part2Completed && $part3Completed && $part4Completed ? 'pulse-animation' : '' }}">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Bagian 4</h5>
                                    <p class="mb-0">Dapat mengakses informasi terkait Lowongan Kerja yang
                                        direkomendasikan oleh UAD</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span
                                        class="badge {{ $generalCompleted && $part1Completed && $part2Completed && $part3Completed && $part4Completed ? 'bg-success' : 'bg-warning' }}">
                                        <i
                                            class="fas {{ $generalCompleted && $part1Completed && $part2Completed && $part3Completed && $part4Completed ? 'fa-check' : 'fa-lock' }} me-1"></i>
                                        {{ $generalCompleted && $part1Completed && $part2Completed && $part3Completed && $part4Completed ? 'Terbuka' : 'Kuesioner 4' }}
                                    </span>
                                </div>
                                @if (!$generalCompleted)
                                    <div class="mt-2">
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Harap
                                            selesaikan kuesioner umum terlebih dahulu</small>
                                    </div>
                                @elseif(!$part1Completed)
                                    <div class="mt-2">
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Harap
                                            selesaikan kuesioner bagian 1 terlebih dahulu</small>
                                    </div>
                                @elseif(!$part2Completed)
                                    <div class="mt-2">
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Harap
                                            selesaikan kuesioner bagian 2 terlebih dahulu</small>
                                    </div>
                                @elseif(!$part3Completed)
                                    <div class="mt-2">
                                        <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Harap
                                            selesaikan kuesioner bagian 3 terlebih dahulu</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kategori Lainnya -->
            @if ($otherCategories->isNotEmpty())
                <div class="row mb-5">
                    <div class="col-12">
                        <h3 class="fw-bold mb-4 text-center" style="color: var(--primary-blue);" data-aos="fade-up">
                            Kategori Lainnya</h3>
                        <p class="text-center mb-5" data-aos="fade-up">Anda hanya dapat mengisi 1 kategori kuesioner
                            sesuai dengan status Anda saat ini</p>

                        <div class="row g-4">
                            @foreach ($otherCategories as $cat)
                                <div class="col-lg-4 col-md-6" data-aos="fade-up"
                                    data-aos-delay="{{ ($loop->index + 1) * 100 }}">
                                    <div class="category-card p-4 h-100 text-center position-relative">
                                        <div class="category-icon mx-auto">
                                            <i class="fas {{ $cat->icon ?? 'fa-folder' }}"></i>
                                        </div>
                                        <h4 class="fw-bold mb-3">{{ strtoupper($cat->name) }}</h4>
                                        <p class="mb-0">{{ $cat->description }}</p>
                                        <div class="category-status">
                                            <span class="badge bg-secondary"><i class="fas fa-info-circle me-1"></i>
                                                Tidak Dipilih</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Call to Action -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="text-center p-5 rounded"
                        style="background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue)); color: white;"
                        data-aos="fade-up">
                        <h3 class="fw-bold mb-3">Selesaikan Kuesioner Yang Ada</h3>
                        <p class="lead mb-4">Untuk Membuka Fitur-Fitur Keren Lainnya</p>
                        @if ($isCompleted)
                            <a href="{{ route('questionnaire.answers.category', ['categorySlug' => $category->slug]) }}"
                                class="btn btn-warning btn-lg">
                                <i class="fas fa-eye me-2"></i> Lihat Hasil Jawaban
                            </a>
                        @else
                            <a href="{{ route('questionnaire.fill', ['categorySlug' => $category->slug, 'questionnaireSlug' => $activeQuestionnaire->slug ?? '']) }}"
                                class="btn btn-warning btn-lg">
                                <i class="fas fa-rocket me-2"></i>
                                {{ $isInProgress ? 'Lanjutkan Kuesioner Sekarang' : 'Mulai Kuesioner Sekarang' }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Batalkan Kategori -->
    <div class="modal fade" id="cancelCategoryModal" tabindex="-1" aria-labelledby="cancelCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelCategoryModalLabel">Batalkan Pilihan Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin membatalkan pilihan kategori
                        <strong>{{ $category->name ?? '' }}</strong>?
                    </p>
                    <p class="text-danger">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Jika Anda sudah mulai mengisi kuesioner, data jawaban akan dihapus dan tidak dapat dikembalikan.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="{{ route('questionnaire.category.cancel') }}" method="POST"
                        id="cancelCategoryForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            :root {
                --primary-blue: #003366;
                --secondary-blue: #3b82f6;
                --accent-yellow: #fab300;
            }

            body {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            .main-content {
                flex: 1;
                margin-bottom: auto;
            }

            .progress-section {
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            }

            .progress {
                height: 12px;
                background-color: #e9ecef;
            }

            .progress-bar {
                background-color: var(--accent-yellow);
            }

            .category-card {
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                overflow: hidden;
                border: 2px solid transparent;
                cursor: pointer;
                height: 100%;
            }

            .category-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
                border-color: var(--accent-yellow);
            }

            .category-card input[type="radio"]:checked+.category-label {
                border-color: var(--accent-yellow);
                background-color: rgba(250, 179, 0, 0.1);
            }

            .category-icon {
                width: 70px;
                height: 70px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: bold;
                font-size: 1.5rem;
                margin-bottom: 15px;
            }

            .category-status {
                position: absolute;
                top: 15px;
                right: 15px;
            }

            .questionnaire-card {
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                overflow: hidden;
                border: 2px solid transparent;
            }

            .questionnaire-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            }

            .questionnaire-card.completed {
                border-color: var(--accent-yellow);
            }

            .questionnaire-number {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: bold;
                font-size: 1.2rem;
            }

            .questionnaire-status {
                position: absolute;
                top: 15px;
                right: 15px;
            }

            .feature-card {
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                height: 100%;
            }

            .feature-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.5rem;
                margin-bottom: 15px;
            }

            .feature-locked {
                opacity: 0.6;
            }

            .lock-icon {
                position: absolute;
                top: 15px;
                right: 15px;
                color: #6c757d;
            }

            .stats-card {
                background: white;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
                height: 100%;
            }

            .stats-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            }

            .stats-icon {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.5rem;
            }

            @keyframes pulse {
                0% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.05);
                }

                100% {
                    transform: scale(1);
                }
            }

            .pulse-animation {
                animation: pulse 2s infinite;
            }

            .btn-primary-custom {
                background-color: var(--primary-blue);
                color: white;
                border: none;
            }

            .btn-primary-custom:hover {
                background-color: var(--secondary-blue);
                color: white;
            }

            /* Timeline Styles */
            .timeline-container {
                max-width: 800px;
                margin: 0 auto;
            }

            .timeline {
                position: relative;
                padding-left: 30px;
            }

            .timeline-item {
                position: relative;
                margin-bottom: 30px;
                background: white;
                border-radius: 12px;
                padding: 20px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                border-left: 4px solid #ddd;
            }

            .timeline-item.completed {
                border-left-color: #28a745;
                background-color: rgba(40, 167, 69, 0.05);
            }

            .timeline-item.current {
                border-left-color: var(--accent-yellow);
                background-color: rgba(250, 179, 0, 0.05);
            }

            .timeline-marker {
                position: absolute;
                left: -40px;
                top: 50%;
                transform: translateY(-50%);
                width: 30px;
                height: 30px;
                border-radius: 50%;
                background: white;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }

            .timeline-item.completed .timeline-marker {
                background: #28a745;
                color: white;
            }

            .timeline-item.current .timeline-marker {
                background: var(--accent-yellow);
                color: white;
            }

            .timeline-connector {
                position: absolute;
                left: -25px;
                top: 60px;
                bottom: -30px;
                width: 2px;
                background: #ddd;
            }

            .timeline-item:last-child .timeline-connector {
                display: none;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize AOS
                if (typeof AOS !== 'undefined') {
                    AOS.init({
                        duration: 800,
                        once: true,
                        offset: 100
                    });
                }

                // Category selection
                const categoryCards = document.querySelectorAll('.category-card');
                categoryCards.forEach(card => {
                    card.addEventListener('click', function() {
                        // Remove selected class from all cards
                        document.querySelectorAll('.category-card').forEach(c => {
                            c.style.borderColor = 'transparent';
                            c.style.backgroundColor = 'white';
                        });

                        // Add selected style to clicked card
                        this.style.borderColor = 'var(--accent-yellow)';
                        this.style.backgroundColor = 'rgba(250, 179, 0, 0.1)';

                        // Check the radio input
                        const radio = this.querySelector('input[type="radio"]');
                        if (radio) {
                            radio.checked = true;
                        }
                    });
                });

                // Form validation
                const categoryForm = document.getElementById('categoryForm');
                if (categoryForm) {
                    categoryForm.addEventListener('submit', function(e) {
                        const selectedCategory = this.querySelector('input[name="category_id"]:checked');
                        if (!selectedCategory) {
                            e.preventDefault();
                            alert('Harap pilih satu kategori terlebih dahulu!');
                            return false;
                        }
                        return true;
                    });
                }

                // Cancel category form confirmation
                const cancelForm = document.getElementById('cancelCategoryForm');
                if (cancelForm) {
                    cancelForm.addEventListener('submit', function(e) {
                        if (!confirm('Apakah Anda yakin ingin membatalkan pilihan kategori?')) {
                            e.preventDefault();
                        }
                    });
                }

                // Auto-update progress if in progress
                @if (isset($isInProgress) && $isInProgress)
                    setInterval(() => {
                        fetch("{{ route('questionnaire.api.progress') }}")
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    const currentProgress = {{ $progressPercentage }};
                                    if (Math.abs(data.data.overall_progress - currentProgress) > 5) {
                                        location.reload();
                                    }
                                }
                            })
                            .catch(error => console.error('Error fetching progress:', error));
                    }, 30000);
                @endif

                // Toast notification function
                function showToast(message, type = 'info') {
                    if (typeof bootstrap === 'undefined') return;

                    const toast = document.createElement('div');
                    const bgColor = type === 'warning' ? 'warning' : type === 'success' ? 'success' : 'info';

                    toast.className =
                        `toast align-items-center text-white bg-${bgColor} border-0 position-fixed top-0 end-0 m-3`;
                    toast.style.zIndex = '9999';
                    toast.innerHTML = `
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-${type === 'success' ? 'check' : type === 'warning' ? 'exclamation-triangle' : 'info'}-circle me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                `;

                    document.body.appendChild(toast);
                    const bsToast = new bootstrap.Toast(toast);
                    bsToast.show();

                    toast.addEventListener('hidden.bs.toast', function() {
                        document.body.removeChild(toast);
                    });
                }

                // Show success/error messages
                @if (session('success'))
                    setTimeout(() => {
                        showToast("{{ session('success') }}", 'success');
                    }, 1000);
                @endif

                @if (session('error'))
                    setTimeout(() => {
                        showToast("{{ session('error') }}", 'warning');
                    }, 1000);
                @endif
            });
        </script>
    @endpush
</x-app-layout>
