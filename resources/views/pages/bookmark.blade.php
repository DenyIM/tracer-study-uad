<x-app-layout>
    @section('title', 'Bookmarks')

    <div class="main-content">
        <div class="bookmark-header" data-aos="fade-down">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-5 fw-bold mb-3">Bookmark Saya</h1>
                        <p class="lead mb-0">Kelola dan akses konten yang telah Anda simpan dengan mudah</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="hero-icon">
                            <i class="fas fa-bookmark" style="font-size: 6rem; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="bookmark-tabs" data-aos="fade-up">
                <ul class="nav nav-tabs" id="bookmarkTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="forum-tab" data-bs-toggle="tab" data-bs-target="#forum" type="button" role="tab" aria-controls="forum" aria-selected="true">
                            <i class="fas fa-comments me-2"></i> Forum
                            <span class="badge bg-primary ms-2" id="forum-count">3</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="jobs-tab" data-bs-toggle="tab" data-bs-target="#jobs" type="button" role="tab" aria-controls="jobs" aria-selected="false">
                            <i class="fas fa-briefcase me-2"></i> Lowongan Kerja
                            <span class="badge bg-primary ms-2" id="jobs-count">2</span>
                        </button>
                    </li>
                </ul>
                <div class="tab-content" id="bookmarkTabsContent">
                    <div class="tab-pane fade show active" id="forum" role="tabpanel" aria-labelledby="forum-tab">
                        <div class="row" id="forum-bookmarks">
                            <div class="col-lg-6 mb-4 fade-in" id="post1">
                                <div class="bookmark-card">
                                    <div class="bookmark-header-card">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3">AD</div>
                                                <div>
                                                    <h6 class="mb-0">Admin UAD</h6>
                                                    <small class="text-muted">Administrator · 1 jam lalu</small>
                                                </div>
                                            </div>
                                            <button class="remove-bookmark-btn" onclick="removeBookmark('post1', 'forum')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="bookmark-body">
                                        <h6 class="fw-bold mb-2">Pendaftaran Bootcamp Data Science UAD 2024</h6>
                                        <p class="mb-3 small text-muted">Pendaftaran Bootcamp Data Science UAD 2024 telah dibuka! Program ini terbuka untuk semua alumni UAD dengan latar belakang apapun.</p>
                                        
                                        <div class="post-actions">
                                            <button class="like-btn">
                                                <i class="far fa-heart"></i> <span>42</span>
                                            </button>
                                            <button class="comment-btn">
                                                <i class="far fa-comment"></i> <span>15</span>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="bookmark-footer">
                                        <span class="badge bg-primary">Pengumuman</span>
                                        <small class="text-muted">Disimpan 2 hari lalu</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-6 mb-4 fade-in" id="post2">
                                <div class="bookmark-card">
                                    <div class="bookmark-header-card">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3">AD</div>
                                                <div>
                                                    <h6 class="mb-0">Admin UAD</h6>
                                                    <small class="text-muted">Administrator · 3 jam lalu</small>
                                                </div>
                                            </div>
                                            <button class="remove-bookmark-btn" onclick="removeBookmark('post2', 'forum')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="bookmark-body">
                                        <h6 class="fw-bold mb-2">REUNI AKBAR ALUMNI UAD 2024</h6>
                                        <p class="mb-3 small text-muted">Hai para alumni UAD! Kami dengan senang hati mengundang Anda untuk menghadiri Reuni Akbar Alumni UAD 2024.</p>
                                        
                                        <div class="post-actions">
                                            <button class="like-btn">
                                                <i class="far fa-heart"></i> <span>67</span>
                                            </button>
                                            <button class="comment-btn">
                                                <i class="far fa-comment"></i> <span>23</span>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="bookmark-footer">
                                        <span class="badge bg-primary">Event Alumni</span>
                                        <small class="text-muted">Disimpan 1 hari lalu</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-6 mb-4 fade-in" id="post3">
                                <div class="bookmark-card">
                                    <div class="bookmark-header-card">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3">AD</div>
                                                <div>
                                                    <h6 class="mb-0">Admin UAD</h6>
                                                    <small class="text-muted">Administrator · 5 jam lalu</small>
                                                </div>
                                            </div>
                                            <button class="remove-bookmark-btn" onclick="removeBookmark('post3', 'forum')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="bookmark-body">
                                        <h6 class="fw-bold mb-2">Workshop Kewirausahaan untuk Alumni</h6>
                                        <p class="mb-3 small text-muted">Bergabunglah dalam workshop kewirausahaan khusus alumni UAD untuk mengembangkan keterampilan bisnis Anda.</p>
                                        
                                        <div class="post-actions">
                                            <button class="like-btn">
                                                <i class="far fa-heart"></i> <span>35</span>
                                            </button>
                                            <button class="comment-btn">
                                                <i class="far fa-comment"></i> <span>12</span>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="bookmark-footer">
                                        <span class="badge bg-primary">Workshop</span>
                                        <small class="text-muted">Disimpan 3 hari lalu</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="jobs" role="tabpanel" aria-labelledby="jobs-tab">
                        <div class="row" id="jobs-bookmarks">
                            <div class="col-lg-6 mb-4 fade-in" id="job1">
                                <div class="bookmark-card">
                                    <div class="bookmark-header-card">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="d-flex align-items-center">
                                                <div class="company-logo me-3">BI</div>
                                                <div>
                                                    <h6 class="mb-0">Data Analyst</h6>
                                                    <p class="text-muted mb-0">Bank Indonesia</p>
                                                </div>
                                            </div>
                                            <button class="remove-bookmark-btn" onclick="removeBookmark('job1', 'jobs')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="bookmark-body">
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
                                    
                                    <div class="bookmark-footer">
                                        <div class="d-flex gap-3">
                                            <div class="salary-badge">💰 8–12 juta</div>
                                            <div class="deadline-badge">
                                                <i class="fas fa-hourglass-end me-1"></i> 15 Des 2025
                                            </div>
                                        </div>
                                        <small class="text-muted">Disimpan 3 hari lalu</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-6 mb-4 fade-in" id="job2">
                                <div class="bookmark-card">
                                    <div class="bookmark-header-card">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="d-flex align-items-center">
                                                <div class="company-logo me-3" style="background: linear-gradient(135deg, #28a745, #20c997);">TP</div>
                                                <div>
                                                    <h6 class="mb-0">UI/UX Designer</h6>
                                                    <p class="text-muted mb-0">Tokopedia</p>
                                                </div>
                                            </div>
                                            <button class="remove-bookmark-btn" onclick="removeBookmark('job2', 'jobs')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="bookmark-body">
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
                                    
                                    <div class="bookmark-footer">
                                        <div class="d-flex gap-3">
                                            <div class="salary-badge">💰 10–15 juta</div>
                                            <div class="deadline-badge">
                                                <i class="fas fa-hourglass-end me-1"></i> 20 Jan 2026
                                            </div>
                                        </div>
                                        <small class="text-muted">Disimpan 1 minggu lalu</small>
                                    </div>
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
    <link rel="stylesheet" href="{{ asset('css/bookmark.css') }}">
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/bookmark.js') }}"></script>
    @endpush
</x-app-layout>

