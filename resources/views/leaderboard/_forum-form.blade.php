<div class="form-section">
    <h4 class="fw-bold mb-4" style="color: var(--primary-blue);">Kirim Informasi Forum
    </h4>
    <form id="forumForm" method="POST" action="{{ route('leaderboard.submit.forum') }}">
        @csrf
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
        </div>

        <div class="mb-4">
            <label class="form-label">Judul Informasi</label>
            <input type="text" class="form-control" name="title"
                placeholder="Contoh: Seminar Digital Marketing 2024" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Deskripsi Lengkap</label>
            <textarea class="form-control form-textarea" name="description"
                placeholder="Jelaskan secara detail tentang informasi yang ingin Anda bagikan..." required></textarea>
        </div>

        <div class="mb-4">
            <label class="form-label">Tanggal & Waktu</label>
            <input type="datetime-local" class="form-control" name="date_time">
        </div>

        <div class="mb-4">
            <label class="form-label">Lokasi / Platform</label>
            <input type="text" class="form-control" name="location"
                placeholder="Contoh: Zoom Meeting, Gedung A UAD, dll">
        </div>

        <div class="mb-4">
            <label class="form-label">Link Pendaftaran/Informasi</label>
            <input type="url" class="form-control" name="link" placeholder="https://...">
        </div>

        <div class="mb-4">
            <label class="form-label">Kontak Penyelenggara</label>
            <input type="text" class="form-control" name="contact" placeholder="Nama dan nomor/email kontak">
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
