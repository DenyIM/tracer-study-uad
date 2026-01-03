<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesai! - Tracer Study UAD</title>
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

        .completion-header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        .completion-icon {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--success-green), #20c997);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3.5rem;
            margin: 0 auto 30px;
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.3);
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

        .completion-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 30px 0;
            text-align: center;
            border-top: 5px solid var(--success-green);
        }

        .stats-display {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
            flex-wrap: wrap;
        }

        .stat-item {
            background: var(--light-blue);
            border-radius: 15px;
            padding: 20px;
            min-width: 200px;
            text-align: center;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .stat-value.points {
            color: var(--accent-yellow);
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--primary-blue);
            font-weight: 500;
        }

        .completion-progress {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin: 30px 0;
        }

        .progress-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .progress {
            height: 25px;
            border-radius: 12px;
            background-color: #e9ecef;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(90deg, var(--accent-yellow), #fef3c7);
            background-size: 200% 100%;
            animation: progressAnimation 2s ease-in-out infinite alternate;
        }

        @keyframes progressAnimation {
            0% {
                background-position: 0% 50%;
            }
            100% {
                background-position: 100% 50%;
            }
        }

        .progress-text {
            text-align: center;
            margin-top: 15px;
            font-weight: 500;
            color: var(--success-green);
        }

        .next-steps {
            margin: 40px 0;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-top: 30px;
        }

        .step-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--accent-yellow);
            transition: transform 0.3s ease;
        }

        .step-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .step-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--light-blue);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .btn-home {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 51, 102, 0.3);
            color: white;
        }

        .rank-badge {
            display: inline-block;
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: var(--primary-blue);
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            margin-top: 10px;
            box-shadow: 0 3px 10px rgba(255, 215, 0, 0.2);
        }

        .footer {
            background-color: var(--primary-blue);
            color: white;
            padding: 20px 0;
            margin-top: 50px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .completion-card {
                padding: 25px 20px;
                margin: 20px 0;
            }
            
            .completion-icon {
                width: 100px;
                height: 100px;
                font-size: 2.8rem;
            }
            
            .stats-display {
                flex-direction: column;
                align-items: center;
            }
            
            .stat-item {
                width: 100%;
                max-width: 300px;
            }
            
            .steps-grid {
                grid-template-columns: 1fr;
            }
            
            .btn-home {
                padding: 12px 30px;
                font-size: 1rem;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <header class="completion-header">
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
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <div class="completion-card">
                        <div class="completion-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        
                        <h1 class="fw-bold mb-3" style="color: var(--primary-blue);">
                            Selamat!
                        </h1>
                        
                        <h4 class="mb-4 text-accent">
                            Semua Kuesioner di Kategori Telah Diselesaikan
                        </h4>
                        
                        <p class="lead mb-4">
                            Terima kasih atas kontribusi Anda dalam mengisi semua kuesioner Tracer Study UAD. Data yang Anda berikan sangat berharga untuk pengembangan almamater.
                        </p>
                        
                        <div class="stats-display">
                            <div class="stat-item" data-aos="fade-right" data-aos-delay="100">
                                <div class="stat-value points" id="pointsValue">0</div>
                                <div class="stat-label">Total Points Didapatkan</div>
                            </div>
                            
                            <div class="stat-item" data-aos="fade-left" data-aos-delay="100">
                                <div class="stat-value" id="rankValue">#0</div>
                                <div class="stat-label">Ranking Terkini</div>
                            </div>
                        </div>
                        
                        <div class="completion-progress" data-aos="fade-up" data-aos-delay="200">
                            <div class="progress-title">
                                <h5 class="fw-bold mb-1">Progress Penyelesaian Kuesioner</h5>
                                <p class="text-muted mb-0">Status penyelesaian semua bagian kuesioner</p>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 100%"></div>
                            </div>
                            <div class="progress-text">
                                <i class="fas fa-check-circle me-2"></i>100% - SEMUA KUESIONER TELAH SELESAI
                            </div>
                        </div>
                        
                        <div class="next-steps" data-aos="fade-up" data-aos-delay="300">
                            <h4 class="fw-bold mb-4 text-center" style="color: var(--primary-blue);">
                                <i class="fas fa-arrow-right me-2"></i>Langkah Selanjutnya
                            </h4>
                            
                            <div class="steps-grid">
                                <div class="step-card">
                                    <div class="step-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Bangun Jaringan</h5>
                                    <p class="mb-0 small">Selancar di forum alumni untuk mendapatkan informasi menarik dan terhubung dengan alumni lainnya.</p>
                                </div>
                                
                                <div class="step-card">
                                    <div class="step-icon">
                                        <i class="fas fa-bullseye"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Atur Target</h5>
                                    <p class="mb-0 small">Tetapkan target karir dan manfaatkan fitur konsultasi untuk mencapai tujuan profesional Anda.</p>
                                </div>
                                
                                <div class="step-card">
                                    <div class="step-icon">
                                        <i class="fas fa-briefcase"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Jelajahi Lowongan</h5>
                                    <p class="mb-0 small">Temukan peluang karir terbaru di bagian lowongan kerja eksklusif untuk alumni UAD.</p>
                                </div>
                                
                                <div class="step-card">
                                    <div class="step-icon">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                    <h5 class="fw-bold mb-3">Kumpulkan Points</h5>
                                    <p class="mb-0 small">Terus aktif di platform untuk mengumpulkan points dan mendapatkan rewards menarik.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="400">
                            <button class="btn btn-home" id="backToHomeBtn">
                                <i class="fas fa-home me-2"></i> Kembali ke Menu Utama
                            </button>
                        </div>
                        
                        <div class="mt-5 p-3 bg-light rounded" data-aos="fade-up" data-aos-delay="500">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle text-primary fs-4 me-3"></i>
                                <p class="mb-0 small">
                                    <strong>Info:</strong> Semua fitur platform Tracer Study UAD sekarang telah terbuka untuk Anda. Jelajahi menu utama untuk mulai menggunakan berbagai fitur yang tersedia.
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

        // Data points dan ranking
        let totalPoints = 200; // Total points dari semua kuesioner
        let currentRank = 45;  // Ranking saat ini

        // Simpan status penyelesaian ke localStorage
        localStorage.setItem('all_questionnaires_completed', 'true');
        localStorage.setItem('completion_date', new Date().toISOString());
        
        // Update total points di localStorage
        let savedPoints = parseInt(localStorage.getItem('total_points') || '0');
        if (savedPoints < totalPoints) {
            localStorage.setItem('total_points', totalPoints.toString());
        }

        // Animasi counter untuk points
        function animateCounter(elementId, targetValue, duration = 1000) {
            const element = document.getElementById(elementId);
            let current = 0;
            const increment = targetValue / (duration / 50);
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= targetValue) {
                    current = targetValue;
                    clearInterval(timer);
                    
                    // Tampilkan toast setelah counter selesai
                    if (elementId === 'pointsValue') {
                        showToast(`+${targetPoints} Points didapatkan!`, 'success');
                    }
                }
                element.textContent = elementId === 'pointsValue' ? `+${Math.floor(current)}` : `#${Math.floor(current)}`;
            }, 50);
        }

        // Hitung total points yang didapatkan
        const targetPoints = totalPoints;
        const targetRank = currentRank;

        // Jalankan animasi counter
        setTimeout(() => {
            animateCounter('pointsValue', targetPoints);
        }, 500);

        setTimeout(() => {
            animateCounter('rankValue', targetRank);
        }, 1000);

        // Tombol Kembali ke Menu Utama
        document.getElementById('backToHomeBtn').addEventListener('click', function() {
            // Tampilkan loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengalihkan...';
            this.disabled = true;
            
            // Simulasi loading
            setTimeout(() => {
                window.location.href = '/back-to-main';
            }, 1000);
        });

        // Fungsi untuk menampilkan toast (sama seperti halaman sebelumnya)
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
        
        // Tambahkan achievement ke localStorage
        const achievements = JSON.parse(localStorage.getItem('achievements') || '[]');
        const hasAchievement = achievements.some(ach => ach.id === 'all_questionnaires');
        
        if (!hasAchievement) {
            achievements.push({
                id: 'all_questionnaires',
                title: 'Kompletor Kuesioner',
                description: 'Telah menyelesaikan semua kuesioner Tracer Study',
                date: new Date().toISOString(),
                points: targetPoints
            });
            localStorage.setItem('achievements', JSON.stringify(achievements));
        }
    </script>
</body>
</html>