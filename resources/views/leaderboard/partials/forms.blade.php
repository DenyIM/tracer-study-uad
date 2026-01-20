<!-- Section Kirim Informasi -->
<div class="submit-section mt-5" data-aos="fade-up">
    <div class="section-header mb-4">
        <h2 class="fw-bold mb-3" style="color: var(--primary-blue);">
            <i class="fas fa-paper-plane me-2"></i>Kirim Informasi & Dapatkan Points
        </h2>
        <p class="text-muted">Kontribusikan informasi bermanfaat dan dapatkan poin untuk meningkatkan peringkat Anda!</p>
    </div>

    <!-- Form Container dengan Tabs Internal -->
    <div class="form-container" data-aos="fade-up">
        <ul class="nav nav-pills mb-4" id="submitTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="forum-tab" data-bs-toggle="pill" data-bs-target="#forum"
                    type="button">
                    <i class="fas fa-comments me-2"></i>Informasi Forum
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="job-tab" data-bs-toggle="pill" data-bs-target="#job" type="button">
                    <i class="fas fa-briefcase me-2"></i>Lowongan Kerja
                </button>
            </li>
        </ul>

        <div class="tab-content" id="submitTabsContent">
            <!-- Forum Form -->
            <div class="tab-pane fade show active" id="forum" role="tabpanel">
                <div class="form-section">
                    <h4 class="fw-bold mb-4" style="color: var(--primary-blue);">Kirim Informasi Forum</h4>
                    <form id="forumForm">
                        @csrf
                        <input type="hidden" name="_method" value="POST">

                        <div class="mb-4">
                            <label class="form-label">Kategori Informasi</label>
                            <select class="form-select" name="category" required>
                                <option value="">Pilih kategori...</option>
                                <option value="seminar">Seminar & Workshop</option>
                                <option value="event">Event Alumni</option>
                                <option value="tips">Tips & Pengalaman</option>
                                <option value="bootcamp">Bootcamp & Pelatihan</option>
                                <option value="other">Lainnya</option>
                            </select>
                            <div class="error-message"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Judul Informasi</label>
                            <input type="text" class="form-control" name="title"
                                placeholder="Contoh: Seminar Digital Marketing 2024" required>
                            <div class="error-message"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Deskripsi Lengkap</label>
                            <textarea class="form-control form-textarea" name="description"
                                placeholder="Jelaskan secara detail tentang informasi yang ingin Anda bagikan..." required rows="5"></textarea>
                            <div class="error-message"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Tanggal & Waktu</label>
                            <input type="datetime-local" class="form-control" name="date_time">
                            <div class="error-message"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Lokasi / Platform</label>
                            <input type="text" class="form-control" name="location"
                                placeholder="Contoh: Zoom Meeting, Gedung A UAD, dll">
                            <div class="error-message"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Link Pendaftaran/Informasi</label>
                            <input type="url" class="form-control" name="link" placeholder="https://...">
                            <div class="error-message"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Kontak Penyelenggara</label>
                            <input type="text" class="form-control" name="contact"
                                placeholder="Nama dan nomor/email kontak">
                            <div class="error-message"></div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="submit-btn pulse-animation">
                                <i class="fas fa-paper-plane me-2"></i>Kirim untuk Verifikasi Admin
                            </button>
                        </div>
                    </form>

                    <div class="success-message" id="forumSuccessMessage">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <h4 class="fw-bold mb-2">Informasi Forum Berhasil Dikirim!</h4>
                        <p class="mb-0">Tim admin akan memverifikasi dalam 1-2 hari kerja.</p>
                    </div>
                </div>
            </div>

            <!-- Job Form -->
            <div class="tab-pane fade" id="job" role="tabpanel">
                <div class="form-section">
                    <h4 class="fw-bold mb-4" style="color: var(--primary-blue);">Kirim Informasi Lowongan Kerja</h4>
                    <form id="jobForm">
                        @csrf
                        <input type="hidden" name="_method" value="POST">

                        <div class="mb-4">
                            <label class="form-label">Nama Perusahaan</label>
                            <input type="text" class="form-control" name="company_name"
                                placeholder="Contoh: PT. Teknologi Indonesia" required>
                            <div class="error-message"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Posisi yang Dibutuhkan</label>
                            <input type="text" class="form-control" name="position"
                                placeholder="Contoh: Software Engineer" required>
                            <div class="error-message"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Lokasi Kerja</label>
                            <input type="text" class="form-control" name="location"
                                placeholder="Contoh: Jakarta Selatan" required>
                            <div class="error-message"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Deskripsi Pekerjaan</label>
                            <textarea class="form-control form-textarea" name="job_description"
                                placeholder="Jelaskan tanggung jawab dan deskripsi pekerjaan..." required rows="5"></textarea>
                            <div class="error-message"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Kualifikasi & Persyaratan</label>
                            <textarea class="form-control form-textarea" name="qualifications"
                                placeholder="Sebutkan kualifikasi yang dibutuhkan..." required rows="5"></textarea>
                            <div class="error-message"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Bidang yang Dicari</label>
                            <select class="form-select" name="field" required>
                                <option value="">Pilih bidang...</option>
                                <option value="it">IT & Teknologi</option>
                                <option value="marketing">Marketing</option>
                                <option value="finance">Keuangan</option>
                                <option value="hrd">HRD</option>
                                <option value="engineering">Engineering</option>
                                <option value="other">Lainnya</option>
                            </select>
                            <div class="error-message"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Batas Pendaftaran</label>
                            <input type="date" class="form-control" name="deadline">
                            <div class="error-message"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Link Pendaftaran</label>
                            <input type="url" class="form-control" name="link" placeholder="https://..."
                                required>
                            <div class="error-message"></div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Kontak HRD/Perusahaan</label>
                            <input type="text" class="form-control" name="contact"
                                placeholder="Email atau nomor telepon">
                            <div class="error-message"></div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="submit-btn pulse-animation">
                                <i class="fas fa-paper-plane me-2"></i>Kirim untuk Verifikasi Admin
                            </button>
                        </div>
                    </form>

                    <div class="success-message" id="jobSuccessMessage">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <h4 class="fw-bold mb-2">Informasi Lowongan Kerja Berhasil Dikirim!</h4>
                        <p class="mb-0">Tim admin akan memverifikasi dalam 1-2 hari kerja.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
