<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Tracer Study - Universitas Ahmad Dahlan</title>
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

        .bg-primary-custom {
            background-color: var(--primary-blue) !important;
        }

        .bg-light-blue {
            background-color: var(--light-blue) !important;
        }

        .bg-light-yellow {
            background-color: var(--light-yellow) !important;
        }

        .text-accent {
            color: var(--accent-yellow) !important;
        }

        .btn-primary-custom {
            color: var(--light-blue);
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .btn-primary-custom:hover {
            background-color: var(--secondary-blue);
            border-color: var(--secondary-blue);
        }

        .btn-outline-primary-custom {
            color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .btn-outline-primary-custom:hover {
            background-color: var(--primary-blue);
            color: white;
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: white;
            font-size: 1.8rem;
        }

        .step-icon {
            width: 50px;
            height: 50px;
            background-color: var(--accent-yellow);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: white;
            font-weight: bold;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-blue);
        }

        .stat-number-custom {
            color: #fab300 !important;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            padding: 100px 0;
        }

        .hero-section-custom {
            color: rgb(0, 0, 0);
            padding: 10px 0;
        }

        .testimonial-card {
            border-left: 4px solid var(--accent-yellow);
        }

        .footer {
            background-color: #003366;
            color: white;
        }

        .logo-placeholder {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-yellow));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 10px;
        }

        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .pulse-animation {
            animation: pulse 2s infinite;
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

        .btn-warning {
            background-color: var(--accent-yellow);
            border-color: var(--accent-yellow);
        }

        .btn-warning:hover {
            background-color: #e5a000;
            border-color: #e5a000;
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        html {
            scroll-behavior: smooth;
        }

        section {
            scroll-margin-top: 80px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>

<body>
    @include('layouts.header')

    <section class="hero-section" id="beranda">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4" data-aos="fade-right" data-aos-duration="800">Sistem Informasi
                        Tracer Study Universitas Ahmad Dahlan</h1>
                    <p class="lead mb-4" data-aos="fade-right" data-aos-duration="800" data-aos-delay="200">Menjembatani
                        alumni dengan dunia kerja dan mengumpulkan data untuk pengembangan kualitas pendidikan</p>
                    <div class="d-flex flex-wrap gap-3" data-aos="fade-up" data-aos-duration="800" data-aos-delay="400">
                        <a href="{{ route('register') }}" class="btn btn-warning btn-lg fw-bold pulse-animation">Daftar
                            Sekarang</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Login Alumni</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="p-4 bg-light-blue rounded-3 mt-4 mt-lg-0 hover-lift" data-aos="fade-left"
                        data-aos-duration="800" data-aos-delay="300">
                        {{-- <i class="fas fa-graduation-cap display-1 text-primary-custom mb-3"></i> --}}
                        <img src="{{ asset('logo-tracer-study.png') }}" style="width: auto; height: auto;"
                            class="img-fluid rounded">
                        <h4 class="hero-section-custom">Platform Koneksi Alumni</h4>
                        <p class="text-muted">Bergabung dengan ribuan alumni UAD lainnya</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5" id="tentang">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5" data-aos="fade-up">
                    <h2 class="fw-bold mb-3">Apa Itu Tracer Study?</h2>
                    <p class="lead">Tracer Study adalah studi pelacakan jejak alumni untuk mengumpulkan data tentang
                        transisi dari dunia pendidikan ke dunia kerja serta pengembangan karir alumni.</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-bullseye feature-icon"></i>
                            <h5 class="card-title">Tujuan</h5>
                            <p class="card-text">Meningkatkan kualitas pendidikan dengan umpan balik dari alumni tentang
                                relevansi kurikulum dengan dunia kerja.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-chart-line feature-icon"></i>
                            <h5 class="card-title">Manfaat</h5>
                            <p class="card-text">Data yang terkumpul membantu universitas dalam pengambilan keputusan
                                strategis untuk peningkatan kualitas.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-handshake feature-icon"></i>
                            <h5 class="card-title">Partisipasi</h5>
                            <p class="card-text">Kontribusi Anda sangat berharga untuk kemajuan almamater dan membantu
                                adik-adik tingkat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light-blue">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5" data-aos="fade-up">
                    <h2 class="fw-bold mb-3">Fitur Utama Kami</h2>
                    <p class="lead">Akses penuh terbuka setelah login dan mengisi kuesioner</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="500">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="step-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h5 class="card-title">Tracer Study</h5>
                            <p class="card-text">Pengisian kuesioner tracer study dengan tampilan yang ramah dan
                                interaktif.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="step-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h5 class="card-title">Forum Alumni</h5>
                            <p class="card-text">Diskusi dan berbagi pengalaman dengan sesama alumni Universitas Ahmad
                                Dahlan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="step-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h5 class="card-title">Lowongan Kerja</h5>
                            <p class="card-text">Rekomendasi pekerjaan sesuai dengan bidang studi dan minat karir Anda.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="300">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="step-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h5 class="card-title">Event & Seminar</h5>
                            <p class="card-text">Informasi acara kampus dan pengembangan karir untuk alumni.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="400">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="step-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h5 class="card-title">Mentorship</h5>
                            <p class="card-text">Konsultasi dengan para mentor untuk pengembangan karir dan bisnis.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="zoom-in" data-aos-delay="600">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="step-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h5 class="card-title">Leaderboard & Sistem Poin</h5>
                            <p class="card-text">Dapatkan pengakuan atas kontribusi dan partisipasi aktif Anda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5" data-aos="fade-up">
                    <h2 class="fw-bold mb-3">Alur Penggunaan Platform</h2>
                    <p class="lead">Fitur akan terbuka secara bertahap setelah menyelesaikan kuesioner</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0 bg-light-yellow h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="step-number bg-primary-custom text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 40px; height: 40px;">1</div>
                            <h5 class="card-title">Kuesioner Umum & Kuesioner 1</h5>
                            <p class="card-text">Isi kuesioner umum dan kuesioner 1 untuk membuka fitur Leaderboard.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0 bg-light-yellow h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="step-number bg-primary-custom text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 40px; height: 40px;">2</div>
                            <h5 class="card-title">Kuesioner 2</h5>
                            <p class="card-text">Isi kuesioner bagian 2 untuk mengakses fitur forum.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="card border-0 bg-light-yellow h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="step-number bg-primary-custom text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 40px; height: 40px;">3</div>
                            <h5 class="card-title">Kuesioner 3</h5>
                            <p class="card-text">Isi kuesioner bagian 3 untuk mengakses fitur mentorship untuk alumni.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="card border-0 bg-light-yellow h-100 hover-lift">
                        <div class="card-body text-center p-4">
                            <div class="step-number bg-primary-custom text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 40px; height: 40px;">4</div>
                            <h5 class="card-title">Kuesioner 4</h5>
                            <p class="card-text">Isi kuesioner bagian 4 untuk mengakses penuh daftar rekomendasi
                                lowongan pekerjaan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- <section class="py-5 bg-primary-custom text-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-number stat-number-custom" data-count="5000">0</div>
                    <p class="mb-0">Alumni Terdaftar</p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-number stat-number-custom" data-count="85">0</div>
                    <p class="mb-0">Alumni Bekerja</p>
                </div>
                <div class="col-md-3 mb-4 mb-md-0" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-number stat-number-custom" data-count="200">0</div>
                    <p class="mb-0">Lowongan Tersedia</p>
                </div>
                <div class="col-md-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-number stat-number-custom" data-count="50">0</div>
                    <p class="mb-0">Perusahaan Mitra</p>
                </div>
            </div>
        </div>
    </section> --}}

    {{-- <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center mb-5" data-aos="fade-up">
                    <h2 class="fw-bold mb-3">Testimoni Alumni</h2>
                    <p class="lead">Pengalaman alumni yang telah merasakan manfaat platform ini</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-4" data-aos="flip-left" data-aos-delay="100">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4 testimonial-card">
                            <p class="card-text mb-3">"Melalui tracer study ini, saya bisa terhubung dengan banyak alumni dan mendapatkan referensi lowongan kerja yang sesuai dengan bidang saya."</p>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary-custom text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">A</div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Deny Iqbal</h6>
                                    <small class="text-muted">Teknik Informatika, 2018</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="flip-left" data-aos-delay="200">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4 testimonial-card">
                            <p class="card-text mb-3">"Forum alumni sangat membantu saya dalam berbagi pengalaman kerja dan mendapatkan saran karir dari senior yang sudah berpengalaman."</p>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary-custom text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">S</div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Siti Nurhaliza</h6>
                                    <small class="text-muted">Manajemen, 2019</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4" data-aos="flip-left" data-aos-delay="300">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4 testimonial-card">
                            <p class="card-text mb-3">"Sistem kuesioner bertahap membuat pengisian data tidak membosankan. Fitur yang terbuka secara bertahap juga memberikan motivasi untuk menyelesaikan semua kuesioner."</p>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary-custom text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">D</div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Dewi Sartika</h6>
                                    <small class="text-muted">Akuntansi, 2020</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}

    <section class="py-5 bg-light" id="faq">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2 class="text-center fw-bold mb-5" data-aos="fade-up">Pertanyaan Umum</h2>

                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="100">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#answer1">
                                    Bagaimana cara mendaftar di platform Tracer Study?
                                </button>
                            </h2>
                            <div id="answer1" class="accordion-collapse collapse show"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Klik tombol "Daftar" di bagian atas halaman, isi data diri Anda, dan verifikasi
                                    email dengan masukkan kode otp. Setelah itu, anda dapat melakukan Login Akun atau
                                    anda bisa langsung Login dengan Akun google khusus UAD.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="200">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#answer2">
                                    Apa manfaat mengisi kuesioner tracer study?
                                </button>
                            </h2>
                            <div id="answer2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Data yang Anda berikan membantu universitas meningkatkan kualitas pendidikan dan
                                    relevansi kurikulum. Selain itu, Anda juga mendapatkan akses ke fitur-fitur
                                    eksklusif seperti forum alumni, lowongan kerja, dan mentorship.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item" data-aos="fade-up" data-aos-delay="300">
                            <h2 class="accordion-header" id="faq3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#answer3">
                                    Apakah data pribadi saya aman?
                                </button>
                            </h2>
                            <div id="answer3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Ya, data pribadi Anda dilindungi dan hanya digunakan untuk keperluan tracer study.
                                    Data akan dianalisis secara agregat dan tidak akan dibagikan kepada pihak ketiga
                                    tanpa izin.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0" data-aos="fade-up">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('logo-tracer-study.png') }}" style="width: 200px; height: auto;"
                            class="img-fluid rounded">
                        {{-- <span class="fw-bold text-white">Tracer Study</span> --}}
                    </div>
                    <p class="mb-3">Sistem Informasi Tracer Study Universitas Ahmad Dahlan</p>
                    <div class="d-flex">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
                    <h5 class="mb-3">Tautan Cepat</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#beranda" class="text-white-50 text-decoration-none">Beranda</a>
                        </li>
                        <li class="mb-2"><a href="#tentang" class="text-white-50 text-decoration-none">Tentang</a>
                        </li>
                        <li class="mb-2"><a href="#faq" class="text-white-50 text-decoration-none">FAQ</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Kebijakan
                                Privasi</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0" data-aos="fade-up" data-aos-delay="200">
                    <h5 class="mb-3">Kontak</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2 text-accent"></i>
                            <span class="text-white-50">Jl. Prof. Dr. Soepomo, Janturan, Warungboto, Umbulharjo,
                                Yogyakarta</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone me-2 text-accent"></i>
                            <span class="text-white-50">(0274) 563515</span>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-envelope me-2 text-accent"></i>
                            <span class="text-white-50">tracerstudy@uad.ac.id</span>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <h5 class="mb-3">Universitas Ahmad Dahlan</h5>
                    <p class="text-white-50">Universitas Ahmad Dahlan adalah perguruan tinggi swasta di Yogyakarta yang
                        berdiri pada 18 November 1960.</p>
                </div>
            </div>

            <hr class="my-4 bg-white-50">

            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-white-50 mb-0">&copy;{{ date('Y') }} Tracer Study Universitas Ahmad Dahlan.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-white-50 mb-0">Sistem Informasi Tracer Study</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Counter Animation untuk Stats Section
        function animateCounter() {
            const counters = document.querySelectorAll('.stat-number');
            const speed = 200; // semakin kecil semakin cepat

            counters.forEach(counter => {
                const target = +counter.getAttribute('data-count');
                const count = +counter.innerText;

                const inc = target / speed;

                if (count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(() => animateCounter(counter), 1);
                } else {
                    counter.innerText = target + (counter.getAttribute('data-count') === '85' ? '%' : '+');
                }
            });
        }

        // Trigger counter ketika section stats masuk viewport
        const statsSection = document.querySelector('.bg-primary-custom');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter();
                    observer.unobserve(entry.target);
                }
            });
        });

        if (statsSection) {
            observer.observe(statsSection);
        }

        // Smooth scrolling untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');

                if (targetId === '#') return;

                const target = document.querySelector(targetId);
                if (target) {
                    // Calculate offset based on header height
                    const headerHeight = document.querySelector('header').offsetHeight;
                    const targetPosition = target.getBoundingClientRect().top + window.pageYOffset -
                        headerHeight;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });

                    // Update URL hash without scrolling
                    history.pushState(null, null, targetId);
                }
            });
        });

        // Handle navbar active state on scroll
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (scrollY >= (sectionTop - 100)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        });
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
