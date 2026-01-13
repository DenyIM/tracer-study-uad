{{-- resources/views/admin/views/questionnaire/export-complete-answers.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - {{ date('d F Y') }}</title>
    <style>
        /* CSS untuk PDF LENGKAP */
        @page {
            margin: 15px;
            size: {{ $format === 'summary' ? 'A4 portrait' : 'A4 landscape' }};
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: {{ $format === 'summary' ? '9px' : '10px' }};
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* Header */
        .header {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2c3e50;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 3px;
        }

        .subtitle {
            font-size: 12px;
            color: #7f8c8d;
            margin-bottom: 5px;
        }

        .report-info {
            font-size: 9px;
            color: #95a5a6;
        }

        /* Filters Box */
        .filters-box {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 3px;
            padding: 8px;
            margin-bottom: 10px;
            font-size: 9px;
        }

        .filter-item {
            display: inline-block;
            margin-right: 15px;
        }

        /* Stats Summary */
        .stats-summary {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }

        .stat-item {
            flex: 1;
            min-width: 120px;
            background-color: #f1f8ff;
            border: 1px solid #d1e3ff;
            border-radius: 4px;
            padding: 6px;
            text-align: center;
        }

        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #2980b9;
        }

        .stat-label {
            font-size: 8px;
            color: #7f8c8d;
            text-transform: uppercase;
        }

        /* DETAILED VIEW - Per Alumni */
        .alumni-section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .alumni-header {
            background-color: #2c3e50;
            color: white;
            padding: 6px 10px;
            border-radius: 3px 3px 0 0;
            margin-bottom: 0;
        }

        .alumni-info {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-top: none;
            padding: 8px;
            font-size: 9px;
        }

        .info-item {
            display: inline-block;
            margin-right: 15px;
        }

        .info-label {
            font-weight: bold;
            color: #2c3e50;
        }

        /* Answers Table */
        .answers-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 9px;
        }

        .answers-table th {
            background-color: #34495e;
            color: white;
            border: 1px solid #2c3e50;
            padding: 5px;
            text-align: left;
            font-weight: bold;
        }

        .answers-table td {
            border: 1px solid #ddd;
            padding: 5px;
            vertical-align: top;
        }

        .question-cell {
            width: 30%;
            background-color: #f8f9fa;
        }

        .answer-cell {
            width: 70%;
        }

        .category-badge {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 1px 4px;
            border-radius: 2px;
            font-size: 8px;
            margin-right: 3px;
        }

        /* SUMMARY VIEW - Matrix Table */
        .matrix-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        .matrix-table th {
            background-color: #2c3e50;
            color: white;
            border: 1px solid #2c3e50;
            padding: 4px;
            text-align: center;
            font-weight: bold;
            position: sticky;
            top: 0;
        }

        .matrix-table td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: center;
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .matrix-alumni-info {
            background-color: #f8f9fa;
            text-align: left;
            white-space: nowrap;
        }

        /* Answer formatting */
        .answer-text {
            max-width: 300px;
            word-wrap: break-word;
        }

        .answer-options {
            background-color: #e8f4fc;
            padding: 3px;
            border-radius: 2px;
            margin: 1px 0;
        }

        .answer-scale {
            background-color: #d5f4e6;
            padding: 3px;
            border-radius: 2px;
            display: inline-block;
        }

        .answer-skipped {
            color: #e74c3c;
            font-style: italic;
        }

        /* Page footer */
        .footer {
            margin-top: 20px;
            padding-top: 5px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8px;
            color: #95a5a6;
        }

        .page-number {
            text-align: right;
            font-size: 8px;
            color: #95a5a6;
        }

        /* Utilities */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
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

        .mt-1 {
            margin-top: 5px;
        }

        .mt-2 {
            margin-top: 10px;
        }

        /* Page break handling */
        .page-break {
            page-break-before: always;
        }

        .avoid-break {
            page-break-inside: avoid;
        }

        /* Print adjustments */
        @media print {
            .no-print {
                display: none;
            }

            .answers-table {
                page-break-inside: auto;
            }

            .alumni-section {
                page-break-inside: avoid;
            }

            .matrix-table th {
                position: sticky;
                top: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="title">{{ $title }}</div>
        <div class="subtitle">{{ $subtitle }}</div>
        <div class="report-info">
            Dicetak: {{ $generated_at }} |
            Format: {{ $format === 'detailed' ? 'Detail per Alumni' : 'Ringkasan Matriks' }} |
            Total Data: {{ $summary_stats['total_alumni'] }} alumni, {{ $summary_stats['total_answers'] }} jawaban
        </div>
    </div>

    <!-- Filters Info -->
    @if ($filters['has_filters'])
        <div class="filters-box">
            <span class="text-bold">Filter yang diterapkan:</span>
            @foreach ($filters['filter_info'] as $key => $value)
                <span class="filter-item">• {{ ucfirst($key) }}: {{ $value }}</span>
            @endforeach
            @if ($filters['start_date'])
                <span class="filter-item">• Dari: {{ $filters['start_date'] }}</span>
            @endif
            @if ($filters['end_date'])
                <span class="filter-item">• Sampai: {{ $filters['end_date'] }}</span>
            @endif
        </div>
    @endif

    <!-- Statistics Summary -->
    <div class="stats-summary">
        <div class="stat-item">
            <div class="stat-value">{{ $summary_stats['total_alumni'] }}</div>
            <div class="stat-label">Total Alumni</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $summary_stats['total_answers'] }}</div>
            <div class="stat-label">Total Jawaban</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $summary_stats['total_questions'] }}</div>
            <div class="stat-label">Total Pertanyaan</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $summary_stats['total_points'] }}</div>
            <div class="stat-label">Total Points</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $summary_stats['avg_answers_per_alumni'] }}</div>
            <div class="stat-label">Rata-rata Jawaban</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $summary_stats['completion_rate'] }}%</div>
            <div class="stat-label">Tingkat Penyelesaian</div>
        </div>
    </div>

    @if ($format === 'detailed')
        <!-- DETAILED VIEW: Per Alumni -->
        @foreach ($alumni_data as $alumni_id => $data)
            <div class="alumni-section {{ $loop->iteration % 3 == 0 ? 'page-break' : '' }}">
                <div class="alumni-header">
                    <strong>ALUMNI {{ $loop->iteration }}: {{ $data['info']->fullname }}</strong>
                </div>

                <div class="alumni-info">
                    <span class="info-item">
                        <span class="info-label">NIM:</span> {{ $data['info']->nim ?? '-' }}
                    </span>
                    <span class="info-item">
                        <span class="info-label">Prodi:</span> {{ $data['info']->study_program ?? '-' }}
                    </span>
                    <span class="info-item">
                        <span class="info-label">Lulus:</span>
                        {{ $data['info']->graduation_date ? \Carbon\Carbon::parse($data['info']->graduation_date)->format('Y') : '-' }}
                    </span>
                    <span class="info-item">
                        <span class="info-label">Jawaban:</span> {{ $data['answers_count'] }}
                    </span>
                    <span class="info-item">
                        <span class="info-label">Points:</span> {{ $data['total_points'] }}
                    </span>
                    <span class="info-item">
                        <span class="info-label">Terakhir:</span>
                        {{ $data['last_answer'] ? \Carbon\Carbon::parse($data['last_answer'])->format('d/m/Y H:i') : '-' }}
                    </span>
                </div>

                <!-- Answers Table -->
                <table class="answers-table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Pertanyaan</th>
                            <th width="15%">Kategori & Kuesioner</th>
                            <th width="10%">Tipe</th>
                            <th width="45%">Jawaban</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['answers'] as $answer_index => $answer)
                            @php
                                $question = $answer->question;
                                $questionnaire = $question->questionnaire ?? null;
                                $category = $questionnaire->category ?? null;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $answer_index + 1 }}</td>
                                <td class="question-cell">
                                    <div class="text-bold">{{ $question->question_text }}</div>
                                    @if ($question->description)
                                        <div class="text-muted" style="font-size: 8px;">
                                            {{ \Illuminate\Support\Str::limit($question->description, 50) }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if ($category)
                                        <span class="category-badge">{{ $category->name }}</span>
                                    @endif
                                    @if ($questionnaire)
                                        <div style="font-size: 8px; margin-top: 2px;">
                                            {{ $questionnaire->name }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span style="font-size: 8px;">{{ $question->question_type }}</span>
                                </td>
                                <td class="answer-cell">
                                    @if ($answer->is_skipped)
                                        <span class="answer-skipped">(Pertanyaan dilewati)</span>
                                    @elseif($answer->scale_value !== null)
                                        <div class="answer-scale">
                                            <strong>Skala: {{ $answer->scale_value }}</strong>
                                            @if ($question->scale_label_low && $question->scale_label_high)
                                                <div style="font-size: 8px;">
                                                    {{ $question->scale_label_low }} (1) -
                                                    {{ $question->scale_label_high }} (5)
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($answer->selected_options)
                                        @php
                                            $options = json_decode($answer->selected_options, true);
                                            if (is_array($options) && !empty($options)) {
                                                foreach ($options as $option) {
                                                    echo '<div class="answer-options">• ' . $option . '</div>';
                                                }
                                            }
                                        @endphp
                                    @elseif($answer->answer)
                                        <div class="answer-text">{{ $answer->answer }}</div>
                                    @else
                                        <span class="text-muted">(Tidak ada jawaban)</span>
                                    @endif

                                    <!-- Points and Date -->
                                    <div style="margin-top: 3px; font-size: 8px; color: #7f8c8d;">
                                        @if ($answer->points)
                                            <strong>Points: {{ $answer->points }}</strong> |
                                        @endif
                                        Dijawab:
                                        {{ $answer->answered_at ? \Carbon\Carbon::parse($answer->answered_at)->format('d/m/Y H:i') : '-' }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @else
        <!-- SUMMARY VIEW: Matrix Table -->
        <div class="page-break">
            <div style="margin-bottom: 10px; text-align: center;">
                <strong>MATRIKS JAWABAN ALUMNI</strong><br>
                <small>Total: {{ count($answer_matrix) }} alumni × {{ $questions->count() }} pertanyaan</small>
            </div>

            <table class="matrix-table">
                <thead>
                    <tr>
                        <th rowspan="2" width="5%">No</th>
                        <th rowspan="2" width="10%">NIM</th>
                        <th rowspan="2" width="15%">Nama Alumni</th>
                        <th rowspan="2" width="15%">Program Studi</th>
                        <th colspan="{{ $questions->count() }}" style="text-align: center;">Pertanyaan</th>
                        <th rowspan="2" width="5%">Total</th>
                    </tr>
                    <tr>
                        @foreach ($questions as $question)
                            <th width="{{ 70 / $questions->count() }}%" title="{{ $question->question_text }}">
                                Q{{ $loop->iteration }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($answer_matrix as $row_index => $row)
                        <tr>
                            <td class="text-center">{{ $row_index + 1 }}</td>
                            <td class="matrix-alumni-info">{{ $row['nim'] }}</td>
                            <td class="matrix-alumni-info">{{ \Illuminate\Support\Str::limit($row['nama'], 20) }}</td>
                            <td class="matrix-alumni-info">{{ \Illuminate\Support\Str::limit($row['prodi'], 15) }}</td>

                            @foreach ($questions as $question)
                                <td
                                    title="Pertanyaan: {{ $question->question_text }}
Jawaban: {{ $row['q_' . $question->id] }}
Kategori: {{ $question->questionnaire->category->name ?? '-' }}
Tipe: {{ $question->question_type }}">
                                    {{ $row['q_' . $question->id] }}
                                </td>
                            @endforeach

                            <td class="text-center">
                                @php
                                    $answerCount = 0;
                                    foreach ($questions as $question) {
                                        if ($row['q_' . $question->id] !== '-') {
                                            $answerCount++;
                                        }
                                    }
                                @endphp
                                {{ $answerCount }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-bold" style="text-align: right;">TOTAL JAWABAN:</td>
                        @foreach ($questions as $question)
                            <td class="text-center text-bold">
                                @php
                                    $answeredCount = 0;
                                    foreach ($answer_matrix as $row) {
                                        if ($row['q_' . $question->id] !== '-') {
                                            $answeredCount++;
                                        }
                                    }
                                @endphp
                                {{ $answeredCount }}
                            </td>
                        @endforeach
                        <td class="text-center text-bold">
                            {{ array_sum(array_column($answer_matrix, 'q_' . $question->id !== '-' ? 1 : 0)) }}
                        </td>
                    </tr>
                </tfoot>
            </table>

            <div style="margin-top: 10px; font-size: 8px; color: #7f8c8d;">
                <strong>Keterangan:</strong>
                <ul style="margin: 3px 0; padding-left: 15px;">
                    <li>Q1, Q2, ... = Nomor pertanyaan (lihat judul kolom)</li>
                    <li>Angka dalam sel = Jawaban/skala yang diberikan</li>
                    <li>Teks dalam sel = Jawaban singkat (dipotong jika panjang)</li>
                    <li>- = Tidak ada jawaban / dilewati</li>
                    <li>Hover pada sel untuk melihat detail lengkap</li>
                </ul>
            </div>
        </div>

        <!-- Question Reference -->
        <div class="page-break">
            <div style="margin-bottom: 10px;">
                <strong>REFERENSI PERTANYAAN</strong>
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 9px;">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Kode</th>
                        <th width="60%">Pertanyaan Lengkap</th>
                        <th width="10%">Tipe</th>
                        <th width="10%">Kategori</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($questions as $question)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>Q{{ $loop->iteration }}</td>
                            <td>{{ $question->question_text }}</td>
                            <td class="text-center">{{ $question->question_type }}</td>
                            <td>{{ $question->questionnaire->category->name ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="text-center">
            <strong>LAPORAN LENGKAP JAWABAN ALUMNI</strong><br>
            Sistem Tracer Study - Data per {{ $generated_at }}<br>
            © {{ date('Y') }} - Hak Cipta Dilindungi
        </div>
        <div class="page-number">
            Halaman <span class="page"></span>
        </div>
    </div>

    <script type="text/javascript">
        // Script untuk nomor halaman
        var vars = {};
        var x = document.location.search.substring(1).split('&');
        for (var i in x) {
            var z = x[i].split('=', 2);
            vars[z[0]] = unescape(z[1]);
        }

        var page = vars.page || 1;
        document.querySelector('.page').textContent = page;
    </script>
</body>

</html>
