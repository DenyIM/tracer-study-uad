// NOTIFICATION AND LOGOUT SYSTEM - FIXED VERSION

let notifications = [
    {
        id: 1,
        type: 'forum',
        title: 'Balasan Komentar',
        text: 'Siti Rahayu membalas komentar Anda di postingan "Tips Interview Kerja" dengan memberikan saran yang sangat bermanfaat untuk persiapan interview di perusahaan teknologi',
        time: '5 menit lalu',
        unread: true,
        icon: 'fas fa-comments'
    },
    {
        id: 2,
        type: 'verification',
        title: 'Verifikasi Diterima',
        text: 'Informasi lowongan kerja yang Anda submit untuk posisi Data Analyst di PT. Teknologi Indonesia telah diverifikasi dan diterima. Anda mendapatkan +50 Points untuk kontribusi ini!',
        time: '1 jam lalu',
        unread: true,
        icon: 'fas fa-check-circle'
    },
    {
        id: 3,
        type: 'consultation',
        title: 'Konsultasi Diproses',
        text: 'Permintaan konsultasi karir Anda dengan mentor dari industri teknologi sedang diproses. Tim kami akan menghubungi Anda via WhatsApp dalam 1-2 hari kerja untuk menjadwalkan sesi konsultasi',
        time: '3 jam lalu',
        unread: true,
        icon: 'fas fa-user-clock'
    },
    {
        id: 4,
        type: 'points',
        title: 'Points Bertambah',
        text: 'Anda mendapatkan 25 points karena aktif berpartisipasi di forum diskusi "Peluang Karir di Era Digital" hari ini. Teruslah berbagi pengalaman dan pengetahuan dengan alumni lainnya',
        time: '5 jam lalu',
        unread: true,
        icon: 'fas fa-coins'
    },
    {
        id: 5,
        type: 'verification',
        title: 'Verifikasi Ditolak',
        text: 'Informasi forum yang Anda submit tentang "Seminar Kewirausahaan" perlu revisi pada bagian tanggal dan waktu pelaksanaan. Silakan periksa detailnya di halaman submission dan submit ulang',
        time: '1 hari lalu',
        unread: false,
        icon: 'fas fa-exclamation-circle'
    },
    {
        id: 6,
        type: 'forum',
        title: 'Balasan Komentar',
        text: 'Ahmad Rizki membalas komentar Anda di postingan "Peluang Karir Data Science" dengan menambahkan informasi tentang sertifikasi yang direkomendasikan untuk pemula di bidang data science dan machine learning',
        time: '2 hari lalu',
        unread: false,
        icon: 'fas fa-comments'
    }
];

// ==================== NOTIFICATION FUNCTIONS ====================

// Fungsi untuk memuat notifikasi
function loadNotifications() {
    const notificationList = document.getElementById('notificationList');
    const notificationBadge = document.getElementById('notificationBadge');
    const notificationCount = document.getElementById('notificationCount');

    // Cek jika element tidak ditemukan
    if (!notificationList) {
        console.warn('Element notificationList tidak ditemukan');
        return;
    }

    // Hitung notifikasi yang belum dibaca
    const unreadCount = notifications.filter(notif => notif.unread).length;

    // Update badge jika ada
    if (notificationBadge) {
        notificationBadge.textContent = unreadCount;
    }

    if (notificationCount) {
        notificationCount.textContent = unreadCount;
    }

    // Kosongkan daftar notifikasi
    notificationList.innerHTML = '';

    if (notifications.length === 0) {
        // Tampilkan pesan jika tidak ada notifikasi
        notificationList.innerHTML = `
            <div class="notification-empty">
                <i class="far fa-bell"></i>
                <p>Tidak ada notifikasi</p>
            </div>
        `;
        return;
    }

    // Tambahkan notifikasi ke daftar
    notifications.forEach(notification => {
        const notificationItem = document.createElement('a');
        notificationItem.className = `dropdown-item notification-item ${notification.unread ? 'unread' : ''}`;
        notificationItem.href = '#';
        notificationItem.innerHTML = `
            <div class="notification-icon ${notification.type}">
                <i class="${notification.icon}"></i>
            </div>
            <div class="notification-content">
                <div class="notification-title">${notification.title}</div>
                <div class="notification-text">${notification.text}</div>
                <div class="notification-time">${notification.time}</div>
            </div>
        `;

        // Tambahkan event listener untuk menandai sebagai sudah dibaca
        notificationItem.addEventListener('click', function (e) {
            e.preventDefault();
            markAsRead(notification.id);
        });

        notificationList.appendChild(notificationItem);
    });
}

// Fungsi untuk menandai notifikasi sebagai sudah dibaca
function markAsRead(notificationId) {
    const notification = notifications.find(n => n.id === notificationId);
    if (notification && notification.unread) {
        notification.unread = false;
        loadNotifications();
        showToast('Notifikasi ditandai sebagai sudah dibaca', 'success');
    }
}

// Fungsi untuk refresh notifikasi
function refreshNotifications() {
    const refreshBtn = document.getElementById('notificationRefreshBtn');
    if (!refreshBtn) return;

    // Tampilkan loading state
    refreshBtn.classList.add('loading');

    // Simulasi request ke server
    setTimeout(() => {
        // Tambahkan notifikasi baru (simulasi)
        const newNotification = {
            id: notifications.length + 1,
            type: 'forum',
            title: 'Komentar Baru',
            text: 'Budi Santoso mengomentari postingan Anda "Pengalaman Kerja di Startup Teknologi" dengan bertanya tentang tips menghadapi tantangan di lingkungan kerja yang dinamis dan cepat berubah seperti di startup',
            time: 'Baru saja',
            unread: true,
            icon: 'fas fa-comment'
        };

        // Tambahkan notifikasi baru di urutan teratas
        notifications.unshift(newNotification);

        // Muat ulang notifikasi
        loadNotifications();

        // Hapus loading state
        refreshBtn.classList.remove('loading');

        // Tampilkan toast bahwa notifikasi telah diperbarui
        showToast('Notifikasi telah diperbarui', 'success');
    }, 1000);
}

// ==================== TOAST FUNCTION ====================

// Fungsi untuk menampilkan toast
function showToast(message, type = 'info') {
    // Buat elemen toast
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0 position-fixed top-0 end-0 m-3`;
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

    // Inisialisasi dan tampilkan toast menggunakan Bootstrap
    if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    } else {
        // Fallback jika Bootstrap tidak tersedia
        toast.classList.add('show');
        setTimeout(() => {
            if (toast.parentNode) {
                document.body.removeChild(toast);
            }
        }, 3000);
    }

    // Hapus toast setelah ditutup
    toast.addEventListener('hidden.bs.toast', function () {
        if (toast.parentNode) {
            document.body.removeChild(toast);
        }
    });

    // Fallback untuk auto remove
    setTimeout(() => {
        if (toast.parentNode) {
            document.body.removeChild(toast);
        }
    }, 5000);
}

// ==================== LOGOUT FUNCTIONS ====================

// Fungsi untuk menampilkan dialog logout
function showLogoutDialog(event) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }

    const logoutModal = document.getElementById('logoutModal');
    if (logoutModal) {
        logoutModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    } else {
        console.warn('Logout modal element tidak ditemukan');
        // Fallback ke fungsi logout langsung
        logoutUser();
    }
}

// Fungsi untuk menyembunyikan dialog logout
function hideLogoutDialog() {
    const logoutModal = document.getElementById('logoutModal');
    if (logoutModal) {
        logoutModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Fungsi untuk logout langsung (tanpa modal)
function logoutUser() {
    showToast('Anda telah logout. Mengarahkan ke halaman homepage...', 'success');

    // Simulasi redirect ke halaman homepage setelah 1.5 detik
    setTimeout(() => {
        window.location.href = '/logout'; // Sesuaikan dengan URL homepage Anda
    }, 1500);
}

// Fungsi untuk konfirmasi logout dari modal
function confirmLogout() {
    hideLogoutDialog();
    logoutUser();
}

// ==================== INITIALIZATION ====================

// Fungsi untuk inisialisasi semua event listeners
function initializeApp() {
    console.log('Initializing notification and logout system...');

    // Muat notifikasi pertama kali
    loadNotifications();

    // Setup event listener untuk tombol refresh notifikasi
    const refreshBtn = document.getElementById('notificationRefreshBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', refreshNotifications);
    } else {
        console.warn('Tombol refresh notifikasi tidak ditemukan');
    }

    // Setup event listener untuk dropdown notifikasi
    const notificationDropdownBtn = document.getElementById('notificationDropdownBtn');
    if (notificationDropdownBtn) {
        notificationDropdownBtn.addEventListener('click', function () {
            // Muat notifikasi setiap kali dropdown dibuka
            setTimeout(loadNotifications, 100);
        });
    }

    // Setup event listener untuk tombol logout
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', showLogoutDialog);
    } else {
        console.warn('Tombol logout tidak ditemukan');
    }

    // Setup event listener untuk modal logout
    const logoutModal = document.getElementById('logoutModal');
    if (logoutModal) {
        // Close modal ketika klik di luar
        logoutModal.addEventListener('click', function (event) {
            if (event.target === this) {
                hideLogoutDialog();
            }
        });

        // Setup tombol cancel logout
        const logoutCancelBtn = document.getElementById('logoutCancelBtn');
        if (logoutCancelBtn) {
            logoutCancelBtn.addEventListener('click', hideLogoutDialog);
        }

        // Setup tombol confirm logout
        const logoutConfirmBtn = document.getElementById('logoutConfirmBtn');
        if (logoutConfirmBtn) {
            logoutConfirmBtn.addEventListener('click', confirmLogout);
        }
    }

    // Close logout modal dengan Escape key
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            const logoutModal = document.getElementById('logoutModal');
            if (logoutModal && logoutModal.style.display === 'flex') {
                hideLogoutDialog();
            }
        }
    });

    // Tambahkan style untuk loading animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .notification-refresh-btn.loading i {
            animation: spin 1s linear infinite;
        }
        .logout-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .toast {
            z-index: 10000;
        }
    `;
    document.head.appendChild(style);
}

// ==================== EVENT LISTENERS ====================

// Tunggu DOM sepenuhnya dimuat
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeApp);
} else {
    // DOM sudah dimuat
    initializeApp();
}

// Juga inisialisasi jika dokumen sudah siap
window.addEventListener('load', function () {
    // Pastikan AOS diinisialisasi jika ada
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });
    }
});

// Export untuk testing (opsional)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        notifications,
        loadNotifications,
        refreshNotifications,
        showToast,
        showLogoutDialog,
        logoutUser
    };
}