<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - {{ date('d F Y') }}</title>
    <style>
        /* CSS untuk PDF */
        @page {
            margin: 20px;
            size: A4 portrait;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2c3e50;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }

        .report-info {
            font-size: 10px;
            color: #95a5a6;
            margin-top: 10px;
        }

        /* Sections */
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
            background-color: #f8f9fa;
            padding: 8px 12px;
            border-left: 4px solid #3498db;
            margin-bottom: 12px;
            border-radius: 3px;
        }

        /* Stat Cards */
        .stats-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .stat-card {
            flex: 1;
            min-width: 120px;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
            background-color: #f8f9fa;
        }

        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #2980b9;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 10px;
            color: #7f8c8d;
            text-transform: uppercase;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }

        th {
            background-color: #2c3e50;
            color: white;
            border: 1px solid #34495e;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }

        td {
            border: 1px solid #ddd;
            padding: 7px;
            vertical-align: top;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table-responsive {
            overflow-x: auto;
        }

        /* Charts Summary */
        .chart-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }

        .chart-item {
            flex: 1;
            min-width: 200px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            padding: 12px;
            background-color: #fff;
        }

        .chart-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 12px;
        }

        .chart-data {
            font-size: 10px;
        }

        .data-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            padding-bottom: 4px;
            border-bottom: 1px dotted #eee;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            margin: 1px;
        }

        .badge-success {
            background-color: #27ae60;
            color: white;
        }

        .badge-primary {
            background-color: #3498db;
            color: white;
        }

        .badge-warning {
            background-color: #f39c12;
            color: white;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        /* Filters */
        .filters-box {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .filter-item {
            margin-bottom: 3px;
        }

        /* Conclusion */
        .conclusion-box {
            background-color: #e8f4fc;
            border-left: 4px solid #3498db;
            padding: 12px;
            margin: 15px 0;
            border-radius: 3px;
            font-size: 11px;
        }

        .conclusion-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #95a5a6;
        }

        .page-number {
            text-align: right;
            font-size: 9px;
            color: #95a5a6;
            margin-top: 10px;
        }

        /* Utilities */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-muted {
            color: #7f8c8d;
        }

        .mb-1 {
            margin-bottom: 5px;
        }

        .mb-2 {
            margin-bottom: 10px;
        }

        .mb-3 {
            margin-bottom: 15px;
        }

        .mt-1 {
            margin-top: 5px;
        }

        .mt-2 {
            margin-top: 10px;
        }

        .mt-3 {
            margin-top: 15px;
        }

        /* Page break */
        .page-break {
            page-break-before: always;
        }

        /* Small screens adjustments */
        @media print {
            .no-print {
                display: none;
            }

            .stat-card {
                page-break-inside: avoid;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }

        /* Grafik Table Styles */
        .chart-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9px;
        }

        .chart-table th {
            background-color: #2c3e50;
            color: white;
            padding: 6px;
            text-align: center;
            border: 1px solid #34495e;
        }

        .chart-table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }

        .chart-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        /* Mini Chart Bars */
        .mini-bar {
            height: 15px;
            background-color: #e0e0e0;
            border-radius: 3px;
            margin: 3px 0;
            overflow: hidden;
            position: relative;
        }

        .mini-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #3498db, #2980b9);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        /* Data Highlight */
        .data-highlight {
            background-color: #e8f4fc;
            border-left: 3px solid #3498db;
            padding: 8px;
            margin: 5px 0;
            border-radius: 3px;
        }

        .highlight-title {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 3px;
            font-size: 10px;
        }

        .highlight-value {
            font-size: 11px;
            color: #2980b9;
            font-weight: bold;
        }

        /* Icons in PDF */
        .pdf-icon {
            font-size: 10px;
            margin-right: 3px;
        }

        /* Chart Score Card */
        .score-card {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            margin: 2px;
        }

        .score-excellent {
            background-color: #27ae60;
            color: white;
        }

        .score-good {
            background-color: #2ecc71;
            color: white;
        }

        .score-average {
            background-color: #f39c12;
            color: white;
        }

        .score-poor {
            background-color: #e74c3c;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="title">{{ $title }}</div>
        <div class="subtitle">{{ $subtitle }}</div>
        <div class="report-info">
            Periode: <strong>{{ $period }}</strong> |
            Kategori: {{ $category_filter }} |
            Dicetak: {{ $generated_at }}

            @if ($is_date_filtered)
                <br>
                <span style="color: #e74c3c; font-weight: bold;">
                    <i class="bi bi-calendar-check"></i> Data difilter berdasarkan tanggal: {{ $filter_date_range }}
                </span>
            @endif
        </div>
    </div>

    <!-- Jika tidak ada data untuk periode tersebut -->
    @if (!$has_data_for_period && $is_date_filtered)
        <div class="alert alert-warning"
            style="background-color: #fff3cd; border: 2px solid #ffc107; margin-bottom: 20px;">
            <div style="font-weight: bold; font-size: 14px; margin-bottom: 8px;">
                <i class="bi bi-exclamation-triangle"></i> PERHATIAN: DATA TIDAK DITEMUKAN
            </div>
            <p>
                <strong>Tidak ada data kuesioner</strong> untuk periode
                <strong>{{ $filter_date_range }}</strong>.
            </p>
            <p class="mb-0">
                <i class="bi bi-lightbulb"></i> <strong>Saran:</strong>
                Coba periksa rentang tanggal atau gunakan periode yang lebih luas.
            </p>
        </div>
    @endif

    <!-- Filter Info -->
    @if ($filters['has_filters'])
        <div class="filters-box">
            <div class="text-bold mb-1">Filter yang diterapkan:</div>
            @if ($filters['category_id'])
                <div class="filter-item">• Kategori: {{ $category_filter }}</div>
            @endif
            @if ($filters['start_date'])
                <div class="filter-item">• Tanggal Mulai: {{ $filters['start_date'] }}</div>
            @endif
            @if ($filters['end_date'])
                <div class="filter-item">• Tanggal Akhir: {{ $filters['end_date'] }}</div>
            @endif
        </div>
    @endif

    <!-- Ringkasan Statistik -->
    <div class="section">
        <div class="section-title">Ringkasan Statistik</div>

        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-value">{{ number_format($total_alumni) }}</div>
                <div class="stat-label">Total Alumni</div>
            </div>

            <div class="stat-card">
                <div class="stat-value">{{ number_format($alumni_with_answers) }}</div>
                <div class="stat-label">Responden</div>
            </div>

            <div class="stat-card">
                <div class="stat-value">{{ $response_rate }}%</div>
                <div class="stat-label">Response Rate</div>
            </div>

            <div class="stat-card">
                <div class="stat-value">{{ number_format($total_answers) }}</div>
                <div class="stat-label">Total Jawaban</div>
            </div>

            <div class="stat-card">
                <div class="stat-value">{{ number_format($total_questions) }}</div>
                <div class="stat-label">Total Pertanyaan</div>
            </div>
        </div>

        <div class="conclusion-box">
            <div class="conclusion-title">Analisis Singkat:</div>
            <p>
                Dari {{ number_format($total_alumni) }} alumni, sebanyak {{ number_format($alumni_with_answers) }}
                alumni
                ({{ $response_rate }}%) telah berpartisipasi dalam pengisian kuesioner.
                Total {{ number_format($total_answers) }} jawaban telah terkumpul dari
                {{ number_format($total_questions) }} pertanyaan yang tersedia.
            </p>
        </div>
    </div>

    <!-- Ringkasan Grafik -->
    <div class="section">
        <div class="section-title">Ringkasan Data Grafik</div>

        <!-- Detail Semua Grafik Tracer Study -->
        <div class="section page-break">
            <div class="section-title">Analisis Lengkap Tracer Study</div>

            <!-- Grafik 1: Status Lulusan -->
            @if (isset($chart_data['graduate_status']))
                <div class="chart-summary mb-3">
                    <div class="chart-item">
                        <div class="chart-title">1. Status Lulusan Saat Ini</div>
                        <div class="chart-data">
                            @foreach ($chart_data['graduate_status']['labels'] as $index => $label)
                                <div class="data-item">
                                    <span>{{ $label }}</span>
                                    <span
                                        class="text-bold">{{ $chart_data['graduate_status']['values'][$index] ?? 0 }}%</span>
                                </div>
                            @endforeach
                        </div>
                        @if (isset($chart_data['graduate_status']['conclusion']))
                            <div class="mt-2 text-muted" style="font-size: 9px;">
                                📊 {{ $chart_data['graduate_status']['conclusion'] }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Grafik 2: Waktu Tunggu -->
            @if (isset($chart_data['waiting_time']))
                <div class="chart-summary mb-3">
                    <div class="chart-item">
                        <div class="chart-title">2. Waktu Tunggu Mendapat Pekerjaan</div>
                        <div class="chart-data">
                            @foreach ($chart_data['waiting_time']['labels'] as $index => $label)
                                <div class="data-item">
                                    <span>{{ $label }}</span>
                                    <span
                                        class="text-bold">{{ $chart_data['waiting_time']['values'][$index] ?? 0 }}%</span>
                                </div>
                            @endforeach
                        </div>
                        @if (isset($chart_data['waiting_time']['conclusion']))
                            <div class="mt-2 text-muted" style="font-size: 9px;">
                                ⏱️ {{ $chart_data['waiting_time']['conclusion'] }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Grafik 3: Hubungan Studi-Pekerjaan -->
            @if (isset($chart_data['study_work_relevance']))
                <div class="chart-summary mb-3">
                    <div class="chart-item">
                        <div class="chart-title">3. Hubungan Bidang Studi dengan Pekerjaan</div>
                        <div class="chart-data">
                            @foreach ($chart_data['study_work_relevance']['labels'] as $index => $label)
                                <div class="data-item">
                                    <span>{{ $label }}</span>
                                    <span
                                        class="text-bold">{{ $chart_data['study_work_relevance']['values'][$index] ?? 0 }}%</span>
                                </div>
                            @endforeach
                        </div>
                        @if (isset($chart_data['study_work_relevance']['conclusion']))
                            <div class="mt-2 text-muted" style="font-size: 9px;">
                                🔗 {{ $chart_data['study_work_relevance']['conclusion'] }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Grafik 4: Tingkat Tempat Kerja -->
            @if (isset($chart_data['work_level']))
                <div class="chart-summary mb-3">
                    <div class="chart-item">
                        <div class="chart-title">4. Tingkat Tempat Kerja</div>
                        <div class="chart-data">
                            @foreach ($chart_data['work_level']['labels'] as $index => $label)
                                <div class="data-item">
                                    <span>{{ $label }}</span>
                                    <span
                                        class="text-bold">{{ $chart_data['work_level']['values'][$index] ?? 0 }}%</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Grafik 5: Kisaran Gaji -->
            @if (isset($chart_data['salary_range']))
                <div class="chart-summary mb-3">
                    <div class="chart-item">
                        <div class="chart-title">5. Kisaran Gaji Lulusan</div>
                        <div class="chart-data">
                            @foreach ($chart_data['salary_range']['labels'] as $index => $label)
                                <div class="data-item">
                                    <span>{{ $label }}</span>
                                    <span
                                        class="text-bold">{{ $chart_data['salary_range']['values'][$index] ?? 0 }}%</span>
                                </div>
                            @endforeach
                        </div>
                        @if (isset($chart_data['salary_range']['conclusion']))
                            <div class="mt-2 text-muted" style="font-size: 9px;">
                                💰 {{ $chart_data['salary_range']['conclusion'] }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Grafik 6-8: Metode Pembelajaran, Kompetensi, Sumber Biaya -->
        <div class="section page-break">
            <div class="section-title">Analisis Lanjutan</div>

            <!-- Grafik 6: Metode Pembelajaran -->
            @if (isset($chart_data['learning_methods']) && isset($chart_data['learning_methods']['methods']))
                <div class="mb-3">
                    <div class="chart-title mb-2">6. Analisis Metode Pembelajaran</div>
                    <div class="table-responsive">
                        <table style="font-size: 9px;">
                            <thead>
                                <tr>
                                    <th>Metode Pembelajaran</th>
                                    @if (isset($chart_data['learning_methods']['scales']))
                                        @foreach ($chart_data['learning_methods']['scales'] as $scale)
                                            <th class="text-center">Skala {{ $scale }}</th>
                                        @endforeach
                                    @else
                                        <th class="text-center">Skala 1</th>
                                        <th class="text-center">Skala 2</th>
                                        <th class="text-center">Skala 3</th>
                                        <th class="text-center">Skala 4</th>
                                        <th class="text-center">Skala 5</th>
                                    @endif
                                    <th class="text-center">Rata²</th>
                                    <th class="text-center">Tinggi (4-5)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($chart_data['learning_methods']['methods'] as $method)
                                    @php
                                        $values = $method['values'];
                                        $total = array_sum($values);
                                        $average =
                                            $total > 0
                                                ? array_sum(
                                                        array_map(
                                                            function ($v, $i) {
                                                                return $v * ($i + 1);
                                                            },
                                                            $values,
                                                            array_keys($values),
                                                        ),
                                                    ) / $total
                                                : 0;
                                        $highPercentage =
                                            $total > 0 ? ((($values[3] ?? 0) + ($values[4] ?? 0)) / $total) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $method['name'] }}</td>
                                        @foreach ($values as $value)
                                            <td class="text-center">{{ number_format($value, 1) }}%</td>
                                        @endforeach
                                        <td class="text-center">{{ number_format($average, 2) }}</td>
                                        <td class="text-center">{{ number_format($highPercentage, 1) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-muted" style="font-size: 9px;">
                        📚 Skala 1-5: 1=Sangat Kecil, 5=Sangat Besar
                    </div>
                </div>
            @endif

            <!-- Grafik 7: Kompetensi Alumni -->
            @if (isset($chart_data['competence']) && isset($chart_data['competence']['competencies']))
                <div class="mb-3">
                    <div class="chart-title mb-2">7. Tingkat Kompetensi Alumni</div>
                    <div class="table-responsive">
                        <table style="font-size: 9px;">
                            <thead>
                                <tr>
                                    <th>Kompetensi</th>
                                    @if (isset($chart_data['competence']['scales']))
                                        @foreach ($chart_data['competence']['scales'] as $scale)
                                            <th class="text-center">Skala {{ $scale }}</th>
                                        @endforeach
                                    @else
                                        <th class="text-center">Skala 1</th>
                                        <th class="text-center">Skala 2</th>
                                        <th class="text-center">Skala 3</th>
                                        <th class="text-center">Skala 4</th>
                                        <th class="text-center">Skala 5</th>
                                    @endif
                                    <th class="text-center">Rata²</th>
                                    <th class="text-center">Tinggi (4-5)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($chart_data['competence']['competencies'] as $competence => $values)
                                    @php
                                        $total = array_sum($values);
                                        $average =
                                            $total > 0
                                                ? array_sum(
                                                        array_map(
                                                            function ($v, $i) {
                                                                return $v * ($i + 1);
                                                            },
                                                            $values,
                                                            array_keys($values),
                                                        ),
                                                    ) / $total
                                                : 0;
                                        $highPercentage =
                                            $total > 0 ? ((($values[3] ?? 0) + ($values[4] ?? 0)) / $total) * 100 : 0;
                                    @endphp
                                    <tr>
                                        <td>{{ strlen($competence) > 25 ? substr($competence, 0, 25) . '...' : $competence }}
                                        </td>
                                        @foreach ($values as $value)
                                            <td class="text-center">{{ number_format($value, 1) }}%</td>
                                        @endforeach
                                        <td class="text-center">{{ number_format($average, 2) }}</td>
                                        <td class="text-center">{{ number_format($highPercentage, 1) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2 text-muted" style="font-size: 9px;">
                        🎯 Skala 1-5: 1=Sangat Rendah, 5=Sangat Tinggi
                    </div>
                </div>
            @endif

            <!-- Grafik 8: Sumber Biaya Kuliah -->
            @if (isset($chart_data['funding_source']))
                <div>
                    <div class="chart-title mb-2">8. Sumber Biaya Kuliah</div>
                    <div class="chart-data">
                        @foreach ($chart_data['funding_source']['labels'] as $index => $label)
                            <div class="data-item">
                                <span>{{ $label }}</span>
                                <span
                                    class="text-bold">{{ $chart_data['funding_source']['values'][$index] ?? 0 }}%</span>
                            </div>
                        @endforeach
                    </div>
                    @if (isset($chart_data['funding_source']['dominant']))
                        <div class="mt-2 text-muted" style="font-size: 9px;">
                            💸 {{ $chart_data['funding_source']['dominant'] }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Ringkasan Statistik Lanjutan -->
        <div class="section">
            <div class="section-title">Ringkasan Statistik Lanjutan</div>

            <div class="stats-container">
                @if (isset($chart_data['summary']))
                    <div class="stat-card">
                        <div class="stat-value">{{ $chart_data['summary']['total_categories'] ?? 0 }}</div>
                        <div class="stat-label">Total Kategori</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-value">{{ $chart_data['summary']['total_questionnaires'] ?? 0 }}</div>
                        <div class="stat-label">Kuesioner</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-value">{{ $chart_data['summary']['total_questions'] ?? 0 }}</div>
                        <div class="stat-label">Pertanyaan</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-value">{{ $chart_data['summary']['total_alumni_respondents'] ?? 0 }}</div>
                        <div class="stat-label">Alumni Responden</div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-value">{{ $chart_data['summary']['avg_completion_rate'] ?? 0 }}%</div>
                        <div class="stat-label">Rata² Penyelesaian</div>
                    </div>
                @endif
            </div>

            @if (isset($chart_data['summary']['most_active_category']))
                <div class="conclusion-box">
                    <div class="conclusion-title">Kategori Paling Aktif:</div>
                    <p>
                        <strong>{{ $chart_data['summary']['most_active_category']['name'] ?? '-' }}</strong>
                        dengan {{ $chart_data['summary']['most_active_category']['count'] ?? 0 }} alumni responden.
                        Tanggal respons terakhir: {{ $chart_data['summary']['latest_response'] ?? '-' }}
                    </p>
                </div>
            @endif
        </div>

        <div class="chart-summary">
            <!-- Status Lulusan -->
            @if (isset($chart_data['graduate_status']))
                <div class="chart-item">
                    <div class="chart-title">Status Lulusan Saat Ini</div>
                    <div class="chart-data">
                        @foreach ($chart_data['graduate_status']['labels'] as $index => $label)
                            <div class="data-item">
                                <span>{{ $label }}</span>
                                <span
                                    class="text-bold">{{ $chart_data['graduate_status']['values'][$index] ?? 0 }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Hubungan Studi-Pekerjaan -->
            @if (isset($chart_data['study_work_relevance']))
                <div class="chart-item">
                    <div class="chart-title">Hubungan Studi dengan Pekerjaan</div>
                    <div class="chart-data">
                        @foreach ($chart_data['study_work_relevance']['labels'] as $index => $label)
                            <div class="data-item">
                                <span>{{ $label }}</span>
                                <span
                                    class="text-bold">{{ $chart_data['study_work_relevance']['values'][$index] ?? 0 }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Kisaran Gaji -->
            @if (isset($chart_data['salary_range']))
                <div class="chart-item">
                    <div class="chart-title">Kisaran Gaji Lulusan</div>
                    <div class="chart-data">
                        @foreach ($chart_data['salary_range']['labels'] as $index => $label)
                            <div class="data-item">
                                <span>{{ $label }}</span>
                                <span
                                    class="text-bold">{{ $chart_data['salary_range']['values'][$index] ?? 0 }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Data Kategori -->
    <div class="section">
        <div class="section-title">Data per Kategori</div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="30%">Nama Kategori</th>
                        <th width="15%">Jumlah Kuesioner</th>
                        <th width="20%">Alumni yang Memilih</th>
                        <th width="15%">Status</th>
                        <th width="15%">Urutan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $index => $category)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $category->name }}</td>
                            <td class="text-center">{{ $category->questionnaires_count }}</td>
                            <td class="text-center">{{ $category->alumni_statuses_count }}</td>
                            <td class="text-center">
                                <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-warning' }}">
                                    {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="text-center">{{ $category->order }}</td>
                        </tr>
                    @endforeach
                    @if ($categories->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Belum ada data kategori
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pertanyaan Paling Sering Dijawab -->
    <div class="section page-break">
        <div class="section-title">10 Pertanyaan Paling Sering Dijawab</div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="45%">Pertanyaan</th>
                        <th width="15%">Tipe</th>
                        <th width="15%">Kategori</th>
                        <th width="10%">Jawaban</th>
                        <th width="10%">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($top_questions as $index => $question)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $question['text'] }}</td>
                            <td class="text-center">
                                <span class="badge badge-info">{{ $question['type'] }}</span>
                            </td>
                            <td>{{ $question['category'] }}</td>
                            <td class="text-center">{{ $question['answers_count'] }}</td>
                            <td class="text-center">
                                {{ $total_alumni > 0 ? round(($question['answers_count'] / $total_alumni) * 100, 1) : 0 }}%
                            </td>
                        </tr>
                    @endforeach
                    @if ($top_questions->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Belum ada data pertanyaan
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top 10 Alumni -->
    <div class="section">
        <div class="section-title">Top 10 Alumni Berdasarkan Partisipasi</div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th width="5%">Rank</th>
                        <th width="25%">Nama Alumni</th>
                        <th width="15%">NIM</th>
                        <th width="20%">Program Studi</th>
                        <th width="15%">Jumlah Jawaban</th>
                        <th width="10%">Points</th>
                        <th width="10%">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($top_alumni as $index => $alumni)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $alumni['name'] }}</td>
                            <td>{{ $alumni['nim'] ?? '-' }}</td>
                            <td>{{ $alumni['study_program'] ?? '-' }}</td>
                            <td class="text-center">{{ $alumni['total_answers'] }}</td>
                            <td class="text-center">{{ $alumni['total_points'] ?? 0 }}</td>
                            <td class="text-center">{{ $alumni['completion_rate'] }}%</td>
                        </tr>
                    @endforeach
                    @if ($top_alumni->isEmpty())
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada data alumni
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Kesimpulan dan Rekomendasi -->
    <div class="section">
        <div class="section-title">Kesimpulan dan Rekomendasi</div>

        <div class="conclusion-box">
            <div class="conclusion-title">KESIMPULAN:</div>
            <ol>
                <li><strong>Tingkat Response:</strong> {{ $response_rate }}% alumni telah berpartisipasi dalam
                    kuesioner.</li>
                <li><strong>Distribusi Kategori:</strong> Kategori paling populer adalah
                    {{ $categories->first()->name ?? '-' }}.</li>
                <li><strong>Partisipasi Aktif:</strong> {{ $top_alumni->count() }} alumni sangat aktif dengan rata-rata
                    {{ $top_alumni->avg('total_answers') ?? 0 }} jawaban per alumni.</li>
                <li><strong>Data Keaktifan:</strong> {{ $total_answers }} jawaban terkumpul dari
                    {{ $total_questions }} pertanyaan.</li>
            </ol>
        </div>

        {{-- <div class="conclusion-box">
            <div class="conclusion-title">REKOMENDASI:</div>
            <ol>
                <li><strong>Tingkatkan Response Rate:</strong> Lakukan follow-up ke alumni yang belum mengisi kuesioner.
                </li>
                <li><strong>Perbaiki Kuesioner:</strong> Analisis pertanyaan dengan response rate rendah untuk
                    perbaikan.</li>
                <li><strong>Pengembangan Kategori:</strong> Kembangkan kategori berdasarkan kebutuhan pasar kerja
                    terkini.</li>
                <li><strong>Reward System:</strong> Pertimbangkan sistem reward untuk alumni yang aktif berpartisipasi.
                </li>
                <li><strong>Update Berkala:</strong> Lakukan update kuesioner setiap semester untuk data yang relevan.
                </li>
            </ol>
        </div> --}}
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="text-center">
            <strong>SISTEM TRACER STUDY</strong><br>
            Laporan ini dihasilkan secara otomatis<br>
            © {{ date('Y') }} - Universitas Ahmad Dahlan
        </div>
        <div class="page-number">
            Halaman <span class="page"></span> dari <span class="topage"></span>
        </div>
    </div>

    <script type="text/javascript">
        // Script untuk nomor halaman (bekerja di DomPDF)
        var vars = {};
        var x = document.location.search.substring(1).split('&');
        for (var i in x) {
            var z = x[i].split('=', 2);
            vars[z[0]] = unescape(z[1]);
        }

        var page = vars.page || 1;
        var total = vars.topage || 1;

        document.querySelector('.page').textContent = page;
        document.querySelector('.topage').textContent = total;
    </script>
</body>

</html>
