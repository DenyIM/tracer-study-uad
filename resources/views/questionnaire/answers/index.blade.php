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

        .badge-whatsapp {
            background-color: #25d366;
            color: white;
            border: 1px solid #128c7e;
        }

        .badge-other {
            background-color: #6f42c1;
            color: white;
            border: 1px solid #5a32a3;
        }

        .email-display {
            margin-top: 10px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #ffc107;
        }

        .whatsapp-display {
            margin-top: 10px;
            padding: 10px;
            background-color: #dcf8c6;
            border-radius: 6px;
            border-left: 3px solid #25d366;
        }

        .other-display {
            margin-top: 10px;
            padding: 10px;
            background-color: #e9d8fd;
            border-radius: 6px;
            border-left: 3px solid #6f42c1;
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

                                // Tentukan apakah ini pertanyaan pertama atau kedua untuk label yang tepat
                                $isFirstQuestion = $question->order == 1 && $questionnaire->is_general;
                                $isSecondQuestion = $question->order == 2 && $questionnaire->is_general;

                                // Tentukan skala label berdasarkan pertanyaan
                                $scaleLabels = [];
                                
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
                                        3 => 'Sedang',
                                        4 => 'Tinggi',
                                        5 => 'Sangat Tinggi',
                                    ];
                                }

                                // Parse answer data - PERBAIKAN UTAMA DI SINI
                                $answerText = $answer->answer;
                                $selectedOptions = $answer->selected_options;
                                $scaleValue = $answer->scale_value;

                                // DEBUG: Tampilkan data untuk debugging
                                // dd($answer->toArray());

                                // Proses selected_options untuk checkbox
                                if (!empty($selectedOptions)) {
                                    if (is_string($selectedOptions)) {
                                        // Coba decode JSON
                                        $decoded = json_decode($selectedOptions, true);
                                        if (json_last_error() === JSON_ERROR_NONE) {
                                            $selectedOptions = $decoded;
                                        } else {
                                            // Jika bukan JSON, mungkin string biasa
                                            $selectedOptions = [$selectedOptions];
                                        }
                                    }
                                    
                                    // Pastikan $selectedOptions adalah array
                                    if (!is_array($selectedOptions)) {
                                        $selectedOptions = [$selectedOptions];
                                    }
                                } else {
                                    $selectedOptions = [];
                                }
                            @endphp

                            <div class="question-item">
                                <div class="d-flex align-items-start">
                                    <div class="question-number me-3">{{ $loop->iteration }}</div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-bold mb-2">{!! nl2br(e($question->question_text)) !!}</h5>

                                        @if ($question->description)
                                            <p class="text-muted mb-3">{!! nl2br(e($question->description)) !!}</p>
                                        @endif

                                        <div class="answer-content">
                                            @if (in_array($question->question_type, ['likert_scale', 'competency_scale']))
                                                @if ($scaleValue)
                                                    @php
                                                        $scaleLabel = '';
                                                        if ($scaleValue == 1) {
                                                            $scaleLabel = $scaleLabels[1] ?? 'Sangat Rendah';
                                                        } elseif ($scaleValue == 2) {
                                                            $scaleLabel = $scaleLabels[2] ?? 'Rendah';
                                                        } elseif ($scaleValue == 3) {
                                                            $scaleLabel = $scaleLabels[3] ?? 'Sedang';
                                                        } elseif ($scaleValue == 4) {
                                                            $scaleLabel = $scaleLabels[4] ?? 'Tinggi';
                                                        } elseif ($scaleValue == 5) {
                                                            $scaleLabel = $scaleLabels[5] ?? 'Sangat Tinggi';
                                                        }
                                                    @endphp
                                                    <div class="answer-text">
                                                        <table class="scale-table">
                                                            <tr>
                                                                <th width="50%">Skala Nilai</th>
                                                                <th width="50%">Keterangan</th>
                                                            </tr>
                                                            <tr>
                                                                <td class="scale-value">{{ $scaleValue }}</td>
                                                                <td>{{ $scaleLabel }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                @endif
                                            @elseif(in_array($question->question_type, ['checkbox', 'checkbox_per_row']))
                                                @if (count($selectedOptions) > 0)
                                                    <div class="answer-text">
                                                        @foreach ($selectedOptions as $option)
                                                            @php
                                                                // Handle berbagai format data
                                                                $displayText = $option;
                                                                $isSpecialOption = false;
                                                                $mainText = $option;
                                                                $additionalValue = '';
                                                                
                                                                // Cek jika ada format dengan delimiter
                                                                if (is_string($option) && str_contains($option, ':')) {
                                                                    $parts = explode(':', $option, 2);
                                                                    $mainText = trim($parts[0]);
                                                                    $additionalValue = isset($parts[1]) ? trim($parts[1]) : '';
                                                                    
                                                                    if (str_contains($mainText, 'email') || str_contains($mainText, 'Ya,')) {
                                                                        $isSpecialOption = true;
                                                                        $badgeClass = 'badge-email';
                                                                    } elseif (str_contains($mainText, 'WhatsApp')) {
                                                                        $isSpecialOption = true;
                                                                        $badgeClass = 'badge-whatsapp';
                                                                    } elseif (str_contains($mainText, 'Lainnya')) {
                                                                        $isSpecialOption = true;
                                                                        $badgeClass = 'badge-other';
                                                                    }
                                                                }
                                                                
                                                                // Format khusus untuk data seeder/array PHP
                                                                if (is_array($option)) {
                                                                    $displayText = $option['text'] ?? $option[0] ?? json_encode($option);
                                                                    $mainText = $displayText;
                                                                }
                                                            @endphp
                                                            
                                                            @if ($isSpecialOption)
                                                                <div class="mb-2">
                                                                    <span class="answer-badge {{ $badgeClass }}">{{ $mainText }}</span>
                                                                    @if (!empty($additionalValue))
                                                                        <div class="{{ 
                                                                            str_contains($mainText, 'email') ? 'email-display' : 
                                                                            (str_contains($mainText, 'WhatsApp') ? 'whatsapp-display' : 'other-display') 
                                                                        }}">
                                                                            @if (str_contains($mainText, 'email'))
                                                                                <strong>Email:</strong> {{ $additionalValue }}
                                                                            @elseif(str_contains($mainText, 'WhatsApp'))
                                                                                <strong>WhatsApp:</strong> {{ $additionalValue }}
                                                                            @elseif(str_contains($mainText, 'Lainnya'))
                                                                                <strong>Keterangan:</strong> {{ $additionalValue }}
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <div class="mb-2">
                                                                    <span class="answer-badge badge-checkbox">{{ $mainText }}</span>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @elseif($answerText)
                                                    {{-- Fallback: jika tidak ada selected_options tapi ada answer --}}
                                                    <div class="answer-text">
                                                        @php
                                                            // Coba parse jika answerText adalah JSON string
                                                            $parsedAnswers = [];
                                                            if (is_string($answerText) && str_starts_with($answerText, '[') && str_ends_with($answerText, ']')) {
                                                                $parsedAnswers = json_decode($answerText, true) ?? [];
                                                            }
                                                            
                                                            if (!empty($parsedAnswers) && is_array($parsedAnswers)) {
                                                                foreach ($parsedAnswers as $parsedOption) {
                                                                    if (is_string($parsedOption)) {
                                                                        // Handle format dengan delimiter
                                                                        if (str_contains($parsedOption, ':')) {
                                                                            $parts = explode(':', $parsedOption, 2);
                                                                            $mainText = trim($parts[0]);
                                                                            $additionalValue = isset($parts[1]) ? trim($parts[1]) : '';
                                                                            
                                                                            echo '<div class="mb-2">';
                                                                            echo '<span class="answer-badge badge-checkbox">' . $mainText . '</span>';
                                                                            if (!empty($additionalValue)) {
                                                                                if (str_contains($mainText, 'Lainnya')) {
                                                                                    echo '<div class="other-display"><strong>Keterangan:</strong> ' . $additionalValue . '</div>';
                                                                                } else {
                                                                                    echo '<div class="email-display"><strong>Detail:</strong> ' . $additionalValue . '</div>';
                                                                                }
                                                                            }
                                                                            echo '</div>';
                                                                        } else {
                                                                            echo '<div class="mb-2"><span class="answer-badge badge-checkbox">' . $parsedOption . '</span></div>';
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                // Tampilkan sebagai plain text
                                                                echo '<div class="mb-2"><span class="answer-badge badge-checkbox">' . $answerText . '</span></div>';
                                                            }
                                                        @endphp
                                                    </div>
                                                @else
                                                    <div class="answer-text text-muted">
                                                        <i class="fas fa-info-circle me-2"></i>Tidak ada jawaban yang dipilih
                                                    </div>
                                                @endif
                                            @elseif(in_array($question->question_type, ['radio', 'dropdown']))
                                                @if ($answerText)
                                                    <div class="answer-text">
                                                        @php
                                                            if (is_string($answerText) && str_contains($answerText, ':')) {
                                                                $parts = explode(':', $answerText, 2);
                                                                $mainText = trim($parts[0]);
                                                                $additionalValue = isset($parts[1]) ? trim($parts[1]) : '';

                                                                if (str_contains($mainText, 'email') || str_contains($mainText, 'Ya,')) {
                                                                    echo '<span class="answer-badge badge-email">' . $mainText . '</span>';
                                                                    if ($additionalValue) {
                                                                        echo '<div class="email-display"><strong>Email:</strong> ' . $additionalValue . '</div>';
                                                                    }
                                                                } elseif (str_contains($mainText, 'WhatsApp')) {
                                                                    echo '<span class="answer-badge badge-whatsapp">' . $mainText . '</span>';
                                                                    if ($additionalValue) {
                                                                        echo '<div class="whatsapp-display"><strong>WhatsApp:</strong> ' . $additionalValue . '</div>';
                                                                    }
                                                                } elseif (str_contains($mainText, 'Lainnya')) {
                                                                    echo '<span class="answer-badge badge-other">' . $mainText . '</span>';
                                                                    if ($additionalValue) {
                                                                        echo '<div class="other-display"><strong>Keterangan:</strong> ' . $additionalValue . '</div>';
                                                                    }
                                                                } else {
                                                                    echo $answerText;
                                                                }
                                                            } else {
                                                                echo $answerText;
                                                            }
                                                        @endphp
                                                    </div>
                                                @endif
                                            @elseif($question->question_type === 'likert_per_row')
                                                @php
                                                    // Handle data dari seeder (array PHP langsung atau JSON)
                                                    $answerValues = [];
                                                    
                                                    // Logic untuk membaca data dari seeder
                                                    if ($answer) {
                                                        // Coba dari answer field
                                                        if ($answer->answer && is_string($answer->answer)) {
                                                            $answerValues = json_decode($answer->answer, true) ?? [];
                                                        } 
                                                        // Coba dari scale_value (backward compatibility)
                                                        elseif ($answer->scale_value && is_string($answer->scale_value)) {
                                                            $answerValues = json_decode($answer->scale_value, true) ?? [];
                                                        }
                                                        // Jika sudah array langsung
                                                        elseif (is_array($answer->answer)) {
                                                            $answerValues = $answer->answer;
                                                        }
                                                    }
                                                    
                                                    // Handle row items dari seeder (array PHP, bukan JSON)
                                                    $rowItems = $question->row_items;
                                                    if (is_string($rowItems)) {
                                                        $rowItems = json_decode($rowItems, true) ?? [];
                                                    }
                                                    
                                                    if (!is_array($rowItems)) {
                                                        $rowItems = [];
                                                    }
                                                @endphp

                                                @if (count($answerValues) > 0 && is_array($answerValues))
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
                                                                @php
                                                                    // Penyesuaian untuk data seeder
                                                                    $itemText = is_array($item) ? ($item['text'] ?? $item) : $item;
                                                                    $itemKey = is_string($key) ? $key : $itemText;
                                                                    
                                                                    if (isset($answerValues[$itemKey])) {
                                                                        $scaleValue = $answerValues[$itemKey];
                                                                        $scaleLabel = '';
                                                                        
                                                                        // Penyesuaian label berdasarkan pertanyaan
                                                                        if ($isSecondQuestion) {
                                                                            // Untuk pertanyaan kedua (metode pembelajaran)
                                                                            if ($scaleValue == 1) {
                                                                                $scaleLabel = 'Tidak Sama Sekali';
                                                                            } elseif ($scaleValue == 2) {
                                                                                $scaleLabel = 'Kurang';
                                                                            } elseif ($scaleValue == 3) {
                                                                                $scaleLabel = 'Cukup';
                                                                            } elseif ($scaleValue == 4) {
                                                                                $scaleLabel = 'Besar';
                                                                            } elseif ($scaleValue == 5) {
                                                                                $scaleLabel = 'Sangat Besar';
                                                                            }
                                                                        } else {
                                                                            // Untuk pertanyaan lain
                                                                            if ($scaleValue == 1) {
                                                                                $scaleLabel = 'Sangat Rendah';
                                                                            } elseif ($scaleValue == 2) {
                                                                                $scaleLabel = 'Rendah';
                                                                            } elseif ($scaleValue == 3) {
                                                                                $scaleLabel = 'Sedang';
                                                                            } elseif ($scaleValue == 4) {
                                                                                $scaleLabel = 'Tinggi';
                                                                            } elseif ($scaleValue == 5) {
                                                                                $scaleLabel = 'Sangat Tinggi';
                                                                            }
                                                                        }
                                                                @endphp
                                                                <tr>
                                                                    <td>{{ $itemText }}</td>
                                                                    <td class="scale-value">{{ $scaleValue }}</td>
                                                                    <td class="scale-label">{{ $scaleLabel }}</td>
                                                                </tr>
                                                                @php
                                                                    }
                                                                @endphp
                                                            @endforeach
                                                        </table>
                                                    </div>
                                                @endif
                                            @elseif(in_array($question->question_type, ['textarea']))
                                                @if ($answerText)
                                                    <div class="answer-text" style="white-space: pre-line;">
                                                        {{ $answerText }}</div>
                                                @endif
                                            @else
                                                @if ($answerText)
                                                    <div class="answer-text">{{ $answerText }}</div>
                                                @endif
                                            @endif

                                            @if ($answer->answered_at)
                                                <div class="text-muted small mt-3">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Diisi: {{ $answer->answered_at->format('d/m/Y H:i') }}
                                                </div>
                                            @endif

                                            @if ($question->points && $answer->points)
                                                <div class="text-success small mt-1">
                                                    <i class="fas fa-star me-1"></i>
                                                    Poin: {{ $answer->points }}
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
    
    {{-- DEBUG SCRIPT untuk melihat data --}}
    <script>
        console.log("DEBUG: Data jawaban");
        @foreach ($answers as $answer)
            console.log("Question ID: {{ $answer->question_id }}", {
                question_type: "{{ $answer->question->question_type ?? 'N/A' }}",
                answer: @json($answer->answer),
                selected_options: @json($answer->selected_options),
                scale_value: {{ $answer->scale_value ?? 'null' }},
                raw_data: @json($answer->toArray())
            });
        @endforeach
    </script>
</body>

</html>