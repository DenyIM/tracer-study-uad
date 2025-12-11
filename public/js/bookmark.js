// Bookmark removal functionality with sliding animation
function removeBookmark(itemId, type) {
    if (confirm('Apakah Anda yakin ingin menghapus bookmark ini?')) {
        // Dalam implementasi nyata, ini akan mengirim permintaan ke server
        console.log(`Menghapus bookmark ${itemId} dari ${type}`);

        // Perbaikan: Gunakan selector yang benar untuk container jobs
        const containerId = type === 'forum' ? 'forum-bookmarks' : 'jobs-bookmarks';
        const container = document.getElementById(containerId);
        const cardToRemove = document.getElementById(itemId);

        if (!container || !cardToRemove) {
            console.error('Container atau card tidak ditemukan');
            return;
        }

        const cards = Array.from(container.querySelectorAll('.col-lg-6'));
        const indexToRemove = cards.indexOf(cardToRemove);

        if (indexToRemove === -1) return;

        // Animasi penghapusan untuk kartu yang dihapus
        const cardElement = cardToRemove.querySelector('.bookmark-card');
        cardElement.classList.add('removing');

        // Setelah animasi penghapusan selesai, hapus elemen
        setTimeout(() => {
            cardToRemove.remove();

            // Animasikan kartu yang tersisa untuk bergeser ke atas
            animateRemainingCards(cards, indexToRemove, container);

            // Perbarui jumlah bookmark di tab
            updateBookmarkCount(type, -1);

            // Tampilkan pesan sukses
            showToast('Bookmark berhasil dihapus', 'success');

            // Periksa apakah tab sekarang kosong
            checkEmptyState(type);
        }, 400);
    }
}

// Animate remaining cards to slide up
function animateRemainingCards(cards, removedIndex, container) {
    // Mulai dari kartu setelah yang dihapus
    for (let i = removedIndex + 1; i < cards.length; i++) {
        const card = cards[i];
        const cardElement = card.querySelector('.bookmark-card');

        // Tambahkan kelas animasi
        cardElement.classList.add('sliding-up');

        // Setelah animasi selesai, hapus kelas animasi
        setTimeout(() => {
            cardElement.classList.remove('sliding-up');
        }, 400);
    }
}

// Update bookmark count in tabs
function updateBookmarkCount(type, change) {
    const countElement = document.getElementById(`${type}-count`);
    if (countElement) {
        const currentCount = parseInt(countElement.textContent);
        const newCount = Math.max(0, currentCount + change);
        countElement.textContent = newCount;
    }
}

// Check if a tab is empty and show empty state
function checkEmptyState(type) {
    // Perbaikan: Gunakan selector yang benar untuk container jobs
    const containerId = type === 'forum' ? 'forum-bookmarks' : 'jobs-bookmarks';
    const container = document.getElementById(containerId);

    if (!container) return;

    const cards = container.querySelectorAll('.col-lg-6');

    if (cards.length === 0) {
        // Hapus semua kartu yang ada
        container.innerHTML = '';

        // Tambahkan state kosong
        const emptyStateContainer = document.createElement('div');
        emptyStateContainer.className = 'col-12 empty-state-container';

        const emptyState = document.createElement('div');
        emptyState.className = 'empty-state empty-state-appear';

        const typeText = type === 'forum' ? 'forum' : 'lowongan kerja';
        const typeIcon = type === 'forum' ? 'comments' : 'briefcase';
        const typeAction = type === 'forum' ? 'postingan forum' : 'lowongan kerja';

        emptyState.innerHTML = `
                    <div class="empty-state-icon">
                        <i class="far fa-bookmark"></i>
                    </div>
                    <h4>Belum ada bookmark ${typeText}</h4>
                    <p>Simpan ${typeAction} yang menarik untuk dilihat kembali nanti. Bookmark membantu Anda mengorganisir konten favorit dengan mudah.</p>
                    
                    <div class="empty-state-actions">
                        <a href="nav-${type}" class="empty-state-btn empty-state-btn-primary">
                            <i class="fas fa-${typeIcon} me-2"></i> Jelajahi ${type === 'forum' ? 'Forum' : 'Lowongan Kerja'}
                        </a>
                    </div>
                `;

        emptyStateContainer.appendChild(emptyState);
        container.appendChild(emptyStateContainer);
    }
}

// Like functionality
document.querySelectorAll('.like-btn').forEach(button => {
    button.addEventListener('click', function () {
        const icon = this.querySelector('i');
        const count = this.querySelector('span');

        if (this.classList.contains('liked')) {
            this.classList.remove('liked');
            icon.classList.remove('fas');
            icon.classList.add('far');
            if (count) {
                const currentCount = parseInt(count.textContent);
                count.textContent = currentCount - 1;
            }
        } else {
            this.classList.add('liked');
            icon.classList.remove('far');
            icon.classList.add('fas');
            if (count) {
                const currentCount = parseInt(count.textContent);
                count.textContent = currentCount + 1;
            }
        }
    });
});

// Toast notification function
function showToast(message, type = 'info') {
    // Buat elemen toast
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'info'} border-0 position-fixed top-0 end-0 m-3`;
    toast.style.zIndex = '9999';
    toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;

    // Tambahkan ke body
    document.body.appendChild(toast);

    // Inisialisasi dan tampilkan toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    // Hapus toast setelah ditutup
    toast.addEventListener('hidden.bs.toast', function () {
        document.body.removeChild(toast);
    });
}