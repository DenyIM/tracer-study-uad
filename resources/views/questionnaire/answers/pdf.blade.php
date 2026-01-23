<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Kuesioner - {{ $category->name }}</title>
    <style>
        /* Base Styles */
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 15px;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #003366;
        }

        .header h1 {
            color: #003366;
            font-size: 18px;
            margin: 5px 0;
        }

        .header h2 {
            color: #003366;
            font-size: 14px;
            margin: 5px 0;
        }

        /* Metadata */
        .metadata {
            margin-bottom: 20px;
            font-size: 10px;
            color: #666;
        }

        .timestamp {
            text-align: right;
            margin-bottom: 10px;
        }

        .alumni-info {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        /* Section */
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #003366;
            color: white;
            padding: 8px 10px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 3px;
        }

        /* Question */
        .question {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .question-header {
            margin-bottom: 8px;
            display: flex;
            align-items: flex-start;
        }

        .question-number {
            background: #003366;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
            margin-right: 8px;
            flex-shrink: 0;
        }

        .question-text {
            font-weight: bold;
            color: #333;
        }

        /* Answer */
        .answer-box {
            background: #f8f9fa;
            border-left: 3px solid #003366;
            padding: 10px;
            margin-left: 28px;
            border-radius: 0 4px 4px 0;
            word-wrap: break-word;
        }

        /* Checkbox Answers */
        .checkbox-list {
            margin: 0;
            padding-left: 15px;
        }

        .checkbox-item {
            margin-bottom: 5px;
            padding-left: 5px;
        }

        /* Scale Table */
        .scale-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .scale-table th {
            background: #e9ecef;
            padding: 6px;
            border: 1px solid #dee2e6;
            text-align: left;
            font-weight: bold;
        }

        .scale-table td {
            padding: 6px;
            border: 1px solid #dee2e6;
        }

        .scale-value {
            text-align: center;
            font-weight: bold;
            background: #e9ecef;
        }

        /* Text Area */
        .text-answer {
            white-space: pre-wrap;
            word-wrap: break-word;
            max-height: 200px;
            overflow: hidden;
        }

        /* Other Option */
        .other-option {
            margin-top: 8px;
            padding: 6px 8px;
            background: #f0f0f0;
            border-left: 2px solid #6c757d;
            font-size: 10px;
        }

        .other-label {
            font-weight: bold;
            color: #6c757d;
            margin-right: 5px;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 9px;
        }

        /* Page Break */
        .page-break {
            page-break-before: always;
        }

        /* Utilities */
        .mt-2 {
            margin-top: 10px;
        }

        .text-muted {
            color: #6c757d;
            font-size: 9px;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1>UNIVERSITAS AHMAD DAHLAN</h1>
        <h2>HASIL KUESIONER TRACER STUDY</h2>
        <h3>Kategori: {{ $category->name }}</h3>
    </div>

    <!-- Metadata -->
    <div class="metadata">
        <div class="timestamp">
            Dicetak: {{ now()->format('d F Y H:i') }}
        </div>

        <div class="alumni-info">
            <table width="100%">
                <tr>
                    <td width="50%"><strong>Nama:</strong> {{ $alumni->fullname }}</td>
                    <td><strong>Program Studi:</strong> {{ $alumni->study_program ?? '-' }}</td>
                </tr>
                <tr>
                    <td><strong>Tahun Lulus:</strong> {{ optional($alumni->graduation_date)->format('Y') ?? '-' }}</td>
                    <td><strong>Email:</strong> {{ auth()->user()->email ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Questionnaire Sections -->
    @foreach ($questionnaires as $questionnaire)
        @php
            $hasAnswers = false;
            foreach ($questionnaire->questions()->orderBy('order')->get() as $question) {
                $answer = $answers[$question->id] ?? null;
                if ($answer && !$answer->is_skipped) {
                    $hasAnswers = true;
                    break;
                }
            }
        @endphp

        @if ($hasAnswers)
            <div class="section @if (!$loop->first) page-break @endif">
                <div class="section-title">
                    {{ $questionnaire->name }}
                </div>

                @php $questionNumber = 1; @endphp

                @foreach ($questionnaire->questions()->orderBy('order')->get() as $question)
                    @php
                        $answer = $answers[$question->id] ?? null;
                        if (!$answer || $answer->is_skipped) {
                            continue;
                        }

                        // Parse answer data
                        $answerText = $answer->answer ?? '';
                        $selectedOptions = [];

                        // Handle checkbox options
                        if (in_array($question->question_type, ['checkbox', 'checkbox_per_row'])) {
                            if ($answer->selected_options) {
                                if (is_string($answer->selected_options)) {
                                    $selectedOptions = json_decode($answer->selected_options, true) ?? [];
                                } elseif (is_array($answer->selected_options)) {
                                    $selectedOptions = $answer->selected_options;
                                }
                            }

                            // Fallback to answer field
                            if (empty($selectedOptions) && $answer->answer) {
                                if (str_starts_with($answer->answer, '[')) {
                                    $selectedOptions = json_decode($answer->answer, true) ?? [];
                                } else {
                                    $selectedOptions = [$answer->answer];
                                }
                            }
                        }

                        // Handle likert per row
                        $answerValues = [];
                        $rowItems = [];
                        if ($question->question_type === 'likert_per_row') {
                            if ($answer->answer) {
                                if (is_string($answer->answer)) {
                                    $answerValues = json_decode($answer->answer, true) ?? [];
                                } elseif (is_array($answer->answer)) {
                                    $answerValues = $answer->answer;
                                }
                            }

                            if ($question->row_items) {
                                if (is_string($question->row_items)) {
                                    $rowItems = json_decode($question->row_items, true) ?? [];
                                } else {
                                    $rowItems = $question->row_items;
                                }
                            }
                        }
                    @endphp

                    <div class="question">
                        <!-- Question Header -->
                        <div class="question-header">
                            <div class="question-number">{{ $questionNumber++ }}</div>
                            <div class="question-text">{{ $question->question_text }}</div>
                        </div>

                        <!-- Answer Content -->
                        <div class="answer-box">
                            @if (in_array($question->question_type, ['likert_scale', 'competency_scale']))
                                @if ($answer->scale_value)
                                    <table class="scale-table">
                                        <tr>
                                            <th width="50%">Skala</th>
                                            <th width="50%">Keterangan</th>
                                        </tr>
                                        <tr>
                                            <td class="scale-value">{{ $answer->scale_value }}</td>
                                            <td>
                                                @if ($answer->scale_value == 1)
                                                    Sangat Rendah
                                                @elseif($answer->scale_value == 2)
                                                    Rendah
                                                @elseif($answer->scale_value == 3)
                                                    Cukup
                                                @elseif($answer->scale_value == 4)
                                                    Tinggi
                                                @elseif($answer->scale_value == 5)
                                                    Sangat Tinggi
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                @else
                                    <div class="text-muted">Tidak diisi</div>
                                @endif
                            @elseif(in_array($question->question_type, ['checkbox', 'checkbox_per_row']))
                                @if (!empty($selectedOptions))
                                    <ul class="checkbox-list">
                                        @foreach ($selectedOptions as $option)
                                            @php
                                                $displayText = $option;
                                                $additionalText = '';

                                                if (is_string($option) && str_contains($option, ':')) {
                                                    $parts = explode(':', $option, 2);
                                                    $displayText = trim($parts[0]);
                                                    $additionalText = isset($parts[1]) ? trim($parts[1]) : '';
                                                }
                                            @endphp

                                            <li class="checkbox-item">
                                                {{ $displayText }}
                                                @if ($additionalText)
                                                    <div class="other-option mt-2">
                                                        <span class="other-label">Keterangan:</span>
                                                        {{ $additionalText }}
                                                    </div>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @elseif($answerText)
                                    {{ $answerText }}
                                @else
                                    <div class="text-muted">Tidak diisi</div>
                                @endif
                            @elseif(in_array($question->question_type, ['radio', 'dropdown']))
                                @if ($answerText)
                                    @php
                                        $displayText = $answerText;
                                        $additionalText = '';

                                        if (str_contains($answerText, ':')) {
                                            $parts = explode(':', $answerText, 2);
                                            $displayText = trim($parts[0]);
                                            $additionalText = isset($parts[1]) ? trim($parts[1]) : '';
                                        }
                                    @endphp

                                    <div>
                                        {{ $displayText }}
                                        @if ($additionalText)
                                            <div class="other-option mt-2">
                                                <span class="other-label">Keterangan:</span>
                                                {{ $additionalText }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-muted">Tidak diisi</div>
                                @endif
                            @elseif($question->question_type === 'likert_per_row')
                                @if (!empty($answerValues) && !empty($rowItems))
                                    <table class="scale-table">
                                        <tr>
                                            <th>Item</th>
                                            <th width="80">Skala</th>
                                        </tr>
                                        @foreach ($rowItems as $key => $item)
                                            @if (isset($answerValues[$key]))
                                                @php
                                                    $itemText = is_array($item) ? $item['text'] ?? $item : $item;
                                                    $scaleVal = $answerValues[$key];
                                                @endphp
                                                <tr>
                                                    <td>{{ $itemText }}</td>
                                                    <td class="scale-value">{{ $scaleVal }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                @else
                                    <div class="text-muted">Tidak diisi</div>
                                @endif
                            @elseif($question->question_type === 'textarea')
                                <div class="text-answer">
                                    {{ $answerText }}
                                </div>
                            @elseif($question->question_type === 'text')
                                <div>
                                    {{ $answerText }}
                                </div>
                            @else
                                <div>
                                    {{ $answerText }}
                                </div>
                            @endif

                            <!-- Timestamp -->
                            @if ($answer->answered_at)
                                <div class="text-muted mt-2">
                                    Diisi: {{ $answer->answered_at->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari Sistem Tracer Study UAD</p>
        <p>&copy; {{ date('Y') }} Universitas Ahmad Dahlan</p>
    </div>
</body>

</html>
