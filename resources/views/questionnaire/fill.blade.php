<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuesioner - Tracer Study UAD</title>
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
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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

        .questionnaire-header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        .progress-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .progress {
            height: 12px;
            background-color: #e9ecef;
        }

        .progress-bar {
            background-color: var(--accent-yellow);
        }

        .question-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .question-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .question-nav-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-height: 400px;
            overflow-y: auto;
        }

        .question-nav-item {
            padding: 12px 15px;
            border-bottom: 1px solid #eaeaea;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .question-nav-item:hover {
            background-color: var(--light-blue);
        }

        .question-nav-item.active {
            background-color: var(--light-blue);
            border-left: 4px solid var(--accent-yellow);
        }

        .question-nav-item.answered {
            color: var(--primary-blue);
            font-weight: 500;
        }

        .nav-status {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
        }

        .nav-status.answered {
            background-color: #28a745;
            color: white;
        }

        .nav-status.current {
            background-color: var(--accent-yellow);
            color: white;
        }

        .nav-status.pending {
            background-color: #6c757d;
            color: white;
        }

        .nav-question-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 0.9rem;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .answer-option {
            padding: 15px;
            border: 2px solid #eaeaea;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .answer-option:hover {
            border-color: var(--secondary-blue);
            background-color: var(--light-blue);
        }

        .answer-option.selected {
            border-color: var(--accent-yellow);
            background-color: var(--light-yellow);
        }

        .likert-scale {
            width: 100%;
            text-align: center;
        }

        .likert-option {
            display: inline-block;
            margin: 0 10px;
            text-align: center;
            min-width: 80px;
        }

        .likert-option input[type="radio"] {
            margin-bottom: 5px;
        }

        .likert-label {
            display: block;
            font-size: 0.85rem;
            margin-top: 5px;
            color: #6c757d;
        }

        .btn-group-left {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-group-right {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .form-section {
            margin-bottom: 20px;
        }

        .competency-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .competency-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #eaeaea;
        }

        .competency-name {
            font-weight: 500;
            color: var(--primary-blue);
            min-width: 200px;
        }

        .competency-scale {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .footer {
            background-color: var(--primary-blue);
            color: white;
            padding: 20px 0;
            margin-top: 50px;
            text-align: center;
        }

        .category-badge {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
        }

        .section-badge {
            background-color: var(--light-blue);
            color: var(--primary-blue);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
        }

        .email-input-container {
            margin-top: 10px;
            display: none;
        }

        .email-input-container.show {
            display: block;
        }

        @media (max-width: 768px) {
            .question-nav-card {
                margin-bottom: 20px;
            }

            .navigation-buttons {
                flex-direction: column;
                gap: 15px;
            }

            .btn-group-left,
            .btn-group-right {
                width: 100%;
                justify-content: center;
            }

            .competency-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .competency-scale {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>

<body>
    <!-- Modal Konfirmasi -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Pengiriman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mengirim bagian kuesioner ini?</p>
                    <p class="text-muted small">Setelah dikirim, Anda tidak dapat mengubah jawaban pada bagian ini.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary-custom" id="confirmSubmitBtn">Ya, Kirim Bagian
                        Ini</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Loading -->
    <div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bg-transparent">
                <div class="modal-body text-center">
                    <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5 class="mt-3 text-white">Mengirim Kuesioner...</h5>
                    <p class="text-light">Harap tunggu sebentar</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <header class="questionnaire-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0" style="color: var(--primary-blue);">Kuesioner Tracer Study UAD</h3>
                    <div class="mt-1">
                        <span class="category-badge">{{ $category->name }}</span>
                        <span class="section-badge">{{ $questionnaire->name }}</span>
                    </div>
                </div>
                <div class="text-end">
                    <p class="mb-0"><strong>{{ auth()->user()->alumni->fullname }}</strong></p>
                    <p class="text-muted mb-0 small">{{ auth()->user()->alumni->study_program }}
                        {{ optional(auth()->user()->alumni->graduation_date)->format('Y') }}</p>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="container py-4">
            <!-- Progress Bar Pertanyaan -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="progress-section p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold" id="progressText">
                                Progress Pertanyaan:
                                Pertanyaan <span id="currentQuestionNumber">1</span> dari {{ $questions->count() }}
                            </span>
                            <span class="fw-bold text-accent" id="progressPercent">
                                {{ $questions->count() > 0 ? round((1 / $questions->count()) * 100) : 0 }}%
                            </span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" id="progressBar"
                                style="width: {{ $questions->count() > 0 ? round((1 / $questions->count()) * 100) : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Navigation Sidebar -->
                <div class="col-lg-4 mb-4" data-aos="fade-right">
                    <div class="question-nav-card p-3">
                        <h5 class="fw-bold mb-3" style="color: var(--primary-blue);">Daftar Pertanyaan</h5>

                        @foreach ($questions as $index => $question)
                            @php
                                $answer = $answers[$question->id] ?? null;
                                $isAnswered = $answer && !$answer->is_skipped;
                                $isCurrent = $loop->first;
                            @endphp

                            <div class="question-nav-item 
                                 {{ $isCurrent ? 'active current' : '' }} 
                                 {{ $isAnswered ? 'answered' : '' }}"
                                data-question-id="{{ $question->id }}"
                                onclick="navigateToQuestion({{ $question->id }})">

                                <div class="d-flex align-items-center">
                                    <div class="nav-question-number">{{ $loop->iteration }}</div>
                                    <div
                                        class="nav-status 
                                         {{ $isAnswered ? 'answered' : '' }} 
                                         {{ $isCurrent ? 'current' : '' }} 
                                         pending me-3">
                                        @if ($isAnswered)
                                            <i class="fas fa-check"></i>
                                        @elseif($isCurrent)
                                            <i class="fas fa-pen"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="text-truncate d-block">
                                            {{ Str::limit($question->question_text, 30) }}
                                        </span>
                                        <small class="text-muted d-block">
                                            {{ $question->question_type }}
                                            @if ($question->is_required)
                                                <span class="text-danger ms-1">*</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Progress Urutan Kuesioner -->
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold mb-2">Urutan Kuesioner</h6>
                            <div class="sequence-progress">
                                <div class="d-flex justify-content-between mb-2">
                                    <span id="sequenceProgressText">
                                        @if ($currentSequence)
                                            Bagian {{ $currentSequence->order }} dari
                                            {{ $category->sequences()->count() }}
                                        @else
                                            Bagian 1 dari 1
                                        @endif
                                    </span>
                                    <span class="fw-bold text-info" id="sequenceProgressPercent">
                                        @if ($currentSequence)
                                            {{ round(($currentSequence->order / $category->sequences()->count()) * 100) }}%
                                        @else
                                            100%
                                        @endif
                                    </span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-info" id="sequenceProgressBar"
                                        style="width: {{ $currentSequence ? round(($currentSequence->order / $category->sequences()->count()) * 100) : 100 }}%">
                                    </div>
                                </div>
                                <div class="text-center mt-2">
                                    <small class="text-muted">
                                        @if ($currentSequence && $currentSequence->isLast())
                                            <i class="fas fa-check-circle text-success me-1"></i> Bagian terakhir
                                        @elseif($currentSequence)
                                            <i class="fas fa-arrow-right text-info me-1"></i> Berlanjut ke bagian
                                            berikutnya
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="col-lg-8" data-aos="fade-left">
                    <!-- Form untuk semua pertanyaan -->
                    <form id="questionnaireForm" action="{{ route('questionnaire.submit', $questionnaire->id) }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="questionnaire_id" value="{{ $questionnaire->id }}">

                        @foreach ($questions as $index => $question)
                            @php
                                $answer = $answers[$question->id] ?? null;
                                $isCurrent = $loop->first;
                            @endphp

                            <div class="question-card p-4 mb-4 question-container {{ $isCurrent ? '' : 'd-none' }}"
                                id="question-{{ $question->id }}" data-question-index="{{ $loop->index }}">

                                <div class="d-flex align-items-start mb-4">
                                    <div class="question-number">{{ $loop->iteration }}</div>
                                    <div class="flex-grow-1 ms-3">
                                        <h4 class="fw-bold mb-2">{{ $question->question_text }}</h4>
                                        @if ($question->description)
                                            <p class="text-muted mb-0">{{ $question->description }}</p>
                                        @endif
                                        @if ($question->helper_text)
                                            <p class="text-info small mt-1">
                                                <i class="fas fa-info-circle"></i> {{ $question->helper_text }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Dynamic Answer Area -->
                                <div class="answer-area mb-4" id="answer-area-{{ $question->id }}">
                                    @include('questionnaire.answers.partials.answer-input', [
                                        'question' => $question,
                                        'answer' => $answer,
                                    ])
                                </div>

                                <!-- Navigation Buttons per Question -->
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <div class="btn-group-left">
                                        @if ($loop->first && $prevSequence)
                                            <a href="{{ route('questionnaire.fill', [
                                                'categorySlug' => $category->slug,
                                                'questionnaireSlug' => $prevSequence->questionnaire->slug,
                                            ]) }}"
                                                class="btn btn-outline-primary">
                                                <i class="fas fa-arrow-left me-2"></i> Bagian Sebelumnya
                                            </a>
                                        @elseif(!$loop->first)
                                            <button type="button" class="btn btn-outline-primary btn-prev-question">
                                                <i class="fas fa-arrow-left me-2"></i> Sebelumnya
                                            </button>
                                        @endif
                                    </div>

                                    <div class="btn-group-right">
                                        @if ($question->is_required)
                                            <span class="text-danger me-3">
                                                <i class="fas fa-asterisk"></i> Wajib diisi
                                            </span>
                                        @endif

                                        @if (!$question->is_required)
                                            <button type="button"
                                                class="btn btn-outline-warning btn-skip-question me-2"
                                                data-question-id="{{ $question->id }}">
                                                <i class="fas fa-forward me-2"></i> Lewati
                                            </button>
                                        @endif

                                        @if ($loop->last)
                                            <button type="button" class="btn btn-success"
                                                id="submitQuestionnaireBtn">
                                                <i class="fas fa-paper-plane me-2"></i> Selesaikan Bagian Ini
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-primary-custom btn-next-question">
                                                Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </form>

                    <!-- Global Navigation Buttons -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('questionnaire.dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-home me-2"></i> Kembali ke Dashboard
                                </a>
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-info" id="saveAllBtn">
                                    <i class="fas fa-save me-2"></i> Simpan Semua
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; 2025 Tracer Study Universitas Ahmad Dahlan.</p>
        </div>
    </footer>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Configuration
        const questions = @json($questions);
        const questionnaireId = {{ $questionnaire->id }};
        const categorySlug = "{{ $category->slug }}";
        const csrfToken = "{{ csrf_token() }}";
        const totalSequences = {{ $category->sequences()->count() }};
        const currentSequenceOrder = {{ $currentSequence ? $currentSequence->order : 1 }};

        let currentQuestionIndex = 0;
        let answers = {};
        let savedAnswers = {};
        let answeredQuestions = new Set();

        // Load saved answers from localStorage
        function loadSavedAnswers() {
            const saved = localStorage.getItem(`questionnaire_${questionnaireId}_answers`);
            if (saved) {
                savedAnswers = JSON.parse(saved);

                // Update answered questions set
                Object.keys(savedAnswers).forEach(questionId => {
                    if (savedAnswers[questionId].isAnswered) {
                        answeredQuestions.add(parseInt(questionId));
                    }
                });
            }
        }

        // Save answer to localStorage
        function saveAnswer(questionId, answerData) {
            answers[questionId] = {
                ...answerData,
                isAnswered: true
            };
            savedAnswers[questionId] = answers[questionId];
            answeredQuestions.add(questionId);

            localStorage.setItem(`questionnaire_${questionnaireId}_answers`, JSON.stringify(savedAnswers));

            // Update navigation item
            const navItem = document.querySelector(`[data-question-id="${questionId}"]`);
            if (navItem) {
                navItem.classList.add('answered');
                const statusIcon = navItem.querySelector('.nav-status');
                if (statusIcon) {
                    statusIcon.className = 'nav-status answered me-3';
                    statusIcon.innerHTML = '<i class="fas fa-check"></i>';
                }
            }
        }

        // Get answer for a question
        function getAnswerValue(questionId, questionType) {
            const container = document.getElementById(`answer-area-${questionId}`);
            if (!container) return null;

            switch (questionType) {
                case 'radio':
                case 'radio_per_row':
                    const selectedRadio = container.querySelector('input[type="radio"]:checked');
                    if (selectedRadio) {
                        // Handle jawaban dengan input tambahan (email)
                        if (selectedRadio.value.includes('email') || selectedRadio.value.includes('Ya,')) {
                            const emailInput = container.querySelector('.email-input');
                            if (emailInput && emailInput.value) {
                                return selectedRadio.value + ': ' + emailInput.value;
                            }
                            return selectedRadio.value;
                        }
                        return selectedRadio.value;
                    }
                    return null;

                case 'dropdown':
                    const select = container.querySelector('select');
                    if (select && select.value) {
                        // Handle other option for dropdown
                        if (select.value === 'Lainnya, sebutkan!') {
                            const otherInput = container.querySelector('input[name^="other_"]');
                            if (otherInput && otherInput.value) {
                                return 'Lainnya: ' + otherInput.value;
                            }
                        }
                        return select.value;
                    }
                    return null;

                case 'text':
                case 'textarea':
                case 'date':
                case 'number':
                    const input = container.querySelector('input, textarea');
                    return input ? input.value : null;

                case 'checkbox':
                case 'checkbox_per_row':
                    const checkboxes = container.querySelectorAll('input[type="checkbox"]:checked');
                    const values = Array.from(checkboxes).map(cb => {
                        // Handle other option for checkbox
                        if (cb.value === 'Lainnya') {
                            const otherInput = container.querySelector('input[name^="other_"]');
                            if (otherInput && otherInput.value) {
                                return 'Lainnya: ' + otherInput.value;
                            }
                        }
                        // Handle jawaban dengan input tambahan
                        if (cb.value.includes('email') || cb.value.includes('Ya,')) {
                            const emailInput = container.querySelector('.email-input');
                            if (emailInput && emailInput.value) {
                                return cb.value + ': ' + emailInput.value;
                            }
                        }
                        return cb.value;
                    }).filter(value => value);
                    return values.length > 0 ? values : null;

                case 'likert_scale':
                case 'competency_scale':
                    const scaleRadio = container.querySelector('input[type="radio"]:checked');
                    return scaleRadio ? parseInt(scaleRadio.value) : null;

                case 'likert_per_row':
                    const rowRadios = container.querySelectorAll('input[type="radio"]:checked');
                    const rowValues = {};
                    rowRadios.forEach(radio => {
                        const name = radio.name;
                        const match = name.match(/\[(\w+)\]/);
                        if (match) {
                            rowValues[match[1]] = radio.value;
                        }
                    });
                    return Object.keys(rowValues).length > 0 ? rowValues : null;

                default:
                    const defaultInput = container.querySelector('input, textarea, select');
                    return defaultInput ? defaultInput.value : null;
            }
        }

        // Check if question has been answered
        function isQuestionAnswered(questionId, questionType) {
            const answerValue = getAnswerValue(questionId, questionType);

            if (answerValue !== null) {
                if (Array.isArray(answerValue) && answerValue.length === 0) {
                    return false;
                }
                if (typeof answerValue === 'object' && Object.keys(answerValue).length === 0) {
                    return false;
                }
                if (answerValue === '') {
                    return false;
                }
                return true;
            }
            return false;
        }

        // Show question
        function showQuestion(index) {
            // Hide all questions
            document.querySelectorAll('.question-container').forEach(q => {
                q.classList.add('d-none');
            });

            // Show current question
            const question = document.getElementById(`question-${questions[index].id}`);
            if (question) {
                question.classList.remove('d-none');
                currentQuestionIndex = index;

                // Update progress
                updateProgress(index + 1);

                // Update navigation items
                updateNavigationItems(questions[index].id);

                // Handle email input visibility
                handleEmailInputVisibility(questions[index].id, questions[index].question_type);
            }
        }

        // Update progress
        function updateProgress(currentQuestionNumber) {
            const totalQuestions = questions.length;
            const progressPercent = Math.round((currentQuestionNumber / totalQuestions) * 100);

            document.getElementById('progressBar').style.width = `${progressPercent}%`;
            document.getElementById('progressText').innerHTML =
                `Progress Pertanyaan: <br>Pertanyaan <span id="currentQuestionNumber">${currentQuestionNumber}</span> dari ${totalQuestions}`;
            document.getElementById('progressPercent').textContent = `${progressPercent}%`;

            // Update urutan kuesioner
            const sequencePercent = Math.round((currentSequenceOrder / totalSequences) * 100);
            document.getElementById('sequenceProgressBar').style.width = `${sequencePercent}%`;
            document.getElementById('sequenceProgressText').textContent =
                `Bagian ${currentSequenceOrder} dari ${totalSequences}`;
            document.getElementById('sequenceProgressPercent').textContent = `${sequencePercent}%`;
        }

        // Update navigation items
        function updateNavigationItems(currentQuestionId) {
            document.querySelectorAll('.question-nav-item').forEach(item => {
                item.classList.remove('active', 'current');

                const itemQuestionId = parseInt(item.getAttribute('data-question-id'));
                if (itemQuestionId === currentQuestionId) {
                    item.classList.add('active', 'current');

                    // Update status icon
                    const statusIcon = item.querySelector('.nav-status');
                    statusIcon.className = 'nav-status current me-3';
                    statusIcon.innerHTML = '<i class="fas fa-pen"></i>';
                }
            });
        }

        // Navigate to question
        function navigateToQuestion(questionId) {
            const index = questions.findIndex(q => q.id === questionId);
            if (index !== -1) {
                showQuestion(index);
            }
        }

        // Save answer via AJAX
        function saveAnswerToServer(questionId, answerData) {
            return fetch("{{ route('questionnaire.answer.store', $question->id) }}".replace(':questionId', questionId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        question_id: questionId,
                        ...answerData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        return true;
                    }
                    return false;
                })
                .catch(error => {
                    console.error('Error saving answer:', error);
                    return false;
                });
        }

        // Skip question
        function skipQuestion(questionId) {
            return fetch("{{ route('questionnaire.answer.skip', $question->id) }}".replace(':questionId', questionId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        _method: 'POST'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mark question as answered
                        answeredQuestions.add(questionId);

                        // Move to next question
                        if (currentQuestionIndex < questions.length - 1) {
                            showQuestion(currentQuestionIndex + 1);
                        }

                        return true;
                    }
                    return false;
                });
        }

        // Handle email input visibility
        function handleEmailInputVisibility(questionId, questionType) {
            const container = document.getElementById(`answer-area-${questionId}`);
            if (!container) return;

            const emailInputContainers = container.querySelectorAll('.email-input-container');
            emailInputContainers.forEach(emailContainer => {
                const isVisible = emailContainer.classList.contains('show');
                if (isVisible) {
                    // Cek apakah option yang memerlukan email dipilih
                    const relatedOption = container.querySelector(
                        'input[type="radio"]:checked, input[type="checkbox"]:checked');
                    if (relatedOption) {
                        const optionValue = relatedOption.value;
                        if (optionValue.includes('email') || optionValue.includes('Ya,')) {
                            emailContainer.classList.add('show');
                            return;
                        }
                    }
                    emailContainer.classList.remove('show');
                }
            });
        }

        // Handle jawaban dengan input email
        function handleEmailAnswerInput(questionId, questionType) {
            const container = document.getElementById(`answer-area-${questionId}`);
            if (!container) return;

            const inputs = container.querySelectorAll('input[type="radio"], input[type="checkbox"]');
            inputs.forEach(input => {
                input.addEventListener('change', function() {
                    const emailContainers = container.querySelectorAll('.email-input-container');
                    emailContainers.forEach(emailContainer => {
                        if (this.value.includes('email') || this.value.includes('Ya,')) {
                            emailContainer.classList.add('show');

                            // Focus ke input email
                            const emailInput = emailContainer.querySelector('.email-input');
                            if (emailInput) {
                                setTimeout(() => emailInput.focus(), 100);
                            }
                        } else {
                            emailContainer.classList.remove('show');
                        }
                    });
                });
            });
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', function() {
            loadSavedAnswers();

            // Initialize email input visibility for all questions
            questions.forEach(question => {
                handleEmailInputVisibility(question.id, question.question_type);
                handleEmailAnswerInput(question.id, question.question_type);
            });

            // Handle answer changes
            document.addEventListener('change', function(e) {
                const target = e.target;
                const questionContainer = target.closest('.question-container');

                if (questionContainer) {
                    const questionId = questionContainer.id.replace('question-', '');
                    const question = questions.find(q => q.id == questionId);

                    if (question) {
                        // Handle email input visibility
                        handleEmailInputVisibility(question.id, question.question_type);

                        // Auto-save answer
                        const answerValue = getAnswerValue(question.id, question.question_type);
                        if (answerValue !== null) {
                            saveAnswer(question.id, answerValue);
                        }
                    }
                }
            });

            // Handle text input changes (email input khusus)
            document.addEventListener('input', function(e) {
                const target = e.target;
                if (target.classList.contains('email-input')) {
                    const questionContainer = target.closest('.question-container');
                    if (questionContainer) {
                        const questionId = questionContainer.id.replace('question-', '');
                        const question = questions.find(q => q.id == questionId);
                        if (question) {
                            // Auto-save answer
                            setTimeout(() => {
                                const answerValue = getAnswerValue(question.id, question
                                    .question_type);
                                if (answerValue !== null) {
                                    saveAnswer(question.id, answerValue);
                                }
                            }, 500);
                        }
                    }
                } else if (target.type === 'text' || target.type === 'textarea' ||
                    target.type === 'number' || target.type === 'date') {
                    const questionContainer = target.closest('.question-container');
                    if (questionContainer) {
                        const questionId = questionContainer.id.replace('question-', '');
                        const question = questions.find(q => q.id == questionId);
                        if (question) {
                            // Auto-save answer
                            setTimeout(() => {
                                const answerValue = getAnswerValue(question.id, question
                                    .question_type);
                                if (answerValue !== null) {
                                    saveAnswer(question.id, answerValue);
                                }
                            }, 500);
                        }
                    }
                }
            });

            // Next question button - Validasi jawaban sebelum lanjut
            document.querySelectorAll('.btn-next-question').forEach(btn => {
                btn.addEventListener('click', function() {
                    const currentQuestion = questions[currentQuestionIndex];

                    // Validasi pertanyaan wajib
                    if (currentQuestion.is_required) {
                        if (!isQuestionAnswered(currentQuestion.id, currentQuestion
                            .question_type)) {
                            alert('Harap isi jawaban untuk pertanyaan ini sebelum melanjutkan.');
                            return;
                        }
                    }

                    // Save answer to server
                    const answerValue = getAnswerValue(currentQuestion.id, currentQuestion
                        .question_type);
                    if (answerValue !== null) {
                        let answerData = {};

                        if (currentQuestion.question_type === 'checkbox' || currentQuestion
                            .question_type === 'checkbox_per_row') {
                            answerData.selected_options = answerValue;
                        } else if (currentQuestion.is_scale || currentQuestion.question_type ===
                            'likert_per_row') {
                            answerData.scale_value = answerValue;
                        } else {
                            answerData.answer = answerValue;
                        }

                        saveAnswerToServer(currentQuestion.id, {
                            ...answerData,
                            question_type: currentQuestion.question_type
                        });
                    }

                    // Show next question
                    if (currentQuestionIndex < questions.length - 1) {
                        showQuestion(currentQuestionIndex + 1);
                    }
                });
            });

            // Previous question button
            document.querySelectorAll('.btn-prev-question').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (currentQuestionIndex > 0) {
                        showQuestion(currentQuestionIndex - 1);
                    }
                });
            });

            // Skip question button
            document.querySelectorAll('.btn-skip-question').forEach(btn => {
                btn.addEventListener('click', function() {
                    const questionId = parseInt(this.getAttribute('data-question-id'));
                    if (confirm('Apakah Anda yakin ingin melewati pertanyaan ini?')) {
                        skipQuestion(questionId);
                    }
                });
            });

            // Save all button
            document.getElementById('saveAllBtn').addEventListener('click', function() {
                // Save all answers
                const promises = questions.map(question => {
                    const answerValue = getAnswerValue(question.id, question.question_type);
                    if (answerValue !== null) {
                        let answerData = {};

                        if (question.question_type === 'checkbox' || question.question_type ===
                            'checkbox_per_row') {
                            answerData.selected_options = answerValue;
                        } else if (question.is_scale || question.question_type ===
                            'likert_per_row') {
                            answerData.scale_value = answerValue;
                        } else {
                            answerData.answer = answerValue;
                        }

                        return saveAnswerToServer(question.id, {
                            ...answerData,
                            question_type: question.question_type
                        });
                    }
                    return Promise.resolve(true);
                });

                Promise.all(promises).then(results => {
                    const successCount = results.filter(r => r).length;
                    alert(`${successCount} dari ${questions.length} jawaban berhasil disimpan.`);
                });
            });

            // Submit questionnaire button
            document.getElementById('submitQuestionnaireBtn').addEventListener('click', function() {
                // Validate all required questions
                let hasErrors = false;
                let firstErrorQuestion = null;

                questions.forEach(question => {
                    if (question.is_required && !answeredQuestions.has(question.id)) {
                        if (!isQuestionAnswered(question.id, question.question_type)) {
                            hasErrors = true;
                            if (!firstErrorQuestion) {
                                firstErrorQuestion = question;
                            }
                        }
                    }
                });

                if (hasErrors && firstErrorQuestion) {
                    navigateToQuestion(firstErrorQuestion.id);
                    alert(
                        `Harap isi pertanyaan ${questions.findIndex(q => q.id === firstErrorQuestion.id) + 1}: ${firstErrorQuestion.question_text}`
                    );
                    return;
                }

                // Show confirmation modal
                const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
                confirmationModal.show();
            });

            // Confirm submit button
            document.getElementById('confirmSubmitBtn').addEventListener('click', function() {
                // Close confirmation modal
                const confirmationModal = bootstrap.Modal.getInstance(document.getElementById(
                    'confirmationModal'));
                confirmationModal.hide();

                // Show loading modal
                const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
                loadingModal.show();

                // Submit form via AJAX
                const formData = new FormData();
                formData.append('_token', csrfToken);
                formData.append('questionnaire_id', questionnaireId);

                // Collect all answers
                questions.forEach(question => {
                    const answerValue = getAnswerValue(question.id, question.question_type);
                    if (answerValue !== null) {
                        if (question.question_type === 'checkbox' || question.question_type ===
                            'checkbox_per_row') {
                            if (Array.isArray(answerValue)) {
                                answerValue.forEach(value => {
                                    formData.append(`answers[${question.id}][]`, value);
                                });
                            }
                        } else if (question.question_type === 'likert_per_row') {
                            if (typeof answerValue === 'object') {
                                Object.keys(answerValue).forEach(key => {
                                    formData.append(`answers[${question.id}][${key}]`,
                                        answerValue[key]);
                                });
                            }
                        } else {
                            formData.append(`answers[${question.id}]`,
                                typeof answerValue === 'object' ? JSON.stringify(answerValue) :
                                answerValue);
                        }
                    }
                });

                // Kirim data ke server
                fetch("{{ route('questionnaire.submit', $questionnaire->id) }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        loadingModal.hide();

                        if (data.success) {
                            // Clear localStorage
                            localStorage.removeItem(`questionnaire_${questionnaireId}_answers`);

                            // Redirect berdasarkan respons
                            if (data.redirect_url) {
                                window.location.href = data.redirect_url;
                            } else if (data.completed) {
                                window.location.href = "{{ route('questionnaire.completed') }}";
                            } else {
                                window.location.href = data.redirect_url ||
                                    "{{ route('questionnaire.dashboard') }}";
                            }
                        } else {
                            alert(data.message || 'Terjadi kesalahan saat mengirim kuesioner.');
                        }
                    })
                    .catch(error => {
                        loadingModal.hide();
                        console.error('Error:', error);
                        alert('Terjadi kesalahan jaringan.');
                    });
            });

            // Initialize first question
            if (questions.length > 0) {
                showQuestion(0);
            }
        });

        // Make navigateToQuestion available globally
        window.navigateToQuestion = navigateToQuestion;
    </script>
</body>

</html>
