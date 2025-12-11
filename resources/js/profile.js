// User data dengan NPWP dan Status Alumni
let userData = {
    namaLengkap: "Deny Iqbal",
    nim: "20180801234",
    jurusan: "Teknik Informatika",
    tahunLulus: "2022",
    email: "deny.iqbal@email.com",
    nomorHp: "+62 812-3456-7890",
    npwp: "12.345.678.9-012.345",
};

let userStats = {
    rank: 24,
    points: 15250
};

let isEditMode = false;
let originalData = {};

// Initialize profile data
function initializeProfile() {
    const profileInfoGrid = document.getElementById('profileInfoGrid');
    const fields = [
        { label: 'Nama Lengkap', key: 'namaLengkap', type: 'text' },
        { label: 'NIM', key: 'nim', type: 'text' },
        { label: 'Jurusan/Prodi', key: 'jurusan', type: 'text' },
        { label: 'Tahun Lulus', key: 'tahunLulus', type: 'text' },
        { label: 'Email', key: 'email', type: 'email' },
        { label: 'Nomor HP/WhatsApp', key: 'nomorHp', type: 'tel' },
        { label: 'NPWP', key: 'npwp', type: 'text' }
    ];

    profileInfoGrid.innerHTML = fields.map(field => `
                <div class="info-item">
                    <span class="info-label">${field.label}</span>
                    <div class="info-value editable" data-key="${field.key}" onclick="handleFieldClick('${field.key}')">
                        ${userData[field.key]}
                    </div>
                </div>
            `).join('');

    // Update stats
    document.getElementById('rankValue').textContent = `#${userStats.rank}`;
    document.getElementById('pointsValue').textContent = userStats.points.toLocaleString();

    // Set theme from localStorage
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);
    document.getElementById('themeToggle').checked = savedTheme === 'dark';
}

// Handle field click for editing
function handleFieldClick(key) {
    if (!isEditMode) return;

    const valueElement = document.querySelector(`[data-key="${key}"]`);
    const currentValue = userData[key];

    valueElement.innerHTML = `
                <input type="text" class="info-input" value="${currentValue}" 
                       onblur="updateField('${key}', this.value)"
                       onkeypress="handleKeyPress(event, '${key}', this)"
                       autocomplete="off">
            `;
    valueElement.querySelector('input').focus();
}

// Handle key press in input field
function handleKeyPress(event, key, inputElement) {
    if (event.key === 'Enter') {
        updateField(key, inputElement.value);
    }
}

// Update field value
function updateField(key, value) {
    userData[key] = value;
    const valueElement = document.querySelector(`[data-key="${key}"]`);
    valueElement.textContent = value;

    // Update profile name if it's the name field
    if (key === 'namaLengkap') {
        document.getElementById('profileName').textContent = value;
        updateAvatarInitials(value);
    }
}

// Update avatar initials
function updateAvatarInitials(name) {
    const initials = name.split(' ').map(n => n[0]).join('').toUpperCase();
    document.getElementById('profileAvatar').textContent = initials;
}

// Enable edit mode
function enableEditMode() {
    isEditMode = true;
    originalData = { ...userData };

    const actionButtons = document.getElementById('actionButtons');
    actionButtons.innerHTML = `
                <button class="btn-save" onclick="saveChanges()">
                    <i class="fas fa-save me-2"></i> Simpan Perubahan
                </button>
                <button class="btn-cancel" onclick="cancelEdit()">
                    <i class="fas fa-times me-2"></i> Batal
                </button>
            `;

    // Make all fields editable
    document.querySelectorAll('.info-value').forEach(el => {
        el.classList.add('editable');
    });
}

// Save changes
function saveChanges() {
    isEditMode = false;

    const actionButtons = document.getElementById('actionButtons');
    actionButtons.innerHTML = `
                <button class="btn-edit" onclick="enableEditMode()">
                    <i class="fas fa-edit me-2"></i> Edit Profil
                </button>
            `;

    // Remove editable class
    document.querySelectorAll('.info-value').forEach(el => {
        el.classList.remove('editable');
    });

    showToast('Perubahan berhasil disimpan!', 'success');

    // Here you would typically send the data to the server
    console.log('Data yang disimpan:', userData);
}

// Cancel edit
function cancelEdit() {
    isEditMode = false;
    userData = { ...originalData };
    initializeProfile();

    const actionButtons = document.getElementById('actionButtons');
    actionButtons.innerHTML = `
                <button class="btn-edit" onclick="enableEditMode()">
                    <i class="fas fa-edit me-2"></i> Edit Profil
                </button>
            `;

    showToast('Perubahan dibatalkan', 'info');
}

// Handle photo upload
function handlePhotoUpload(event) {
    const file = event.target.files[0];
    if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            showToast('Harap pilih file gambar', 'error');
            return;
        }

        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            showToast('Ukuran file maksimal 5MB', 'error');
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            const avatar = document.getElementById('profileAvatar');
            avatar.style.backgroundImage = `url(${e.target.result})`;
            avatar.textContent = '';
            showToast('Foto profil berhasil diubah!', 'success');
        };
        reader.readAsDataURL(file);
    }
}

// Toggle theme
function toggleTheme() {
    const themeToggle = document.getElementById('themeToggle');
    const theme = themeToggle.checked ? 'dark' : 'light';

    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);

    showToast(`Tema ${theme === 'dark' ? 'gelap' : 'terang'} diaktifkan`, 'success');
}

// Modal functions
function openPasswordModal() {
    alert('Modal Ganti Password akan ditampilkan di sini');
}

function openNotificationModal() {
    alert('Modal Pengaturan Notifikasi akan ditampilkan di sini');
}

// Toast notification function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info';

    toast.className = `toast align-items-center text-white bg-${bgColor} border-0 position-fixed top-0 end-0 m-3`;
    toast.style.zIndex = '9999';
    toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'}-circle me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;

    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    toast.addEventListener('hidden.bs.toast', function () {
        document.body.removeChild(toast);
    });
}

// Initialize the profile when page loads
document.addEventListener('DOMContentLoaded', initializeProfile);