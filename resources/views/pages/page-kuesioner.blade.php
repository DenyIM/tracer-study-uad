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

        .question-nav-item.locked {
            cursor: not-allowed;
            opacity: 0.6;
            background-color: #f8f9fa;
        }

        .question-nav-item.locked:hover {
            background-color: #f8f9fa;
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

        .nav-status.locked {
            background-color: #dc3545;
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
            margin-left: 10px;
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
                    <button type="button" class="btn btn-primary-custom" id="confirmSubmitBtn">Ya, Kirim
                        Bagian Ini</button>
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

    <header class="questionnaire-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0" style="color: var(--primary-blue);">Kuesioner Tracer Study UAD</h3>
                    <div class="mt-1">
                        <span class="category-badge">Bekerja di Perusahaan</span>
                        <span class="section-badge">Bagian 1: Informasi Karir Awal</span>
                    </div>
                </div>
                <div class="text-end">
                    <p class="mb-0"><strong>Deny Iqbal</strong></p>
                    <p class="text-muted mb-0 small">Teknik Informatika 2018</p>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="container py-4">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="progress-section p-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-semibold" id="progressText">Progress: Pertanyaan 1 dari 2</span>
                            <span class="fw-bold text-accent" id="progressPercent">50%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" id="progressBar" style="width: 50%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 mb-4" data-aos="fade-right">
                    <div class="question-nav-card p-3">
                        <h5 class="fw-bold mb-3" style="color: var(--primary-blue);">Daftar Pertanyaan</h5>

                        <div class="question-nav-item active current" data-question-id="1">
                            <div class="d-flex align-items-center">
                                <div class="nav-question-number">1</div>
                                <div class="nav-status current me-3">
                                    <i class="fas fa-pen"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span>Lama Dapat Pekerjaan</span>
                                </div>
                            </div>
                        </div>

                        <div class="question-nav-item locked" data-question-id="2">
                            <div class="d-flex align-items-center">
                                <div class="nav-question-number">2</div>
                                <div class="nav-status locked me-3">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span>Pendapatan Per Bulan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8" data-aos="fade-left">
                    <div class="question-card p-4">
                        <div class="d-flex align-items-start mb-4">
                            <div class="question-number" id="currentQuestionNumber">1</div>
                            <div class="flex-grow-1 ms-3">
                                <h4 class="fw-bold mb-2" id="currentQuestionTitle">Sejak lulus, berapa lama Anda
                                    mendapat pekerjaan sebagai karyawan untuk pertama kali?</h4>
                                <p class="text-muted mb-0" id="currentQuestionDescription">Pilih satu opsi yang sesuai
                                </p>
                            </div>
                        </div>

                        <div id="dynamicAnswerArea">
                            <!-- Area ini akan diisi secara dinamis berdasarkan tipe pertanyaan -->
                            <!-- Untuk contoh statis: pertanyaan 1 kategori Bekerja di Perusahaan - Bagian 1 -->
                            <div class="mb-4">
                                <div class="answer-option" data-option="3-<6 bulan">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="question_radio"
                                            id="radio_option_1" value="3-<6 bulan" required>
                                        <label class="form-check-label fw-medium" for="radio_option_1">
                                            3-<6 bulan </label>
                                    </div>
                                </div>

                                <div class="answer-option" data-option="6-<9 bulan">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="question_radio"
                                            id="radio_option_2" value="6-<9 bulan" required>
                                        <label class="form-check-label fw-medium" for="radio_option_2">
                                            6-<9 bulan </label>
                                    </div>
                                </div>

                                <div class="answer-option" data-option="9-<12 bulan">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="question_radio"
                                            id="radio_option_3" value="9-<12 bulan" required>
                                        <label class="form-check-label fw-medium" for="radio_option_3">
                                            9-<12 bulan </label>
                                    </div>
                                </div>

                                <div class="answer-option" data-option=">12 bulan">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="question_radio"
                                            id="radio_option_4" value=">12 bulan" required>
                                        <label class="form-check-label fw-medium" for="radio_option_4">
                                            >12 bulan
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-3 navigation-buttons">
                            <div class="btn-group-left">
                                <button class="btn btn-outline-secondary" id="backToMainBtn">
                                    <i class="fas fa-home me-2"></i> Kembali ke Halaman Utama
                                </button>
                                <button class="btn btn-outline-primary" id="prevQuestionBtn">
                                    <i class="fas fa-arrow-left me-2"></i> Pertanyaan Sebelumnya
                                </button>
                            </div>

                            <div class="btn-group-right" id="dynamicButtonGroup">
                                <button class="btn btn-outline-secondary" id="saveDraftBtn">
                                    <i class="fas fa-save me-2"></i> Simpan Sementara
                                </button>
                                <button class="btn btn-primary-custom" id="nextQuestionBtn">
                                    Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; 2025 Tracer Study Universitas Ahmad Dahlan.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Konfigurasi
        const CATEGORY_NAME = "Bekerja di Perusahaan";
        const SECTION_NAME = "Bagian 1: Informasi Karir Awal";
        const TOTAL_QUESTIONS_IN_SECTION = 2;

        let currentQuestionId = 1;
        let highestAnsweredQuestion = 1;

        // Data pertanyaan untuk Bekerja di Perusahaan - Bagian 1
        const questions = {
            1: {
                title: "Sejak lulus, berapa lama Anda mendapat pekerjaan sebagai karyawan untuk pertama kali?",
                description: "Pilih satu opsi yang sesuai",
                number: 1,
                type: "radio-options",
                options: [
                    "3-<6 bulan",
                    "6-<9 bulan",
                    "9-<12 bulan",
                    ">12 bulan"
                ],
                required: true
            },
            2: {
                title: "Rata-rata pendapatan bersih (take home pay) per bulan?",
                description: "Pilih satu opsi yang sesuai",
                number: 2,
                type: "radio-options",
                options: [
                    "0-<3 juta",
                    "3-<6 juta",
                    "6-<10 juta",
                    ">10 juta"
                ],
                required: true
            }
        };

        // Function to render dynamic answer area berdasarkan tipe pertanyaan
        function renderAnswerArea(questionId) {
            const question = questions[questionId];
            const answerArea = document.getElementById('dynamicAnswerArea');

            if (!question) return;

            let html = '';

            switch (question.type) {
                case 'dropdown':
                    html = renderDropdownOptions(question);
                    break;
                case 'radio-options':
                    html = renderRadioOptions(question);
                    break;
                case 'text-input':
                    html = renderTextInput(question);
                    break;
                case 'textarea':
                    html = renderTextarea(question);
                    break;
                case 'date-input':
                    html = renderDateInput(question);
                    break;
                case 'number-input':
                    html = renderNumberInput(question);
                    break;
                case 'checkbox-options':
                    html = renderCheckboxOptions(question);
                    break;
                case 'competency-scale':
                    html = renderCompetencyScale(question);
                    break;
                default:
                    html = '<p>Tipe pertanyaan tidak dikenali</p>';
            }

            answerArea.innerHTML = html;

            // Attach event listeners setelah render
            attachAnswerEvents();

            // Restore saved answer jika ada
            restoreAnswer(questionId);
        }

        // Render radio options
        function renderRadioOptions(question) {
            let html = '<div class="mb-4">';

            question.options.forEach((option, index) => {
                html += `
                    <div class="answer-option" data-option="${option}">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" 
                                   name="question_radio" 
                                   id="radio_option_${index}" 
                                   value="${option}"
                                   ${question.required ? 'required' : ''}>
                            <label class="form-check-label fw-medium" for="radio_option_${index}">
                                ${option}
                            </label>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            return html;
        }

        // Render dropdown options
        function renderDropdownOptions(question) {
            let html = '<div class="mb-4">';

            html += `
                <select class="form-select form-select-lg mb-3" id="question_dropdown" ${question.required ? 'required' : ''}>
                    <option value="" selected disabled>Pilih salah satu...</option>
            `;

            question.options.forEach(option => {
                html += `<option value="${option}">${option}</option>`;
            });

            html += `</select>`;

            if (question.hasOther) {
                html += `
                    <div class="mt-3" id="otherInputContainer" style="display: none;">
                        <label for="otherInput" class="form-label fw-medium">Sebutkan:</label>
                        <input type="text" class="form-control" id="otherInput" placeholder="Tuliskan...">
                    </div>
                `;
            }

            html += '</div>';
            return html;
        }

        // Render text input
        function renderTextInput(question) {
            return `
                <div class="mb-4">
                    <input type="text" class="form-control form-control-lg" 
                           id="text_input" 
                           placeholder="Tuliskan jawaban Anda..."
                           ${question.required ? 'required' : ''}>
                </div>
            `;
        }

        // Render textarea
        function renderTextarea(question) {
            return `
                <div class="mb-4">
                    <textarea class="form-control" 
                              id="textarea_input" 
                              rows="4" 
                              placeholder="Tuliskan jawaban Anda..."
                              ${question.required ? 'required' : ''}></textarea>
                </div>
            `;
        }

        // Render date input
        function renderDateInput(question) {
            return `
                <div class="mb-4">
                    <input type="date" class="form-control form-control-lg" 
                           id="date_input" 
                           ${question.required ? 'required' : ''}>
                </div>
            `;
        }

        // Render number input
        function renderNumberInput(question) {
            return `
                <div class="mb-4">
                    <input type="number" class="form-control form-control-lg" 
                           id="number_input" 
                           placeholder="Masukkan angka..."
                           min="0"
                           ${question.required ? 'required' : ''}>
                </div>
            `;
        }

        // Render checkbox options
        function renderCheckboxOptions(question) {
            let html = '<div class="mb-4">';

            question.options.forEach((option, index) => {
                html += `
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" 
                               id="checkbox_option_${index}" 
                               value="${option}">
                        <label class="form-check-label" for="checkbox_option_${index}">
                            ${option}
                        </label>
                    </div>
                `;
            });

            if (question.hasOther) {
                html += `
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" 
                               id="checkbox_other" 
                               value="other">
                        <label class="form-check-label" for="checkbox_other">
                            Lainnya:
                        </label>
                    </div>
                    <div class="mt-2 ms-4" id="otherCheckboxInputContainer" style="display: none;">
                        <input type="text" class="form-control" id="otherCheckboxInput" placeholder="Tuliskan...">
                    </div>
                `;
            }

            html += '</div>';
            return html;
        }

        // Function to render dynamic buttons
        function renderButtons(questionId) {
            const buttonGroup = document.getElementById('dynamicButtonGroup');
            const isLastQuestion = questionId === TOTAL_QUESTIONS_IN_SECTION;

            let html = `
                <button class="btn btn-outline-secondary" id="saveDraftBtn">
                    <i class="fas fa-save me-2"></i> Simpan Sementara
                </button>
            `;

            if (isLastQuestion) {
                html += `
                    <button class="btn btn-success" id="submitQuestionnaireBtn">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Bagian Ini
                    </button>
                `;
            } else {
                html += `
                    <button class="btn btn-primary-custom" id="nextQuestionBtn">
                        Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                `;
            }

            buttonGroup.innerHTML = html;

            // Re-attach event listeners
            document.getElementById('saveDraftBtn').addEventListener('click', saveDraft);
            if (isLastQuestion) {
                document.getElementById('submitQuestionnaireBtn').addEventListener('click', showConfirmationModal);
            } else {
                document.getElementById('nextQuestionBtn').addEventListener('click', goToNextQuestion);
            }
        }

        // Attach events untuk answer options
        function attachAnswerEvents() {
            // Untuk dropdown dengan opsi "Lainnya"
            const dropdown = document.getElementById('question_dropdown');
            if (dropdown) {
                dropdown.addEventListener('change', function() {
                    const otherInputContainer = document.getElementById('otherInputContainer');
                    if (otherInputContainer && this.value && this.value.includes("Lainnya")) {
                        otherInputContainer.style.display = 'block';
                        document.getElementById('otherInput').required = true;
                    } else if (otherInputContainer) {
                        otherInputContainer.style.display = 'none';
                        document.getElementById('otherInput').required = false;
                    }

                    // Simpan jawaban saat berubah
                    saveAnswer();
                });
            }

            // Untuk radio options
            const radioOptions = document.querySelectorAll('.answer-option');
            radioOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const radioInput = this.querySelector('input[type="radio"]');
                    if (radioInput) {
                        radioInput.checked = true;

                        // Add visual feedback
                        document.querySelectorAll('.answer-option').forEach(opt => {
                            opt.classList.remove('selected');
                        });
                        this.classList.add('selected');

                        // Simpan jawaban
                        saveAnswer();
                    }
                });
            });

            // Untuk checkbox dengan opsi "Lainnya"
            const otherCheckbox = document.getElementById('checkbox_other');
            if (otherCheckbox) {
                otherCheckbox.addEventListener('change', function() {
                    const otherCheckboxInputContainer = document.getElementById('otherCheckboxInputContainer');
                    if (otherCheckboxInputContainer) {
                        otherCheckboxInputContainer.style.display = this.checked ? 'block' : 'none';
                    }
                });
            }

            // Untuk semua input
            document.querySelectorAll('input, textarea, select').forEach(input => {
                if (input.type !== 'radio' && input.type !== 'checkbox') {
                    input.addEventListener('input', saveAnswer);
                }
            });

            // Untuk radio input
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', saveAnswer);
            });

            // Untuk checkbox input
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.addEventListener('change', saveAnswer);
            });
        }

        // Restore saved answer
        function restoreAnswer(questionId) {
            const savedAnswers = JSON.parse(localStorage.getItem('kuesioner_draft') || '{}');
            const savedAnswer = savedAnswers[questionId];

            if (!savedAnswer) return;

            const question = questions[questionId];

            switch (question.type) {
                case 'dropdown':
                    const dropdown = document.getElementById('question_dropdown');
                    if (dropdown) {
                        if (savedAnswer.startsWith && savedAnswer.startsWith('Lainnya:')) {
                            dropdown.value = "Lainnya, sebutkan!";
                            const otherInputContainer = document.getElementById('otherInputContainer');
                            const otherInput = document.getElementById('otherInput');
                            if (otherInputContainer && otherInput) {
                                otherInputContainer.style.display = 'block';
                                otherInput.value = savedAnswer.replace('Lainnya: ', '');
                            }
                        } else {
                            dropdown.value = savedAnswer;
                        }
                    }
                    break;

                case 'radio-options':
                    const radioInput = document.querySelector(`input[value="${savedAnswer}"]`);
                    if (radioInput) {
                        radioInput.checked = true;
                        const answerOption = radioInput.closest('.answer-option');
                        if (answerOption) {
                            document.querySelectorAll('.answer-option').forEach(opt => {
                                opt.classList.remove('selected');
                            });
                            answerOption.classList.add('selected');
                        }
                    }
                    break;

                case 'text-input':
                    const textInput = document.getElementById('text_input');
                    if (textInput) textInput.value = savedAnswer;
                    break;

                case 'textarea':
                    const textarea = document.getElementById('textarea_input');
                    if (textarea) textarea.value = savedAnswer;
                    break;

                case 'date-input':
                    const dateInput = document.getElementById('date_input');
                    if (dateInput) dateInput.value = savedAnswer;
                    break;

                case 'number-input':
                    const numberInput = document.getElementById('number_input');
                    if (numberInput) numberInput.value = savedAnswer;
                    break;

                case 'checkbox-options':
                    if (Array.isArray(savedAnswer)) {
                        savedAnswer.forEach(value => {
                            const checkbox = document.querySelector(`input[value="${value}"]`);
                            if (checkbox) checkbox.checked = true;
                        });
                    }
                    break;
            }
        }

        // Function to update progress
        function updateProgress(questionId) {
            const progressPercent = (questionId / TOTAL_QUESTIONS_IN_SECTION) * 100;
            const progressText = `Progress: Pertanyaan ${questionId} dari ${TOTAL_QUESTIONS_IN_SECTION}`;
            const progressPercentText = `${Math.round(progressPercent)}%`;

            document.getElementById('progressBar').style.width = `${progressPercent}%`;
            document.getElementById('progressText').textContent = progressText;
            document.getElementById('progressPercent').textContent = progressPercentText;
        }

        // Question navigation functionality dengan pembatasan
        document.querySelectorAll('.question-nav-item').forEach(item => {
            item.addEventListener('click', function() {
                const questionId = parseInt(this.getAttribute('data-question-id'));

                // Cek apakah pertanyaan ini sudah dijawab atau tidak
                if (questionId <= highestAnsweredQuestion) {
                    navigateToQuestion(questionId);
                } else {
                    showNotification('Anda harus menyelesaikan pertanyaan sebelumnya terlebih dahulu',
                        'warning');
                }
            });
        });

        // Function untuk navigasi pertanyaan
        function navigateToQuestion(questionId) {
            currentQuestionId = questionId;
            updateQuestionDisplay(questionId);
            updateNavigationStates(questionId);
            updateProgress(questionId);
            renderAnswerArea(questionId);
            renderButtons(questionId);
        }

        // Function to update question display
        function updateQuestionDisplay(questionId) {
            const question = questions[questionId];

            if (!question) return;

            document.getElementById('currentQuestionNumber').textContent = question.number;
            document.getElementById('currentQuestionTitle').textContent = question.title;
            document.getElementById('currentQuestionDescription').textContent = question.description;
        }

        // Function to update navigation states dengan status locked
        function updateNavigationStates(questionId) {
            document.querySelectorAll('.question-nav-item').forEach(navItem => {
                navItem.classList.remove('active', 'current', 'locked');

                const statusIcon = navItem.querySelector('.nav-status');
                const itemQuestionId = parseInt(navItem.getAttribute('data-question-id'));

                if (itemQuestionId < currentQuestionId) {
                    navItem.classList.add('answered');
                    statusIcon.className = 'nav-status answered me-3';
                    statusIcon.innerHTML = '<i class="fas fa-check"></i>';
                } else if (itemQuestionId === currentQuestionId) {
                    navItem.classList.add('current');
                    statusIcon.className = 'nav-status current me-3';
                    statusIcon.innerHTML = '<i class="fas fa-pen"></i>';
                } else if (itemQuestionId > highestAnsweredQuestion) {
                    navItem.classList.add('locked');
                    statusIcon.className = 'nav-status locked me-3';
                    statusIcon.innerHTML = '<i class="fas fa-lock"></i>';
                } else {
                    navItem.classList.remove('answered');
                    statusIcon.className = 'nav-status pending me-3';
                    statusIcon.innerHTML = '';
                }
            });

            const currentNavItem = document.querySelector(`[data-question-id="${questionId}"]`);
            if (currentNavItem) {
                currentNavItem.classList.add('active');
            }
        }

        // Navigation functions
        document.getElementById('prevQuestionBtn').addEventListener('click', function() {
            if (currentQuestionId > 1) {
                navigateToQuestion(currentQuestionId - 1);
            }
        });

        function goToNextQuestion() {
            // Validasi input terlebih dahulu
            if (!validateCurrentQuestion()) {
                showNotification('Harap lengkapi jawaban untuk pertanyaan ini', 'warning');
                return;
            }

            if (currentQuestionId < TOTAL_QUESTIONS_IN_SECTION) {
                // Update highestAnsweredQuestion jika user menjawab pertanyaan baru
                if (currentQuestionId === highestAnsweredQuestion) {
                    highestAnsweredQuestion = currentQuestionId + 1;
                }
                navigateToQuestion(currentQuestionId + 1);
            }
        }

        // Validasi pertanyaan saat ini
        function validateCurrentQuestion() {
            const question = questions[currentQuestionId];
            if (!question.required) return true;

            const answer = getQuestionAnswer(currentQuestionId);
            return answer !== null && answer !== '';
        }

        // Validasi semua pertanyaan sebelum submit
        function validateAllQuestions() {
            const savedAnswers = JSON.parse(localStorage.getItem('kuesioner_draft') || '{}');

            for (let i = 1; i <= TOTAL_QUESTIONS_IN_SECTION; i++) {
                const question = questions[i];

                if (question.required) {
                    const answer = savedAnswers[i];

                    if (!answer || answer === "" || (Array.isArray(answer) && answer.length === 0)) {
                        // Navigasi ke pertanyaan yang belum diisi
                        navigateToQuestion(i);
                        showNotification(`Harap lengkapi pertanyaan nomor ${i} terlebih dahulu`, 'warning');
                        return false;
                    }
                }
            }
            return true;
        }

        // Tombol kembali ke halaman utama
        document.getElementById('backToMainBtn').addEventListener('click', function() {
            if (confirm(
                    'Apakah Anda yakin ingin kembali ke halaman utama? Perubahan yang belum disimpan akan hilang.'
                )) {
                window.location.href = '/nav-kuesioner';
            }
        });

        // Simpan jawaban untuk pertanyaan saat ini
        function saveAnswer() {
            const answer = getQuestionAnswer(currentQuestionId);
            if (answer !== null && answer !== '') {
                // Update UI untuk menandai pertanyaan telah dijawab
                const navItem = document.querySelector(`[data-question-id="${currentQuestionId}"]`);
                if (navItem && !navItem.classList.contains('answered')) {
                    navItem.classList.add('answered');
                    const statusIcon = navItem.querySelector('.nav-status');
                    if (statusIcon) {
                        statusIcon.className = 'nav-status answered me-3';
                        statusIcon.innerHTML = '<i class="fas fa-check"></i>';
                    }
                }

                // Simpan ke localStorage
                const savedAnswers = JSON.parse(localStorage.getItem('kuesioner_draft') || '{}');
                savedAnswers[currentQuestionId] = answer;
                localStorage.setItem('kuesioner_draft', JSON.stringify(savedAnswers));

                // Update highestAnsweredQuestion jika perlu
                if (currentQuestionId === highestAnsweredQuestion && currentQuestionId < TOTAL_QUESTIONS_IN_SECTION) {
                    highestAnsweredQuestion = currentQuestionId + 1;
                    updateNavigationStates(currentQuestionId);
                }
            }
        }

        // Simpan sementara
        function saveDraft() {
            saveAnswer();
            showNotification('Jawaban berhasil disimpan sementara!', 'success');
        }

        // Ambil jawaban untuk pertanyaan tertentu
        function getQuestionAnswer(questionId) {
            const question = questions[questionId];
            if (!question) return null;

            switch (question.type) {
                case 'dropdown':
                    const dropdown = document.getElementById('question_dropdown');
                    if (!dropdown || !dropdown.value) return null;

                    const value = dropdown.value;
                    if (value === "Lainnya, sebutkan!" || value.includes("Lainnya")) {
                        const otherInput = document.getElementById('otherInput');
                        return otherInput && otherInput.value.trim() ? `Lainnya: ${otherInput.value}` : value;
                    }
                    return value;

                case 'radio-options':
                    const selectedRadio = document.querySelector('input[name="question_radio"]:checked');
                    return selectedRadio ? selectedRadio.value : null;

                case 'text-input':
                    const textInput = document.getElementById('text_input');
                    return textInput ? textInput.value.trim() : null;

                case 'textarea':
                    const textarea = document.getElementById('textarea_input');
                    return textarea ? textarea.value.trim() : null;

                case 'date-input':
                    const dateInput = document.getElementById('date_input');
                    return dateInput ? dateInput.value : null;

                case 'number-input':
                    const numberInput = document.getElementById('number_input');
                    return numberInput ? numberInput.value : null;

                case 'checkbox-options':
                    const selectedCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
                    const values = Array.from(selectedCheckboxes).map(cb => {
                        if (cb.id === 'checkbox_other') {
                            const otherInput = document.getElementById('otherCheckboxInput');
                            return otherInput && otherInput.value.trim() ? `Lainnya: ${otherInput.value}` :
                                'Lainnya';
                        }
                        return cb.value;
                    });
                    return values.length > 0 ? values : null;

                default:
                    return null;
            }
        }

        // Tampilkan modal konfirmasi
        function showConfirmationModal() {
            if (!validateAllQuestions()) {
                return;
            }

            const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
            confirmationModal.show();
        }

        // Event listener untuk tombol konfirmasi
        document.getElementById('confirmSubmitBtn').addEventListener('click', function() {
            // Tutup modal konfirmasi
            const confirmationModal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
            confirmationModal.hide();

            // Tampilkan loading modal
            const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
            loadingModal.show();

            // Kirim kuesioner (simulasi)
            setTimeout(() => {
                // Kumpulkan semua jawaban dari localStorage
                const allAnswers = JSON.parse(localStorage.getItem('kuesioner_draft') || '{}');

                // Simpan sebagai submitted untuk bagian ini
                const submittedSections = JSON.parse(localStorage.getItem('kuesioner_submitted_sections') ||
                    '{}');
                submittedSections['bekerja_bagian_1'] = allAnswers;
                localStorage.setItem('kuesioner_submitted_sections', JSON.stringify(submittedSections));

                // Hapus draft setelah berhasil submit
                localStorage.removeItem('kuesioner_draft');

                // Tampilkan notifikasi sukses
                showNotification('Bagian kuesioner berhasil dikirim! Mengalihkan ke bagian berikutnya...',
                    'success');

                // Redirect ke halaman berikutnya setelah 1.5 detik
                setTimeout(() => {
                    loadingModal.hide();
                    // Redirect ke halaman bagian berikutnya (Bagian 2)
                    window.location.href = '/section2-kuesioner';
                }, 1500);

            }, 1000);
        });

        // Function untuk menampilkan notifikasi
        function showNotification(message, type = 'info') {
            const toast = document.createElement('div');
            const bgColor = type === 'warning' ? 'warning' : type === 'success' ? 'success' : 'info';

            toast.className = `toast align-items-center text-white bg-${bgColor} border-0 position-fixed top-0 end-0 m-3`;
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-${type === 'success' ? 'check' : type === 'warning' ? 'exclamation-triangle' : 'info'}-circle me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;

            document.body.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();

            toast.addEventListener('hidden.bs.toast', function() {
                document.body.removeChild(toast);
            });
        }

        // Initialize
        updateProgress(currentQuestionId);
        updateNavigationStates(currentQuestionId);
        renderAnswerArea(currentQuestionId);
        renderButtons(currentQuestionId);

        // Load saved answers saat inisialisasi
        const savedAnswers = JSON.parse(localStorage.getItem('kuesioner_draft') || '{}');

        // Tentukan highestAnsweredQuestion berdasarkan jawaban yang sudah disimpan
        for (let i = 1; i <= TOTAL_QUESTIONS_IN_SECTION; i++) {
            if (savedAnswers[i]) {
                highestAnsweredQuestion = Math.max(highestAnsweredQuestion, i + 1);
            }
        }

        // Pastikan highestAnsweredQuestion tidak melebihi TOTAL_QUESTIONS_IN_SECTION
        highestAnsweredQuestion = Math.min(highestAnsweredQuestion, TOTAL_QUESTIONS_IN_SECTION);

        updateNavigationStates(currentQuestionId);

        // Attach event listeners untuk answer options statis
        document.querySelectorAll('.answer-option').forEach(option => {
            option.addEventListener('click', function() {
                const radioInput = this.querySelector('input[type="radio"]');
                if (radioInput) {
                    radioInput.checked = true;

                    // Add visual feedback
                    document.querySelectorAll('.answer-option').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                    this.classList.add('selected');

                    // Simpan jawaban
                    saveAnswer();
                }
            });
        });
    </script>
</body>

</html>
