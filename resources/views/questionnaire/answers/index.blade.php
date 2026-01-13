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
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
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

        .section-header {
            padding: 15px 20px;
            background-color: var(--light-blue);
            border-radius: 12px 12px 0 0;
            border-bottom: 2px solid var(--primary-blue);
        }

        .answer-text {
            background-color: #f8f9fa;
            padding: 12px 15px;
            border-radius: 8px;
            border-left: 4px solid var(--secondary-blue);
            margin-top: 10px;
        }

        /* Scale table styles */
        .scale-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
        }

        .scale-table th {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: left;
            border: 1px solid #dee2e6;
            font-weight: 600;
            color: var(--primary-blue);
        }

        .scale-table td {
            padding: 10px;
            border: 1px solid #dee2e6;
        }

        .scale-value {
            text-align: center;
            font-weight: bold;
            background-color: #e9ecef;
        }

        .scale-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        /* Badge styles */
        .answer-badge {
            display: inline-block;
            padding: 5px 12px;
            margin-right: 5px;
            margin-bottom: 5px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-checkbox {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }

        .badge-email {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffecb5;
        }

        .email-display {
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #007bff;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }

        .footer {
            background-color: var(--primary-blue);
            color: white;
            padding: 20px 0;
            margin-top: 30px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .question-item {
                padding: 15px;
            }

            .scale-table {
                font-size: 0.85rem;
            }

            .scale-table th,
            .scale-table td {
                padding: 6px;
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
                    <a href="{{ route('questionnaire.dashboard') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                    </a>
                    <a href="{{ route('questionnaire.answers.export', ['categorySlug' => $category->slug]) }}"
                        class="btn btn-danger">
                        <i class="fas fa-file-pdf me-2"></i> Export PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Category Banner -->
        @if ($category)
            <div class="category-banner">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="category-icon-large">
                            <i class="fas {{ $category->icon ?? 'fa-folder' }} fa-2x"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h3 class="fw-bold mb-2">{{ $category->name }}</h3>
                        <p class="mb-0 opacity-90">{{ $category->description }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Answers Sections -->
        @if ($questionnaires->isEmpty())
            <div class="answers-section">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-clipboard-list fa-4x" style="color: #dee2e6;"></i>
                    </div>
                    <h4 class="fw-bold mb-3" style="color: var(--primary-blue);">Belum Ada Jawaban</h4>
                    <p class="mb-4">Anda belum mengisi kuesioner untuk kategori ini.</p>
                    <a href="{{ route('questionnaire.fill', ['categorySlug' => $category->slug]) }}"
                        class="btn btn-primary">
                        <i class="fas fa-play-circle me-2"></i> Mulai Isi Kuesioner
                    </a>
                </div>
            </div>
        @else
            @foreach ($questionnaires as $questionnaire)
                @php
                    $questionnaireAnswers = $answers->filter(function ($answer) use ($questionnaire) {
                        return $answer->question->questionnaire_id == $questionnaire->id && !$answer->is_skipped;
                    });
                @endphp

                @if ($questionnaireAnswers->isNotEmpty())
                    <div class="answers-section mb-4">
                        <div class="section-header">
                            <h4 class="fw-bold mb-0">{{ $questionnaire->name }}</h4>
                            @if ($questionnaire->description)
                                <p class="text-muted mb-0 small">{{ $questionnaire->description }}</p>
                            @endif
                        </div>

                        @foreach ($questionnaire->questions()->orderBy('order')->get() as $question)
                            @php
                                $answer = $answers->where('question_id', $question->id)->first();
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
                                <div class="d-flex align-items-start">
                                    <div class="question-number me-3">{{ $loop->iteration }}</div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold mb-2">{{ $question->question_text }}</h5>

                                        @if ($question->description)
                                            <p class="text-muted mb-3">{{ $question->description }}</p>
                                        @endif

                                        <div class="answer-content">
                                            @if (in_array($question->question_type, ['likert_scale', 'competency_scale']))
                                                @if ($answer->scale_value)
                                                    <div class="answer-text">
                                                        <table class="scale-table">
                                                            <tr>
                                                                <th width="50%">Skala Nilai</th>
                                                                <th width="50%">Keterangan</th>
                                                            </tr>
                                                            <tr>
                                                                <td class="scale-value">{{ $answer->scale_value }}</td>
                                                                <td>{{ $scaleLabels[$answer->scale_value] ?? 'Tidak tersedia' }}
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                @endif
                                            @elseif(in_array($question->question_type, ['checkbox', 'checkbox_per_row']))
                                                @if ($answer->selected_options)
                                                    <div class="answer-text">
                                                        @php
                                                            // Handle both array and JSON string
                                                            $selectedOptions = $answer->selected_options;
                                                            if (is_string($selectedOptions)) {
                                                                $selectedOptions =
                                                                    json_decode($selectedOptions, true) ?? [];
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
                                                                    $emailValue = isset($parts[1])
                                                                        ? trim($parts[1])
                                                                        : '';
                                                                @endphp
                                                                <span
                                                                    class="answer-badge badge-email">{{ $mainText }}</span>
                                                                @if ($emailValue)
                                                                    <div class="email-display">
                                                                        <strong>Email:</strong> {{ $emailValue }}
                                                                    </div>
                                                                @endif
                                                            @else
                                                                <span
                                                                    class="answer-badge badge-checkbox">{{ $option }}</span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            @elseif(in_array($question->question_type, ['radio', 'dropdown', 'radio_per_row']))
                                                @if ($answer->answer)
                                                    <div class="answer-text">
                                                        @if (strpos($answer->answer, 'email') !== false || strpos($answer->answer, 'Ya,') !== false)
                                                            @php
                                                                $parts = explode(':', $answer->answer, 2);
                                                                $mainText = trim($parts[0]);
                                                                $emailValue = isset($parts[1]) ? trim($parts[1]) : '';
                                                            @endphp
                                                            <span
                                                                class="answer-badge badge-email">{{ $mainText }}</span>
                                                            @if ($emailValue)
                                                                <div class="email-display">
                                                                    <strong>Email:</strong> {{ $emailValue }}
                                                                </div>
                                                            @endif
                                                        @else
                                                            {{ $answer->answer }}
                                                        @endif
                                                    </div>
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

                                                @if (count($answerValues) > 0)
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
                                                                        <td class="scale-value">
                                                                            {{ $answerValues[$key] }}</td>
                                                                        <td class="scale-label">
                                                                            {{ $scaleLabels[$answerValues[$key]] ?? 'Tidak tersedia' }}
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                @endif
                                            @elseif(in_array($question->question_type, ['textarea']))
                                                @if ($answer->answer)
                                                    <div class="answer-text" style="white-space: pre-line;">
                                                        {{ $answer->answer }}</div>
                                                @endif
                                            @else
                                                @if ($answer->answer)
                                                    <div class="answer-text">{{ $answer->answer }}</div>
                                                @endif
                                            @endif

                                            @if ($answer->answered_at)
                                                <div class="text-muted small mt-3">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Diisi: {{ $answer->answered_at->format('d/m/Y H:i') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        @endif
    </div>

    <footer class="footer">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Tracer Study Universitas Ahmad Dahlan.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
