<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Kuesioner Tracer Study - {{ $category->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
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
            border: 1px solid #dee2e6;
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
            font-size: 14px;
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
            font-size: 11px;
        }

        .question-text {
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
            font-size: 13px;
        }

        .answer-text {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            border-left: 4px solid #fab300;
            margin-top: 5px;
            font-size: 12px;
        }

        .scale-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        .scale-table th {
            background-color: #e9ecef;
            text-align: left;
            padding: 8px;
            border: 1px solid #dee2e6;
            font-weight: bold;
        }

        .scale-table td {
            padding: 8px;
            border: 1px solid #dee2e6;
        }

        .scale-value {
            text-align: center;
            font-weight: bold;
        }

        .scale-label {
            font-size: 10px;
            color: #6c757d;
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

        .answer-badge.checkbox {
            background-color: #d1e7dd;
            border-color: #badbcc;
            color: #0f5132;
        }

        .answer-badge.email {
            background-color: #fff3cd;
            border-color: #ffecb5;
            color: #856404;
        }

        .email-display {
            margin-top: 5px;
            padding: 5px 10px;
            background-color: #f8f9fa;
            border-radius: 4px;
            border-left: 3px solid #007bff;
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

        .no-answer {
            color: #6c757d;
            font-style: italic;
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
                @if ($questionnaire->description)
                    <div style="font-size: 11px; font-weight: normal;">{{ $questionnaire->description }}</div>
                @endif
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

                    // Tentukan skala label berdasarkan pertanyaan
                    $isFirstQuestion = $questionnaire->is_general && $question->order == 1;
                    $isSecondQuestion = $questionnaire->is_general && $question->order == 2;

                    if ($isSecondQuestion) {
                        // Skala untuk pertanyaan 2: Metode Pembelajaran
                        $scaleLabels = [
                            1 => 'Tidak Sama Sekali',
                            2 => 'Kurang',
                            3 => 'Cukup',
                            4 => 'Besar',
                            5 => 'Sangat Besar',
                        ];
                    } else {
                        // Skala default untuk pertanyaan lain
                        $scaleLabels = [
                            1 => 'Sangat Rendah',
                            2 => 'Rendah',
                            3 => 'Cukup',
                            4 => 'Tinggi',
                            5 => 'Sangat Tinggi',
                        ];
                    }
                @endphp

                <div class="question-item">
                    <div class="question-text">
                        <span class="question-number">{{ $questionNumber++ }}</span>
                        {{ $question->question_text }}
                    </div>

                    <div class="answer-content">
                        @if ($question->question_type === 'likert_scale' || $question->question_type === 'competency_scale')
                            @if ($answer->scale_value)
                                <div class="answer-text">
                                    <table class="scale-table">
                                        <tr>
                                            <th width="50%">Skala Nilai</th>
                                            <th width="50%">Keterangan</th>
                                        </tr>
                                        <tr>
                                            <td class="scale-value">{{ $answer->scale_value }}</td>
                                            <td>{{ $scaleLabels[$answer->scale_value] ?? 'Tidak tersedia' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            @else
                                <div class="answer-text no-answer">Tidak diisi</div>
                            @endif
                        @elseif($question->question_type === 'checkbox' || $question->question_type === 'checkbox_per_row')
                            @if ($answer->selected_options)
                                <div class="answer-text">
                                    @php
                                        // Handle both array and JSON string
                                        $selectedOptions = $answer->selected_options;
                                        if (is_string($selectedOptions)) {
                                            $selectedOptions = json_decode($selectedOptions, true) ?? [];
                                        }

                                        if (!is_array($selectedOptions)) {
                                            $selectedOptions = [$selectedOptions];
                                        }
                                    @endphp
                                    @foreach ($selectedOptions as $option)
                                        @if (strpos($option, 'email') !== false || strpos($option, 'Ya,') !== false)
                                            @php
                                                $parts = explode(':', $option, 2);
                                                $mainText = trim($parts[0]);
                                                $emailValue = isset($parts[1]) ? trim($parts[1]) : '';
                                            @endphp
                                            <div class="answer-badge email">{{ $mainText }}</div>
                                            @if ($emailValue)
                                                <div class="email-display">
                                                    <strong>Email:</strong> {{ $emailValue }}
                                                </div>
                                            @endif
                                        @else
                                            <div class="answer-badge checkbox">{{ $option }}</div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="answer-text no-answer">Tidak diisi</div>
                            @endif
                        @elseif($question->question_type === 'radio' || $question->question_type === 'radio_per_row')
                            @if ($answer->answer)
                                <div class="answer-text">
                                    @if (strpos($answer->answer, 'email') !== false || strpos($answer->answer, 'Ya,') !== false)
                                        @php
                                            $parts = explode(':', $answer->answer, 2);
                                            $mainText = trim($parts[0]);
                                            $emailValue = isset($parts[1]) ? trim($parts[1]) : '';
                                        @endphp
                                        <div class="answer-badge email">{{ $mainText }}</div>
                                        @if ($emailValue)
                                            <div class="email-display">
                                                <strong>Email:</strong> {{ $emailValue }}
                                            </div>
                                        @endif
                                    @else
                                        {{ $answer->answer }}
                                    @endif
                                </div>
                            @else
                                <div class="answer-text no-answer">Tidak diisi</div>
                            @endif
                        @elseif($question->question_type === 'likert_per_row')
                            @php
                                // Handle both array and JSON string for answer
                                $answerValues = $answer->answer;
                                if (is_string($answerValues)) {
                                    $answerValues = json_decode($answerValues, true) ?? [];
                                }

                                // Handle row items
                                $rowItems = $question->row_items;
                                if (is_string($rowItems)) {
                                    $rowItems = json_decode($rowItems, true) ?? [];
                                }

                                if (!is_array($rowItems)) {
                                    $rowItems = [];
                                }
                            @endphp

                            @if ($answerValues && count($answerValues) > 0)
                                <div class="answer-text">
                                    <table class="scale-table">
                                        <tr>
                                            <th width="60%">
                                                @if ($isFirstQuestion)
                                                    Kompetensi
                                                @elseif($isSecondQuestion)
                                                    Metode Pembelajaran
                                                @else
                                                    Item
                                                @endif
                                            </th>
                                            <th width="20%">Skala</th>
                                            <th width="20%">Keterangan</th>
                                        </tr>
                                        @foreach ($rowItems as $key => $item)
                                            @if (isset($answerValues[$key]))
                                                <tr>
                                                    <td>{{ $item['text'] ?? $item }}</td>
                                                    <td class="scale-value">{{ $answerValues[$key] }}</td>
                                                    <td class="scale-label">
                                                        {{ $scaleLabels[$answerValues[$key]] ?? 'Tidak tersedia' }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                </div>
                            @else
                                <div class="answer-text no-answer">Tidak diisi</div>
                            @endif
                        @elseif($question->question_type === 'textarea')
                            @if ($answer->answer)
                                <div class="answer-text" style="white-space: pre-line;">{{ $answer->answer }}</div>
                            @else
                                <div class="answer-text no-answer">Tidak diisi</div>
                            @endif
                        @else
                            @if ($answer->answer)
                                <div class="answer-text">{{ $answer->answer }}</div>
                            @else
                                <div class="answer-text no-answer">Tidak diisi</div>
                            @endif
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
