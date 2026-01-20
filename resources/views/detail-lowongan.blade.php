<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Software Engineer - PT Maju Jaya Teknologi | Tracer Study UAD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #003366;
            --secondary-blue: #3b82f6;
            --light-blue: #dbeafe;
            --accent-yellow: #fab300;
            --light-yellow: #fef3c7;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .bg-primary-custom {
            background-color: var(--primary-blue) !important;
        }

        .bg-light-blue {
            background-color: var(--light-blue) !important;
        }

        .text-accent {
            color: var(--accent-yellow) !important;
        }

        .navbar-brand {
            font-weight: 700;
        }

        .nav-link {
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--accent-yellow) !important;
        }

        .nav-link.active {
            color: var(--accent-yellow) !important;
            font-weight: 600;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .job-header-section {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            padding: 40px 0;
            position: relative;
            overflow: hidden;
        }

        .job-header-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="%23ffffff" opacity="0.1"><polygon points="0,0 1000,50 1000,100 0,100"/></svg>');
            background-size: cover;
        }

        .company-logo-large {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-weight: bold;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .main-content {
            margin-top: -30px;
            position: relative;
            z-index: 10;
        }

        .job-detail-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .card-header-custom {
            background: var(--light-blue);
            padding: 20px 25px;
            border-bottom: 2px solid var(--primary-blue);
        }

        .card-body-custom {
            padding: 25px;
        }

        .quick-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }

        .info-item {
            background: var(--light-blue);
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid var(--accent-yellow);
        }

        .info-label {
            font-size: 0.8rem;
            color: var(--primary-blue);
            font-weight: 600;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--primary-blue);
        }

        .salary-card {
            background: linear-gradient(135deg, var(--light-yellow), #fff9e6);
            border: 2px solid var(--accent-yellow);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .salary-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .salary-amount {
            font-size: 1.8rem;
            font-weight: 700;
            color: #856404;
            margin-bottom: 5px;
        }

        .salary-period {
            color: #856404;
            font-size: 0.9rem;
        }

        .requirements-list {
            list-style: none;
            padding: 0;
        }

        .requirements-list li {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            position: relative;
            padding-left: 25px;
        }

        .requirements-list li:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: var(--accent-yellow);
            font-weight: bold;
        }

        .action-buttons {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 20px;
            border-top: 2px solid var(--light-blue);
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-apply {
            background: linear-gradient(135deg, var(--accent-yellow), #ffc107);
            border: none;
            color: #000;
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-apply:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(251, 176, 0, 0.4);
        }

        .bookmark-btn-large {
            background: none;
            border: 2px solid var(--primary-blue);
            color: var(--primary-blue);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .bookmark-btn-large.active {
            background: var(--accent-yellow);
            border-color: var(--accent-yellow);
            color: white;
        }

        .bookmark-btn-large:hover {
            transform: scale(1.1);
        }

        .alumni-benefits {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin: 25px 0;
        }

        .benefit-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .benefit-icon {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.1rem;
        }

        .related-job-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .related-job-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.12);
        }

        @media (max-width: 768px) {
            .quick-info-grid {
                grid-template-columns: 1fr;
            }

            .company-logo-large {
                width: 60px;
                height: 60px;
                font-size: 1.2rem;
            }

            .salary-amount {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <header class="sticky-top bg-white shadow-sm">
        <nav class="navbar navbar-expand-lg navbar-light bg-white">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="{{ asset('logo-tracer-study.png') }}" style="width: 150px; height: auto;"
                        class="img-fluid rounded">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="nav-kuesioner"><i class="fas fa-clipboard-list me-1"></i>
                                Kuesioner</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="nav-leaderboard"><i class="fas fa-crown me-1"></i> Leaderboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="nav-forum"><i class="fas fa-comments me-1"></i> Forum</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="nav-mentor"><i class="fas fa-chalkboard-teacher me-1"></i>
                                Mentorship</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="nav-lowongan"><i class="fas fa-briefcase me-1"></i> Lowongan
                                Kerja</a>
                    </ul>

                    <div class="d-flex align-items-center">
                        <button class="btn btn-warning btn-sm me-2" style="display: none;">
                            <i class="fas fa-plus-circle me-1"></i> Posting
                        </button>

                        <div class="dropdown me-3">
                            <button class="btn btn-outline-secondary position-relative" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <span class="notification-badge">4</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                                <div class="notification-header">
                                    <span>Notifikasi</span>
                                    <div class="notification-count">4</div>
                                </div>

                                <div class="notification-list">
                                    <a class="dropdown-item notification-item unread" href="#">
                                        <div class="notification-icon">M</div>
                                        <div class="notification-content">
                                            <div class="notification-title">Mentorship</div>
                                            <div class="notification-text">Kualifikasi Terbaru Lowongan Kerja</div>
                                            <div class="notification-time">5m ago</div>
                                        </div>
                                    </a>

                                    <a class="dropdown-item notification-item unread" href="#">
                                        <div class="notification-icon">M</div>
                                        <div class="notification-content">
                                            <div class="notification-title">Mentorship</div>
                                            <div class="notification-text">Lowongan baru untuk Informatika di Google
                                                Indonesia dengan persyaratan khusus untuk lulusan UAD</div>
                                            <div class="notification-time">1h ago</div>
                                        </div>
                                    </a>

                                    <a class="dropdown-item notification-item unread" href="#">
                                        <div class="notification-icon">M</div>
                                        <div class="notification-content">
                                            <div class="notification-title">Mentorship</div>
                                            <div class="notification-text">Deny Iqbal memposting event alumni baru untuk
                                                angkatan 2018-2020 di Yogyakarta</div>
                                            <div class="notification-time">2h ago</div>
                                        </div>
                                    </a>

                                    <a class="dropdown-item notification-item" href="#">
                                        <div class="notification-icon">M</div>
                                        <div class="notification-content">
                                            <div class="notification-title">Mentorship</div>
                                            <div class="notification-text">Membalas komentar Anda pada diskusi tentang
                                                peluang karir di bidang teknologi</div>
                                            <div class="notification-time">3h ago</div>
                                        </div>
                                    </a>
                                </div>

                                <div class="notification-divider">2015</div>

                                <div class="notification-footer">Lihat Semua Notifikasi</div>
                            </div>
                        </div>

                        <div class="dropdown">
                            <button class="btn btn-outline-primary d-flex align-items-center" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar me-2">DI</div>
                                <span>Deny Iqbal</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end profile-dropdown">
                                <li>
                                    <h6 class="dropdown-header">Profil Alumni</h6>
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profil
                                        Saya</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>
                                        Pengaturan</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-bookmark me-2"></i>
                                        Bookmark</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i>
                                        Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <section class="job-header-section" data-aos="fade-down">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        <div class="company-logo-large me-4">MJ</div>
                        <div>
                            <h1 class="display-6 fw-bold mb-2">Software Engineer</h1>
                            <h4 class="fw-normal mb-3">PT Maju Jaya Teknologi</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                </div>
            </div>
        </div>
    </section><br>

    <div class="container main-container">
        <div class="row">
            <div class="col-lg-8">
                <div class="quick-info-grid" data-aos="fade-up">
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-map-marker-alt me-2"></i>Lokasi</div>
                        <div class="info-value">Jakarta Selatan (Hybrid)</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-briefcase me-2"></i>Tipe Pekerjaan</div>
                        <div class="info-value">Full Time</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-clock me-2"></i>Pengalaman</div>
                        <div class="info-value">2-3 Tahun</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-graduation-cap me-2"></i>Pendidikan</div>
                        <div class="info-value">S1 Informatika</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-hourglass-end me-2"></i>Deadline</div>
                        <div class="info-value">30 November 2025</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><i class="fas fa-money-bill-wave me-2"></i>Gaji</div>
                        <div class="info-value">Rp 6-10 Juta</div>
                    </div>
                </div>

                <div class="job-detail-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-header-custom">
                        <h4 class="mb-0"><i class="fas fa-file-alt me-2"></i>Deskripsi Pekerjaan</h4>
                    </div>
                    <div class="card-body-custom">
                        <p class="lead">Kami mencari Software Engineer yang passionate tentang teknologi untuk
                            bergabung dengan tim pengembangan produk inovatif kami.</p>

                        <h5 class="mt-4 mb-3">Tanggung Jawab Utama:</h5>
                        <ul class="requirements-list">
                            <li>Mengembangkan dan memelihara aplikasi web menggunakan Laravel dan MySQL</li>
                            <li>Mendesain dan mengimplementasikan RESTful API untuk integrasi sistem</li>
                            <li>Berpartisipasi dalam code review dan memberikan feedback konstruktif</li>
                            <li>Berkolaborasi dengan tim product dan design untuk memahami kebutuhan pengguna</li>
                            <li>Mengoptimalkan performa aplikasi dan memastikan scalability</li>
                            <li>Menerapkan best practices dalam software development dan security</li>
                        </ul>

                        <h5 class="mt-4 mb-3">Teknologi yang Digunakan:</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-primary">Laravel</span>
                            <span class="badge bg-primary">MySQL</span>
                            <span class="badge bg-primary">JavaScript</span>
                            <span class="badge bg-primary">Vue.js</span>
                            <span class="badge bg-primary">RESTful API</span>
                            <span class="badge bg-primary">Git</span>
                            <span class="badge bg-primary">Docker</span>
                            <span class="badge bg-primary">AWS</span>
                        </div>
                    </div>
                </div>

                <div class="job-detail-card" data-aos="fade-up" data-aos-delay="150">
                    <div class="card-header-custom">
                        <h4 class="mb-0"><i class="fas fa-list-check me-2"></i>Kualifikasi & Persyaratan</h4>
                    </div>
                    <div class="card-body-custom">
                        <h5 class="mb-3">Kualifikasi Wajib:</h5>
                        <ul class="requirements-list">
                            <li>Lulusan S1 Informatika, Ilmu Komputer, atau bidang terkait</li>
                            <li>Pengalaman 2-3 tahun dalam pengembangan aplikasi web dengan Laravel</li>
                            <li>Memahami konsep OOP, MVC, dan design patterns</li>
                            <li>Pengalaman dengan database MySQL dan query optimization</li>
                            <li>Familiar dengan version control (Git)</li>
                            <li>Memahami fundamental JavaScript dan framework modern (Vue.js/React)</li>
                        </ul>

                        <h5 class="mt-4 mb-3">Kualifikasi yang Diutamakan:</h5>
                        <ul class="requirements-list">
                            <li>Pengalaman dengan cloud services (AWS, Google Cloud)</li>
                            <li>Memahami containerization (Docker)</li>
                            <li>Pengalaman dengan testing (PHPUnit, Jest)</li>
                            <li>Memahami agile development methodology</li>
                            <li>Portfolio atau project GitHub yang dapat ditunjukkan</li>
                        </ul>

                        <h5 class="mt-4 mb-3">Soft Skills:</h5>
                        <ul class="requirements-list">
                            <li>Kemampuan komunikasi yang baik dalam tim</li>
                            <li>Problem-solving dan analytical thinking</li>
                            <li>Kemampuan belajar cepat dan adaptif dengan teknologi baru</li>
                            <li>Detail-oriented dan commitment terhadap kualitas kode</li>
                        </ul>
                    </div>
                </div>

                <div class="job-detail-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-header-custom">
                        <h4 class="mb-0"><i class="fas fa-building me-2"></i>Tentang Perusahaan</h4>
                    </div>
                    <div class="card-body-custom">
                        <div class="row">
                            <div class="col-md-8">
                                <h5>PT Maju Jaya Teknologi</h5>
                                <p class="mb-3">Perusahaan teknologi yang berfokus pada pengembangan solusi digital
                                    untuk UMKM Indonesia. Didirikan pada tahun 2018, kami telah membantu lebih dari 500
                                    UMKM dalam transformasi digital.</p>

                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <strong><i class="fas fa-users me-2"></i>Ukuran Perusahaan:</strong>
                                        <p>50-100 Karyawan</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <strong><i class="fas fa-industry me-2"></i>Industri:</strong>
                                        <p>Teknologi & Software Development</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <strong><i class="fas fa-globe me-2"></i>Website:</strong>
                                        <p><a href="https://majujaya-tech.com" target="_blank">majujaya-tech.com</a>
                                        </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <strong><i class="fas fa-map-pin me-2"></i>Lokasi Kantor:</strong>
                                        <p>Jl. Sudirman Kav. 52-53, Jakarta Selatan</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="company-logo-large mx-auto mb-3"
                                        style="background: var(--primary-blue); color: white;">MJ</div>
                                    <a href="https://majujaya-tech.com" target="_blank"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-external-link-alt me-2"></i>Kunjungi Website
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="job-detail-card" data-aos="fade-left">
                    <div class="card-header-custom">
                        <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Cara Melamar</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="mb-4">
                            <h6>Langkah-langkah Pendaftaran:</h6>
                            <ol class="ps-3">
                                <li class="mb-2">Klik tombol "Lamar Sekarang" di bawah</li>
                                <li class="mb-2">Isi formulir aplikasi online</li>
                                <li class="mb-2">Upload CV dan portfolio terbaru</li>
                                <li class="mb-2">Lampirkan transkrip nilai</li>
                                <li>Tunggu konfirmasi via email dalam 3-5 hari kerja</li>
                            </ol>
                        </div>

                        <div class="mb-3">
                            <h6>Dokumen yang Diperlukan:</h6>
                            <ul class="ps-3">
                                <li>CV terbaru</li>
                                <li>Portfolio project (jika ada)</li>
                                <li>Transkrip nilai</li>
                                <li>Sertifikat relevan (opsional)</li>
                            </ul>
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Tips:</strong> Sertakan link ke project dalam portfolio untuk meningkatkan
                                peluang Anda.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="job-detail-card" data-aos="fade-left" data-aos-delay="100">
                    <div class="card-header-custom">
                        <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Lowongan Serupa</h5>
                    </div>
                    <div class="card-body-custom">
                        <div class="related-job-card">
                            <h6 class="fw-bold">Backend Developer</h6>
                            <p class="small text-muted mb-2">PT Tech Solution Indonesia</p>
                            <div class="d-flex justify-content-between">
                                <span class="badge bg-light text-dark">💰 7-9 jt</span>
                                <span class="badge bg-light text-dark">📍 Jakarta</span>
                            </div>
                        </div>

                        <div class="related-job-card">
                            <h6 class="fw-bold">Full Stack Developer</h6>
                            <p class="small text-muted mb-2">Startup Digital Nusantara</p>
                            <div class="d-flex justify-content-between">
                                <span class="badge bg-light text-dark">💰 8-11 jt</span>
                                <span class="badge bg-light text-dark">📍 Remote</span>
                            </div>
                        </div>

                        <div class="related-job-card">
                            <h6 class="fw-bold">PHP Developer</h6>
                            <p class="small text-muted mb-2">CV Kreasi Teknologi</p>
                            <div class="d-flex justify-content-between">
                                <span class="badge bg-light text-dark">💰 5-8 jt</span>
                                <span class="badge bg-light text-dark">📍 Yogyakarta</span>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                Lihat Semua Lowongan Serupa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="action-buttons" data-aos="fade-up">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <button class="bookmark-btn-large me-3 active">
                            <i class="fas fa-bookmark"></i>
                        </button>
                        <div>
                            <h6 class="mb-1">Simpan Lowongan</h6>
                            <small class="text-muted">45 alumni menyimpan lowongan ini</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="https://majujaya-tech.com/careers/apply/software-engineer" target="_blank"
                        class="btn-apply me-3">
                        <i class="fas fa-paper-plane me-2"></i>Lamar Sekarang
                    </a>
                    <a href="/back-to-list" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 600,
            once: true,
            offset: 100
        });

        // Bookmark functionality
        document.querySelector('.bookmark-btn-large').addEventListener('click', function() {
            const icon = this.querySelector('i');
            if (this.classList.contains('active')) {
                this.classList.remove('active');
                // In real implementation, remove from saved jobs
            } else {
                this.classList.add('active');
                // In real implementation, add to saved jobs
            }
        });

        // Simulate related job clicks
        document.querySelectorAll('.related-job-card').forEach(card => {
            card.addEventListener('click', function() {
                const jobTitle = this.querySelector('h6').textContent;
                alert(`Membuka detail lowongan: ${jobTitle}`);
            });
        });
    </script>
</body>

</html>
