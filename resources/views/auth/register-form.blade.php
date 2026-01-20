<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Data Alumni - Tracer Study UAD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* Styles tetap sama seperti sebelumnya */
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

        .user-info-box {
            background: var(--light-blue);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 5px solid var(--accent-yellow);
        }

        .user-info-item {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }

        .user-info-label {
            font-weight: 600;
            color: var(--primary-blue);
            min-width: 150px;
        }

        .user-info-value {
            color: #333;
        }

        .info-icon {
            color: var(--accent-yellow);
            margin-right: 10px;
            width: 20px;
        }

        .form-section {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border: 2px solid #eaeaea;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
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

            .form-control,
            .form-select {
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
                    <h3 class="fw-bold mb-0" style="color: var(--primary-blue);">LENGKAPI DATA ALUMNI</h3>
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
                        <!-- Informasi dari Google -->
                        <div class="user-info-box">
                            <h5 class="mb-3" style="color: var(--primary-blue);">
                                <i class="fas fa-user-circle me-2"></i>Informasi dari Google
                            </h5>
                            <div class="user-info-item">
                                <span class="user-info-label">
                                    <i class="fas fa-user info-icon"></i>Nama Lengkap:
                                </span>
                                <span
                                    class="user-info-value">{{ $alumni->fullname ?? (Auth::user()->name ?? 'N/A') }}</span>
                            </div>
                            <div class="user-info-item">
                                <span class="user-info-label">
                                    <i class="fas fa-envelope info-icon"></i>Email:
                                </span>
                                <span class="user-info-value">{{ Auth::user()->email }}</span>
                            </div>
                            <div class="alert alert-info mt-3 mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Data nama dan email sudah diambil dari akun Google Anda. Silakan lengkapi informasi
                                lainnya di bawah.
                            </div>
                        </div>

                        <h4 class="section-title">Data Akademik</h4>

                        <form id="alumniRegistrationForm" action="{{ route('alumni.registration.submit') }}"
                            method="POST">
                            @csrf

                            <div class="form-section">


                                <!-- Info NIM dari email -->
                                @if ($alumni->nim)
                                    <div class="alert alert-info mb-3">
                                        <i class="fas fa-id-card me-2"></i>
                                        <strong>NIM Anda:</strong> {{ $alumni->nim }}
                                        <small class="d-block mt-1">(diambil dari email Anda)</small>
                                    </div>
                                @endif

                                <label for="fullname" class="form-label required-field">Nama Lengkap</label>

                                <!-- Warning jika nama tidak cocok -->
                                @if (isset($email_warning) && $email_warning['is_name_mismatch'])
                                    <div class="alert alert-warning alert-dismissible fade show mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Perhatian:</strong> Nama di email
                                        (<strong>{{ $email_warning['name_from_email'] }}</strong>)
                                        sedikit berbeda dengan nama Google Anda
                                        (<strong>{{ $email_warning['name_from_google'] }}</strong>).
                                        Anda bisa memperbaikinya di bawah ini.
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif


                                <!-- Input Nama -->
                                <input type="text" class="form-control" id="fullname" name="fullname"
                                    placeholder="Masukkan nama lengkap sesuai ijazah"
                                    value="{{ old('fullname', $alumni->fullname ?? ($user->name ?? '')) }}" required>

                                <!-- Petunjuk -->
                                <div class="info-text">
                                    Gunakan nama lengkap sesuai dengan yang tertera pada ijazah Anda.
                                    Anda bisa mengoreksi nama yang diambil dari akun Google.
                                </div>
                            </div>

                            <!-- Program Studi -->
                            <div class="form-section">
                                <label for="study_program" class="form-label required-field">Program Studi</label>
                                <select class="form-select" id="study_program" name="study_program" required>
                                    <option value="" selected disabled>Pilih Program Studi</option>
                                    <option value="Informatika"
                                        {{ old('study_program', $alumni->study_program ?? '') == 'Informatika' ? 'selected' : '' }}>
                                        Informatika</option>
                                    <option value="Sistem Informasi"
                                        {{ old('study_program', $alumni->study_program ?? '') == 'Sistem Informasi' ? 'selected' : '' }}>
                                        Sistem Informasi</option>
                                    <option value="Teknik Elektro"
                                        {{ old('study_program', $alumni->study_program ?? '') == 'Teknik Elektro' ? 'selected' : '' }}>
                                        Teknik Elektro</option>
                                    <option value="Teknik Industri"
                                        {{ old('study_program', $alumni->study_program ?? '') == 'Teknik Industri' ? 'selected' : '' }}>
                                        Teknik Industri</option>
                                    <option value="Teknik Sipil"
                                        {{ old('study_program', $alumni->study_program ?? '') == 'Teknik Sipil' ? 'selected' : '' }}>
                                        Teknik Sipil</option>
                                    <option value="Arsitektur"
                                        {{ old('study_program', $alumni->study_program ?? '') == 'Arsitektur' ? 'selected' : '' }}>
                                        Arsitektur</option>
                                    <option value="Manajemen"
                                        {{ old('study_program', $alumni->study_program ?? '') == 'Manajemen' ? 'selected' : '' }}>
                                        Manajemen</option>
                                    <option value="Akuntansi"
                                        {{ old('study_program', $alumni->study_program ?? '') == 'Akuntansi' ? 'selected' : '' }}>
                                        Akuntansi</option>
                                    <option value="Ilmu Komunikasi"
                                        {{ old('study_program', $alumni->study_program ?? '') == 'Ilmu Komunikasi' ? 'selected' : '' }}>
                                        Ilmu Komunikasi</option>
                                    <option value="Psikologi"
                                        {{ old('study_program', $alumni->study_program ?? '') == 'Psikologi' ? 'selected' : '' }}>
                                        Psikologi</option>
                                    <option value="Kedokteran"
                                        {{ old('study_program', $alumni->study_program ?? '') == 'Kedokteran' ? 'selected' : '' }}>
                                        Kedokteran</option>
                                    <option value="Farmasi"
                                        {{ old('study_program', $alumni->study_program ?? '') == 'Farmasi' ? 'selected' : '' }}>
                                        Farmasi</option>
                                    <option value="Pendidikan Bahasa Inggris"
                                        {{ old('study_program', $alumni->study_program ?? '') == 'Pendidikan Bahasa Inggris' ? 'selected' : '' }}>
                                        Pendidikan Bahasa Inggris</option>
                                    <option value="Pendidikan Matematika"
                                        {{ old('study_program', $alumni->study_program ?? '') == 'Pendidikan Matematika' ? 'selected' : '' }}>
                                        Pendidikan Matematika</option>
                                    <option value="Lainnya"
                                        {{ old('study_program', $alumni->study_program ?? '') == 'Lainnya' ? 'selected' : '' }}>
                                        Lainnya</option>
                                </select>
                                @error('study_program')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="info-text">Pilih program studi tempat Anda menyelesaikan studi S1.</div>
                            </div>

                            <!-- Tanggal Lulus -->
                            <div class="form-section">
                                <label for="graduation_date" class="form-label required-field">Tanggal Lulus
                                    (Wisuda)</label>
                                <input type="date" class="form-control" id="graduation_date" name="graduation_date"
                                    value="{{ old('graduation_date', $alumni->graduation_date ? \Carbon\Carbon::parse($alumni->graduation_date)->format('Y-m-d') : '') }}"
                                    required max="{{ date('Y-m-d') }}">
                                @error('graduation_date')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="info-text">Pilih tanggal lulus/wisuda Anda.</div>
                            </div>

                            <!-- Nomor HP -->
                            <div class="form-section">
                                <label for="phone" class="form-label required-field">Nomor Handphone</label>
                                <div class="input-group">
                                    <span class="input-group-text">+62</span>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        placeholder="81234567890" required pattern="[0-9]{9,13}"
                                        value="{{ old('phone', $alumni->phone ?? '') }}">
                                </div>
                                @error('phone')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="info-text">Masukkan nomor handphone aktif (tanpa angka 0 di depan). Contoh:
                                    81234567890</div>
                            </div>

                            <!-- NPWP (Opsional) -->
                            <div class="form-section">
                                <label for="npwp" class="form-label optional-field">Nomor Pokok Wajib Pajak
                                    (NPWP)</label>
                                <input type="text" class="form-control" id="npwp" name="npwp"
                                    placeholder="Contoh: 12.345.678.9-012.345"
                                    value="{{ old('npwp', $alumni->npwp ?? '') }}">
                                @error('npwp')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="info-text">Diisi jika Anda sudah memiliki NPWP. Jika belum, biarkan kosong.
                                </div>
                            </div>

                            <!-- Consent Checkbox -->
                            <div class="form-section mt-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="dataConsent"
                                        name="dataConsent" required>
                                    <label class="form-check-label" for="dataConsent">
                                        Saya menyatakan bahwa data yang saya berikan adalah benar dan dapat
                                        dipertanggungjawabkan.
                                    </label>
                                </div>
                                @error('dataConsent')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="d-flex justify-content-end pt-4 mt-3 border-top">
                                <button type="submit" class="btn btn-primary-custom" id="submitBasicInfoBtn">
                                    <i class="fas fa-check me-2"></i> Simpan Data
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
            <p class="mb-0">&copy; {{ date('Y') }} Tracer Study Universitas Ahmad Dahlan.</p>
            <p class="small mt-2">Data yang Anda berikan akan dijaga kerahasiaannya dan hanya digunakan untuk keperluan
                tracer study.</p>
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
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('0')) {
                value = value.substring(1);
            }
            e.target.value = value;
        });

        // Client-side validation sebelum submit
        document.getElementById('alumniRegistrationForm').addEventListener('submit', function(e) {
            const nim = document.getElementById('nim').value.trim();
            const studyProgram = document.getElementById('study_program').value;
            const graduationDate = document.getElementById('graduation_date').value;
            const phone = document.getElementById('phone').value.trim();
            const consent = document.getElementById('dataConsent').checked;

            if (nim.length < 5) {
                e.preventDefault();
                alert('NIM harus diisi minimal 5 karakter');
                document.getElementById('nim').focus();
                return false;
            }

            if (!studyProgram) {
                e.preventDefault();
                alert('Harap pilih program studi');
                return false;
            }

            if (!graduationDate) {
                e.preventDefault();
                alert('Harap pilih tanggal lulus');
                return false;
            }

            if (phone.length < 9 || phone.length > 13) {
                e.preventDefault();
                alert('Nomor handphone harus antara 9-13 digit');
                document.getElementById('phone').focus();
                return false;
            }

            if (!consent) {
                e.preventDefault();
                alert('Harap centang pernyataan persetujuan');
                return false;
            }

            return true;
        });

        // Set max date untuk graduation date (hari ini)
        document.getElementById('graduation_date').max = new Date().toISOString().split('T')[0];

        // Tampilkan loading saat submit
        document.getElementById('alumniRegistrationForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBasicInfoBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
            submitBtn.disabled = true;
        });
    </script>
</body>

</html>
