<div class="submit-section mt-5" data-aos="fade-up">
    <div class="section-header mb-4">
        <h2 class="fw-bold mb-3" style="color: var(--primary-blue);">
            <i class="fas fa-paper-plane me-2"></i>Kirim Informasi & Dapatkan Points
        </h2>
        <p class="text-muted">Kontribusikan informasi bermanfaat dan dapatkan poin untuk meningkatkan
            peringkat Anda!</p>
    </div>

    <!-- Points Information Card -->
    <div class="points-info-card mb-5">
        <h4 class="fw-bold mb-4" style="color: var(--primary-blue);">Cara Mendapatkan Points</h4>
        <div class="points-item">
            <div class="points-icon">
                <i class="fas fa-comments"></i>
            </div>
            <div>
                <h6 class="mb-1">Informasi Forum (Event, Seminar, Tips)</h6>
                <p class="mb-0 text-muted">Dapatkan <strong>2,000 points</strong> per informasi yang
                    disetujui admin</p>
            </div>
        </div>
        <div class="points-item">
            <div class="points-icon">
                <i class="fas fa-briefcase"></i>
            </div>
            <div>
                <h6 class="mb-1">Informasi Lowongan Kerja</h6>
                <p class="mb-0 text-muted">Dapatkan <strong>3,000 points</strong> per lowongan yang
                    disetujui admin</p>
            </div>
        </div>
        <div class="points-item">
            <div class="points-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div>
                <h6 class="mb-1">Mengisi Kuesioner Tracer Study</h6>
                <p class="mb-0 text-muted">Dapatkan <strong>5,000 points</strong> per kuesioner yang
                    diselesaikan</p>
            </div>
        </div>
    </div>

    <!-- Form Container dengan Tabs Internal -->
    <div class="form-container" data-aos="fade-up">
        <ul class="nav nav-pills mb-4" id="submitTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="forum-tab" data-bs-toggle="pill" data-bs-target="#forum"
                    type="button">
                    <i class="fas fa-comments me-2"></i>Informasi Forum
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="job-tab" data-bs-toggle="pill" data-bs-target="#job" type="button">
                    <i class="fas fa-briefcase me-2"></i>Lowongan Kerja
                </button>
            </li>
        </ul>

        <div class="tab-content" id="submitTabsContent">
            <!-- Forum Form -->
            <div class="tab-pane fade show active" id="forum" role="tabpanel">
                @include('leaderboard._forum-form')
            </div>

            <!-- Job Form -->
            <div class="tab-pane fade" id="job" role="tabpanel">
                @include('leaderboard._job-form')
            </div>
        </div>
    </div>
</div>
