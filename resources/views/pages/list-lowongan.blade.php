<x-app-layout>
    @section('title', 'Lowongan Pekerjaan')

    <section class="hero-section" data-aos="fade-down">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">Temukan Karir Impian Anda</h1>
                    <p class="lead mb-4">Jelajahi ribuan lowongan kerja eksklusif untuk alumni UAD. Temukan pekerjaan yang sesuai dengan passion dan keahlian Anda.</p>
                </div>
                <div class="col-lg-4 text-center">
                    <div class="hero-icon">
                        <i class="fas fa-search" style="font-size: 6rem; opacity: 0.8;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container main-container">
        <div class="search-hero" data-aos="fade-up" data-aos-delay="100">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="filter-label">🔍 Kata Kunci</label>
                        <input type="text" class="form-control" placeholder="Posisi, perusahaan, atau skill...">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="filter-label">💼 Posisi/Industri</label>
                        <select class="form-select">
                            <option value="">Semua Posisi</option>
                            <option value="it">Teknologi Informasi</option>
                            <option value="finance">Keuangan & Perbankan</option>
                            <option value="marketing">Pemasaran</option>
                            <option value="hrd">HR & Development</option>
                            <option value="engineering">Engineering</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-group">
                        <label class="filter-label">📍 Lokasi</label>
                        <select class="form-select">
                            <option value="">Semua Lokasi</option>
                            <option value="jakarta">Jakarta</option>
                            <option value="bandung">Bandung</option>
                            <option value="yogyakarta">Yogyakarta</option>
                            <option value="surabaya">Surabaya</option>
                            <option value="remote">Remote</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row mt-3">
                <div class="col-12">
                    <button class="btn btn-link p-0 text-decoration-none" id="toggleAdvancedFilter">
                        <i class="fas fa-sliders-h me-2"></i> Pencarian Spesifik
                    </button>
                </div>
            </div>
            
            <div class="advanced-filter" id="advancedFilter">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="filter-group">
                            <label class="filter-label">🎓 Pendidikan Terakhir</label>
                            <select class="form-select">
                                <option value="">Semua Pendidikan</option>
                                <option value="d3">D3</option>
                                <option value="s1">S1</option>
                                <option value="s2">S2</option>
                                <option value="s3">S3</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="filter-group">
                            <label class="filter-label">📚 Jurusan UAD</label>
                            <select class="form-select">
                                <option value="">Semua Jurusan</option>
                                <option value="informatika">Teknik Informatika</option>
                                <option value="sipil">Teknik Sipil</option>
                                <option value="elektro">Teknik Elektro</option>
                                <option value="manajemen">Manajemen</option>
                                <option value="akuntansi">Akuntansi</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="filter-group">
                            <label class="filter-label">💰 Range Gaji</label>
                            <select class="form-select">
                                <option value="">Semua Range</option>
                                <option value="0-3">Rp 0 - 3 juta</option>
                                <option value="3-6">Rp 3 - 6 juta</option>
                                <option value="6-10">Rp 6 - 10 juta</option>
                                <option value="10-15">Rp 10 - 15 juta</option>
                                <option value="15+">> Rp 15 juta</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="filter-group">
                            <label class="filter-label">🛠️ Skill</label>
                            <select class="form-select">
                                <option value="">Semua Skill</option>
                                <option value="programming">Programming</option>
                                <option value="design">Design</option>
                                <option value="analytics">Data Analytics</option>
                                <option value="management">Management</option>
                                <option value="marketing">Digital Marketing</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="filter-group">
                            <label class="filter-label">⏳ Pengalaman</label>
                            <select class="form-select">
                                <option value="">Semua Level</option>
                                <option value="freshgraduate">Fresh Graduate</option>
                                <option value="1-3">1-3 Tahun</option>
                                <option value="3-5">3-5 Tahun</option>
                                <option value="5+">> 5 Tahun</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="filter-group">
                            <label class="filter-label">🕒 Tipe Pekerjaan</label>
                            <select class="form-select">
                                <option value="">Semua Tipe</option>
                                <option value="fulltime">Full Time</option>
                                <option value="parttime">Part Time</option>
                                <option value="internship">Internship</option>
                                <option value="kontrak">Kontrak</option>
                                <option value="freelance">Freelance</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <button class="btn btn-primary-custom btn-lg px-5">
                        <i class="fas fa-search me-2"></i> Cari Lowongan
                    </button>
                </div>
            </div>
        </div>

        <div class="sort-options" data-aos="fade-up" data-aos-delay="150">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold me-3">Urutkan berdasarkan:</span>
                    <button class="sort-btn active" data-sort="popular">Terpopuler</button>
                    <button class="sort-btn" data-sort="newest">Terbaru</button>
                    <button class="sort-btn" data-sort="oldest">Terlama</button>
                </div>
                <div class="text-muted">
                    <span id="jobCount">1245</span> lowongan ditemukan
                </div>
            </div>
        </div>

        <div class="job-grid">
            <div class="job-card" data-aos="fade-up" data-aos-delay="200" onclick="viewJobDetail(1)">
                <div class="job-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <div class="company-logo me-3">MJ</div>
                            <div>
                                <h5 class="fw-bold mb-1">Software Engineer</h5>
                                <p class="text-muted mb-0">PT Maju Jaya Teknologi</p>
                            </div>
                        </div>
                        <button class="bookmark-btn" onclick="event.stopPropagation(); toggleBookmark(this)">
                            <i class="far fa-bookmark"></i>
                        </button>
                    </div>
                </div>
                
                <div class="job-body" href="/detlow">
                    <div class="job-meta">
                        <div class="meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jakarta Selatan – Hybrid</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-briefcase"></i>
                            <span>Full Time</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>2-3 tahun</span>
                        </div>
                    </div>
                    
                    <p class="mb-3 small text-muted">Kami mencari Software Engineer yang berpengalaman dalam pengembangan aplikasi web menggunakan Laravel dan MySQL.</p>
                    
                    <div class="skills-tags">
                        <span class="skill-tag">Laravel</span>
                        <span class="skill-tag">MySQL</span>
                        <span class="skill-tag">API</span>
                        <span class="skill-tag">JavaScript</span>
                    </div>
                </div>
                
                <div class="job-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-3">
                            <div class="salary-badge">💰 6–10 juta</div>
                            <div class="deadline-badge">
                                <i class="fas fa-hourglass-end me-1"></i> 30 Nov 2025
                            </div>
                        </div>
                        <span class="text-muted small">🔍 245 dilihat</span>
                    </div>
                </div>
            </div>

            <div class="job-card" data-aos="fade-up" data-aos-delay="250" onclick="viewJobDetail(2)">
                <div class="job-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <div class="company-logo me-3" style="background: linear-gradient(135deg, #28a745, #20c997);">BI</div>
                            <div>
                                <h5 class="fw-bold mb-1">Data Analyst</h5>
                                <p class="text-muted mb-0">Bank Indonesia</p>
                            </div>
                        </div>
                        <button class="bookmark-btn active" onclick="event.stopPropagation(); toggleBookmark(this)">
                            <i class="fas fa-bookmark"></i>
                        </button>
                    </div>
                </div>
                
                <div class="job-body">
                    <div class="job-meta">
                        <div class="meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jakarta Pusat – On-site</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-briefcase"></i>
                            <span>Full Time</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>1-2 tahun</span>
                        </div>
                    </div>
                    
                    <p class="mb-3 small text-muted">Analisis data keuangan dan ekonomi untuk mendukung pengambilan keputusan strategis di bank sentral.</p>
                    
                    <div class="skills-tags">
                        <span class="skill-tag">Python</span>
                        <span class="skill-tag">SQL</span>
                        <span class="skill-tag">Tableau</span>
                        <span class="skill-tag">Statistics</span>
                    </div>
                </div>
                
                <div class="job-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-3">
                            <div class="salary-badge">💰 8–12 juta</div>
                            <div class="deadline-badge">
                                <i class="fas fa-hourglass-end me-1"></i> 15 Des 2025
                            </div>
                        </div>
                        <span class="text-muted small">🔍 189 dilihat</span>
                    </div>
                </div>
            </div>

            <div class="job-card" data-aos="fade-up" data-aos-delay="300" onclick="viewJobDetail(3)">
                <div class="job-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <div class="company-logo me-3" style="background: linear-gradient(135deg, #dc3545, #e83e8c);">TP</div>
                            <div>
                                <h5 class="fw-bold mb-1">UI/UX Designer</h5>
                                <p class="text-muted mb-0">Tokopedia</p>
                            </div>
                        </div>
                        <button class="bookmark-btn" onclick="event.stopPropagation(); toggleBookmark(this)">
                            <i class="far fa-bookmark"></i>
                        </button>
                    </div>
                </div>
                
                <div class="job-body">
                    <div class="job-meta">
                        <div class="meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jakarta – Remote</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-briefcase"></i>
                            <span>Full Time</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>3-5 tahun</span>
                        </div>
                    </div>
                    
                    <p class="mb-3 small text-muted">Mendesain pengalaman pengguna yang luar biasa untuk produk e-commerce terkemuka di Indonesia.</p>
                    
                    <div class="skills-tags">
                        <span class="skill-tag">Figma</span>
                        <span class="skill-tag">Adobe XD</span>
                        <span class="skill-tag">User Research</span>
                        <span class="skill-tag">Prototyping</span>
                    </div>
                </div>
                
                <div class="job-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-3">
                            <div class="salary-badge">💰 10–15 juta</div>
                            <div class="deadline-badge">
                                <i class="fas fa-hourglass-end me-1"></i> 20 Jan 2026
                            </div>
                        </div>
                        <span class="text-muted small">🔍 312 dilihat</span>
                    </div>
                </div>
            </div>

            <div class="job-card" data-aos="fade-up" data-aos-delay="350" onclick="viewJobDetail(4)">
                <div class="job-header">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex align-items-center">
                            <div class="company-logo me-3" style="background: linear-gradient(135deg, #6f42c1, #d63384);">GP</div>
                            <div>
                                <h5 class="fw-bold mb-1">Product Manager</h5>
                                <p class="text-muted mb-0">GoPay</p>
                            </div>
                        </div>
                        <button class="bookmark-btn" onclick="event.stopPropagation(); toggleBookmark(this)">
                            <i class="far fa-bookmark"></i>
                        </button>
                    </div>
                </div>
                
                <div class="job-body">
                    <div class="job-meta">
                        <div class="meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Jakarta – Hybrid</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-briefcase"></i>
                            <span>Full Time</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>4-6 tahun</span>
                        </div>
                    </div>
                    
                    <p class="mb-3 small text-muted">Memimpin pengembangan produk fintech inovatif untuk jutaan pengguna di Indonesia.</p>
                    
                    <div class="skills-tags">
                        <span class="skill-tag">Product Strategy</span>
                        <span class="skill-tag">Agile</span>
                        <span class="skill-tag">Data Analysis</span>
                        <span class="skill-tag">Stakeholder Mgmt</span>
                    </div>
                </div>
                
                <div class="job-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-3">
                            <div class="salary-badge">💰 15–25 juta</div>
                            <div class="deadline-badge">
                                <i class="fas fa-hourglass-end me-1"></i> 10 Des 2025
                            </div>
                        </div>
                        <span class="text-muted small">🔍 421 dilihat</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-5" data-aos="fade-up">
            <button class="btn btn-outline-primary btn-lg">
                <i class="fas fa-redo me-2"></i> Muat Lebih Banyak Lowongan
            </button>
        </div>
    </div>
    

    @push('styles')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/list-lowongan.css') }}">
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/list-lowongan.js') }}"></script>
    @endpush
</x-app-layout>



