<x-app-layout>
    @section('title', 'Forum')

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="progress-section p-4 mb-4" data-aos="fade-right">
                    <div class="d-flex align-items-center mb-3">
                        <div class="user-avatar me-3">DI</div>
                        <div>
                            <h6 class="mb-0">Deny Iqbal</h6>
                            <small class="text-muted">Teknik Informatika 2018</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Progress Kuesioner</small>
                            <small class="fw-bold text-accent">94%</small>
                        </div>
                        <div class="progress">
                            <div class="progress-bar animated" role="progressbar" style="width: 94%"></div>
                        </div>
                    </div>
                    <button class="btn btn-primary-custom btn-sm w-100">
                        Lanjutkan Kuesioner
                    </button>
                </div>
                
                <div class="category-filter p-4" data-aos="fade-right" data-aos-delay="100">
                    <h6 class="fw-bold mb-3">Kategori Forum</h6>
                    <div class="d-flex flex-wrap">
                        <button class="category-btn active">Semua</button>
                        <button class="category-btn">Seminar & Bootcamp</button>
                        <button class="category-btn">Event Alumni</button>
                        <button class="category-btn">Tanya Jawab Umum</button>
                        <button class="category-btn">Karir & Pekerjaan</button>
                        <button class="category-btn">Tips & Pengalaman</button>
                        <button class="category-btn">Diskusi Akademik</button>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="post-card p-4 mb-4" data-aos="fade-up" id="post1">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <div class="user-avatar me-3">AD</div>
                            <div>
                                <h6 class="mb-0">Admin UAD</h6>
                                <small class="text-muted">Administrator · 1 jam lalu</small>
                            </div>
                        </div>
                        <div class="post-header-right">
                            <span class="badge bg-primary post-category-badge">Pengumuman</span>
                            <div class="post-options">
                                <button class="btn btn-sm btn-outline-secondary" id="postOptionsBtn1">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="post-options-menu" id="postOptionsMenu1">
                                    <button class="post-option-item" onclick="openReportModal('post1')">
                                        <i class="fas fa-flag me-2"></i> Laporkan
                                    </button>
                                    <button class="post-option-item" onclick="bookmarkPost('post1')">
                                        <i class="fas fa-bookmark me-2"></i> Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <p class="mb-3" id="postContent1">
                        📢 <strong>PENGUMUMAN PENTING</strong><br><br>
                        Pendaftaran Bootcamp Data Science UAD 2024 telah dibuka!<br><br>
                        Program ini terbuka untuk semua alumni UAD dengan latar belakang apapun. Bootcamp akan dilaksanakan secara hybrid dengan jadwal fleksibel.<br><br>
                        📅 <strong>Tanggal Penting:</strong><br>
                        - Pendaftaran: 1 - 30 Desember 2024<br>
                        - Seleksi: 2 - 5 Januari 2025<br>
                        - Kelas Dimulai: 10 Januari 2025<br><br>
                        🎯 <strong>Benefit:</strong><br>
                        - Sertifikat resmi UAD<br>
                        - Networking dengan praktisi industri<br>
                        - Peluang magang di perusahaan mitra<br><br>
                        Untuk informasi lebih lanjut dan pendaftaran, kunjungi: tracerstudy.uad.ac.id/bootcamp
                    </p>
                    
                    <div class="post-actions d-flex justify-content-between mt-3">
                        <div class="d-flex gap-3">
                            <button class="like-btn">
                                <i class="far fa-heart me-1"></i> <span>42</span>
                            </button>
                            <button class="dislike-btn">
                                <i class="far fa-thumbs-down me-1"></i> <span>3</span>
                            </button>
                            <button class="comment-btn" onclick="openCommentDialog('post1')">
                                <i class="far fa-comment me-1"></i> <span>15</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="post-card p-4 mb-4" data-aos="fade-up" data-aos-delay="100" id="post2">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <div class="user-avatar me-3">AD</div>
                            <div>
                                <h6 class="mb-0">Admin UAD</h6>
                                <small class="text-muted">Administrator · 3 jam lalu</small>
                            </div>
                        </div>
                        <div class="post-header-right">
                            <span class="badge bg-primary post-category-badge">Event Alumni</span>
                            <div class="post-options">
                                <button class="btn btn-sm btn-outline-secondary" id="postOptionsBtn2">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="post-options-menu" id="postOptionsMenu2">
                                    <button class="post-option-item" onclick="openReportModal('post2')">
                                        <i class="fas fa-flag me-2"></i> Laporkan
                                    </button>
                                    <button class="post-option-item" onclick="bookmarkPost('post2')">
                                        <i class="fas fa-bookmark me-2"></i> Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <p class="mb-3">
                        🎉 <strong>REUNI AKBAR ALUMNI UAD 2024</strong><br><br>
                        Hai para alumni UAD! Kami dengan senang hati mengundang Anda untuk menghadiri Reuni Akbar Alumni UAD 2024.<br><br>
                        Acara ini akan menjadi momen spesial untuk bertemu kembali dengan teman-teman seangkatan, berbagi cerita, dan memperluas jaringan profesional.<br><br>
                        📍 <strong>Lokasi:</strong> Gedung Auditorium Kampus Utama UAD<br>
                        📅 <strong>Tanggal:</strong> Sabtu, 15 Desember 2024<br>
                        🕒 <strong>Waktu:</strong> 18.00 - 22.00 WIB<br><br>
                        Daftar segera melalui link di bio kami! Tempat terbatas untuk 500 peserta pertama.
                    </p>
                    
                    <div class="post-images-grid">
                        <img src="gambar1.png" alt="Poster Reuni UAD 2024" onclick="openLightbox('post2', 0)">
                        <img src="gambar2.png" alt="Detail Acara Reuni" onclick="openLightbox('post2', 1)">
                    </div>
                    
                    <div class="post-actions d-flex justify-content-between">
                        <div class="d-flex gap-3">
                            <button class="like-btn">
                                <i class="far fa-heart me-1"></i> <span>67</span>
                            </button>
                            <button class="dislike-btn">
                                <i class="far fa-thumbs-down me-1"></i> <span>5</span>
                            </button>
                            <button class="comment-btn" onclick="openCommentDialog('post2')">
                                <i class="far fa-comment me-1"></i> <span>23</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3">
                <div class="info-box p-4 mb-4" data-aos="fade-left">
                    <h6 class="fw-bold mb-3">Info Penting</h6>
                    <div class="mb-3">
                        <small class="text-primary fw-bold">📢 Pengumuman</small>
                        <p class="mb-1 small">Pendaftaran Bootcamp Data Science dibuka hingga 30 Desember 2024</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-success fw-bold">🎯 Event</small>
                        <p class="mb-1 small">Reuni Akbar Alumni UAD 2024 akan diselenggarakan 15 Desember</p>
                    </div>
                    <div>
                        <small class="text-warning fw-bold">⚠️ Peringatan</small>
                        <p class="mb-0 small">Lengkapi kuesioner untuk akses fitur lengkap platform</p>
                    </div>
                </div>
                
                <div class="info-box p-4" data-aos="fade-left" data-aos-delay="100">
                    <h6 class="fw-bold mb-3">Top 3 Alumni Paling Aktif</h6><br><br>
                    
                    <div class="podium-container">
                        <div class="podium">
                            <div class="podium-place podium-2">
                                <div class="podium-stand">
                                    <div class="podium-content">
                                        <div class="podium-avatar">SR</div>
                                        <div class="podium-name">Siti Rahayu</div>
                                        <div class="podium-points">28,500</div>
                                    </div>
                                </div>
                                <div class="place-badge">2nd</div>
                            </div>
                            
                            <div class="podium-place podium-1">
                                <div class="crown">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <div class="podium-stand">
                                    <div class="podium-content">
                                        <div class="podium-avatar">AR</div>
                                        <div class="podium-name">Ahmad Rizki</div>
                                        <div class="podium-points">32,000</div>
                                    </div>
                                </div>
                                <div class="place-badge">1st</div>
                            </div>
                            
                            <div class="podium-place podium-3">
                                <div class="podium-stand">
                                    <div class="podium-content">
                                        <div class="podium-avatar">BS</div>
                                        <div class="podium-name">Budi Santoso</div>
                                        <div class="podium-points">25,800</div>
                                    </div>
                                </div>
                                <div class="place-badge">3rd</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">Total points berdasarkan kontribusi di platform</small>
                    </div>
                    
                    <button class="leaderboard-btn" onclick="navigateToLeaderboard()">
                        <i class="fas fa-crown me-2"></i> Lihat Leaderboard Lengkap
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="comment-dialog" id="commentDialog">
        <div class="comment-container">
            <div class="comment-header">
                <h6 class="mb-0">Komentar</h6>
                <button type="button" class="btn-close" onclick="closeCommentDialog()"></button>
            </div>
            <div class="comment-body" id="commentBody">
                <!-- Comments and replies will be loaded here -->
            </div>
            <div class="comment-footer">
                <div class="main-comment-input">
                    <div class="main-avatar">DI</div>
                    <textarea class="main-input-field" placeholder="Tulis komentar..." id="commentInput"></textarea>
                    <button class="main-send-btn" onclick="sendComment()">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reportCommentModal" tabindex="-1" aria-labelledby="reportCommentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportCommentModalLabel">Laporkan Komentar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Mengapa Anda ingin melaporkan komentar ini?</p>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="reportCommentReason" id="commentSpam" value="commentSpam">
                        <label class="form-check-label" for="commentSpam">
                            <strong>Spam atau iklan</strong><br>
                            <small class="text-muted">Komentar berisi promosi atau link yang tidak relevan</small>
                        </label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="reportCommentReason" id="commentHarassment" value="commentHarassment">
                        <label class="form-check-label" for="commentHarassment">
                            <strong>Komentar kasar atau pelecehan</strong><br>
                            <small class="text-muted">Mengandung kata-kata kasar, ujaran kebencian, atau ancaman</small>
                        </label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="reportCommentReason" id="commentMisinformation" value="commentMisinformation">
                        <label class="form-check-label" for="commentMisinformation">
                            <strong>Informasi salah</strong><br>
                            <small class="text-muted">Menyebarkan informasi yang tidak benar atau menyesatkan</small>
                        </label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="reportCommentReason" id="commentOffensive" value="commentOffensive">
                        <label class="form-check-label" for="commentOffensive">
                            <strong>Konten ofensif</strong><br>
                            <small class="text-muted">Mengandung unsur SARA atau konten yang tidak pantas</small>
                        </label>
                    </div>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="reportCommentReason" id="commentOther" value="commentOther">
                        <label class="form-check-label" for="commentOther">
                            <strong>Lainnya</strong><br>
                            <small class="text-muted">Alasan lain yang perlu diperhatikan admin</small>
                        </label>
                    </div>
                    
                    <div class="mt-3">
                        <label class="form-label">Keterangan tambahan (opsional):</label>
                        <textarea class="form-control" id="commentReportDetails" rows="3" placeholder="Berikan penjelasan lebih detail..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="submitCommentReport()">Laporkan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Laporkan Postingan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Mengapa Anda ingin melaporkan postingan ini?</p>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="reportReason" id="misinformation" value="misinformation">
                        <label class="form-check-label" for="misinformation">
                            <strong>Postingan mengandung misinformasi</strong><br>
                            <small class="text-muted">Informasi yang diberikan tidak akurat atau menyesatkan</small>
                        </label>
                    </div>
                    <div class="report-reason-details" id="misinformationDetails">
                        <label class="form-label small mt-2">Apa yang salah dengan informasi ini?</label>
                        <textarea class="form-control report-textarea" placeholder="Jelaskan bagian mana yang tidak akurat dan berikan informasi yang benar jika memungkinkan..."></textarea>
                    </div>
                    
                    <div class="form-check mb-2 mt-3">
                        <input class="form-check-input" type="radio" name="reportReason" id="cancelledEvent" value="cancelledEvent">
                        <label class="form-check-label" for="cancelledEvent">
                            <strong>Kegiatan/informasi sudah tidak berlaku</strong><br>
                            <small class="text-muted">Event sudah dibatalkan atau informasi sudah kedaluwarsa</small>
                        </label>
                    </div>
                    <div class="report-reason-details" id="cancelledEventDetails">
                        <label class="form-label small mt-2">Informasi tambahan:</label>
                        <textarea class="form-control report-textarea" placeholder="Berikan informasi tentang pembatalan atau perubahan yang terjadi..."></textarea>
                    </div>
                    
                    <div class="form-check mb-2 mt-3">
                        <input class="form-check-input" type="radio" name="reportReason" id="spam" value="spam">
                        <label class="form-check-label" for="spam">
                            <strong>Spam atau konten tidak relevan</strong><br>
                            <small class="text-muted">Postingan berisi konten yang tidak sesuai dengan forum</small>
                        </label>
                    </div>
                    
                    <div class="form-check mb-2 mt-3">
                        <input class="form-check-input" type="radio" name="reportReason" id="duplicate" value="duplicate">
                        <label class="form-check-label" for="duplicate">
                            <strong>Postingan duplikat</strong><br>
                            <small class="text-muted">Informasi yang sama sudah pernah diposting sebelumnya</small>
                        </label>
                    </div>
                    
                    <div class="form-check mb-2 mt-3">
                        <input class="form-check-input" type="radio" name="reportReason" id="other" value="other">
                        <label class="form-check-label" for="other">
                            <strong>Lainnya</strong><br>
                            <small class="text-muted">Alasan lain yang membantu admin menjaga kualitas informasi</small>
                        </label>
                    </div>
                    <div class="report-reason-details" id="otherDetails">
                        <label class="form-label small mt-2">Jelaskan alasan pelaporan:</label>
                        <textarea class="form-control report-textarea" placeholder="Jelaskan mengapa postingan ini perlu diperbaiki atau dihapus..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="submitReport()">Kirim Laporan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="lightbox" id="lightbox">
        <button class="lightbox-close" onclick="closeLightbox()">
            <i class="fas fa-times"></i>
        </button>
        <button class="lightbox-nav lightbox-prev" onclick="changeLightboxImage(-1)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="lightbox-nav lightbox-next" onclick="changeLightboxImage(1)">
            <i class="fas fa-chevron-right"></i>
        </button>
        <div class="lightbox-content">
            <img class="lightbox-img" id="lightbox-img" src="" alt="">
            <div class="lightbox-counter" id="lightbox-counter"></div>
        </div>
    </div>

    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="bookmarkToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-bookmark text-primary me-2"></i>
                <strong class="me-auto">Tersimpan</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Post berhasil disimpan.
            </div>
        </div>
        
        <div id="reportToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-flag text-success me-2"></i>
                <strong class="me-auto">Laporan Dikirim</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Terima kasih! Laporan Anda telah dikirim ke admin.
            </div>
        </div>
        
        <div id="commentReportToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="fas fa-flag text-danger me-2"></i>
                <strong class="me-auto">Komentar Dilaporkan</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Komentar telah dilaporkan. Tim admin akan meninjaunya.
            </div>
        </div>
    </div>
    

    @push('styles')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/forum.css') }}">
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/forum.js') }}"></script>
    @endpush
</x-app-layout>

