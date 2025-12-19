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

        .questionnaire-header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }

        .question-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            max-width: 800px;
            margin: 0 auto;
        }

        .form-section {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            border: 2px solid #eaeaea;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-blue);
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }

        .required-field::after {
            content: " *";
            color: #dc3545;
        }

        .optional-field::after {
            content: " (Opsional)";
            color: #6c757d;
            font-weight: normal;
        }

        .info-text {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .footer {
            background-color: var(--primary-blue);
            color: white;
            padding: 20px 0;
            margin-top: 50px;
            text-align: center;
        }

        .btn-primary-custom {
            background-color: var(--accent-yellow);
            border-color: var(--accent-yellow);
            color: var(--primary-blue);
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            background-color: #e0a500;
            border-color: #e0a500;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(250, 179, 0, 0.3);
        }

        .section-title {
            color: var(--primary-blue);
            border-bottom: 2px solid var(--light-blue);
            padding-bottom: 10px;
            margin-bottom: 25px;
            font-weight: 700;
        }

        /* Styling untuk date input */
        input[type="date"] {
            cursor: pointer;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            background-color: var(--light-blue);
        }

        input[type="date"]::-webkit-calendar-picker-indicator:hover {
            background-color: var(--secondary-blue);
        }

        @media (max-width: 768px) {
            .form-control, .form-select {
                padding: 10px 12px;
            }
            
            .btn-primary-custom {
                width: 100%;
                margin-bottom: 10px;
            }
            
            .question-card {
                margin: 0 15px;
            }
        }
    </style>
</head>

<body>
    <header class="questionnaire-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="fw-bold mb-0" style="color: var(--primary-blue);">REGISTER DATA USER</h3>
                    <p class="text-muted mb-0">INFORMASI DASAR ALUMNI</p>
                </div>
                <div class="text-end">
                    <p class="mb-0"><strong>Data Diri</strong></p>
                    <p class="text-muted mb-0 small">Lengkapi informasi berikut</p>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-10" data-aos="fade-up">
                    <div class="question-card p-4">
                        <h4 class="section-title">Data Pribadi</h4>
                        
                        <form id="basicInfoForm">
                            <!-- Nama Lengkap -->
                            <div class="form-section">
                                <label for="fullName" class="form-label required-field">Nama Lengkap</label>
                                <input type="text" class="form-control" id="fullName" 
                                       placeholder="Masukkan nama lengkap anda" required>
                                <div class="info-text">Harap gunakan nama lengkap sesuai dengan yang tertera pada ijazah Anda.</div>
                            </div>

                            <!-- NIM -->
                            <div class="form-section">
                                <label for="nim" class="form-label required-field">Nomor Induk Mahasiswa (NIM)</label>
                                <input type="text" class="form-control" id="nim" 
                                       placeholder="Contoh: 1234567890" required>
                                <div class="info-text">Masukkan NIM Anda selama berkuliah di UAD.</div>
                            </div>

                            <!-- Program Studi -->
                            <div class="form-section">
                                <label for="studyProgram" class="form-label required-field">Program Studi</label>
                                <select class="form-select" id="studyProgram" required>
                                    <option value="" selected disabled>Pilih Program Studi</option>
                                    <option value="Teknik Informatika">Teknik Informatika</option>
                                    <option value="Sistem Informasi">Sistem Informasi</option>
                                    <option value="Teknik Elektro">Teknik Elektro</option>
                                    <option value="Teknik Industri">Teknik Industri</option>
                                    <option value="Teknik Sipil">Teknik Sipil</option>
                                    <option value="Arsitektur">Arsitektur</option>
                                    <option value="Manajemen">Manajemen</option>
                                    <option value="Akuntansi">Akuntansi</option>
                                    <option value="Ilmu Komunikasi">Ilmu Komunikasi</option>
                                    <option value="Psikologi">Psikologi</option>
                                    <option value="Kedokteran">Kedokteran</option>
                                    <option value="Farmasi">Farmasi</option>
                                    <option value="Pendidikan Bahasa Inggris">Pendidikan Bahasa Inggris</option>
                                    <option value="Pendidikan Matematika">Pendidikan Matematika</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                                <div class="info-text">Pilih program studi tempat Anda menyelesaikan studi S1.</div>
                            </div>

                            <!-- Tanggal Lulus dengan Input Date Native -->
                            <div class="form-section">
                                <label for="graduationDate" class="form-label required-field">Tanggal Lulus (Wisuda)</label>
                                <input type="date" class="form-control" id="graduationDate" required>
                                <div class="info-text">Klik ikon kalender atau ketik tanggal dalam format DD/MM/YYYY.</div>
                            </div>

                            <!-- Nomor HP -->
                            <div class="form-section">
                                <label for="phoneNumber" class="form-label required-field">Nomor Handphone</label>
                                <div class="input-group">
                                    <span class="input-group-text">+62</span>
                                    <input type="tel" class="form-control" id="phoneNumber" 
                                           placeholder="81234567890" required
                                           pattern="[0-9]{9,13}">
                                </div>
                                <div class="info-text">Masukkan nomor handphone aktif (tanpa kode negara +62 di awal). Contoh: 81234567890</div>
                            </div>

                            <!-- NPWP (Opsional) -->
                            <div class="form-section">
                                <label for="npwp" class="form-label optional-field">Nomor Pokok Wajib Pajak (NPWP)</label>
                                <input type="text" class="form-control" id="npwp" 
                                       placeholder="Contoh: 12.345.678.9-012.345">
                                <div class="info-text">Diisi jika Anda sudah memiliki NPWP. Jika belum, biarkan kosong.</div>
                            </div>

                            <!-- Consent Checkbox -->
                            <div class="form-section mt-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="dataConsent" required>
                                    <label class="form-check-label" for="dataConsent">
                                        Saya menyatakan bahwa data yang saya berikan adalah benar dan dapat dipertanggungjawabkan.
                                    </label>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="d-flex justify-content-end pt-4 mt-3 border-top">
                                <button type="submit" class="btn btn-primary-custom" id="submitBasicInfoBtn">
                                    <i class="fas fa-arrow-right me-2"></i> Isi Kuesioner 1
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; 2025 Tracer Study Universitas Ahmad Dahlan.</p>
            <p class="small mt-2">Data yang Anda berikan akan dijaga kerahasiaannya dan hanya digunakan untuk keperluan tracer study.</p>
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

        // Format NPWP otomatis
        document.getElementById('npwp').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length >= 2) {
                value = value.substring(0, 2) + '.' + value.substring(2);
            }
            if (value.length >= 6) {
                value = value.substring(0, 6) + '.' + value.substring(6);
            }
            if (value.length >= 10) {
                value = value.substring(0, 10) + '.' + value.substring(10);
            }
            if (value.length >= 12) {
                value = value.substring(0, 12) + '-' + value.substring(12);
            }
            if (value.length >= 16) {
                value = value.substring(0, 16) + '.' + value.substring(16);
            }
            
            e.target.value = value;
        });

        // Format nomor HP (hilangkan angka 0 di depan)
        document.getElementById('phoneNumber').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = value.substring(1);
            }
            e.target.value = value;
        });

        // Handle form submission
        document.getElementById('basicInfoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                return;
            }
            
            // Format tanggal ke DD-MM-YYYY
            const dateInput = document.getElementById('graduationDate').value;
            let formattedDate = '';
            if (dateInput) {
                const date = new Date(dateInput);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                formattedDate = `${day}-${month}-${year}`;
            }
            
            // Simpan data ke localStorage
            const formData = {
                fullName: document.getElementById('fullName').value.trim(),
                nim: document.getElementById('nim').value.trim(),
                studyProgram: document.getElementById('studyProgram').value,
                graduationDate: formattedDate,
                phoneNumber: document.getElementById('phoneNumber').value.trim(),
                npwp: document.getElementById('npwp').value,
                submissionDate: new Date().toISOString()
            };
            
            localStorage.setItem('user_basic_info', JSON.stringify(formData));
            showAlert('Data berhasil disimpan! Mengarahkan ke kuesioner bagian 1...', 'success');
            
            // Redirect setelah 1.5 detik
            setTimeout(() => {
                window.location.href = '/go-to-kuesioner1';
            }, 1500);
        });

        // Validasi form yang SEDERHANA
        function validateForm() {
            const fullName = document.getElementById('fullName').value.trim();
            const nim = document.getElementById('nim').value.trim();
            const studyProgram = document.getElementById('studyProgram').value;
            const graduationDate = document.getElementById('graduationDate').value;
            const phoneNumber = document.getElementById('phoneNumber').value.trim();
            const consent = document.getElementById('dataConsent').checked;
            
            // Validasi sederhana
            if (fullName.length < 3) {
                alert('Nama lengkap harus diisi minimal 3 karakter');
                document.getElementById('fullName').focus();
                return false;
            }
            
            if (nim.length < 5) {
                alert('NIM harus diisi minimal 5 karakter');
                document.getElementById('nim').focus();
                return false;
            }
            
            if (!studyProgram) {
                alert('Harap pilih program studi');
                return false;
            }
            
            if (!graduationDate) {
                alert('Harap pilih tanggal lulus');
                return false;
            }
            
            if (phoneNumber.length < 9 || phoneNumber.length > 13) {
                alert('Nomor handphone harus antara 9-13 digit');
                document.getElementById('phoneNumber').focus();
                return false;
            }
            
            if (!consent) {
                alert('Harap centang pernyataan persetujuan');
                return false;
            }
            
            return true;
        }

        // Alert sederhana
        function showAlert(message, type = 'info') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3`;
            alertDiv.style.zIndex = '9999';
            alertDiv.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle me-2"></i>
                    <div>${message}</div>
                    <button type="button" class="btn-close ms-3" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            document.body.appendChild(alertDiv);
            
            if (type !== 'success') {
                setTimeout(() => alertDiv.remove(), 3000);
            }
        }

        // Load data yang tersimpan (SEDERHANA)
        document.addEventListener('DOMContentLoaded', function() {
            const savedData = localStorage.getItem('user_basic_info');
            if (savedData) {
                try {
                    const data = JSON.parse(savedData);
                    
                    // Isi form dengan data yang tersimpan
                    document.getElementById('fullName').value = data.fullName || '';
                    document.getElementById('nim').value = data.nim || '';
                    document.getElementById('studyProgram').value = data.studyProgram || '';
                    document.getElementById('phoneNumber').value = data.phoneNumber || '';
                    document.getElementById('npwp').value = data.npwp || '';
                    
                    // Konversi tanggal DD-MM-YYYY ke format YYYY-MM-DD untuk input date
                    if (data.graduationDate) {
                        const parts = data.graduationDate.split('-');
                        if (parts.length === 3) {
                            const formattedDate = `${parts[2]}-${parts[1]}-${parts[0]}`;
                            document.getElementById('graduationDate').value = formattedDate;
                        }
                    }
                } catch (e) {
                    console.log('Tidak ada data tersimpan atau format salah');
                }
            }
        });
    </script>
</body>

</html>