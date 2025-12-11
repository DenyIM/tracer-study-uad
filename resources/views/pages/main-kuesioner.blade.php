<x-app-layout>
     @section('title', 'Kuesioner')

    <div class="container py-5">
            <div class="row mb-5">
                <div class="col-12 text-center" data-aos="fade-up">
                    <h1 class="fw-bold mb-3" style="color: var(--primary-blue);">Kuesioner Tracer Study</h1>
                    <p class="lead mb-4">Pilih kategori status Anda saat ini untuk melanjutkan kuesioner</p>
                    
                    <div class="progress-section p-4 mx-auto" style="max-width: 600px;" data-aos="fade-up" data-aos-delay="100">
                        <div class="mb-3">
                            <h5 class="mb-1">Deny Iqbal</h5>
                            <p class="text-muted mb-3">Teknik Informatika 2018</p>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="fw-semibold">Progress Kuesioner</span>
                                <span class="fw-bold text-accent">50%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 50%"></div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <p class="text-muted mb-0">2 dari 4 kuesioner telah diselesaikan</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4 text-center" style="color: var(--primary-blue);" data-aos="fade-up">Pilih Kategori Status Anda</h3>
                    <p class="text-center mb-5" data-aos="fade-up">Silakan pilih kategori yang sesuai dengan status Anda saat ini untuk mengisi kuesioner yang relevan</p>
                    
                    <div class="row g-4">
                        <!-- Kategori 1 -->
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                            <div class="category-card p-4 h-100 text-center position-relative" data-bs-toggle="modal" data-bs-target="#categoryModal" data-category="bekerja">
                                <div class="category-icon mx-auto">
                                    <i class="fas fa-building"></i>
                                </div>
                                <h4 class="fw-bold mb-3">BEKERJA DI PERUSAHAAN/INSTANSI</h4>
                                <p class="mb-0">Alumni yang sedang bekerja di perusahaan, instansi pemerintah, atau organisasi lainnya.</p>
                                <div class="category-status">
                                    <span class="badge bg-primary"><i class="fas fa-arrow-right me-1"></i> Pilih</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Kategori 2 -->
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                            <div class="category-card p-4 h-100 text-center position-relative" data-bs-toggle="modal" data-bs-target="#categoryModal" data-category="wirausaha">
                                <div class="category-icon mx-auto">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <h4 class="fw-bold mb-3">WIRAUSAHA/PEMILIK USAHA</h4>
                                <p class="mb-0">Alumni yang memiliki usaha sendiri atau berwirausaha.</p>
                                <div class="category-status">
                                    <span class="badge bg-primary"><i class="fas fa-arrow-right me-1"></i> Pilih</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Kategori 3 -->
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                            <div class="category-card p-4 h-100 text-center position-relative" data-bs-toggle="modal" data-bs-target="#categoryModal" data-category="pendidikan">
                                <div class="category-icon mx-auto">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <h4 class="fw-bold mb-3">MELANJUTKAN PENDIDIKAN</h4>
                                <p class="mb-0">Alumni yang sedang melanjutkan pendidikan ke jenjang yang lebih tinggi.</p>
                                <div class="category-status">
                                    <span class="badge bg-primary"><i class="fas fa-arrow-right me-1"></i> Pilih</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Kategori 4 -->
                        <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="400">
                            <div class="category-card p-4 h-100 text-center position-relative" data-bs-toggle="modal" data-bs-target="#categoryModal" data-category="pencari">
                                <div class="category-icon mx-auto">
                                    <i class="fas fa-search"></i>
                                </div>
                                <h4 class="fw-bold mb-3">PENCARI KERJA</h4>
                                <p class="mb-0">Alumni yang sedang mencari pekerjaan atau belum mendapatkan pekerjaan.</p>
                                <div class="category-status">
                                    <span class="badge bg-primary"><i class="fas fa-arrow-right me-1"></i> Pilih</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Kategori 5 -->
                        <div class="col-lg-6 col-md-6" data-aos="fade-up" data-aos-delay="500">
                            <div class="category-card p-4 h-100 text-center position-relative" data-bs-toggle="modal" data-bs-target="#categoryModal" data-category="tidak-kerja">
                                <div class="category-icon mx-auto">
                                    <i class="fas fa-user-clock"></i>
                                </div>
                                <h4 class="fw-bold mb-3">TIDAK BEKERJA & TIDAK MENCARI</h4>
                                <p class="mb-0">Alumni yang tidak bekerja dan tidak sedang mencari pekerjaan.</p>
                                <div class="category-status">
                                    <span class="badge bg-primary"><i class="fas fa-arrow-right me-1"></i> Pilih</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade category-modal" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="categoryModalLabel">Daftar Kuesioner</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h4 class="fw-bold mb-4 text-center" id="modalCategoryTitle">BEKERJA DI PERUSAHAAN/INSTANSI</h4>
                            <p class="text-center mb-4">Berikut adalah daftar kuesioner yang perlu Anda isi berdasarkan kategori status Anda saat ini.</p>
                            
                            <div class="row g-4">
                                <!-- Kuesioner 1 (Umum) -->
                                <div class="col-12" data-aos="fade-up">
                                    <div class="questionnaire-card p-4 h-100 position-relative completed">
                                        <div class="questionnaire-status">
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i> Selesai</span>
                                        </div>
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="questionnaire-number me-3">1</div>
                                            <div>
                                                <h4 class="fw-bold mb-1">Kuesioner 1 (Umum)</h4>
                                                <p class="text-muted mb-0">Data Diri & Pendidikan</p>
                                            </div>
                                        </div>
                                        <p class="mb-3">Kuesioner tentang informasi pribadi dan riwayat pendidikan di UAD. Wajib diisi oleh seluruh alumni.</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-success"><i class="fas fa-check-circle me-1"></i> Telah diselesaikan</span>
                                            <button class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i> Lihat Hasil
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Kuesioner 2 -->
                                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                                    <div class="questionnaire-card p-4 h-100 position-relative" id="kuesioner2">
                                        <div class="questionnaire-status">
                                            <span class="badge bg-warning"><i class="fas fa-lock me-1"></i> Terkunci</span>
                                        </div>
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="questionnaire-number me-3">2</div>
                                            <div>
                                                <h4 class="fw-bold mb-1">Kuesioner 2</h4>
                                                <p class="text-muted mb-0" id="kuesioner2Title">Pengalaman Kerja & Karir</p>
                                            </div>
                                        </div>
                                        <p class="mb-3" id="kuesioner2Desc">Kuesioner tentang pengalaman kerja setelah lulus dari UAD.</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-warning"><i class="fas fa-clock me-1"></i> Belum diselesaikan</span>
                                            <button class="btn btn-primary-custom btn-sm btn-start-kuesioner" data-kuesioner="2">
                                                <i class="fas fa-play me-1"></i> Mulai
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Kuesioner 3 -->
                                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                                    <div class="questionnaire-card p-4 h-100 position-relative" id="kuesioner3">
                                        <div class="questionnaire-status">
                                            <span class="badge bg-warning"><i class="fas fa-lock me-1"></i> Terkunci</span>
                                        </div>
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="questionnaire-number me-3">3</div>
                                            <div>
                                                <h4 class="fw-bold mb-1">Kuesioner 3</h4>
                                                <p class="text-muted mb-0" id="kuesioner3Title">Keterampilan & Kompetensi</p>
                                            </div>
                                        </div>
                                        <p class="mb-3" id="kuesioner3Desc">Kuesioner tentang keterampilan yang diperoleh selama kuliah dan pengembangannya.</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-warning"><i class="fas fa-clock me-1"></i> Belum diselesaikan</span>
                                            <button class="btn btn-primary-custom btn-sm btn-start-kuesioner" data-kuesioner="3">
                                                <i class="fas fa-play me-1"></i> Mulai
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Kuesioner 4 -->
                                <div class="col-12" data-aos="fade-up" data-aos-delay="300">
                                    <div class="questionnaire-card p-4 h-100 position-relative" id="kuesioner4">
                                        <div class="questionnaire-status">
                                            <span class="badge bg-warning"><i class="fas fa-lock me-1"></i> Terkunci</span>
                                        </div>
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="questionnaire-number me-3">4</div>
                                            <div>
                                                <h4 class="fw-bold mb-1">Kuesioner 4</h4>
                                                <p class="text-muted mb-0" id="kuesioner4Title">Kepuasan & Saran</p>
                                            </div>
                                        </div>
                                        <p class="mb-3" id="kuesioner4Desc">Kuesioner tentang kepuasan terhadap pendidikan di UAD dan saran pengembangan.</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-warning"><i class="fas fa-clock me-1"></i> Belum diselesaikan</span>
                                            <button class="btn btn-primary-custom btn-sm btn-start-kuesioner" data-kuesioner="4">
                                                <i class="fas fa-play me-1"></i> Mulai
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <p class="text-muted"><small>Kuesioner 1 adalah kuesioner umum yang wajib diisi oleh semua alumni. Kuesioner 2-4 disesuaikan dengan kategori status Anda saat ini.</small></p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-5">
                <div class="col-12">
                    <h3 class="fw-bold mb-4 text-center" style="color: var(--primary-blue);" data-aos="fade-up">Fitur yang Akan Terbuka</h3>
                    <p class="text-center mb-5" data-aos="fade-up">Selesaikan kuesioner bagian 1 sampai 4 sesuai kategori status anda untuk membuka fitur-fitur eksklusif berikut:</p>
                    
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                            <div class="feature-card p-4 position-relative">
                                <div class="text-center">
                                    <div class="feature-icon mx-auto pulse-animation">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Bagian 1</h5>
                                    <p class="mb-0">Membuka fitur Leadearboard dan kumpulkan point untuk mendapat keuntungan eksklusif</p>
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
                                    <p class="mb-0">Dapat mengakses Informasi terkait Event, Seminar, Diskusi dan lainnya di Forum Tracer Study UAD</p>
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
                                    <p class="mb-0">Mengakses layanan Konsultasi terkait rencana karir dan bisnis dengan para Mentor via Email/WA</p>
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
                                    <p class="mb-0">Dapat mengakses informasi terkait Lowongan Kerja yang direkomendasikan oleh UAD</p>
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
                    <div class="text-center p-5 rounded" style="background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue)); color: white;" data-aos="fade-up">
                        <h3 class="fw-bold mb-3">Selesaikan Kuesioner Yang Ada</h3>
                        <p class="lead mb-4">Untuk Membuka Fitur-Fitur Keren Lainnya</p>
                        <button class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#categoryModal">
                            <i class="fas fa-rocket me-2"></i> Lanjutkan Kuesioner Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>

    @push('styles')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/main-kuesioner.css') }}">
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/main-kuesioner.js') }}"></script>
    @endpush
</x-app-layout>