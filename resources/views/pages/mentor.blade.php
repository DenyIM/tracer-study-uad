<x-app-layout>
    @section('title', 'Mentorship')

    <div class="main-content">
        <div class="container py-5">
            <div class="mentorship-card mb-5" data-aos="fade-up">
                <div class="mentorship-header">
                    <div class="mentorship-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h1 class="fw-bold mb-3">Layanan Mentorship Alumni</h1>
                    <p class="lead mb-0">Konsultasi karir dan pengembangan diri dengan mentor berpengalaman</p>
                </div>
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-8 mx-auto text-center">
                            <p class="mb-4">Pilih jenis mentorship yang sesuai dengan kebutuhan Anda dan isi kuesioner untuk membantu kami menghubungkan Anda dengan mentor yang tepat.</p>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Data pribadi Anda (Nama, NIM, Program Studi, Tahun Lulus, Email) sudah terisi otomatis dari database.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="menu-selection" data-aos="fade-up" data-aos-delay="100">
                <h3 class="fw-bold mb-4 text-center" style="color: var(--primary-blue);">Pilih Jenis Mentorship</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="menu-card active" id="careerMenu">
                            <div class="menu-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Mentorship Karir</h4>
                            <p class="mb-0">Konsultasi untuk pengembangan karir, persiapan kerja, dan perencanaan karir di perusahaan/instansi</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="menu-card" id="businessMenu">
                            <div class="menu-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Mentorship Bisnis</h4>
                            <p class="mb-0">Konsultasi untuk pengembangan usaha, perencanaan bisnis, dan strategi kewirausahaan</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-md-6" data-aos="fade-right">
                    <div class="mentorship-card p-4 h-100">
                        <div class="text-center">
                            <i class="fas fa-clock text-primary-custom fa-2x mb-3"></i>
                            <h5 class="fw-bold mb-3">Proses Mentorship</h5>
                            <p>Setelah mengirim kuesioner, Anda akan:</p>
                            <ol class="text-start">
                                <li>Mendapat konfirmasi email dalam 24 jam</li>
                                <li>Dihubungkan dengan mentor yang sesuai</li>
                                <li>Jadwal sesi konsultasi akan diatur</li>
                                <li>Konsultasi via Zoom/WA sesuai kesepakatan</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <div class="mentorship-card p-4 h-100">
                        <div class="text-center">
                            <i class="fas fa-user-tie text-primary-custom fa-2x mb-3"></i>
                            <h5 class="fw-bold mb-3">Keuntungan Mentorship</h5>
                            <ul class="text-start">
                                <li>Konsultasi dengan praktisi berpengalaman</li>
                                <li>Bimbingan karir yang terpersonalisasi</li>
                                <li>Networking dengan alumni sukses</li>
                                <li>Insight industri terkini</li>
                                <li>Dukungan pengembangan karir jangka panjang</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section" id="careerForm" data-aos="fade-up" data-aos-delay="200">
                <h3 class="fw-bold mb-4 text-center" style="color: var(--primary-blue);">Kuesioner Mentorship Karir</h3>
                
                <form id="careerMentorshipForm">
                    <div class="mb-4">
                        <label class="form-label">1. Jenis Bidang Karier yang diinginkan (Bagi yang merencanakan untuk bekerja di Perusahaan/Instansi)</label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="career_field" value="HRD" required>
                                HRD
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="career_field" value="Marketing">
                                Marketing
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="career_field" value="Produksi">
                                Produksi
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="career_field" value="QC/QA">
                                QC/QA
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="career_field" value="RnD">
                                RnD
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="career_field" value="Keuangan dan Administrasi">
                                Keuangan dan Administrasi
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="career_field" value="IT">
                                IT
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="career_field" value="Hukum dan Advokasi">
                                Hukum dan Advokasi
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="career_field" value="Tenaga Pendidik">
                                Tenaga Pendidik
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="career_field" value="Kesehatan">
                                Kesehatan
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="career_field" value="Pariwisata">
                                Pariwisata
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="career_field" value="other_career">
                                Yang lain:
                                <input type="text" class="form-control ms-2 d-inline-block" style="width: 400px;" placeholder="Sebutkan bidang lain" id="other_career_field" disabled>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">2. No WhatsApp</label>
                        <input type="tel" class="form-control" name="whatsapp" placeholder="Contoh: 6281234567890" required>
                        <div class="form-text">Pastikan nomor WhatsApp aktif untuk komunikasi dengan mentor</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">3. Perihal yang ingin dikonsultasikan</label>
                        <textarea class="form-control" name="consultation_topic" rows="5" placeholder="Jelaskan secara detail topik atau masalah yang ingin dikonsultasikan dengan mentor..." required></textarea>
                        <div class="form-text">Semakin detail penjelasan Anda, semakin tepat mentor yang akan kami rekomendasikan</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Informasi Tambahan (Opsional)</label>
                        <textarea class="form-control" name="additional_info" rows="3" placeholder="Tambahkan informasi lain yang menurut Anda penting untuk diketahui mentor..."></textarea>
                    </div>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn-mentor btn-lg pulse-animation">
                            <i class="fas fa-paper-plane me-2"></i> Kirim via Email
                        </button>
                    </div>
                </form>

                <div class="success-message" id="careerSuccessMessage">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h4 class="fw-bold mb-2">Permintaan Mentorship Karir Berhasil Dikirim!</h4>
                    <p class="mb-0">Kami akan menghubungi Anda melalui email dalam 1-2 hari kerja.</p>
                </div>
            </div>

            <div class="form-section hidden" id="businessForm" data-aos="fade-up" data-aos-delay="200">
                <h3 class="fw-bold mb-4 text-center" style="color: var(--primary-blue);">Kuesioner Mentorship Bisnis</h3>
                
                <form id="businessMentorshipForm">
                    <div class="mb-4">
                        <label class="form-label">1. Jenis Usaha yang direncanakan (Bagi yang ingin menjadi entrepreneur)</label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="business_type" value="Kuliner" required>
                                Kuliner
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="business_type" value="Kesehatan">
                                Kesehatan
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="business_type" value="IT">
                                IT
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="business_type" value="Pendidikan">
                                Pendidikan
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="business_type" value="Pertanian dan Peternakan">
                                Pertanian dan Peternakan
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="business_type" value="Jasa dan akomodasi">
                                Jasa dan akomodasi
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="business_type" value="Kesenian, hiburan, dan rekreasi">
                                Kesenian, hiburan, dan rekreasi
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="business_type" value="other_business">
                                Yang lain:
                                <input type="text" class="form-control ms-2 d-inline-block" style="width: 400px;" placeholder="Sebutkan jenis usaha lain" id="other_business_type" disabled>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">2. No WhatsApp</label>
                        <input type="tel" class="form-control" name="whatsapp" placeholder="Contoh: 6281234567890" required>
                        <div class="form-text">Pastikan nomor WhatsApp aktif untuk komunikasi dengan mentor</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">3. Perihal yang ingin dikonsultasikan</label>
                        <textarea class="form-control" name="consultation_topic" rows="5" placeholder="Jelaskan secara detail topik atau masalah yang ingin dikonsultasikan dengan mentor..." required></textarea>
                        <div class="form-text">Semakin detail penjelasan Anda, semakin tepat mentor yang akan kami rekomendasikan</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Informasi Tambahan (Opsional)</label>
                        <textarea class="form-control" name="additional_info" rows="3" placeholder="Tambahkan informasi lain yang menurut Anda penting untuk diketahui mentor..."></textarea>
                    </div>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn-mentor btn-lg pulse-animation">
                            <i class="fas fa-paper-plane me-2"></i> Kirim via Email
                        </button>
                    </div>
                </form>

                <div class="success-message" id="businessSuccessMessage">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h4 class="fw-bold mb-2">Permintaan Mentorship Bisnis Berhasil Dikirim!</h4>
                    <p class="mb-0">Kami akan menghubungi Anda melalui email dalam 1-2 hari kerja.</p>
                </div>
            </div>
        </div>
    </div>
    
    

    @push('styles')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/mentor.css') }}">
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/mentor.js') }}"></script>
    @endpush
</x-app-layout>





