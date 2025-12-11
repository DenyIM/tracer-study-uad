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

        .question-code {
            font-size: 0.85rem;
            color: var(--primary-blue);
            background-color: var(--light-blue);
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 8px;
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

        .nav-question-code {
            font-size: 0.75rem;
            color: var(--primary-blue);
            background-color: #f0f7ff;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 600;
            margin-left: auto;
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

        @media (max-width: 768px) {
            .question-nav-card {
                margin-bottom: 20px;
            }
            
            .navigation-buttons {
                flex-direction: column;
                gap: 15px;
            }
            
            .btn-group-left, .btn-group-right {
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
                    <p>Apakah Anda yakin ingin mengirim kuesioner ini?</p>
                    <p class="text-muted small">Setelah dikirim, Anda tidak dapat mengubah jawaban.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary-custom" id="confirmSubmitBtn">Ya, Kirim Sekarang</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Loading -->
    <div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
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
                    <p class="text-muted mb-0">BAGIAN 1: STATUS DAN DATA DASAR</p>
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
                            <span class="fw-semibold" id="progressText">Progress: Pertanyaan 1 dari 5</span>
                            <span class="fw-bold text-accent" id="progressPercent">20%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" id="progressBar" style="width: 20%"></div>
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
                                    <span>Status Saat Ini</span>
                                </div>
                                <div class="nav-question-code">F8</div>
                            </div>
                        </div>

                        <div class="question-nav-item locked" data-question-id="2">
                            <div class="d-flex align-items-center">
                                <div class="nav-question-number">2</div>
                                <div class="nav-status locked me-3">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span>Lama Dapat Pekerjaan</span>
                                </div>
                                <div class="nav-question-code">F502</div>
                            </div>
                        </div>

                        <div class="question-nav-item locked" data-question-id="3">
                            <div class="d-flex align-items-center">
                                <div class="nav-question-number">3</div>
                                <div class="nav-status locked me-3">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span>Sumber Dana Kuliah</span>
                                </div>
                                <div class="nav-question-code">F12</div>
                            </div>
                        </div>

                        <div class="question-nav-item locked" data-question-id="4">
                            <div class="d-flex align-items-center">
                                <div class="nav-question-number">4</div>
                                <div class="nav-status locked me-3">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span>Kompetensi Saat Lulus</span>
                                </div>
                                <div class="nav-question-code">F17.A</div>
                            </div>
                        </div>

                        <div class="question-nav-item locked" data-question-id="5">
                            <div class="d-flex align-items-center">
                                <div class="nav-question-number">5</div>
                                <div class="nav-status locked me-3">
                                    <i class="fas fa-lock"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span>Metode Pembelajaran</span>
                                </div>
                                <div class="nav-question-code">F21-F27</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8" data-aos="fade-left">
                    <div class="question-card p-4">
                        <div class="d-flex align-items-start mb-4">
                            <div class="question-number" id="currentQuestionNumber">1</div>
                            <div class="flex-grow-1 ms-3">
                                <div class="question-code" id="currentQuestionCode">F8</div>
                                <h4 class="fw-bold mb-2" id="currentQuestionTitle">Jelaskan status Anda saat ini?</h4>
                                <p class="text-muted mb-0" id="currentQuestionDescription">Pertanyaan Pemisah Rute - Pilih satu opsi yang sesuai</p>
                            </div>
                        </div>

                        <div id="dynamicAnswerArea">
                            <!-- Area ini akan diisi secara dinamis berdasarkan tipe pertanyaan -->
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
                                <!-- Tombol akan diisi secara dinamis -->
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
        const TOTAL_QUESTIONS = 5;
        let currentQuestionId = 1;
        let highestAnsweredQuestion = 1;

        // Data pertanyaan berdasarkan spesifikasi Anda
        const questions = {
            1: {
                title: "Jelaskan status Anda saat ini?",
                description: "Pertanyaan Pemisah Rute - Pilih satu opsi yang sesuai",
                code: "F8",
                number: 1,
                type: "dropdown",
                options: [
                    "Bekerja (full time/part time) di perusahaan/instansi",
                    "Wiraswasta/Pemilik Usaha",
                    "Melanjutkan Pendidikan",
                    "Tidak Kerja, tetapi sedang mencari kerja",
                    "Belum memungkinkan bekerja / Tidak mencari kerja"
                ],
                required: true
            },
            2: {
                title: "Berapa lama Anda mendapat pekerjaan sebagai karyawan/wirausaha pertama kali?",
                description: "Pilih satu opsi yang sesuai",
                code: "F502",
                number: 2,
                type: "radio-options",
                options: [
                    "Belum mendapat pekerjaan",
                    "0-<3 bulan",
                    "3-<6 bulan",
                    "6-<9 bulan",
                    "9-<12 bulan",
                    ">12 bulan"
                ],
                required: true
            },
            3: {
                title: "Sebutkan sumber dana utama pembiayaan kuliah S1 Anda?",
                description: "",
                code: "F12",
                number: 3,
                type: "dropdown",
                options: [
                    "Biaya Sendiri/Keluarga",
                    "Beasiswa ADIK",
                    "Beasiswa BIDIKMISI",
                    "Beasiswa PPA",
                    "Beasiswa AFIRMASI",
                    "Beasiswa Perusahaan/Swasta",
                    "Lainnya, sebutkan!"
                ],
                hasOther: true,
                required: true
            },
            4: {
                title: "Pada saat lulus, pada tingkat mana Anda menguasai kompetensi berikut?",
                description: "1 = Sangat Rendah, 5 = Sangat Tinggi",
                code: "F17.A",
                number: 4,
                type: "competency-scale",
                competencies: [
                    "Etika",
                    "Keahlian Bidang Ilmu",
                    "Bahasa Inggris",
                    "Penggunaan IT",
                    "Komunikasi",
                    "Kerja Sama Tim",
                    "Pengembangan Diri"
                ],
                scale: [1, 2, 3, 4, 5],
                scaleLabels: {
                    1: "Sangat Rendah",
                    2: "Rendah",
                    3: "Cukup",
                    4: "Tinggi",
                    5: "Sangat Tinggi"
                },
                required: true
            },
            5: {
                title: "Menurut Anda, seberapa besar penekanan metode pembelajaran berikut di prodi Anda?",
                description: "",
                code: "F21-F27",
                number: 5,
                type: "learning-methods",
                methods: [
                    "Perkuliahan",
                    "Demonstrasi",
                    "Partisipasi Proyek Riset",
                    "Magang",
                    "Praktikum",
                    "Kerja Lapangan",
                    "Diskusi"
                ],
                scaleOptions: ["Sangat Besar", "Besar", "Cukup", "Kurang", "Tidak Sama Sekali"],
                required: true
            }
        };

        // Function to render dynamic answer area berdasarkan tipe pertanyaan
        function renderAnswerArea(questionId) {
            const question = questions[questionId];
            const answerArea = document.getElementById('dynamicAnswerArea');
            
            if (!question) return;
            
            let html = '';
            
            switch(question.type) {
                case 'dropdown':
                    html = renderDropdownOptions(question);
                    break;
                case 'radio-options':
                    html = renderRadioOptions(question);
                    break;
                case 'competency-scale':
                    html = renderCompetencyScale(question);
                    break;
                case 'learning-methods':
                    html = renderLearningMethods(question);
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

        // Render dropdown options
        function renderDropdownOptions(question) {
            let html = '<div class="mb-4">';
            
            if (question.hasOther) {
                html += `
                    <select class="form-select form-select-lg mb-3" id="question_dropdown" ${question.required ? 'required' : ''}>
                        <option value="" selected disabled>Pilih sumber dana...</option>
                `;
            } else {
                html += `
                    <select class="form-select form-select-lg mb-3" id="question_dropdown" ${question.required ? 'required' : ''}>
                        <option value="" selected disabled>Pilih status Anda...</option>
                `;
            }
            
            question.options.forEach(option => {
                html += `<option value="${option}">${option}</option>`;
            });
            
            html += `</select>`;
            
            if (question.hasOther) {
                html += `
                    <div class="mt-3" id="otherInputContainer" style="display: none;">
                        <label for="otherInput" class="form-label fw-medium">Sebutkan sumber dana lainnya:</label>
                        <input type="text" class="form-control" id="otherInput" placeholder="Tuliskan sumber dana lainnya...">
                    </div>
                `;
            }
            
            html += '</div>';
            return html;
        }

        // Render radio options untuk pertanyaan nomor 2
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

        // Render competency scale
        function renderCompetencyScale(question) {
            let html = '<div class="competency-grid mb-4">';
            
            question.competencies.forEach((competency, index) => {
                html += `
                    <div class="competency-item">
                        <div class="competency-name">${competency}</div>
                        <div class="competency-scale">
                `;
                
                question.scale.forEach(value => {
                    html += `
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" 
                                   name="competency_${index}" 
                                   id="comp_${index}_${value}" 
                                   value="${value}"
                                   ${question.required ? 'required' : ''}>
                            <label class="form-check-label" for="comp_${index}_${value}">
                                ${value}
                            </label>
                        </div>
                    `;
                });
                
                html += `
                        </div>
                    </div>
                `;
            });
            
            // Add legend
            html += `
                <div class="mt-4 p-3 bg-light rounded">
                    <div class="row text-center">
                        <div class="col">
                            <span class="badge bg-secondary">1</span>
                            <div class="small mt-1">${question.scaleLabels[1]}</div>
                        </div>
                        <div class="col">
                            <span class="badge bg-secondary">2</span>
                            <div class="small mt-1">${question.scaleLabels[2]}</div>
                        </div>
                        <div class="col">
                            <span class="badge bg-secondary">3</span>
                            <div class="small mt-1">${question.scaleLabels[3]}</div>
                        </div>
                        <div class="col">
                            <span class="badge bg-secondary">4</span>
                            <div class="small mt-1">${question.scaleLabels[4]}</div>
                        </div>
                        <div class="col">
                            <span class="badge bg-secondary">5</span>
                            <div class="small mt-1">${question.scaleLabels[5]}</div>
                        </div>
                    </div>
                </div>
            `;
            
            html += '</div>';
            return html;
        }

        // Render learning methods
        function renderLearningMethods(question) {
            let html = '<div class="table-responsive mb-4">';
            html += `
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40%">Metode Pembelajaran</th>
                            <th class="text-center">Sangat Besar</th>
                            <th class="text-center">Besar</th>
                            <th class="text-center">Cukup</th>
                            <th class="text-center">Kurang</th>
                            <th class="text-center">Tidak Sama Sekali</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            question.methods.forEach((method, index) => {
                html += `
                    <tr>
                        <td class="fw-medium">${method}</td>
                `;
                
                question.scaleOptions.forEach((option, optIndex) => {
                    html += `
                        <td class="text-center">
                            <input class="form-check-input" type="radio" 
                                   name="method_${index}" 
                                   value="${optIndex + 1}"
                                   ${question.required ? 'required' : ''}>
                        </td>
                    `;
                });
                
                html += '</tr>';
            });
            
            html += `
                    </tbody>
                </table>
            </div>`;
            return html;
        }

        // Function to render dynamic buttons
        function renderButtons(questionId) {
            const buttonGroup = document.getElementById('dynamicButtonGroup');
            const isLastQuestion = questionId === TOTAL_QUESTIONS;
            
            let html = `
                <button class="btn btn-outline-secondary" id="saveDraftBtn">
                    <i class="fas fa-save me-2"></i> Simpan Sementara
                </button>
            `;
            
            if (isLastQuestion) {
                html += `
                    <button class="btn btn-success" id="submitQuestionnaireBtn">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Kuesioner
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
                    if (otherInputContainer && this.value === "Lainnya, sebutkan!") {
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
            
            // Untuk radio options (pertanyaan nomor 2)
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
            
            // Untuk radio input di competency scale
            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', saveAnswer);
            });
            
            // Untuk text input lainnya
            const otherInput = document.getElementById('otherInput');
            if (otherInput) {
                otherInput.addEventListener('input', saveAnswer);
            }
        }

        // Restore saved answer
        function restoreAnswer(questionId) {
            const savedAnswers = JSON.parse(localStorage.getItem('kuesioner_draft') || '{}');
            const savedAnswer = savedAnswers[questionId];
            
            if (!savedAnswer) return;
            
            const question = questions[questionId];
            
            switch(question.type) {
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
                    
                case 'competency-scale':
                    if (typeof savedAnswer === 'object') {
                        Object.keys(savedAnswer).forEach(competency => {
                            const index = question.competencies.indexOf(competency);
                            if (index !== -1) {
                                const radio = document.querySelector(`input[name="competency_${index}"][value="${savedAnswer[competency]}"]`);
                                if (radio) radio.checked = true;
                            }
                        });
                    }
                    break;
                    
                case 'learning-methods':
                    if (typeof savedAnswer === 'object') {
                        Object.keys(savedAnswer).forEach(method => {
                            const index = question.methods.indexOf(method);
                            if (index !== -1) {
                                const value = question.scaleOptions.indexOf(savedAnswer[method]) + 1;
                                const radio = document.querySelector(`input[name="method_${index}"][value="${value}"]`);
                                if (radio) radio.checked = true;
                            }
                        });
                    }
                    break;
            }
        }

        // Function to update progress
        function updateProgress(questionId) {
            const progressPercent = (questionId / TOTAL_QUESTIONS) * 100;
            const progressText = `Progress: Pertanyaan ${questionId} dari ${TOTAL_QUESTIONS}`;
            const progressPercentText = `${Math.round(progressPercent)}%`;
            
            document.getElementById('progressBar').style.width = `${progressPercent}%`;
            document.getElementById('progressText').textContent = progressText;
            document.getElementById('progressPercent').textContent = progressPercentText;
        }

        // Question navigation functionality dengan pembatasan
        document.querySelectorAll('.question-nav-item').forEach(item => {
            item.addEventListener('click', function () {
                const questionId = parseInt(this.getAttribute('data-question-id'));
                
                // Cek apakah pertanyaan ini sudah dijawab atau tidak
                if (questionId <= highestAnsweredQuestion) {
                    navigateToQuestion(questionId);
                } else {
                    showNotification('Anda harus menyelesaikan pertanyaan sebelumnya terlebih dahulu', 'warning');
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
            
            // Validasi khusus untuk pertanyaan routing
            if (questionId === 1) {
                setupRoutingLogic();
            }
        }

        // Function to update question display
        function updateQuestionDisplay(questionId) {
            const question = questions[questionId];
            
            if (!question) return;
            
            document.getElementById('currentQuestionNumber').textContent = question.number;
            document.getElementById('currentQuestionCode').textContent = question.code;
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

        // Setup routing logic untuk pertanyaan F8
        function setupRoutingLogic() {
            const dropdown = document.getElementById('question_dropdown');
            if (dropdown) {
                dropdown.addEventListener('change', function() {
                    const selectedValue = this.value;
                    
                    // Reset semua pertanyaan menjadi locked kecuali yang pertama
                    highestAnsweredQuestion = 1;
                    
                    // Tentukan pertanyaan mana yang akan di-unlock berdasarkan pilihan
                    if (selectedValue === "Melanjutkan Pendidikan" || 
                        selectedValue === "Tidak Kerja, tetapi sedang mencari kerja" ||
                        selectedValue === "Belum memungkinkan bekerja / Tidak mencari kerja") {
                        // Untuk status ini, pertanyaan F502 di-skip
                        highestAnsweredQuestion = 2;
                    } else if (selectedValue) {
                        // Untuk status bekerja/wiraswasta, unlock pertanyaan 2
                        highestAnsweredQuestion = Math.max(highestAnsweredQuestion, 2);
                    }
                    
                    updateNavigationStates(currentQuestionId);
                });
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
            
            if (currentQuestionId < TOTAL_QUESTIONS) {
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
            
            // Periksa status di pertanyaan 1 untuk menentukan routing
            const q1Answer = savedAnswers[1];
            const isQuestion2Relevant = q1Answer === "Bekerja (full time/part time) di perusahaan/instansi" || 
                                        q1Answer === "Wiraswasta/Pemilik Usaha";
            
            for (let i = 1; i <= TOTAL_QUESTIONS; i++) {
                const question = questions[i];
                
                // Skip pertanyaan yang tidak relevan berdasarkan routing
                if (i === 2 && !isQuestion2Relevant) {
                    continue; // Skip pertanyaan 2 jika tidak relevan
                }
                
                if (question.required) {
                    const answer = savedAnswers[i];
                    
                    if (!answer || answer === "" || (typeof answer === 'object' && Object.keys(answer).length === 0)) {
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
            if (confirm('Apakah Anda yakin ingin kembali ke halaman utama? Perubahan yang belum disimpan akan hilang.')) {
                window.location.href = 'halaman-utama-kuesioner.html';
            }
        });

        // Simpan jawaban untuk pertanyaan saat ini
        function saveAnswer() {
            const answer = getQuestionAnswer(currentQuestionId);
            if (answer) {
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
                if (currentQuestionId === highestAnsweredQuestion && currentQuestionId < TOTAL_QUESTIONS) {
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
            
            switch(question.type) {
                case 'dropdown':
                    const dropdown = document.getElementById('question_dropdown');
                    if (!dropdown || !dropdown.value) return null;
                    
                    const value = dropdown.value;
                    if (value === "Lainnya, sebutkan!") {
                        const otherInput = document.getElementById('otherInput');
                        return otherInput && otherInput.value.trim() ? `Lainnya: ${otherInput.value}` : value;
                    }
                    return value;
                    
                case 'radio-options':
                    const selectedRadio = document.querySelector('input[name="question_radio"]:checked');
                    return selectedRadio ? selectedRadio.value : null;
                    
                case 'competency-scale':
                    const competencyAnswers = {};
                    let competencyAnswered = false;
                    
                    question.competencies.forEach((competency, index) => {
                        const radios = document.querySelectorAll(`input[name="competency_${index}"]`);
                        radios.forEach(radio => {
                            if (radio.checked) {
                                competencyAnswers[competency] = radio.value;
                                competencyAnswered = true;
                            }
                        });
                    });
                    
                    return competencyAnswered ? competencyAnswers : null;
                    
                case 'learning-methods':
                    const methodAnswers = {};
                    let methodAnswered = false;
                    
                    question.methods.forEach((method, index) => {
                        const radios = document.querySelectorAll(`input[name="method_${index}"]`);
                        radios.forEach(radio => {
                            if (radio.checked) {
                                const valueIndex = parseInt(radio.value) - 1;
                                methodAnswers[method] = question.scaleOptions[valueIndex];
                                methodAnswered = true;
                            }
                        });
                    });
                    
                    return methodAnswered ? methodAnswers : null;
                    
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
                
                // Simpan sebagai submitted (simulasi pengiriman ke server)
                localStorage.setItem('kuesioner_submitted', JSON.stringify(allAnswers));
                
                // Hapus draft setelah berhasil submit
                localStorage.removeItem('kuesioner_draft');
                
                // Tampilkan notifikasi sukses
                showNotification('Kuesioner berhasil dikirim! Terima kasih atas partisipasi Anda.', 'success');
                
                // Redirect ke halaman baru setelah 1.5 detik
                setTimeout(() => {
                    loadingModal.hide();
                    window.location.href = 'halaman-terimakasih.html'; // Ganti dengan halaman tujuan Anda
                }, 1000);
                
            }, 1000); // Simulasi waktu pengiriman 2 detik
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
        setupRoutingLogic();
        
        // Load saved answers saat inisialisasi
        const savedAnswers = JSON.parse(localStorage.getItem('kuesioner_draft') || '{}');
        
        // Tentukan highestAnsweredQuestion berdasarkan jawaban yang sudah disimpan
        for (let i = 1; i <= TOTAL_QUESTIONS; i++) {
            if (savedAnswers[i]) {
                highestAnsweredQuestion = Math.max(highestAnsweredQuestion, i + 1);
            }
        }
        
        // Apply routing logic berdasarkan jawaban pertanyaan 1 jika ada
        if (savedAnswers[1]) {
            const q1Answer = savedAnswers[1];
            if (q1Answer === "Melanjutkan Pendidikan" || 
                q1Answer === "Tidak Kerja, tetapi sedang mencari kerja" ||
                q1Answer === "Belum memungkinkan bekerja / Tidak mencari kerja") {
                // Untuk status ini, pertanyaan F502 di-skip
                highestAnsweredQuestion = Math.max(highestAnsweredQuestion, 2);
            }
        }
        
        // Pastikan highestAnsweredQuestion tidak melebihi TOTAL_QUESTIONS
        highestAnsweredQuestion = Math.min(highestAnsweredQuestion, TOTAL_QUESTIONS);
        
        updateNavigationStates(currentQuestionId);
    </script>
</body>

</html>