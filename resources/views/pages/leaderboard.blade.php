<x-app-layout>
    @section('title', 'Leaderboard Alumni')

    <section class="leaderboard-header">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3" data-aos="fade-up">Leaderboard Alumni</h1>
            <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">Kompetisi sehat untuk berkontribusi bagi almamater
                dan dapatkan rewards eksklusif!</p>
            <div class="points-badge d-inline-block px-4 py-2" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-coins me-2"></i>Total Points Anda: <strong>15,500</strong>
            </div>
        </div>
    </section>

    <div class="main-content">
        <div class="container py-5">
            <!-- Judul Section Leaderboard -->
            <div class="section-header mb-4" data-aos="fade-up">
                <h2 class="fw-bold mb-3" style="color: var(--primary-blue);">
                    <i class="fas fa-trophy me-2"></i>Peringkat Leaderboard
                </h2>
                <p class="text-muted">Lihat peringkat alumni teratas dan perjuangkan posisi terbaik Anda!</p>
            </div>

            <!-- Podium Section -->
            <div class="podium-container" data-aos="fade-up">
                <div class="podium">
                    <!-- Second Place -->
                    <div class="podium-place podium-2">
                        <div class="podium-stand">
                            <div class="podium-content">
                                <div class="podium-avatar">SR</div>
                                <div class="podium-name">Siti Rahayu</div>
                                <div class="podium-points">28,500 pts</div>
                            </div>
                        </div>
                        <div class="place-badge">2nd</div>
                    </div>

                    <!-- First Place -->
                    <div class="podium-place podium-1">
                        <div class="crown">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="podium-stand">
                            <div class="podium-content">
                                <div class="podium-avatar">AR</div>
                                <div class="podium-name">Ahmad Rizki</div>
                                <div class="podium-points">32,000 pts</div>
                            </div>
                        </div>
                        <div class="place-badge">1st</div>
                    </div>

                    <!-- Third Place -->
                    <div class="podium-place podium-3">
                        <div class="podium-stand">
                            <div class="podium-content">
                                <div class="podium-avatar">BS</div>
                                <div class="podium-name">Budi Santoso</div>
                                <div class="podium-points">25,800 pts</div>
                            </div>
                        </div>
                        <div class="place-badge">3rd</div>
                    </div>
                </div>
            </div>

            <!-- Benefits Section -->
            <div class="benefits-section" data-aos="fade-up">
                <h3 class="text-center fw-bold mb-4" style="color: var(--primary-blue);">Keuntungan Menjadi Top Rank
                </h3>
                <div class="row">
                    <div class="col-md-4">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-award"></i>
                            </div>
                            <h5>Sertifikat Prestasi</h5>
                            <p class="text-muted">Dapatkan sertifikat penghargaan dari Kepala Prodi UAD</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h5>Networking Eksklusif</h5>
                            <p class="text-muted">Akses ke komunitas alumni berprestasi dan mentor</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="fas fa-gift"></i>
                            </div>
                            <h5>Rewards Menarik</h5>
                            <p class="text-muted">Voucher, merchandise eksklusif, dan hadiah lainnya</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leaderboard Table -->
            <div class="leaderboard-table mb-5" data-aos="fade-up">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="80">Rank</th>
                                <th>Alumni</th>
                                <th width="150" class="text-end">Total Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Ranks 1-20 -->
                            <tr>
                                <td class="user-rank">1</td>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">AR</div>
                                        <div class="user-details">
                                            <h6>Ahmad Rizki</h6>
                                            <small>Informatika 2018</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end"><span class="points-badge">32,000 pts</span></td>
                            </tr>
                            <tr>
                                <td class="user-rank">2</td>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">SR</div>
                                        <div class="user-details">
                                            <h6>Siti Rahayu</h6>
                                            <small>Manajemen 2019</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end"><span class="points-badge">28,500 pts</span></td>
                            </tr>
                            <!-- More rows for ranks 3-19 -->
                            <tr>
                                <td class="user-rank">20</td>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">MW</div>
                                        <div class="user-details">
                                            <h6>Maya Wijaya</h6>
                                            <small>Psikologi 2020</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end"><span class="points-badge">16,200 pts</span></td>
                            </tr>

                            <!-- Current User Row (Always visible) -->
                            <tr class="current-user">
                                <td class="user-rank">30</td>
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">DI</div>
                                        <div class="user-details">
                                            <h6>Deny Iqbal</h6>
                                            <small>Informatika 2018</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end"><span class="points-badge">15,500 pts</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="p-3 border-top">
                    <nav aria-label="Leaderboard pagination">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Section Kirim Informasi -->
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
                            <button class="nav-link active" id="forum-tab" data-bs-toggle="pill"
                                data-bs-target="#forum" type="button">
                                <i class="fas fa-comments me-2"></i>Informasi Forum
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="job-tab" data-bs-toggle="pill" data-bs-target="#job"
                                type="button">
                                <i class="fas fa-briefcase me-2"></i>Lowongan Kerja
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="submitTabsContent">
                        <!-- Forum Form -->
                        <div class="tab-pane fade show active" id="forum" role="tabpanel">
                            <div class="form-section">
                                <h4 class="fw-bold mb-4" style="color: var(--primary-blue);">Kirim Informasi Forum
                                </h4>
                                <form id="forumForm">
                                    <div class="mb-4">
                                        <label class="form-label">Kategori Informasi</label>
                                        <select class="form-select" required>
                                            <option value="">Pilih kategori...</option>
                                            <option value="seminar">Seminar & Workshop</option>
                                            <option value="event">Event Alumni</option>
                                            <option value="tips">Tips & Pengalaman</option>
                                            <option value="bootcamp">Bootcamp & Pelatihan</option>
                                            <option value="other">Lainnya</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Judul Informasi</label>
                                        <input type="text" class="form-control"
                                            placeholder="Contoh: Seminar Digital Marketing 2024" required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Deskripsi Lengkap</label>
                                        <textarea class="form-control form-textarea"
                                            placeholder="Jelaskan secara detail tentang informasi yang ingin Anda bagikan..." required></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Tanggal & Waktu</label>
                                        <input type="datetime-local" class="form-control">
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Lokasi / Platform</label>
                                        <input type="text" class="form-control"
                                            placeholder="Contoh: Zoom Meeting, Gedung A UAD, dll">
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Link Pendaftaran/Informasi</label>
                                        <input type="url" class="form-control" placeholder="https://...">
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Kontak Penyelenggara</label>
                                        <input type="text" class="form-control"
                                            placeholder="Nama dan nomor/email kontak">
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="submit-btn pulse-animation">
                                            <i class="fas fa-paper-plane me-2"></i>Kirim untuk Verifikasi Admin
                                        </button>
                                    </div>
                                </form>

                                <div class="success-message" id="forumSuccessMessage">
                                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                                    <h4 class="fw-bold mb-2">Informasi Forum Berhasil Dikirim!</h4>
                                    <p class="mb-0">Tim admin akan memverifikasi dalam 1-2 hari kerja.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Job Form -->
                        <div class="tab-pane fade" id="job" role="tabpanel">
                            <div class="form-section">
                                <h4 class="fw-bold mb-4" style="color: var(--primary-blue);">Kirim Informasi Lowongan
                                    Kerja</h4>
                                <form id="jobForm">
                                    <div class="mb-4">
                                        <label class="form-label">Nama Perusahaan</label>
                                        <input type="text" class="form-control"
                                            placeholder="Contoh: PT. Teknologi Indonesia" required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Posisi yang Dibutuhkan</label>
                                        <input type="text" class="form-control"
                                            placeholder="Contoh: Software Engineer" required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Lokasi Kerja</label>
                                        <input type="text" class="form-control"
                                            placeholder="Contoh: Jakarta Selatan" required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Deskripsi Pekerjaan</label>
                                        <textarea class="form-control form-textarea" placeholder="Jelaskan tanggung jawab dan deskripsi pekerjaan..."
                                            required></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Kualifikasi & Persyaratan</label>
                                        <textarea class="form-control form-textarea" placeholder="Sebutkan kualifikasi yang dibutuhkan..." required></textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Bidang yang Dicari</label>
                                        <select class="form-select" required>
                                            <option value="">Pilih bidang...</option>
                                            <option value="it">IT & Teknologi</option>
                                            <option value="marketing">Marketing</option>
                                            <option value="finance">Keuangan</option>
                                            <option value="hrd">HRD</option>
                                            <option value="engineering">Engineering</option>
                                            <option value="other">Lainnya</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Batas Pendaftaran</label>
                                        <input type="date" class="form-control">
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Link Pendaftaran</label>
                                        <input type="url" class="form-control" placeholder="https://..."
                                            required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label">Kontak HRD/Perusahaan</label>
                                        <input type="text" class="form-control"
                                            placeholder="Email atau nomor telepon">
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="submit-btn pulse-animation">
                                            <i class="fas fa-paper-plane me-2"></i>Kirim untuk Verifikasi Admin
                                        </button>
                                    </div>
                                </form>

                                <div class="success-message" id="jobSuccessMessage">
                                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                                    <h4 class="fw-bold mb-2">Informasi Lowongan Kerja Berhasil Dikirim!</h4>
                                    <p class="mb-0">Tim admin akan memverifikasi dalam 1-2 hari kerja.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="{{ asset('css/leaderboard.css') }}">
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('js/leaderboard.js') }}"></script>
    @endpush
</x-app-layout>
