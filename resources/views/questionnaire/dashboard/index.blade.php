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
        }
    @endphp

    <div class="container py-5">
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
                            <div class="feature-card p-4 position-relative">
                                <div class="text-center">
                                    <div class="feature-icon mx-auto pulse-animation">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Bagian 1</h5>
                                    <p class="mb-0">Membuka fitur Leaderboard dan kumpulkan point untuk mendapat
                                        keuntungan eksklusif</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i> Terbuka</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                            <div class="feature-card p-4 position-relative">
                                <div class="text-center">
                                    <div class="feature-icon mx-auto pulse-animation">
                                        <i class="fas fa-comments"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Bagian 2</h5>
                                    <p class="mb-0">Dapat mengakses Informasi terkait Event, Seminar, Diskusi dan
                                        lainnya di Forum Tracer Study UAD</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i> Terbuka</span>
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
                                    {{ $stats['achievements_count'] ?? 0 }}
                                </div>
                                <div class="stats-label">Achievements</div>
                                <small class="text-muted">Dicapai</small>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                            <div class="stats-card p-4 text-center">
                                <div class="stats-icon mx-auto mb-3">
                                    <i class="fas fa-medal"></i>
                                </div>
                                <div class="stats-number fw-bold mb-2" style="color: #fd7e14; font-size: 2.5rem;">
                                    {{ $stats['current_rank'] ?? 'Beginner' }}
                                </div>
                                <div class="stats-label">Ranking</div>
                                <small class="text-muted">Terkini</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Urutan Kuesioner -->
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4 text-center" style="color: var(--primary-blue);" data-aos="fade-up">
                        Urutan Kuesioner</h3>

                    <div class="modal fade category-modal" id="categoryModal" tabindex="-1"
                        aria-labelledby="categoryModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header"
                                    style="background-color: var(--primary-blue); color: white;">
                                    <h5 class="modal-title" id="categoryModalLabel">Daftar Kuesioner</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h4 class="fw-bold mb-4 text-center" id="modalCategoryTitle">
                                        {{ $category->name }}</h4>
                                    <p class="text-center mb-4">Berikut adalah daftar kuesioner yang perlu Anda isi
                                        berdasarkan kategori status Anda saat ini.</p>

                                    <div class="row g-4">
                                        @foreach ($progressRecords as $record)
                                            @php
                                                $isCompleted =
                                                    $record['progress'] && $record['progress']->status === 'completed';
                                                $isInProgress =
                                                    $record['progress'] &&
                                                    $record['progress']->status === 'in_progress';
                                                $isCurrent =
                                                    $record['questionnaire']->id === ($activeQuestionnaire->id ?? null);

                                                $statusColor = $isCompleted
                                                    ? 'success'
                                                    : ($isInProgress || $isCurrent
                                                        ? 'warning'
                                                        : 'secondary');
                                                $statusIcon = $isCompleted
                                                    ? 'fa-check'
                                                    : ($isInProgress || $isCurrent
                                                        ? 'fa-spinner fa-spin'
                                                        : 'fa-clock');
                                                $statusText = $isCompleted
                                                    ? 'Selesai'
                                                    : ($isInProgress || $isCurrent
                                                        ? 'Dalam Proses'
                                                        : 'Belum');
                                            @endphp

                                            <div class="col-12" data-aos="fade-up"
                                                data-aos-delay="{{ $loop->index * 100 }}">
                                                <div
                                                    class="questionnaire-card p-4 h-100 position-relative {{ $isCompleted ? 'completed' : '' }}">
                                                    <div class="questionnaire-status">
                                                        <span class="badge bg-{{ $statusColor }}">
                                                            <i class="fas {{ $statusIcon }} me-1"></i>
                                                            {{ $statusText }}
                                                        </span>
                                                    </div>
                                                    <div class="d-flex align-items-start mb-3">
                                                        <div class="questionnaire-number me-3">
                                                            {{ $record['sequence']->order }}</div>
                                                        <div>
                                                            <h4 class="fw-bold mb-1">
                                                                {{ $record['questionnaire']->name }}</h4>
                                                            <p class="text-muted mb-0">
                                                                @if ($record['is_general'])
                                                                    (Bagian Umum)
                                                                @else
                                                                    (Bagian {{ $record['section_number'] }})
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                    @if ($record['questionnaire']->description)
                                                        <p class="mb-3">{{ $record['questionnaire']->description }}
                                                        </p>
                                                    @endif
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span
                                                            class="{{ $isCompleted ? 'text-success' : 'text-warning' }}">
                                                            <i
                                                                class="fas {{ $isCompleted ? 'fa-check-circle' : 'fa-clock' }} me-1"></i>
                                                            {{ $record['answered_count'] }} dari
                                                            {{ $record['total_questions'] }} pertanyaan terjawab
                                                        </span>
                                                        @if ($isCompleted)
                                                            <button class="btn btn-outline-primary btn-sm">
                                                                <i class="fas fa-eye me-1"></i> Lihat Hasil
                                                            </button>
                                                        @else
                                                            <a href="{{ route('questionnaire.fill', ['categorySlug' => $category->slug, 'questionnaireSlug' => $record['questionnaire']->slug]) }}"
                                                                class="btn btn-primary-custom btn-sm">
                                                                <i class="fas fa-play me-1"></i>
                                                                {{ $isInProgress || $isCurrent ? 'Lanjutkan' : 'Mulai' }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-4 text-center">
                                        <p class="text-muted"><small>Kuesioner 1 adalah kuesioner umum yang wajib diisi
                                                oleh semua alumni. Kuesioner 2-4 disesuaikan dengan kategori status Anda
                                                saat ini.</small></p>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#categoryModal">
                            <i class="fas fa-list-ol me-2"></i> Lihat Detail Urutan Kuesioner
                        </button>
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
                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                            <div class="feature-card p-4 position-relative">
                                <div class="text-center">
                                    <div class="feature-icon mx-auto pulse-animation">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Bagian 1</h5>
                                    <p class="mb-0">Membuka fitur Leaderboard dan kumpulkan point untuk mendapat
                                        keuntungan eksklusif</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i> Terbuka</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                            <div class="feature-card p-4 position-relative">
                                <div class="text-center">
                                    <div class="feature-icon mx-auto pulse-animation">
                                        <i class="fas fa-comments"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Bagian 2</h5>
                                    <p class="mb-0">Dapat mengakses Informasi terkait Event, Seminar, Diskusi dan
                                        lainnya di Forum Tracer Study UAD</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i> Terbuka</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                            <div
                                class="feature-card p-4 position-relative {{ $sectionsCompleted < 2 ? 'feature-locked' : '' }}">
                                @if ($sectionsCompleted < 2)
                                    <div class="lock-icon">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                @endif
                                <div class="text-center">
                                    <div
                                        class="feature-icon mx-auto {{ $sectionsCompleted >= 2 ? 'pulse-animation' : '' }}">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Bagian 3</h5>
                                    <p class="mb-0">Mengakses layanan Konsultasi terkait rencana karir dan bisnis
                                        dengan para Mentor via Email/WA</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="badge {{ $sectionsCompleted >= 2 ? 'bg-success' : 'bg-warning' }}">
                                        <i
                                            class="fas {{ $sectionsCompleted >= 2 ? 'fa-check' : 'fa-lock' }} me-1"></i>
                                        {{ $sectionsCompleted >= 2 ? 'Terbuka' : 'Kuesioner 3' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                            <div
                                class="feature-card p-4 position-relative {{ $sectionsCompleted < 3 ? 'feature-locked' : '' }}">
                                @if ($sectionsCompleted < 3)
                                    <div class="lock-icon">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                @endif
                                <div class="text-center">
                                    <div
                                        class="feature-icon mx-auto {{ $sectionsCompleted >= 3 ? 'pulse-animation' : '' }}">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Bagian 4</h5>
                                    <p class="mb-0">Dapat mengakses informasi terkait Lowongan Kerja yang
                                        direkomendasikan oleh UAD</p>
                                </div>
                                <div class="mt-3 text-center">
                                    <span class="badge {{ $sectionsCompleted >= 3 ? 'bg-success' : 'bg-warning' }}">
                                        <i
                                            class="fas {{ $sectionsCompleted >= 3 ? 'fa-check' : 'fa-lock' }} me-1"></i>
                                        {{ $sectionsCompleted >= 3 ? 'Terbuka' : 'Kuesioner 4' }}
                                    </span>
                                </div>
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
            <div class="row">
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

    @push('styles')
        <style>
            :root {
                --primary-blue: #003366;
                --secondary-blue: #3b82f6;
                --accent-yellow: #fab300;
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
