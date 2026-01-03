<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Jawaban Kuesioner - Tracer Study UAD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #003366;
            --secondary-blue: #3b82f6;
            --light-blue: #dbeafe;
            --accent-yellow: #fab300;
            --light-yellow: #fef3c7;
            --success-green: #28a745;
            --warning-orange: #fd7e14;
            --danger-red: #dc3545;
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
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

        .header-section {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px 0;
            margin-bottom: 30px;
        }

        .category-banner {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .category-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
        }

        .category-icon-large {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .category-icon-large.working {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
        }

        .category-icon-large.entrepreneur {
            background: linear-gradient(135deg, #fab300, #e67700);
            color: white;
        }

        .category-icon-large.study {
            background: linear-gradient(135deg, #28a745, #2b8a3e);
            color: white;
        }

        .category-icon-large.job-seeker {
            background: linear-gradient(135deg, #fd7e14, #e8590c);
            color: white;
        }

        .category-icon-large.not-working {
            background: linear-gradient(135deg, #dc3545, #c92a2a);
            color: white;
        }

        .answers-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .question-item {
            padding: 20px;
            border-bottom: 1px solid #eaeaea;
            transition: all 0.2s ease;
        }

        .question-item:hover {
            background-color: #f8f9fa;
        }

        .question-item:last-child {
            border-bottom: none;
        }

        .question-number {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        .answer-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }

        .answer-badge.radio {
            background-color: #e7f5ff;
            color: #1864ab;
            border: 1px solid #a5d8ff;
        }

        .answer-badge.text {
            background-color: #f3f0ff;
            color: #5f3dc4;
            border: 1px solid #d0bfff;
        }

        .answer-badge.dropdown {
            background-color: #fff3bf;
            color: #e67700;
            border: 1px solid #ffd43b;
        }

        .answer-badge.checkbox {
            background-color: #d3f9d8;
            color: #2b8a3e;
            border: 1px solid #b2f2bb;
        }

        .section-header {
            padding: 15px 20px;
            background-color: var(--light-blue);
            border-radius: 12px 12px 0 0;
            border-bottom: 2px solid var(--primary-blue);
        }

        .section-header h4 {
            color: var(--primary-blue);
            margin: 0;
        }

        .answer-text {
            background-color: #f8f9fa;
            padding: 12px 15px;
            border-radius: 8px;
            border-left: 4px solid var(--secondary-blue);
            margin-top: 10px;
        }

        .competency-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .competency-table th,
        .competency-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .competency-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--primary-blue);
        }

        .competency-table tr:hover {
            background-color: #f8f9fa;
        }

        .scale-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .scale-1 {
            background-color: #ffe3e3;
            color: #c92a2a;
        }

        .scale-2 {
            background-color: #ffec99;
            color: #e67700;
        }

        .scale-3 {
            background-color: #d3f9d8;
            color: #2b8a3e;
        }

        .scale-4 {
            background-color: #a5d8ff;
            color: #1864ab;
        }

        .scale-5 {
            background-color: #d0bfff;
            color: #5f3dc4;
        }

        .completion-info {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        .summary-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .summary-label {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .timestamp {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .timestamp i {
            margin-right: 5px;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .section-icon.general {
            background-color: rgba(108, 117, 125, 0.1);
            color: #495057;
        }

        .section-icon.part1 {
            background-color: rgba(59, 130, 246, 0.1);
            color: #1d4ed8;
        }

        .section-icon.part2 {
            background-color: rgba(250, 179, 0, 0.1);
            color: #e67700;
        }

        .section-icon.part3 {
            background-color: rgba(40, 167, 69, 0.1);
            color: #2b8a3e;
        }

        .section-icon.part4 {
            background-color: rgba(220, 53, 69, 0.1);
            color: #c92a2a;
        }

        @media (max-width: 768px) {
            .category-icon-large {
                width: 60px;
                height: 60px;
                font-size: 1.8rem;
                margin-right: 15px;
            }

            .category-banner {
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="fw-bold mb-2" style="color: var(--primary-blue);">
                        <i class="fas fa-chart-bar me-2"></i>Hasil Jawaban Kuesioner
                    </h2>
                    <p class="text-muted mb-0">Hasil jawaban kuesioner alumni berdasarkan kategori yang dipilih</p>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-outline-primary me-2" onclick="window.location.href='/admin-dashboard'">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                    </button>
                    <button class="btn btn-primary" onclick="exportToPDF()">
                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Informasi Alumni -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="completion-info">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="fw-bold mb-1">Deny Iqbal</h4>
                            <p class="mb-2">
                                <span class="me-3">
                                    <i class="fas fa-graduation-cap me-1 text-primary"></i>
                                    Teknik Informatika - Lulus 2023
                                </span>
                                <span>
                                    <i class="fas fa-envelope me-1 text-primary"></i>
                                    deny.iqbal@email.com
                                </span>
                            </p>
                            <p class="mb-0">
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>Kuesioner Telah Diselesaikan
                                </span>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="timestamp">
                                <i class="fas fa-clock"></i>
                                Terakhir diupdate: 15 Desember 2024
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="category-banner" id="categoryBanner">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="category-icon-large working" id="categoryIcon">
                        <i class="fas fa-building"></i>
                    </div>
                </div>
                <div class="col">
                    <h3 class="fw-bold mb-2" id="categoryTitle">Bekerja di Perusahaan</h3>
                    <p class="mb-0 opacity-90" id="categoryDescription">
                        Alumni mengisi kuesioner untuk kategori <strong>Bekerja di Perusahaan</strong> sesuai dengan
                        statusnya saat ini.
                    </p>
                </div>
                <div class="col-auto text-end">
                    <div class="badge bg-light text-dark fs-6">
                        <i class="fas fa-calendar-check me-1"></i>
                        Diselesaikan: 12 Des 2024
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="summary-card">
                    <div class="summary-number text-primary">17</div>
                    <div class="summary-label">Total Pertanyaan</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="summary-card">
                    <div class="summary-number text-success">17</div>
                    <div class="summary-label">Pertanyaan Terjawab</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="summary-card">
                    <div class="summary-number" style="color: var(--accent-yellow);">100%</div>
                    <div class="summary-label">Tingkat Penyelesaian</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="summary-card">
                    <div class="summary-number text-info">4</div>
                    <div class="summary-label">Bagian Diselesaikan</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="answers-section mb-4">
                    <div class="section-header">
                        <div class="d-flex align-items-center">
                            <div class="section-icon general">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0">Bagian Umum</h4>
                                <p class="text-muted mb-0 small">Wajib diisi oleh semua alumni</p>
                            </div>
                        </div>
                    </div>

                    <div class="question-item">
                        <div class="d-flex align-items-start">
                            <div class="question-number me-3">1</div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-2">Pada saat lulus, pada tingkat mana Anda menguasai kompetensi
                                    berikut? (1=Sangat Rendah, 5=Sangat Tinggi)</h5>
                                <table class="competency-table">
                                    <thead>
                                        <tr>
                                            <th>Kompetensi</th>
                                            <th>Skala</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Etika</td>
                                            <td><span class="scale-badge scale-5">5</span></td>
                                            <td>Sangat Tinggi</td>
                                        </tr>
                                        <tr>
                                            <td>Keahlian Bidang Ilmu</td>
                                            <td><span class="scale-badge scale-4">4</span></td>
                                            <td>Tinggi</td>
                                        </tr>
                                        <tr>
                                            <td>Bahasa Inggris</td>
                                            <td><span class="scale-badge scale-3">3</span></td>
                                            <td>Cukup</td>
                                        </tr>
                                        <tr>
                                            <td>Penggunaan IT</td>
                                            <td><span class="scale-badge scale-5">5</span></td>
                                            <td>Sangat Tinggi</td>
                                        </tr>
                                        <tr>
                                            <td>Komunikasi</td>
                                            <td><span class="scale-badge scale-4">4</span></td>
                                            <td>Tinggi</td>
                                        </tr>
                                        <tr>
                                            <td>Kerja Sama Tim</td>
                                            <td><span class="scale-badge scale-4">4</span></td>
                                            <td>Tinggi</td>
                                        </tr>
                                        <tr>
                                            <td>Pengembangan Diri</td>
                                            <td><span class="scale-badge scale-3">3</span></td>
                                            <td>Cukup</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="timestamp mt-3">
                                    <i class="fas fa-clock"></i>
                                    Diisi: 10 Desember 2024 - 14:30 WIB
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="question-item">
                        <div class="d-flex align-items-start">
                            <div class="question-number me-3">2</div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-2">Menurut Anda, seberapa besar penekanan metode pembelajaran
                                    berikut di prodi Anda?</h5>
                                <table class="competency-table">
                                    <thead>
                                        <tr>
                                            <th>Metode Pembelajaran</th>
                                            <th>Penekanan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Perkuliahan</td>
                                            <td><span class="answer-badge radio">Sangat Besar</span></td>
                                        </tr>
                                        <tr>
                                            <td>Demonstrasi</td>
                                            <td><span class="answer-badge radio">Besar</span></td>
                                        </tr>
                                        <tr>
                                            <td>Partisipasi Proyek Riset</td>
                                            <td><span class="answer-badge radio">Cukup</span></td>
                                        </tr>
                                        <tr>
                                            <td>Magang</td>
                                            <td><span class="answer-badge radio">Besar</span></td>
                                        </tr>
                                        <tr>
                                            <td>Praktikum</td>
                                            <td><span class="answer-badge radio">Sangat Besar</span></td>
                                        </tr>
                                        <tr>
                                            <td>Kerja Lapangan</td>
                                            <td><span class="answer-badge radio">Cukup</span></td>
                                        </tr>
                                        <tr>
                                            <td>Diskusi</td>
                                            <td><span class="answer-badge radio">Besar</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="timestamp mt-3">
                                    <i class="fas fa-clock"></i>
                                    Diisi: 10 Desember 2024 - 14:45 WIB
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="answers-section">
                    <div class="section-header">
                        <div class="d-flex align-items-center">
                            <div class="section-icon part1">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-0">Kategori: Bekerja di Perusahaan</h4>
                                <p class="text-muted mb-0 small">Kategori yang dipilih berdasarkan status alumni</p>
                            </div>
                        </div>
                    </div>

                    <div class="question-item">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="fw-bold mb-0">Bagian 1: Informasi Karir Awal</h5>
                        </div>
                        <div class="ps-4">
                            <div class="mb-4">
                                <h6 class="fw-bold">1. Sejak lulus, berapa lama Anda mendapat pekerjaan sebagai
                                    karyawan untuk pertama kali?</h6>
                                <div class="answer-badge radio">3-<6 bulan</div>
                                        <div class="timestamp mt-1">
                                            <i class="fas fa-clock"></i>
                                            Diisi: 11 Desember 2024 - 09:15 WIB
                                        </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold">2. Rata-rata pendapatan bersih (take home pay) per bulan?</h6>
                                    <div class="answer-badge radio">6-10 juta</div>
                                    <div class="timestamp mt-1">
                                        <i class="fas fa-clock"></i>
                                        Diisi: 11 Desember 2024 - 09:20 WIB
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="question-item">
                            <div class="d-flex align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Bagian 2: Identitas Perusahaan & Bidang Kerja</h5>
                            </div>
                            <div class="ps-4">
                                <div class="mb-4">
                                    <h6 class="fw-bold">3. Nama perusahaan/kantor tempat bekerja?</h6>
                                    <div class="answer-text">PT. Teknologi Indonesia Maju</div>
                                    <div class="timestamp mt-1">
                                        <i class="fas fa-clock"></i>
                                        Diisi: 11 Desember 2024 - 09:30 WIB
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold">4. Tingkat/skala perusahaan?</h6>
                                    <div class="answer-badge radio">Nasional</div>
                                    <div class="timestamp mt-1">
                                        <i class="fas fa-clock"></i>
                                        Diisi: 11 Desember 2024 - 09:32 WIB
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold">5. Tuliskan bidang pekerjaan Anda (dokter, programmer, guru,
                                        dsb)!</h6>
                                    <div class="answer-text">Software Developer / Backend Engineer</div>
                                    <div class="timestamp mt-1">
                                        <i class="fas fa-clock"></i>
                                        Diisi: 11 Desember 2024 - 09:35 WIB
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="question-item">
                            <div class="d-flex align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Bagian 3: Relevansi Studi & Dukungan Kurikulum</h5>
                            </div>
                            <div class="ps-4">
                                <div class="mb-4">
                                    <h6 class="fw-bold">6. Seberapa erat hubungan bidang studi dengan pekerjaan?</h6>
                                    <div class="answer-badge radio">Sangat Erat</div>
                                    <div class="timestamp mt-1">
                                        <i class="fas fa-clock"></i>
                                        Diisi: 12 Desember 2024 - 11:20 WIB
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold">7. Tuliskan 3 (tiga) mata kuliah yang mendukung pekerjaan</h6>
                                    <div class="answer-text">
                                        1. Pemrograman Berorientasi Objek<br>
                                        2. Basis Data dan Sistem Informasi<br>
                                        3. Rekayasa Perangkat Lunak
                                    </div>
                                    <div class="timestamp mt-1">
                                        <i class="fas fa-clock"></i>
                                        Diisi: 12 Desember 2024 - 11:25 WIB
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="question-item">
                            <div class="d-flex align-items-center mb-3">
                                <h5 class="fw-bold mb-0">Bagian 4: Pengembangan Kompetensi Setelah Lulus</h5>
                            </div>
                            <div class="ps-4">
                                <div class="mb-4">
                                    <h6 class="fw-bold">8. Kompetensi spesifik apa yang Anda kembangkan setelah lulus
                                        yang paling penting dalam pekerjaan?</h6>
                                    <div class="answer-text">
                                        - Penggunaan framework modern seperti Laravel dan React<br>
                                        - Kemampuan DevOps dengan Docker dan CI/CD<br>
                                        - Microservices architecture<br>
                                        - Cloud computing (AWS, Google Cloud)
                                    </div>
                                    <div class="timestamp mt-1">
                                        <i class="fas fa-clock"></i>
                                        Diisi: 12 Desember 2024 - 11:40 WIB
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold">9. Sertifikat / kompetensi yang Anda peroleh dari perusahaan?
                                    </h6>
                                    <div class="answer-text">
                                        - AWS Certified Developer Associate<br>
                                        - Laravel Certified Developer<br>
                                        - Scrum Master Certification (PSM I)<br>
                                        - Google Cloud Associate Engineer
                                    </div>
                                    <div class="timestamp mt-1">
                                        <i class="fas fa-clock"></i>
                                        Diisi: 12 Desember 2024 - 11:45 WIB
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="alert alert-info">
                        <h5 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>Informasi Penting
                        </h5>
                        <p class="mb-0">
                            Alumni <strong>hanya mengisi 1 kategori kuesioner</strong> sesuai dengan statusnya saat ini.
                            Kategori lain tidak ditampilkan karena tidak relevan dengan status alumni.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer mt-5 py-3" style="background-color: var(--primary-blue); color: white;">
            <div class="container text-center">
                <p class="mb-0">&copy; 2025 Tracer Study Universitas Ahmad Dahlan.</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Data kuesioner (contoh static)
            const questionnaireData = {
                alumni: {
                    name: "Deny Iqbal",
                    program_studi: "Teknik Informatika",
                    tahun_lulus: "2023",
                    email: "deny.iqbal@email.com",
                    status: "completed"
                },
                selectedCategory: "working", // working, entrepreneur, study, job-seeker, not-working
                completionDate: "12 Desember 2024",
                generalSection: {
                    completed: true,
                    questions: 2
                },
                workingCategory: {
                    selected: true,
                    completed: true,
                    sections: 4,
                    questions: 9
                }
            };

            // Konfigurasi kategori
            const categoryConfig = {
                working: {
                    title: "Bekerja di Perusahaan",
                    icon: "fa-building",
                    iconClass: "working",
                    description: "Alumni mengisi kuesioner untuk kategori <strong>Bekerja di Perusahaan</strong> sesuai dengan statusnya saat ini.",
                    color: "#3b82f6"
                },
                entrepreneur: {
                    title: "Wirausaha",
                    icon: "fa-briefcase",
                    iconClass: "entrepreneur",
                    description: "Alumni mengisi kuesioner untuk kategori <strong>Wirausaha</strong> sesuai dengan statusnya saat ini.",
                    color: "#fab300"
                },
                study: {
                    title: "Melanjutkan Studi",
                    icon: "fa-graduation-cap",
                    iconClass: "study",
                    description: "Alumni mengisi kuesioner untuk kategori <strong>Melanjutkan Studi</strong> sesuai dengan statusnya saat ini.",
                    color: "#28a745"
                },
                'job-seeker': {
                    title: "Pencari Kerja",
                    icon: "fa-search",
                    iconClass: "job-seeker",
                    description: "Alumni mengisi kuesioner untuk kategori <strong>Pencari Kerja</strong> sesuai dengan statusnya saat ini.",
                    color: "#fd7e14"
                },
                'not-working': {
                    title: "Tidak Bekerja",
                    icon: "fa-home",
                    iconClass: "not-working",
                    description: "Alumni mengisi kuesioner untuk kategori <strong>Tidak Bekerja</strong> sesuai dengan statusnya saat ini.",
                    color: "#dc3545"
                }
            };

            // Fungsi untuk export ke PDF
            function exportToPDF() {
                // Simulasi export PDF
                alert('Fitur export PDF akan segera tersedia!');
                // Implementasi sebenarnya akan menggunakan library seperti jsPDF
            }

            // Fungsi untuk mengupdate tampilan berdasarkan kategori yang dipilih
            function updateViewBasedOnCategory() {
                const category = questionnaireData.selectedCategory;
                const config = categoryConfig[category];

                if (config) {
                    // Update banner kategori
                    document.getElementById('categoryTitle').textContent = config.title;
                    document.getElementById('categoryDescription').innerHTML = config.description;

                    // Update icon kategori
                    const categoryIcon = document.getElementById('categoryIcon');
                    categoryIcon.className = `category-icon-large ${config.iconClass}`;
                    categoryIcon.innerHTML = `<i class="fas ${config.icon}"></i>`;

                    // Update background color banner
                    const banner = document.getElementById('categoryBanner');
                    banner.style.background = `linear-gradient(135deg, ${config.color}, ${darkenColor(config.color, 20)})`;
                }
            }

            // Fungsi untuk menggelapkan warna
            function darkenColor(color, percent) {
                let r = parseInt(color.substring(1, 3), 16);
                let g = parseInt(color.substring(3, 5), 16);
                let b = parseInt(color.substring(5, 7), 16);

                r = Math.floor(r * (100 - percent) / 100);
                g = Math.floor(g * (100 - percent) / 100);
                b = Math.floor(b * (100 - percent) / 100);

                return `#${r.toString(16).padStart(2, '0')}${g.toString(16).padStart(2, '0')}${b.toString(16).padStart(2, '0')}`;
            }

            // Fungsi untuk simulasi ganti kategori (untuk demo)
            function changeCategory(newCategory) {
                if (confirm(`Ubah kategori menjadi ${categoryConfig[newCategory]?.title}?`)) {
                    questionnaireData.selectedCategory = newCategory;
                    updateViewBasedOnCategory();

                    // Update konten kuesioner berdasarkan kategori (dalam implementasi real akan fetch dari API)
                    alert(`Kategori berhasil diubah ke ${categoryConfig[newCategory]?.title}.`);
                }
            }

            // Inisialisasi saat halaman dimuat
            document.addEventListener('DOMContentLoaded', function() {
                updateViewBasedOnCategory();

                // Tooltip initialization
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });
        </script>
</body>

</html>
