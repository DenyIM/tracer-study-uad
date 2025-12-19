<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih - Tracer Study UAD</title>
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
            --success-green: #28a745;
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-content {
            flex: 1;
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

        .thankyou-header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        .completion-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--success-green), #20c997);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            margin: 0 auto 25px;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }

        .completion-icon i {
            animation: checkPulse 0.5s ease;
        }

        @keyframes checkPulse {
            0% {
                transform: scale(0.8);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }

        .thankyou-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 35px;
            margin: 25px 0;
            text-align: center;
            border-top: 5px solid var(--accent-yellow);
        }

        .stats-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 25px 0;
            flex-wrap: wrap;
        }

        .stat-box {
            background: var(--light-blue);
            border-radius: 12px;
            padding: 20px;
            min-width: 180px;
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .stat-value.points {
            color: var(--accent-yellow);
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--primary-blue);
            font-weight: 500;
        }

        .progress-steps {
            display: flex;
            justify-content: center;
            margin: 30px 0;
            position: relative;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 10%;
            right: 10%;
            height: 3px;
            background-color: #e9ecef;
            z-index: 1;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
            width: 25%;
        }

        .step-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .step.completed .step-icon {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
        }

        .step.active .step-icon {
            background: linear-gradient(135deg, var(--accent-yellow), #ffc107);
            color: white;
            transform: scale(1.05);
        }

        .step-label {
            font-size: 0.8rem;
            color: #6c757d;
            text-align: center;
        }

        .step.completed .step-label {
            color: var(--primary-blue);
            font-weight: 600;
        }

        .step.active .step-label {
            color: var(--accent-yellow);
            font-weight: 600;
        }

        .features-section {
            margin: 30px 0;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .feature-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #e9ecef;
            transition: all 0.3s ease;
            text-align: left;
        }

        .feature-card.unlocked {
            border-left: 4px solid var(--success-green);
        }

        .feature-card.locked {
            opacity: 0.8;
        }

        .feature-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .feature-card.unlocked .feature-icon {
            background: linear-gradient(135deg, var(--success-green), #20c997);
        }

        .feature-card.locked .feature-icon {
            background: linear-gradient(135deg, #6c757d, #adb5bd);
        }

        .feature-title {
            flex: 1;
        }

        .feature-badge {
            font-size: 0.75rem;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .feature-badge.unlocked {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-green);
        }

        .feature-badge.locked {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }

        .feature-description {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 0;
        }

        .next-section {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
        }

        .btn-custom {
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-next {
            background: linear-gradient(135deg, var(--accent-yellow), #ffc107);
            border: none;
            color: var(--primary-blue);
        }

        .btn-next:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(250, 179, 0, 0.3);
            color: var(--primary-blue);
        }

        .btn-home {
            background: white;
            border: 2px solid var(--primary-blue);
            color: var(--primary-blue);
        }

        .btn-home:hover {
            background: var(--light-blue);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 51, 102, 0.1);
        }

        .footer {
            background-color: var(--primary-blue);
            color: white;
            padding: 20px 0;
            margin-top: 50px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .thankyou-card {
                padding: 25px 20px;
                margin: 20px 0;
            }
            
            .completion-icon {
                width: 80px;
                height: 80px;
                font-size: 2.5rem;
            }
            
            .stats-container {
                flex-direction: column;
                align-items: center;
            }
            
            .stat-box {
                width: 100%;
                max-width: 250px;
            }
            
            .progress-steps {
                flex-wrap: wrap;
            }
            
            .step {
                width: 50%;
                margin-bottom: 25px;
            }
            
            .progress-steps::before {
                display: none;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .btn-custom {
                padding: 10px 25px;
                font-size: 0.95rem;
            }
        }
    </style>
</head>

<body>
    <header class="thankyou-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a class="navbar-brand d-flex align-items-center" href="#">
                    <img src="{{ asset('logo-tracer-study.png') }}" 
                         style="width: 150px; height: auto;" 
                         class="img-fluid rounded">
                    </a>
                </div>
                <div class="text-end">
                    <p class="mb-0"><strong>Deny Iqbal</strong></p>
                    <p class="text-muted mb-0 small">Teknik Informatika 2018</p>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <div class="thankyou-card">
                        <div class="completion-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        
                        <h1 class="fw-bold mb-3" style="color: var(--primary-blue);">
                            Terima Kasih!
                        </h1>
                        
                        <h4 class="mb-4 text-accent">
                            Kuesioner Bagian 3 Telah Berhasil Diselesaikan
                        </h4>
                        
                        <p class="lead mb-4">
                            Terima kasih atas partisipasi Anda dalam mengisi kuesioner Tracer Study UAD Bagian 3. Kontribusi Anda sangat berharga untuk pengembangan almamater.
                        </p>
                        
                        <div class="stats-container">
                            <div class="stat-box" data-aos="fade-right" data-aos-delay="100">
                                <div class="stat-value">5</div>
                                <div class="stat-label">Pertanyaan Terjawab</div>
                            </div>
                            <div class="stat-box" data-aos="fade-left" data-aos-delay="100">
                                <div class="stat-value points" id="pointsValue">0</div>
                                <div class="stat-label">Points Didapatkan</div>
                            </div>
                        </div>
                        
                        <div class="progress-steps">
                            <div class="step completed">
                                <div class="step-icon">1</div>
                                <div class="step-label">Data Dasar</div>
                            </div>
                            <div class="step completed">
                                <div class="step-icon">2</div>
                                <div class="step-label">Pengalaman Kerja</div>
                            </div>
                            <div class="step completed">
                                <div class="step-icon">3</div>
                                <div class="step-label">Keterampilan</div>
                            </div>
                            <div class="step active">
                                <div class="step-icon">4</div>
                                <div class="step-label">Kepuasan & Saran</div>
                            </div>
                        </div>
                        
                        <div class="features-section">
                            <h4 class="fw-bold mb-4 text-center" style="color: var(--primary-blue);" data-aos="fade-up">
                                Fitur yang Telah Terbuka
                            </h4>
                            
                            <div class="features-grid">
                                <div class="feature-card unlocked" data-aos="fade-right" data-aos-delay="150">
                                    <div class="feature-header">
                                        <div class="feature-icon">
                                            <i class="fas fa-trophy"></i>
                                        </div>
                                        <div class="feature-title">
                                            <h5 class="fw-bold mb-1">Konsultasi Mentor</h5>
                                            <span class="feature-badge unlocked">
                                                <i class="fas fa-lock-open me-1"></i> Terbuka
                                            </span>
                                        </div>
                                    </div>
                                    <p class="feature-description">
                                        Akses konsultasi karir & bisnis gratis dengan mentor profesional dari berbagai industri.
                                    </p>
                                </div>
                            </div>
                            
                            <h4 class="fw-bold mb-4 text-center mt-5" style="color: var(--primary-blue);" data-aos="fade-up">
                                Fitur yang Akan Terbuka Selanjutnya
                            </h4>
                            
                            <div class="features-grid">
                                <div class="feature-card locked" data-aos="fade-left" data-aos-delay="300">
                                    <div class="feature-header">
                                        <div class="feature-icon">
                                            <i class="fas fa-briefcase"></i>
                                        </div>
                                        <div class="feature-title">
                                            <h5 class="fw-bold mb-1">Lowongan Pekerjaan</h5>
                                            <span class="feature-badge locked">
                                                <i class="fas fa-lock me-1"></i> Kuesioner 4
                                            </span>
                                        </div>
                                    </div>
                                    <p class="feature-description">
                                        Akses lowongan kerja premium dari mitra UAD sebelum dipublikasikan ke umum.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="next-section" data-aos="zoom-in">
                            <h4 class="fw-bold mb-3">Lanjutkan Perjalanan Anda!</h4>
                            <p class="mb-4">
                                Lanjutkan kuesioner bagian 4 untuk membuka fitur <strong>Lowongan Pekerjaan</strong> dan mengumpulkan lebih banyak points.
                            </p>
                        </div>
                        
                        <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mt-4">
                            <button class="btn btn-home btn-custom" id="backToHomeBtn" data-aos="fade-right" data-aos-delay="350">
                                <i class="fas fa-home me-2"></i> Kembali ke Halaman Utama
                            </button>
                            <button class="btn btn-next btn-custom" id="nextQuestionnaireBtn" data-aos="fade-left" data-aos-delay="350">
                                <i class="fas fa-arrow-right me-2"></i> Lanjut ke Kuesioner Bagian 4
                            </button>
                        </div>
                        
                        <div class="mt-4 p-3 bg-light rounded" data-aos="fade-up" data-aos-delay="400">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-lightbulb text-accent fs-4 me-3"></i>
                                <p class="mb-0 small">
                                    <strong>Tips:</strong> Setiap kuesioner yang diselesaikan akan membuka 1 fitur baru. Selesaikan semua 4 kuesioner untuk membuka semua fitur eksklusif!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; 2025 Tracer Study Universitas Ahmad Dahlan.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Inisialisasi AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Data points yang didapatkan
        const pointsFromQuestionnaire1 = 50;

        // Animasi counter untuk points
        function animatePointsCounter() {
            const pointsElement = document.getElementById('pointsValue');
            let current = 0;
            const target = pointsFromQuestionnaire1;
            const duration = 1500;
            const increment = target / (duration / 50);
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                    
                    // Tampilkan toast setelah counter selesai
                    showToast(`+${target} Points didapatkan!`, 'success');
                }
                pointsElement.textContent = `+${Math.floor(current)}`;
            }, 50);
        }

        // Jalankan animasi points setelah halaman dimuat
        setTimeout(() => {
            animatePointsCounter();
        }, 500);

        // Simpan progress ke localStorage
        localStorage.setItem('kuesioner_bagian1_completed', 'true');
        localStorage.setItem('last_completed_date', new Date().toISOString());
        
        // Update total points di localStorage
        let totalPoints = parseInt(localStorage.getItem('total_points') || '0');
        totalPoints += pointsFromQuestionnaire1;
        localStorage.setItem('total_points', totalPoints.toString());
        
        // Update unlocked features
        const unlockedFeatures = JSON.parse(localStorage.getItem('unlocked_features') || '[]');
        if (!unlockedFeatures.includes('leaderboard')) {
            unlockedFeatures.push('leaderboard');
            localStorage.setItem('unlocked_features', JSON.stringify(unlockedFeatures));
        }

        // Tombol Lanjutkan ke Kuesioner Bagian 2
        document.getElementById('nextQuestionnaireBtn').addEventListener('click', function() {
            // Tampilkan loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengalihkan...';
            this.disabled = true;
            
            // Simulasi proses pengalihan
            setTimeout(() => {
                window.location.href = 'next-section4';
            }, 1500);
        });

        // Tombol Kembali ke Halaman Utama
        document.getElementById('backToHomeBtn').addEventListener('click', function() {
            // Tampilkan konfirmasi
            if (confirm('Apakah Anda yakin ingin kembali ke halaman utama? Anda dapat melanjutkan kuesioner lain nanti.')) {
                // Tampilkan loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengalihkan...';
                this.disabled = true;
                
                // Alihkan ke halaman utama
                setTimeout(() => {
                    window.location.href = '/back-to-main';
                }, 1000);
            }
        });

        // Fungsi untuk menampilkan toast
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            const bgColor = type === 'warning' ? 'warning' : type === 'success' ? 'success' : 'info';
            
            toast.className = `toast align-items-center text-white bg-${bgColor} border-0 position-fixed top-0 end-0 m-3`;
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
    </script>
</body>
</html>