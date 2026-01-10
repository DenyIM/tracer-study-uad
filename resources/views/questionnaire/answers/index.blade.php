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
            --warning-orange: #fd7e14;
            --danger-red: #dc3545;
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
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
            position: relative;
            overflow: hidden;
        }

        .category-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
        }

        .category-icon-large {
            width: 80px;
            height: 80px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .category-icon-large.working {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .category-icon-large.entrepreneur {
            background: linear-gradient(135deg, #fab300, #e67700);
        }

        .category-icon-large.study {
            background: linear-gradient(135deg, #28a745, #2b8a3e);
        }

        .category-icon-large.job-seeker {
            background: linear-gradient(135deg, #fd7e14, #e8590c);
        }

        .category-icon-large.not-working {
            background: linear-gradient(135deg, #dc3545, #c92a2a);
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
            transition: all 0.2s ease;
        }

        .question-item:hover {
            background-color: #f8f9fa;
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

        .answer-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
        }

        .answer-badge.radio {
            background-color: #e7f5ff;
            color: #1864ab;
            border: 1px solid #a5d8ff;
        }

        .answer-badge.text {
            background-color: #f3f0ff;
            color: #5f3dc4;
            border: 1px solid #d0bfff;
        }

        .answer-badge.dropdown {
            background-color: #fff3bf;
            color: #e67700;
            border: 1px solid #ffd43b;
        }

        .answer-badge.checkbox {
            background-color: #d3f9d8;
            color: #2b8a3e;
            border: 1px solid #b2f2bb;
        }

        .section-header {
            padding: 15px 20px;
            background-color: var(--light-blue);
            border-radius: 12px 12px 0 0;
            border-bottom: 2px solid var(--primary-blue);
        }

        .section-header h4 {
            color: var(--primary-blue);
            margin: 0;
        }

        .answer-text {
            background-color: #f8f9fa;
            padding: 12px 15px;
            border-radius: 8px;
            border-left: 4px solid var(--secondary-blue);
            margin-top: 10px;
            white-space: pre-line;
        }

        .competency-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .competency-table th,
        .competency-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .competency-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: var(--primary-blue);
        }

        .competency-table tr:hover {
            background-color: #f8f9fa;
        }

        .scale-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .scale-1 {
            background-color: #ffe3e3;
            color: #c92a2a;
        }

        .scale-2 {
            background-color: #ffec99;
            color: #e67700;
        }

        .scale-3 {
            background-color: #d3f9d8;
            color: #2b8a3e;
        }

        .scale-4 {
            background-color: #a5d8ff;
            color: #1864ab;
        }

        .scale-5 {
            background-color: #d0bfff;
            color: #5f3dc4;
        }

        .completion-info {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        .summary-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .summary-label {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .timestamp {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .timestamp i {
            margin-right: 5px;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .section-icon.general {
            background-color: rgba(108, 117, 125, 0.1);
            color: #495057;
        }

        .section-icon.part1 {
            background-color: rgba(59, 130, 246, 0.1);
            color: #1d4ed8;
        }

        .section-icon.part2 {
            background-color: rgba(250, 179, 0, 0.1);
            color: #e67700;
        }

        .section-icon.part3 {
            background-color: rgba(40, 167, 69, 0.1);
            color: #2b8a3e;
        }

        .section-icon.part4 {
            background-color: rgba(220, 53, 69, 0.1);
            color: #c92a2a;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .category-icon-large {
                width: 60px;
                height: 60px;
                font-size: 1.8rem;
                margin-right: 15px;
            }

            .category-banner {
                padding: 15px;
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
                    @if ($statusQuestionnaire && $statusQuestionnaire->isCompleted)
                        <a href="{{ route('questionnaire.answers.export', ['categorySlug' => $category->slug]) }}"
                            class="btn btn-danger">
                            <i class="fas fa-file-pdf me-2"></i> Export PDF
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Informasi Alumni -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="completion-info">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="fw-bold mb-1">{{ auth()->user()->alumni->fullname }}</h4>
                            <p class="mb-2">
                                <span class="me-3">
                                    <i class="fas fa-graduation-cap me-1 text-primary"></i>
                                    {{ auth()->user()->alumni->study_program }} -
                                    Lulus {{ optional(auth()->user()->alumni->graduation_date)->format('Y') }}
                                </span>
                                <span>
                                    <i class="fas fa-envelope me-1 text-primary"></i>
                                    {{ auth()->user()->email }}
                                </span>
                            </p>
                            <p class="mb-0">
                                @if ($statusQuestionnaire && $statusQuestionnaire->isCompleted)
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check-circle me-1"></i>Kuesioner Telah Diselesaikan
                                    </span>
                                @elseif($statusQuestionnaire && $statusQuestionnaire->isInProgress)
                                    <span class="badge bg-warning fs-6">
                                        <i class="fas fa-spinner fa-spin me-1"></i>Sedang Dikerjakan
                                    </span>
                                @else
                                    <span class="badge bg-secondary fs-6">
                                        <i class="fas fa-clock me-1"></i>Belum Dimulai
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="timestamp">
                                <i class="fas fa-clock"></i>
                                Terakhir diupdate: {{ now()->format('d F Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Banner -->
        @if ($category)
            <div class="category-banner" id="categoryBanner">
                <div class="row align-items-center">
                    <div class="col-auto">
                        @php
                            $categoryClasses = [
                                'bekerja' => 'working',
                                'wirausaha' => 'entrepreneur',
                                'pendidikan' => 'study',
                                'pencari' => 'job-seeker',
                                'tidak-kerja' => 'not-working',
                            ];
                            $categoryClass = $categoryClasses[$category->slug] ?? 'working';
                        @endphp
                        <div class="category-icon-large {{ $categoryClass }}">
                            <i class="fas {{ $category->icon ?? 'fa-folder' }}"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h3 class="fw-bold mb-2">{{ $category->name }}</h3>
                        <p class="mb-0 opacity-90">{{ $category->description }}</p>
                    </div>
                    <div class="col-auto text-end">
                        @if ($statusQuestionnaire && $statusQuestionnaire->completed_at)
                            <div class="badge bg-light text-dark fs-6">
                                <i class="fas fa-calendar-check me-1"></i>
                                Diselesaikan: {{ $statusQuestionnaire->completed_at->format('d M Y') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Summary Statistics -->
        @php
            $totalQuestions = $category->total_questions ?? 0;
            $totalAnswered = $answers->where('is_skipped', false)->count();
            $completionPercentage = $totalQuestions > 0 ? round(($totalAnswered / $totalQuestions) * 100) : 0;
            $totalPoints = $statusQuestionnaire->total_points ?? 0;
        @endphp

        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="summary-card">
                    <div class="summary-number text-primary">{{ $totalQuestions }}</div>
                    <div class="summary-label">Total Pertanyaan</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="summary-card">
                    <div class="summary-number text-success">{{ $totalAnswered }}</div>
                    <div class="summary-label">Pertanyaan Terjawab</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="summary-card">
                    <div class="summary-number" style="color: var(--accent-yellow);">{{ $completionPercentage }}%</div>
                    <div class="summary-label">Tingkat Penyelesaian</div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="summary-card">
                    <div class="summary-number text-info">{{ $questionnaires->count() }}</div>
                    <div class="summary-label">Bagian Kuesioner</div>
                </div>
            </div>
        </div>

        <!-- Answers Sections -->
        @if ($questionnaires->isEmpty())
            <div class="answers-section">
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-clipboard-list"></i>
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

                    $sectionIcons = ['general', 'part1', 'part2', 'part3', 'part4'];
                    $sectionIcon = $sectionIcons[$loop->index] ?? 'general';
                @endphp

                @if ($questionnaireAnswers->isNotEmpty())
                    <div class="answers-section mb-4">
                        <div class="section-header">
                            <div class="d-flex align-items-center">
                                <div class="section-icon {{ $sectionIcon }}">
                                    <i
                                        class="fas {{ $questionnaire->is_general ? 'fa-clipboard-list' : 'fa-file-alt' }}"></i>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-0">{{ $questionnaire->name }}</h4>
                                    @if ($questionnaire->description)
                                        <p class="text-muted mb-0 small">{{ $questionnaire->description }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @foreach ($questionnaire->questions()->orderBy('order')->get() as $question)
                            @php
                                $answer = $answers->where('question_id', $question->id)->first();
                                if (!$answer || $answer->is_skipped) {
                                    continue;
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
                                            @if (in_array($question->question_type, ['likert_scale', 'competency_scale', 'likert_per_row']))
                                                @if ($answer->scale_value)
                                                    @php
                                                        $scaleLabels = [
                                                            1 => 'Sangat Rendah',
                                                            2 => 'Rendah',
                                                            3 => 'Cukup',
                                                            4 => 'Tinggi',
                                                            5 => 'Sangat Tinggi',
                                                        ];
                                                        $scaleLabel =
                                                            $scaleLabels[$answer->scale_value] ?? $answer->scale_value;
                                                    @endphp
                                                    <div class="answer-text">
                                                        <span class="scale-badge scale-{{ $answer->scale_value }}">
                                                            {{ $answer->scale_value }}
                                                        </span>
                                                        <span class="ms-2">{{ $scaleLabel }}</span>
                                                    </div>
                                                @endif
                                            @elseif(in_array($question->question_type, ['checkbox', 'checkbox_per_row']))
                                                @if ($answer->selected_options && is_array(json_decode($answer->selected_options, true)))
                                                    <div class="answer-text">
                                                        @foreach (json_decode($answer->selected_options, true) as $option)
                                                            <span
                                                                class="answer-badge checkbox me-2 mb-2">{{ $option }}</span>
                                                        @endforeach
                                                    </div>
                                                @elseif($answer->selected_options)
                                                    <div class="answer-text">
                                                        <span
                                                            class="answer-badge checkbox">{{ $answer->selected_options }}</span>
                                                    </div>
                                                @endif
                                            @elseif(in_array($question->question_type, ['radio', 'dropdown', 'radio_per_row']))
                                                @if ($answer->answer)
                                                    <div class="answer-text">
                                                        <span class="answer-badge radio">{{ $answer->answer }}</span>
                                                    </div>
                                                @endif
                                            @elseif(in_array($question->question_type, ['textarea', 'text', 'number', 'date']))
                                                @if ($answer->answer)
                                                    <div class="answer-text">{{ $answer->answer }}</div>
                                                @endif
                                            @else
                                                @if ($answer->formatted_answer)
                                                    <div class="answer-text">{{ $answer->formatted_answer }}</div>
                                                @endif
                                            @endif

                                            @if ($answer->answered_at)
                                                <div class="timestamp mt-3">
                                                    <i class="fas fa-clock"></i>
                                                    Diisi: {{ $answer->answered_at->format('d/m/Y H:i') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($questionnaireAnswers->isEmpty())
                            <div class="p-4 text-center">
                                <p class="text-muted mb-0">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Belum ada jawaban untuk bagian ini
                                </p>
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        @endif

        <!-- Information Box -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <h5 class="alert-heading">
                        <i class="fas fa-info-circle me-2"></i>Informasi Penting
                    </h5>
                    <p class="mb-0">
                        Alumni <strong>hanya mengisi 1 kategori kuesioner</strong> sesuai dengan statusnya saat ini.
                        @if ($category)
                            Kategori lain tidak ditampilkan karena tidak relevan dengan status alumni saat ini.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer mt-5 py-3" style="background-color: var(--primary-blue); color: white;">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} Tracer Study Universitas Ahmad Dahlan.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Data configuration
        const questionnaireData = {
            alumni: {
                name: "{{ auth()->user()->alumni->fullname }}",
                program_studi: "{{ auth()->user()->alumni->study_program }}",
                tahun_lulus: "{{ optional(auth()->user()->alumni->graduation_date)->format('Y') }}",
                email: "{{ auth()->user()->email }}",
                status: "{{ $statusQuestionnaire ? $statusQuestionnaire->status : 'not_started' }}"
            },
            category: @json(
                $category
                    ? [
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'description' => $category->description,
                        'icon' => $category->icon,
                    ]
                    : null),
            completionDate: "{{ $statusQuestionnaire && $statusQuestionnaire->completed_at ? $statusQuestionnaire->completed_at->format('d F Y') : '' }}"
        };

        // Category configuration for styling
        const categoryConfig = {
            'bekerja': {
                color: '#3b82f6',
                icon: 'fa-building'
            },
            'wirausaha': {
                color: '#fab300',
                icon: 'fa-briefcase'
            },
            'pendidikan': {
                color: '#28a745',
                icon: 'fa-graduation-cap'
            },
            'pencari': {
                color: '#fd7e14',
                icon: 'fa-search'
            },
            'tidak-kerja': {
                color: '#dc3545',
                icon: 'fa-home'
            }
        };

        // Update banner styling based on category
        document.addEventListener('DOMContentLoaded', function() {
            const categorySlug = "{{ $category->slug ?? '' }}";
            const banner = document.getElementById('categoryBanner');

            if (banner && categorySlug && categoryConfig[categorySlug]) {
                const config = categoryConfig[categorySlug];
                banner.style.background =
                    `linear-gradient(135deg, ${config.color}, ${darkenColor(config.color, 20)})`;
            }
        });

        // Helper function to darken color
        function darkenColor(color, percent) {
            let r = parseInt(color.substring(1, 3), 16);
            let g = parseInt(color.substring(3, 5), 16);
            let b = parseInt(color.substring(5, 7), 16);

            r = Math.floor(r * (100 - percent) / 100);
            g = Math.floor(g * (100 - percent) / 100);
            b = Math.floor(b * (100 - percent) / 100);

            return `#${r.toString(16).padStart(2, '0')}${g.toString(16).padStart(2, '0')}${b.toString(16).padStart(2, '0')}`;
        }

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>

</html>
