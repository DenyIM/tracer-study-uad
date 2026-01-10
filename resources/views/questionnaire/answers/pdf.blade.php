{{-- resources/views/questionnaire/answers/pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Kuesioner Tracer Study - {{ $category->name }}</title>
    <style>
        /* PDF Styles */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #003366;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #003366;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header h2 {
            color: #003366;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .alumni-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
        }

        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section-header {
            background-color: #003366;
            color: white;
            padding: 10px;
            border-radius: 5px 5px 0 0;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .question-item {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .question-number {
            background-color: #003366;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: inline-block;
            text-align: center;
            line-height: 25px;
            margin-right: 10px;
            font-weight: bold;
        }

        .question-text {
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        .answer-text {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            border-left: 4px solid #fab300;
        }

        .competency-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .competency-table th {
            background-color: #f8f9fa;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
            font-weight: bold;
        }

        .competency-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .answer-badge {
            display: inline-block;
            padding: 4px 10px;
            background-color: #e7f5ff;
            border: 1px solid #a5d8ff;
            border-radius: 15px;
            font-size: 11px;
            margin-right: 5px;
            margin-bottom: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 50px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .page-break {
            page-break-before: always;
        }

        .timestamp {
            text-align: right;
            font-size: 10px;
            color: #666;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>UNIVERSITAS AHMAD DAHLAN</h1>
        <h2>TRACER STUDY - HASIL KUESIONER</h2>
        <h3>Kategori: {{ $category->name }}</h3>
    </div>

    <div class="timestamp">
        Dicetak pada: {{ now()->format('d F Y H:i') }}
    </div>

    <div class="alumni-info">
        <table width="100%">
            <tr>
                <td width="50%"><strong>Nama Alumni:</strong> {{ $alumni->fullname }}</td>
                <td><strong>Program Studi:</strong> {{ $alumni->study_program }}</td>
            </tr>
            <tr>
                <td><strong>Tahun Lulus:</strong> {{ optional($alumni->graduation_date)->format('Y') }}</td>
                <td><strong>Email:</strong> {{ $alumni->email }}</td>
            </tr>
        </table>
    </div>

    @foreach ($questionnaires as $questionnaire)
        <div class="section @if (!$loop->first) page-break @endif">
            <div class="section-header">
                {{ $questionnaire->name }}
            </div>

            @php
                $questionnaireQuestions = $questionnaire->questions()->orderBy('order')->get();
                $questionNumber = 1;
            @endphp

            @foreach ($questionnaireQuestions as $question)
                @php
                    $answer = $answers[$question->id] ?? null;
                    if (!$answer || $answer->is_skipped) {
                        continue;
                    }
                @endphp

                <div class="question-item">
                    <div class="question-text">
                        <span class="question-number">{{ $questionNumber++ }}</span>
                        {{ $question->question_text }}
                    </div>

                    <div class="answer-content">
                        @if ($question->question_type === 'likert_scale' || $question->question_type === 'competency_scale')
                            @php
                                $scaleValue = $answer->scale_value ?? 0;
                                $scaleLabels = [
                                    1 => 'Sangat Rendah',
                                    2 => 'Rendah',
                                    3 => 'Cukup',
                                    4 => 'Tinggi',
                                    5 => 'Sangat Tinggi',
                                ];
                            @endphp
                            <div class="answer-text">
                                Skala: <strong>{{ $scaleValue }}</strong> -
                                {{ $scaleLabels[$scaleValue] ?? 'Tidak diisi' }}
                            </div>
                        @elseif($question->question_type === 'checkbox')
                            <div class="answer-text">
                                @if (is_array($answer->selected_options))
                                    @foreach ($answer->selected_options as $option)
                                        <span class="answer-badge">{{ $option }}</span>
                                    @endforeach
                                @else
                                    {{ $answer->selected_options ?? 'Tidak diisi' }}
                                @endif
                            </div>
                        @elseif($question->question_type === 'radio')
                            <div class="answer-text">
                                {{ $answer->answer ?? 'Tidak diisi' }}
                            </div>
                        @elseif($question->question_type === 'textarea')
                            <div class="answer-text">
                                {{ $answer->answer ?? 'Tidak diisi' }}
                            </div>
                        @elseif($question->question_type === 'competency_grid')
                            @php
                                $rowItems = json_decode($question->row_items, true) ?? [];
                                $scaleOptions = json_decode($question->scale_options_with_labels, true) ?? [];
                            @endphp

                            <table class="competency-table">
                                <thead>
                                    <tr>
                                        <th width="60%">Kompetensi</th>
                                        <th width="20%">Skala</th>
                                        <th width="20%">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rowItems as $index => $row)
                                        @php
                                            $rowAnswer = $answer->selected_options[$index] ?? null;
                                            $scaleLabel = $scaleOptions[$rowAnswer] ?? 'Tidak diisi';
                                        @endphp
                                        <tr>
                                            <td>{{ $row['text'] ?? 'Kompetensi' }}</td>
                                            <td align="center">{{ $rowAnswer ?? '-' }}</td>
                                            <td>{{ $scaleLabel }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="answer-text">
                                {{ $answer->answer ?? 'Tidak diisi' }}
                            </div>
                        @endif
                    </div>

                    @if ($answer->answered_at)
                        <div style="font-size: 10px; color: #666; margin-top: 5px;">
                            Diisi: {{ $answer->answered_at->format('d/m/Y H:i') }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari Sistem Tracer Study UAD</p>
        <p>Universitas Ahmad Dahlan &copy; {{ date('Y') }}</p>
    </div>
</body>

</html>
