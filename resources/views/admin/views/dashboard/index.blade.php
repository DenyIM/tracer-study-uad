@extends('admin.views.layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Analisis Tracer Study')

@section('content')
    <!-- Header dengan Export Button -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card dashboard-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">Dashboard Analisis Tracer Study</h4>
                            <p class="text-muted mb-0">Analisis data dari hasil kuesioner alumni</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.questionnaire.export.form') }}" class="btn btn-danger" target="_blank">
                                <i class="bi bi-file-pdf me-2"></i> Export PDF
                            </a>
                            <a href="{{ route('admin.questionnaire.export.complete.form') }}" class="btn btn-danger"
                                target="_blank">
                                <i class="bi bi-file-pdf me-2"></i> Export Semua Jawaban
                            </a>
                            <button onclick="loadTracerCharts()" class="btn btn-primary ms-2">
                                <i class="bi bi-graph-up me-2"></i> Muat Grafik Tracer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Utama -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill display-4 text-primary"></i>
                    <h3 class="mt-3">{{ number_format($alumniCount) }}</h3>
                    <p class="text-muted mb-0">Total Alumni</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <i class="bi bi-clipboard-check display-4 text-success"></i>
                    <h3 class="mt-3">{{ number_format($totalAnswers) }}</h3>
                    <p class="text-muted mb-0">Total Jawaban</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card dashboard-card">
                <div class="card-body text-center">
                    <i class="bi bi-percent display-4 text-info"></i>
                    <h3 class="mt-3">{{ $avgCompletionRate }}%</h3>
                    <p class="text-muted mb-0">Rata-rata Penyelesaian</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Container untuk Grafik Tracer Study -->
    <div id="tracerChartsContainer">
        <!-- Grafik akan dimuat di sini -->
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Memuat grafik Tracer Study...</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card dashboard-card">
                <div class="card-header">
                    <h5 class="mb-0">Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.questionnaire.statistics') }}" class="btn btn-success w-100">
                                <i class="bi bi-bar-chart me-2"></i> Statistik Lengkap
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.questionnaire.export') }}" class="btn btn-warning w-100">
                                <i class="bi bi-download me-2"></i> Export Data Excel
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.views.users.alumni.index') }}" class="btn btn-primary w-100">
                                <i class="bi bi-people me-2"></i> Kelola Alumni
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-info w-100" onclick="refreshDashboard()">
                                <i class="bi bi-arrow-clockwise me-2"></i> Refresh Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .dashboard-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .chart-container {
            position: relative;
            height: 400px;
            margin-bottom: 20px;
        }

        .chart-container-medium {
            position: relative;
            height: 300px;
            margin-bottom: 20px;
        }

        .chart-container-small {
            position: relative;
            height: 250px;
            margin-bottom: 20px;
        }

        .tracer-chart-card {
            margin-bottom: 30px;
        }

        .chart-description {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 10px;
        }

        .conclusion-box {
            background-color: #f8f9fa;
            border-left: 4px solid #0d6efd;
            padding: 15px;
            margin-top: 15px;
            border-radius: 5px;
        }

        .data-source {
            font-size: 0.8rem;
            color: #6c757d;
            font-style: italic;
            margin-top: 10px;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
            background: none;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
@endpush

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load tracer charts on page load
            loadTracerCharts();

            // Auto refresh every 5 minutes
            setTimeout(() => {
                refreshDashboard();
            }, 300000);
        });

        // Function to load tracer study charts
        function loadTracerCharts() {
            const container = document.getElementById('tracerChartsContainer');
            if (!container) return;

            // Show loading
            container.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Memuat grafik Tracer Study...</p>
            </div>
        `;

            // Fetch data from API
            fetch("{{ route('admin.tracer.charts') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderTracerCharts(data.data);
                        // Tampilkan metadata jika ada
                        if (data.metadata) {
                            console.log('Data REAL loaded:', {
                                source: data.metadata.data_source,
                                alumni: data.metadata.total_alumni,
                                answers: data.metadata.total_answers,
                                time: data.metadata.generated_at
                            });
                        }
                    } else {
                        container.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Gagal memuat data grafik: ${data.message}
                        </div>
                    `;
                    }
                })
                .catch(error => {
                    console.error('Error loading tracer charts:', error);
                    container.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Gagal memuat data grafik. Silakan coba lagi.
                    </div>
                `;
                });
        }

        // Function to render all tracer charts - DIPERBARUI untuk data dinamis
        function renderTracerCharts(data) {
            const container = document.getElementById('tracerChartsContainer');

            if (!container) return;

            // Fungsi untuk cek data kosong
            const hasData = (chartData, minItems = 1) => {
                return chartData &&
                    chartData.labels &&
                    chartData.labels.length >= minItems &&
                    chartData.values &&
                    chartData.values.length >= minItems;
            };

            let html = `
            <div class="row">
                <!-- Grafik 1: Status Lulusan Saat Ini -->
                <div class="col-md-6">
                    <div class="card tracer-chart-card">
                        <div class="card-header">
                            <h5 class="mb-0">1. Status Lulusan Saat Ini</h5>
                        </div>
                        <div class="card-body">
                            ${hasData(data.graduate_status, 1) ? `
                                                                                                <div class="chart-container-medium">
                                                                                                    <canvas id="graduateStatusChart"></canvas>
                                                                                                </div>
                                                                                                ${data.graduate_status.conclusion ? `
                                <div class="conclusion-box">
                                    <strong>Kesimpulan:</strong> ${data.graduate_status.conclusion}
                                </div>
                                ` : ''}
                                                                                                <div class="data-source">
                                                                                                    Sumber: ${data.graduate_status.data_source || 'Database'}
                                                                                                    ${data.graduate_status.total ? `<br><small>Total: ${data.graduate_status.total} alumni</small>` : ''}
                                                                                                </div>
                                                                                            ` : `
                                                                                                <div class="text-center py-4">
                                                                                                    <i class="bi bi-database-slash text-muted display-4"></i>
                                                                                                    <p class="mt-3 text-muted">Belum ada data kategori dari alumni</p>
                                                                                                </div>
                                                                                            `}
                        </div>
                    </div>
                </div>
                
                <!-- Grafik 2: Waktu Tunggu Mendapat Pekerjaan -->
                <div class="col-md-6">
                    <div class="card tracer-chart-card">
                        <div class="card-header">
                            <h5 class="mb-0">2. Waktu Tunggu Mendapat Pekerjaan</h5>
                        </div>
                        <div class="card-body">
                            ${hasData(data.waiting_time, 1) ? `
                                                                                                <div class="chart-container-medium">
                                                                                                    <canvas id="waitingTimeChart"></canvas>
                                                                                                </div>
                                                                                                ${data.waiting_time.conclusion ? `
                                <div class="conclusion-box">
                                    <strong>Kesimpulan:</strong> ${data.waiting_time.conclusion}
                                </div>
                                ` : ''}
                                                                                                <div class="data-source">
                                                                                                    Sumber: ${data.waiting_time.data_source || 'Database'}
                                                                                                </div>
                                                                                            ` : `
                                                                                                <div class="text-center py-4">
                                                                                                    <i class="bi bi-clock-history text-muted display-4"></i>
                                                                                                    <p class="mt-3 text-muted">Belum ada data waktu tunggu</p>
                                                                                                </div>
                                                                                            `}
                        </div>
                    </div>
                </div>
                
                <!-- Grafik 3: Hubungan Bidang Studi dengan Pekerjaan -->
                <div class="col-md-6">
                    <div class="card tracer-chart-card">
                        <div class="card-header">
                            <h5 class="mb-0">3. Hubungan Bidang Studi dengan Pekerjaan</h5>
                        </div>
                        <div class="card-body">
                            ${hasData(data.study_work_relevance, 1) ? `
                                                                                                <div class="chart-container-medium">
                                                                                                    <canvas id="studyWorkRelevanceChart"></canvas>
                                                                                                </div>
                                                                                                ${data.study_work_relevance.conclusion ? `
                                <div class="conclusion-box">
                                    <strong>Kesimpulan:</strong> ${data.study_work_relevance.conclusion}
                                </div>
                                ` : ''}
                                                                                                <div class="data-source">
                                                                                                    Sumber: ${data.study_work_relevance.data_source || 'Database'}
                                                                                                </div>
                                                                                            ` : `
                                                                                                <div class="text-center py-4">
                                                                                                    <i class="bi bi-link text-muted display-4"></i>
                                                                                                    <p class="mt-3 text-muted">Belum ada data relevansi</p>
                                                                                                </div>
                                                                                            `}
                        </div>
                    </div>
                </div>
                
                <!-- Grafik 4: Tingkat Tempat Kerja -->
                <div class="col-md-6">
                    <div class="card tracer-chart-card">
                        <div class="card-header">
                            <h5 class="mb-0">4. Tingkat Tempat Kerja</h5>
                        </div>
                        <div class="card-body">
                            ${hasData(data.work_level, 1) ? `
                                                                                                <div class="chart-container-medium">
                                                                                                    <canvas id="workLevelChart"></canvas>
                                                                                                </div>
                                                                                                <div class="data-source">
                                                                                                    Sumber: ${data.work_level.data_source || 'Database'}
                                                                                                </div>
                                                                                            ` : `
                                                                                                <div class="text-center py-4">
                                                                                                    <i class="bi bi-building text-muted display-4"></i>
                                                                                                    <p class="mt-3 text-muted">Belum ada data tingkat perusahaan</p>
                                                                                                </div>
                                                                                            `}
                        </div>
                    </div>
                </div>
                
                <!-- Grafik 5: Kisaran Gaji -->
                <div class="col-md-6">
                    <div class="card tracer-chart-card">
                        <div class="card-header">
                            <h5 class="mb-0">5. Kisaran Gaji Lulusan</h5>
                        </div>
                        <div class="card-body">
                            ${hasData(data.salary_range, 1) ? `
                                                                                                <div class="chart-container-medium">
                                                                                                    <canvas id="salaryRangeChart"></canvas>
                                                                                                </div>
                                                                                                ${data.salary_range.conclusion ? `
                                <div class="conclusion-box">
                                    <strong>Kesimpulan:</strong> ${data.salary_range.conclusion}
                                </div>
                                ` : ''}
                                                                                                <div class="data-source">
                                                                                                    Sumber: ${data.salary_range.data_source || 'Database'}
                                                                                                </div>
                                                                                            ` : `
                                                                                                <div class="text-center py-4">
                                                                                                    <i class="bi bi-cash text-muted display-4"></i>
                                                                                                    <p class="mt-3 text-muted">Belum ada data gaji</p>
                                                                                                </div>
                                                                                            `}
                        </div>
                    </div>
                </div>
                
                <!-- Grafik 6: Metode Pembelajaran -->
                <div class="col-md-6">
                    <div class="card tracer-chart-card">
                        <div class="card-header">
                            <h5 class="mb-0">6. Metode Pembelajaran</h5>
                        </div>
                        <div class="card-body">
                            ${data.learning_methods && data.learning_methods.methods && data.learning_methods.methods.length > 0 ? `
                                                                                                <div class="chart-container-medium">
                                                                                                    <canvas id="learningMethodChart"></canvas>
                                                                                                </div>
                                                                                                <div class="data-source">
                                                                                                    Sumber: ${data.learning_methods.data_source || 'Database'}
                                                                                                </div>
                                                                                            ` : `
                                                                                                <div class="text-center py-4">
                                                                                                    <i class="bi bi-book text-muted display-4"></i>
                                                                                                    <p class="mt-3 text-muted">Belum ada data metode pembelajaran</p>
                                                                                                </div>
                                                                                            `}
                        </div>
                    </div>
                </div>
                
                <!-- Grafik 7: Kompetensi -->
                <div class="col-md-12">
                    <div class="card tracer-chart-card">
                        <div class="card-header">
                            <h5 class="mb-0">7. Tingkat Kompetensi Alumni</h5>
                        </div>
                        <div class="card-body">
                            ${data.competence && data.competence.competencies && Object.keys(data.competence.competencies).length > 0 ? `
                                                                                                <div class="chart-container">
                                                                                                    <canvas id="competenceChart"></canvas>
                                                                                                </div>
                                                                                                <div class="data-source">
                                                                                                    Sumber: ${data.competence.data_source || 'Database'}
                                                                                                </div>
                                                                                            ` : `
                                                                                                <div class="text-center py-4">
                                                                                                    <i class="bi bi-award text-muted display-4"></i>
                                                                                                    <p class="mt-3 text-muted">Belum ada data kompetensi</p>
                                                                                                </div>
                                                                                            `}
                        </div>
                    </div>
                </div>
                
                <!-- Grafik 8: Sumber Biaya Kuliah -->
                <div class="col-md-6">
                    <div class="card tracer-chart-card">
                        <div class="card-header">
                            <h5 class="mb-0">8. Sumber Biaya Kuliah</h5>
                        </div>
                        <div class="card-body">
                            ${hasData(data.funding_source, 1) ? `
                                                                                                <div class="chart-container-medium">
                                                                                                    <canvas id="fundingSourceChart"></canvas>
                                                                                                </div>
                                                                                                ${data.funding_source.dominant ? `
                                <div class="conclusion-box">
                                    <strong>Kesimpulan:</strong> ${data.funding_source.dominant}
                                </div>
                                ` : ''}
                                                                                                <div class="data-source">
                                                                                                    Sumber: ${data.funding_source.data_source || 'Database'}
                                                                                                </div>
                                                                                            ` : `
                                                                                                <div class="text-center py-4">
                                                                                                    <i class="bi bi-wallet text-muted display-4"></i>
                                                                                                    <p class="mt-3 text-muted">Belum ada data sumber biaya</p>
                                                                                                </div>
                                                                                            `}
                        </div>
                    </div>
                </div>
                
                <!-- Metadata Info -->
                <div class="col-md-12 mt-3">
                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Data Dinamis:</strong> Grafik ini menampilkan data REAL dari kuesioner alumni.
                                Data akan otomatis update ketika:
                                <ul class="mb-0 mt-1">
                                    <li>Alumni mengisi/mengubah jawaban</li>
                                    <li>Admin menambah/mengubah pertanyaan</li>
                                    <li>Kategori kuesioner diupdate</li>
                                </ul>
                            </div>
                            <button class="btn btn-sm btn-outline-primary" onclick="loadTracerCharts()">
                                <i class="bi bi-arrow-clockwise"></i> Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

            container.innerHTML = html;

            // Render all charts after HTML is inserted
            setTimeout(() => {
                renderAllCharts(data);
            }, 100);
        }

        // Function to render all charts - DIPERBARUI untuk data dinamis
        function renderAllCharts(data) {
            // Fungsi generate colors dinamis berdasarkan jumlah data
            function generateColors(count) {
                const baseColors = [
                    '#0d6efd', '#198754', '#ffc107', '#dc3545', '#6c757d',
                    '#0dcaf0', '#6610f2', '#fd7e14', '#20c997', '#6f42c1',
                    '#d63384', '#fd7e14', '#198754', '#0dcaf0', '#6f42c1'
                ];

                if (count <= baseColors.length) {
                    return baseColors.slice(0, count);
                }

                // Generate lebih banyak warna jika perlu (rainbow effect)
                const colors = [];
                for (let i = 0; i < count; i++) {
                    const hue = (i * 137.508) % 360; // Golden angle
                    colors.push(`hsl(${hue}, 70%, 65%)`);
                }
                return colors;
            }

            // Helper function untuk cek data
            const hasData = (chartData, minItems = 1) => {
                return chartData &&
                    chartData.labels &&
                    chartData.labels.length >= minItems &&
                    chartData.values &&
                    chartData.values.length >= minItems;
            };

            // 1. Graduate Status Chart - WARNANYA DINAMIS
            if (hasData(data.graduate_status, 1)) {
                renderPieChart('graduateStatusChart', {
                    labels: data.graduate_status.labels,
                    values: data.graduate_status.values,
                    title: 'Status Lulusan Saat Ini',
                    colors: generateColors(data.graduate_status.labels.length)
                });
            }

            // 2. Waiting Time Chart - WARNANYA DINAMIS
            if (hasData(data.waiting_time, 1)) {
                renderDonutChart('waitingTimeChart', {
                    labels: data.waiting_time.labels,
                    values: data.waiting_time.values,
                    title: 'Waktu Tunggu Mendapat Pekerjaan',
                    colors: generateColors(data.waiting_time.labels.length)
                });
            }

            // 3. Study-Work Relevance Chart - WARNANYA DINAMIS
            if (hasData(data.study_work_relevance, 1)) {
                renderBarChart('studyWorkRelevanceChart', {
                    labels: data.study_work_relevance.labels,
                    values: data.study_work_relevance.values,
                    title: 'Hubungan Bidang Studi dengan Pekerjaan',
                    color: '#0d6efd'
                });
            }

            // 4. Work Level Chart - WARNANYA DINAMIS
            if (hasData(data.work_level, 1)) {
                renderPieChart('workLevelChart', {
                    labels: data.work_level.labels,
                    values: data.work_level.values,
                    title: 'Tingkat Tempat Kerja',
                    colors: generateColors(data.work_level.labels.length)
                });
            }

            // 5. Salary Range Chart - WARNANYA DINAMIS
            if (hasData(data.salary_range, 1)) {
                renderBarChart('salaryRangeChart', {
                    labels: data.salary_range.labels,
                    values: data.salary_range.values,
                    title: 'Kisaran Gaji Lulusan',
                    color: '#198754'
                });
            }

            // 6. Learning Method Chart - DINAMIS BERDASARKAN DATA
            if (data.learning_methods && data.learning_methods.methods && data.learning_methods.methods.length > 0) {
                renderStackedBarChart('learningMethodChart', data.learning_methods);
            }

            // 7. Competence Chart - DINAMIS BERDASARKAN DATA
            if (data.competence && data.competence.competencies && Object.keys(data.competence.competencies).length > 0) {
                renderRadarChart('competenceChart', data.competence);
            }

            // 8. Funding Source Chart - WARNANYA DINAMIS
            if (hasData(data.funding_source, 1)) {
                renderPieChart('fundingSourceChart', {
                    labels: data.funding_source.labels,
                    values: data.funding_source.values,
                    title: 'Sumberdana Pembiayaan Kuliah',
                    colors: generateColors(data.funding_source.labels.length)
                });
            }
        }

        // Individual chart rendering functions - DIPERBARUI untuk error handling
        function renderPieChart(canvasId, data) {
            const ctx = document.getElementById(canvasId);
            if (!ctx) {
                console.error(`Canvas dengan ID ${canvasId} tidak ditemukan`);
                return;
            }

            try {
                new Chart(ctx.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.values,
                            backgroundColor: data.colors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.label}: ${context.raw.toFixed(2)}%`;
                                    }
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error(`Error rendering pie chart ${canvasId}:`, error);
            }
        }

        function renderDonutChart(canvasId, data) {
            const ctx = document.getElementById(canvasId);
            if (!ctx) {
                console.error(`Canvas dengan ID ${canvasId} tidak ditemukan`);
                return;
            }

            try {
                new Chart(ctx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.values,
                            backgroundColor: data.colors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '50%',
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.label}: ${context.raw.toFixed(2)}%`;
                                    }
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error(`Error rendering donut chart ${canvasId}:`, error);
            }
        }

        function renderBarChart(canvasId, data) {
            const ctx = document.getElementById(canvasId);
            if (!ctx) {
                console.error(`Canvas dengan ID ${canvasId} tidak ditemukan`);
                return;
            }

            try {
                new Chart(ctx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Persentase (%)',
                            data: data.values,
                            backgroundColor: data.color,
                            borderColor: data.color,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                ticks: {
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45
                                }
                            }
                        },
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${context.raw.toFixed(2)}%`;
                                    }
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error(`Error rendering bar chart ${canvasId}:`, error);
            }
        }

        function renderStackedBarChart(canvasId, data) {
            const ctx = document.getElementById(canvasId);
            if (!ctx) {
                console.error(`Canvas dengan ID ${canvasId} tidak ditemukan`);
                return;
            }

            try {
                // Generate colors untuk setiap skala
                const scaleColors = generateColors(data.scales.length);

                const datasets = data.scales.map((scale, index) => {
                    return {
                        label: scale,
                        data: data.methods.map(method => method.values[index]),
                        backgroundColor: scaleColors[index]
                    };
                });

                new Chart(ctx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: data.methods.map(m => m.name),
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                stacked: true
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                max: 100,
                                ticks: {
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    font: {
                                        size: 10
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${context.raw}%`;
                                    }
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error(`Error rendering stacked bar chart ${canvasId}:`, error);
            }
        }

        function renderRadarChart(canvasId, data) {
            const ctx = document.getElementById(canvasId);
            if (!ctx) {
                console.error(`Canvas dengan ID ${canvasId} tidak ditemukan`);
                return;
            }

            try {
                const competenceNames = Object.keys(data.competencies);
                const scales = data.scales || ['Rendah', 'Sedang', 'Tinggi', 'Sangat Tinggi'];

                if (competenceNames.length === 0) return;

                // Ambil data untuk skala "Tinggi" (index 2) dan "Sangat Tinggi" (index 3)
                // atau sesuaikan dengan struktur data yang ada
                const datasets = [];

                // Coba ambil 2 skala teratas
                const scaleIndices = scales.length >= 4 ? [2, 3] :
                    scales.length >= 3 ? [1, 2] :
                    scales.length >= 2 ? [0, 1] : [0];

                scaleIndices.forEach(scaleIndex => {
                    if (scaleIndex < scales.length) {
                        const values = competenceNames.map(name => {
                            const compData = data.competencies[name];
                            return compData && compData[scaleIndex] ? compData[scaleIndex] : 0;
                        });

                        const colors = ['#0d6efd20', '#19875420', '#ffc10720', '#dc354520'];
                        const borderColors = ['#0d6efd', '#198754', '#ffc107', '#dc3545'];

                        datasets.push({
                            label: scales[scaleIndex],
                            data: values,
                            backgroundColor: colors[scaleIndex] || '#0d6efd20',
                            borderColor: borderColors[scaleIndex] || '#0d6efd',
                            borderWidth: 1,
                            pointBackgroundColor: borderColors[scaleIndex] || '#0d6efd'
                        });
                    }
                });

                new Chart(ctx.getContext('2d'), {
                    type: 'radar',
                    data: {
                        labels: competenceNames,
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            r: {
                                beginAtZero: true,
                                max: 100,
                                ticks: {
                                    callback: function(value) {
                                        return value + '%';
                                    },
                                    stepSize: 20
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${context.raw}%`;
                                    }
                                }
                            }
                        }
                    }
                });
            } catch (error) {
                console.error(`Error rendering radar chart ${canvasId}:`, error);
            }
        }

        // Fungsi generate colors dinamis
        function generateColors(count) {
            const baseColors = [
                '#0d6efd', '#198754', '#ffc107', '#dc3545', '#6c757d',
                '#0dcaf0', '#6610f2', '#fd7e14', '#20c997', '#6f42c1',
                '#d63384', '#fd7e14', '#198754', '#0dcaf0', '#6f42c1'
            ];

            if (count <= baseColors.length) {
                return baseColors.slice(0, count);
            }

            // Generate lebih banyak warna jika perlu
            const colors = [];
            const goldenRatio = 0.618033988749895;
            let hue = Math.random() * 360; // Start dengan random hue

            for (let i = 0; i < count; i++) {
                hue = (hue + goldenRatio * 360) % 360;
                const saturation = 60 + Math.random() * 20; // 60-80%
                const lightness = 50 + Math.random() * 15; // 50-65%
                colors.push(`hsl(${hue}, ${saturation}%, ${lightness}%)`);
            }
            return colors;
        }

        // Refresh dashboard function
        function refreshDashboard() {
            fetch("{{ route('admin.dashboard.stats') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the charts
                        loadTracerCharts();

                        // Show success message
                        showToast('Data berhasil diperbarui', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error refreshing dashboard:', error);
                    showToast('Gagal memperbarui data', 'error');
                });
        }

        // Toast notification function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type} border-0`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');

            toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

            document.body.appendChild(toast);

            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
@endpush
