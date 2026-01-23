// NOTIFICATION SYSTEM - IMPROVED VERSION

let notifications = [
    {
        id: 1,
        type: 'forum',
        title: 'Balasan Komentar',
        message: 'Siti Rahayu membalas komentar Anda di postingan "Tips Interview Kerja" dengan memberikan saran yang sangat bermanfaat.',
        time: '5 menit lalu',
        is_read: false,
        icon: 'fas fa-comments',
        color: 'primary',
        link: '#'
    },
    {
        id: 2,
        type: 'verification',
        title: 'Verifikasi Diterima',
        message: 'Lowongan kerja Data Analyst di PT. Teknologi Indonesia telah diverifikasi. Anda mendapatkan +50 Points!',
        time: '1 jam lalu',
        is_read: false,
        icon: 'fas fa-check-circle',
        color: 'success',
        link: '#'
    },
    {
        id: 3,
        type: 'consultation',
        title: 'Konsultasi Diproses',
        message: 'Permintaan konsultasi karir Anda dengan mentor dari industri teknologi sedang diproses.',
        time: '3 jam lalu',
        is_read: false,
        icon: 'fas fa-user-clock',
        color: 'warning',
        link: '#'
    },
    {
        id: 4,
        type: 'points',
        title: 'Points Bertambah',
        message: 'Anda mendapatkan 25 points karena aktif berpartisipasi di forum diskusi "Peluang Karir di Era Digital".',
        time: '5 jam lalu',
        is_read: true,
        icon: 'fas fa-coins',
        color: 'warning',
        link: '#'
    },
    {
        id: 5,
        type: 'system',
        title: 'Pengingat Kuesioner',
        message: 'Jangan lupa menyelesaikan bagian 2 kuesioner untuk membuka fitur Forum. Selesaikan sebelum 31 Desember.',
        time: '1 hari lalu',
        is_read: true,
        icon: 'fas fa-clipboard-check',
        color: 'info',
        link: '#'
    },
    {
        id: 6,
        type: 'forum',
        title: 'Diskusi Baru',
        message: 'Budi Santoso membuat postingan baru "Pengalaman Kerja di Startup Teknologi". Yuk bagikan pengalamanmu!',
        time: '2 hari lalu',
        is_read: true,
        icon: 'fas fa-comment-medical',
        color: 'primary',
        link: '#'
    }
];

// ==================== NOTIFICATION FUNCTIONS ====================

// Fungsi untuk memuat notifikasi
function loadNotifications() {
    const notificationList = document.getElementById('notificationList');
    if (!notificationList) return;

    // Hitung notifikasi yang belum dibaca
    const unreadCount = notifications.filter(notif => !notif.is_read).length;
    const totalCount = notifications.length;

    // Update badge di tombol
    updateNotificationBadge(unreadCount);

    // Update counter di dropdown
    const counter = document.getElementById('notificationCounter');
    if (counter) {
        counter.textContent = unreadCount > 0 ? `${unreadCount} baru` : '0 baru';
        counter.className = unreadCount > 0 ? 'badge bg-primary' : 'badge bg-secondary';
    }

    // Kosongkan daftar notifikasi
    notificationList.innerHTML = '';

    if (notifications.length === 0) {
        // Tampilkan pesan jika tidak ada notifikasi
        notificationList.innerHTML = `
            <div class="text-center py-5">
                <i class="far fa-bell fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-0">Tidak ada notifikasi</p>
            </div>
        `;
        return;
    }

    // Tambahkan notifikasi ke daftar
    notifications.forEach(notification => {
        const notificationItem = createNotificationElement(notification);
        notificationList.appendChild(notificationItem);
    });
}

// Fungsi untuk membuat elemen notifikasi
function createNotificationElement(notification) {
    const notificationItem = document.createElement('div');
    notificationItem.className = `notification-item ${notification.is_read ? '' : 'unread'}`;
    notificationItem.dataset.id = notification.id;

    const timeAgo = formatTimeAgo(notification.time);

    notificationItem.innerHTML = `
        <div class="d-flex align-items-start p-3 border-bottom ${notification.is_read ? 'bg-white' : 'bg-light'}">
            <div class="notification-icon me-3 rounded-circle d-flex align-items-center justify-content-center" 
                 style="width: 40px; height: 40px; background-color: ${getNotificationColor(notification.color, 0.1)}; color: ${getNotificationColor(notification.color)};">
                <i class="${notification.icon}"></i>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;">${notification.title}</h6>
                    <small class="text-muted">${timeAgo}</small>
                </div>
                <p class="mb-1" style="font-size: 0.85rem; color: #666;">${notification.message}</p>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <small class="text-muted">
                        <i class="fas fa-circle me-1" style="font-size: 6px; color: ${getNotificationColor(notification.color)};"></i>
                        ${notification.type.charAt(0).toUpperCase() + notification.type.slice(1)}
                    </small>
                    ${!notification.is_read ?
            `<button class="btn btn-sm btn-outline-primary mark-as-read-btn" style="font-size: 0.75rem; padding: 2px 8px;">
                            <i class="fas fa-check me-1"></i>Tandai dibaca
                        </button>` :
            `<small class="text-success"><i class="fas fa-check me-1"></i>Sudah dibaca</small>`
        }
                </div>
            </div>
        </div>
    `;

    // Tambahkan event listener untuk tombol "Tandai dibaca"
    const markAsReadBtn = notificationItem.querySelector('.mark-as-read-btn');
    if (markAsReadBtn) {
        markAsReadBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            markAsRead(notification.id);
        });
    }

    // Event listener untuk klik di seluruh item
    notificationItem.addEventListener('click', function (e) {
        if (!e.target.classList.contains('mark-as-read-btn') && !e.target.closest('.mark-as-read-btn')) {
            markAsRead(notification.id);
            if (notification.link && notification.link !== '#') {
                window.location.href = notification.link;
            }
        }
    });

    return notificationItem;
}

// Fungsi untuk mendapatkan warna notifikasi
function getNotificationColor(colorName, alpha = 1) {
    const colors = {
        'primary': alpha < 1 ? `rgba(13, 110, 253, ${alpha})` : '#0d6efd',
        'success': alpha < 1 ? `rgba(25, 135, 84, ${alpha})` : '#198754',
        'warning': alpha < 1 ? `rgba(255, 193, 7, ${alpha})` : '#ffc107',
        'info': alpha < 1 ? `rgba(13, 202, 240, ${alpha})` : '#0dcaf0',
        'danger': alpha < 1 ? `rgba(220, 53, 69, ${alpha})` : '#dc3545',
        'secondary': alpha < 1 ? `rgba(108, 117, 125, ${alpha})` : '#6c757d'
    };

    return colors[colorName] || colors['primary'];
}

// Fungsi untuk format waktu
function formatTimeAgo(timeString) {
    return timeString; // Untuk statis, return langsung
}

// Fungsi untuk menandai notifikasi sebagai sudah dibaca
function markAsRead(notificationId) {
    const notification = notifications.find(n => n.id === notificationId);
    if (notification && !notification.is_read) {
        notification.is_read = true;
        loadNotifications();
        showToast('Notifikasi ditandai sebagai sudah dibaca', 'success');
    }
}

// Fungsi untuk menandai SEMUA notifikasi sebagai sudah dibaca
function markAllAsRead() {
    let updated = false;
    notifications.forEach(notification => {
        if (!notification.is_read) {
            notification.is_read = true;
            updated = true;
        }
    });

    if (updated) {
        loadNotifications();
        showToast('Semua notifikasi ditandai sebagai sudah dibaca', 'success');
    } else {
        showToast('Tidak ada notifikasi baru', 'info');
    }
}

// Fungsi untuk menghapus semua notifikasi
function clearAllNotifications() {
    if (notifications.length === 0) {
        showToast('Tidak ada notifikasi untuk dihapus', 'info');
        return;
    }

    if (confirm('Apakah Anda yakin ingin menghapus semua notifikasi?')) {
        notifications = [];
        loadNotifications();
        showToast('Semua notifikasi telah dihapus', 'success');
    }
}

// Fungsi untuk refresh notifikasi (tambah notifikasi baru)
function refreshNotifications() {
    const refreshBtn = document.getElementById('notificationRefreshBtn');
    if (!refreshBtn) return;

    // Tampilkan loading state
    const icon = refreshBtn.querySelector('i');
    const originalIcon = icon.className;
    icon.className = 'fas fa-spinner fa-spin';
    refreshBtn.disabled = true;

    // Simulasi request ke server
    setTimeout(() => {
        // Tambahkan notifikasi baru (simulasi)
        const newId = notifications.length > 0 ? Math.max(...notifications.map(n => n.id)) + 1 : 1;
        const newNotification = {
            id: newId,
            type: 'system',
            title: 'Pembaruan Sistem',
            message: 'Sistem telah diperbarui dengan fitur notifikasi yang lebih baik. Coba klik notifikasi untuk menandainya sebagai sudah dibaca!',
            time: 'Baru saja',
            is_read: false,
            icon: 'fas fa-sync-alt',
            color: 'info',
            link: '#'
        };

        // Tambahkan notifikasi baru di urutan teratas
        notifications.unshift(newNotification);

        // Muat ulang notifikasi
        loadNotifications();

        // Kembalikan icon dan state
        icon.className = originalIcon;
        refreshBtn.disabled = false;

        // Tampilkan toast
        showToast('Notifikasi telah diperbarui', 'success');
    }, 1000);
}

// Fungsi untuk update badge notifikasi
function updateNotificationBadge(count) {
    const badge = document.querySelector('.notification-count-badge');
    if (badge) {
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'block';

            // Tambah animasi jika notifikasi baru
            if (count > parseInt(badge.textContent) || badge.style.display === 'none') {
                badge.classList.add('pulse-animation');
                setTimeout(() => badge.classList.remove('pulse-animation'), 1000);
            }
        } else {
            badge.style.display = 'none';
        }
    }
}

// ==================== INITIALIZATION ====================

// Fungsi untuk inisialisasi event listeners notifikasi
function initializeNotificationSystem() {
    console.log('Initializing notification system...');

    // Muat notifikasi pertama kali
    loadNotifications();

    // Setup event listener untuk tombol refresh notifikasi
    const refreshBtn = document.getElementById('notificationRefreshBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            refreshNotifications();
        });
    }

    // Setup event listener untuk tombol "Tandai Semua Sudah Dibaca"
    const markAllReadBtn = document.getElementById('markAllReadBtn');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            markAllAsRead();
        });
    }

    // Setup event listener untuk tombol "Hapus Semua"
    const clearAllBtn = document.getElementById('clearAllBtn');
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            clearAllNotifications();
        });
    }

    // Setup event listener untuk dropdown notifikasi
    const notificationDropdownBtn = document.getElementById('notificationDropdownBtn');
    if (notificationDropdownBtn) {
        notificationDropdownBtn.addEventListener('click', function () {
            // Muat notifikasi setiap kali dropdown dibuka
            setTimeout(loadNotifications, 100);
        });
    }

    // Tambahkan style untuk animasi
    addNotificationStyles();
}

// Fungsi untuk menambahkan styles tambahan
function addNotificationStyles() {
    const style = document.createElement('style');
    style.textContent = `
        /* Animasi untuk badge notifikasi */
        .pulse-animation {
            animation: pulse 0.5s ease-in-out;
        }
        
        @keyframes pulse {
            0% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-50%, -50%) scale(1.2); }
            100% { transform: translate(-50%, -50%) scale(1); }
        }
        
        /* Hover effect untuk notification item */
        .notification-item {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .notification-item:hover {
            background-color: #f8f9fa !important;
        }
        
        .notification-item.unread:hover {
            background-color: #e7f1ff !important;
        }
        
        /* Loading animation untuk refresh button */
        .fa-spinner {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Scrollbar styling untuk notification container */
        #notificationList::-webkit-scrollbar {
            width: 6px;
        }
        
        #notificationList::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        #notificationList::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        #notificationList::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* Style untuk tombol kecil */
        .mark-as-read-btn {
            transition: all 0.2s ease;
        }
        
        .mark-as-read-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    `;
    document.head.appendChild(style);
}

// ==================== EVENT LISTENERS ====================

// Inisialisasi ketika DOM siap
document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi sistem notifikasi
    initializeNotificationSystem();

    // Juga panggil loadNotifications setelah sedikit delay untuk memastikan elemen sudah ada
    setTimeout(loadNotifications, 300);
});

// Export untuk akses global (opsional)
window.NotificationSystem = {
    notifications,
    loadNotifications,
    refreshNotifications,
    markAsRead,
    markAllAsRead,
    clearAllNotifications
};