<div class="form-section">
    <h4 class="fw-bold mb-4" style="color: var(--primary-blue);">Kirim Informasi Lowongan
        Kerja</h4>
    <form id="jobForm" method="POST" action="{{ route('leaderboard.submit.job') }}">
        @csrf
        <div class="mb-4">
            <label class="form-label">Nama Perusahaan</label>
            <input type="text" class="form-control" name="company" placeholder="Contoh: PT. Teknologi Indonesia"
                required>
        </div>

        <div class="mb-4">
            <label class="form-label">Posisi yang Dibutuhkan</label>
            <input type="text" class="form-control" name="position" placeholder="Contoh: Software Engineer" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Lokasi Kerja</label>
            <input type="text" class="form-control" name="location" placeholder="Contoh: Jakarta Selatan" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Deskripsi Pekerjaan</label>
            <textarea class="form-control form-textarea" name="description"
                placeholder="Jelaskan tanggung jawab dan deskripsi pekerjaan..." required></textarea>
        </div>

        <div class="mb-4">
            <label class="form-label">Kualifikasi & Persyaratan</label>
            <textarea class="form-control form-textarea" name="requirements" placeholder="Sebutkan kualifikasi yang dibutuhkan..."
                required></textarea>
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
        </div>

        <div class="mb-4">
            <label class="form-label">Batas Pendaftaran</label>
            <input type="date" class="form-control" name="deadline">
        </div>

        <div class="mb-4">
            <label class="form-label">Link Pendaftaran</label>
            <input type="url" class="form-control" name="link" placeholder="https://..." required>
        </div>

        <div class="mb-4">
            <label class="form-label">Kontak HRD/Perusahaan</label>
            <input type="text" class="form-control" name="contact" placeholder="Email atau nomor telepon">
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
