// Profile Management Script
let isEditMode = false;
let originalValues = {};

// Helper function to get CSRF token
function getCsrfToken() {
    // Coba dari window.profileData dulu
    if (window.profileData && window.profileData.csrfToken) {
        return window.profileData.csrfToken;
    }

    // Coba berbagai cara untuk mendapatkan CSRF token
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    if (metaTag && metaTag.content) {
        return metaTag.content;
    }

    const csrfInput = document.querySelector('input[name="_token"]');
    if (csrfInput && csrfInput.value) {
        return csrfInput.value;
    }

    console.error('CSRF token not found!');
    return '';
}

// Initialize profile data
document.addEventListener('DOMContentLoaded', function () {
    console.log('Profile page loaded');

    // Debug: Log CSRF token
    const csrfToken = getCsrfToken();
    console.log('CSRF Token found:', csrfToken ? 'Yes' : 'No');

    initializeProfile();

    // Event listener untuk klik pada field yang bisa diedit
    document.querySelectorAll('.editable-field').forEach(field => {
        field.addEventListener('click', function (e) {
            if (isEditMode && !e.target.classList.contains('info-input')) {
                makeEditable(this);
            }
        });
    });

    // Tambahkan event listener untuk input fields saat di-enter
    document.querySelectorAll('.info-input').forEach(input => {
        input.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                // Cari field berikutnya atau simpan
                const allInputs = Array.from(document.querySelectorAll('.info-input:not(.d-none)'));
                const currentIndex = allInputs.indexOf(this);
                if (currentIndex < allInputs.length - 1) {
                    allInputs[currentIndex + 1].focus();
                } else {
                    saveProfileChanges();
                }
            }
        });
    });
});

function initializeProfile() {
    console.log('Profile initialized');

    // Set theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', savedTheme);

    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        themeToggle.checked = savedTheme === 'dark';
        themeToggle.addEventListener('change', toggleTheme);
    }

    // Handle password form submission
    const passwordForm = document.getElementById('changePasswordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function (e) {
            e.preventDefault();
            changePassword();
        });
    }

    // Initialize tooltips jika ada
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
}

// Make field editable
function makeEditable(element) {
    if (!isEditMode) {
        showToast('Aktifkan mode edit terlebih dahulu', 'warning');
        return;
    }

    const fieldValue = element.querySelector('.field-value');
    const inputField = element.querySelector('.info-input');

    if (fieldValue && inputField) {
        // Sembunyikan semua input lainnya yang sedang aktif
        document.querySelectorAll('.info-input:not(.d-none)').forEach(input => {
            const parentField = input.closest('.editable-field');
            if (parentField && parentField !== element) {
                const parentValue = parentField.querySelector('.field-value');
                if (parentValue) {
                    parentValue.classList.remove('d-none');
                    input.classList.add('d-none');
                }
            }
        });

        fieldValue.classList.add('d-none');
        inputField.classList.remove('d-none');
        inputField.focus();
        inputField.select();

        // Save original value
        const fieldName = element.dataset.field;
        originalValues[fieldName] = inputField.dataset.original || inputField.value;
    }
}

// Enable edit mode
function enableEditMode() {
    if (isEditMode) return;

    isEditMode = true;

    // Show editable cursor
    document.querySelectorAll('.editable-field').forEach(field => {
        field.style.cursor = 'pointer';
        field.classList.add('editable-active');
    });

    // Show save and cancel buttons
    document.getElementById('saveButton')?.classList.remove('d-none');
    document.getElementById('cancelButton')?.classList.remove('d-none');
    document.querySelector('.btn-edit')?.classList.add('d-none');

    // Highlight first field
    const firstEditableField = document.querySelector('.editable-field');
    if (firstEditableField) {
        setTimeout(() => {
            makeEditable(firstEditableField);
        }, 300);
    }

    showToast('Klik pada field yang ingin diubah. Tekan Enter untuk berpindah field.', 'info');
}

// Cancel edit
function cancelEdit() {
    if (!isEditMode) return;

    isEditMode = false;

    // Reset all fields
    document.querySelectorAll('.editable-field').forEach(field => {
        const fieldValue = field.querySelector('.field-value');
        const inputField = field.querySelector('.info-input');

        if (fieldValue && inputField) {
            // Reset to original value
            const originalValue = inputField.dataset.original || '';
            inputField.value = originalValue;
            fieldValue.textContent = originalValue || '-';

            fieldValue.classList.remove('d-none');
            inputField.classList.add('d-none');
            inputField.classList.remove('is-invalid');
        }
    });

    // Hide editable cursor
    document.querySelectorAll('.editable-field').forEach(field => {
        field.style.cursor = 'default';
        field.classList.remove('editable-active');
    });

    // Hide save/cancel, show edit
    document.getElementById('saveButton')?.classList.add('d-none');
    document.getElementById('cancelButton')?.classList.add('d-none');
    document.querySelector('.btn-edit')?.classList.remove('d-none');

    originalValues = {};
    showToast('Perubahan dibatalkan', 'info');
}

// Save profile changes
function saveProfileChanges() {
    if (!isEditMode) {
        showToast('Anda tidak dalam mode edit', 'error');
        return;
    }

    // Validasi form sebelum submit
    let isValid = true;
    const errors = [];
    const errorFields = [];

    // Validasi required fields
    document.querySelectorAll('.info-input').forEach(input => {
        const isRequired = input.hasAttribute('required');
        const isEmpty = !input.value.trim();

        if (isRequired && isEmpty) {
            isValid = false;
            const fieldName = input.name.replace(/_/g, ' ');
            errors.push(`Field ${fieldName} harus diisi`);
            errorFields.push(input);
            input.classList.add('is-invalid');
        } else if (input.name === 'graduation_year') {
            // Validasi khusus untuk tahun lulus
            const year = parseInt(input.value);
            const currentYear = new Date().getFullYear();
            if (year < 2000 || year > currentYear + 5) {
                isValid = false;
                errors.push(`Tahun lulus harus antara 2000 dan ${currentYear + 5}`);
                errorFields.push(input);
                input.classList.add('is-invalid');
            }
        } else if (input.name === 'phone') {
            // Validasi nomor HP (minimal 10 digit, maksimal 15 digit)
            const phoneRegex = /^[0-9]{10,15}$/;
            if (!phoneRegex.test(input.value.replace(/\D/g, ''))) {
                isValid = false;
                errors.push('Nomor HP harus 10-15 digit angka');
                errorFields.push(input);
                input.classList.add('is-invalid');
            }
        } else {
            input.classList.remove('is-invalid');
        }
    });

    if (!isValid) {
        // Tampilkan error di field pertama yang error
        if (errorFields.length > 0) {
            const firstErrorField = errorFields[0];
            const parentField = firstErrorField.closest('.editable-field');
            if (parentField) {
                makeEditable(parentField);
            }
        }
        showToast(errors.join('<br>'), 'error');
        return;
    }

    // Collect form data
    const formData = new FormData();
    formData.append('_method', 'PATCH');
    formData.append('_token', getCsrfToken());

    // Add all input values
    document.querySelectorAll('.info-input').forEach(input => {
        let value = input.value.trim();

        // Format khusus untuk phone number (hapus semua non-digit)
        if (input.name === 'phone') {
            value = value.replace(/\D/g, '');
        }

        formData.append(input.name, value);
    });

    // Show loading
    const saveBtn = document.getElementById('saveButton');
    if (!saveBtn) {
        console.error('Save button not found!');
        showToast('Tombol simpan tidak ditemukan', 'error');
        return;
    }

    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
    saveBtn.disabled = true;

    // Gunakan route yang benar dari Laravel
    const url = window.profileData?.profileUpdateUrl ||
        document.querySelector('form#profileForm')?.action ||
        window.location.pathname + '/update';

    console.log('Mengirim request ke:', url);
    console.log('CSRF Token:', getCsrfToken());

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Update displayed values
                document.querySelectorAll('.editable-field').forEach(field => {
                    const fieldValue = field.querySelector('.field-value');
                    const inputField = field.querySelector('.info-input');

                    if (fieldValue && inputField) {
                        // Update displayed value
                        const displayValue = inputField.value.trim() || '-';
                        fieldValue.textContent = displayValue;

                        // Update data-original
                        inputField.dataset.original = inputField.value;

                        // Hide input, show value
                        fieldValue.classList.remove('d-none');
                        inputField.classList.add('d-none');
                        inputField.classList.remove('is-invalid');
                    }
                });

                // Update profile name and avatar
                if (data.data && data.data.fullname) {
                    document.getElementById('profileName').textContent = data.data.fullname;
                    updateAvatarInitials(data.data.fullname);
                }

                // Exit edit mode
                isEditMode = false;
                document.querySelectorAll('.editable-field').forEach(field => {
                    field.style.cursor = 'default';
                    field.classList.remove('editable-active');
                });

                document.getElementById('saveButton')?.classList.add('d-none');
                document.getElementById('cancelButton')?.classList.add('d-none');
                document.querySelector('.btn-edit')?.classList.remove('d-none');

                originalValues = {};
                showToast(data.message || 'Profil berhasil diperbarui!', 'success');

                // Update stats jika ada dalam response
                if (data.data) {
                    if (data.data.ranking !== undefined) {
                        document.getElementById('rankValue').textContent = '#' + data.data.ranking;
                    }
                    if (data.data.points !== undefined) {
                        document.getElementById('pointsValue').textContent = data.data.points.toLocaleString();
                    }
                }

                // Reload page setelah 2 detik untuk memastikan data terupdate
                setTimeout(() => {
                    window.location.reload();
                }, 2000);

            } else {
                // Show validation errors
                if (data.errors) {
                    let errorMessages = [];
                    Object.values(data.errors).forEach(errorArray => {
                        errorMessages = errorMessages.concat(errorArray);
                    });
                    showToast(errorMessages.join('<br>'), 'error');

                    // Highlight field yang error
                    if (data.errors) {
                        Object.keys(data.errors).forEach(fieldName => {
                            const input = document.querySelector(`[name="${fieldName}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const parentField = input.closest('.editable-field');
                                if (parentField) {
                                    makeEditable(parentField);
                                }
                            }
                        });
                    }
                } else {
                    showToast(data.message || 'Terjadi kesalahan saat menyimpan', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat menyimpan data. Silakan coba lagi.', 'error');
        })
        .finally(() => {
            // Restore button
            if (saveBtn) {
                saveBtn.innerHTML = originalText;
                saveBtn.disabled = false;
            }
        });
}

// Change password function with real-time validation
// Change password function with real-time validation
function changePassword(event) {
    if (event) event.preventDefault(); // Prevent default form submission

    const form = document.getElementById('changePasswordForm');
    if (!form) {
        console.error('Password form not found!');
        showToast('Form password tidak ditemukan', 'error');
        return;
    }

    const currentPassword = document.getElementById('current_password');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');

    // Clear previous errors
    clearPasswordErrors();

    // Client-side validation
    let isValid = true;
    let errors = {};

    if (!currentPassword.value.trim()) {
        errors.current_password = ['Password saat ini harus diisi'];
        isValid = false;
    }

    if (!newPassword.value.trim()) {
        errors.new_password = ['Password baru harus diisi'];
        isValid = false;
    } else if (newPassword.value.length < 8) {
        errors.new_password = ['Password baru minimal 8 karakter'];
        isValid = false;
    } else if (newPassword.value === currentPassword.value) {
        errors.new_password = ['Password baru harus berbeda dengan password lama'];
        isValid = false;
    }

    if (!confirmPassword.value.trim()) {
        errors.new_password_confirmation = ['Konfirmasi password harus diisi'];
        isValid = false;
    } else if (newPassword.value !== confirmPassword.value) {
        errors.new_password_confirmation = ['Password baru dan konfirmasi tidak cocok'];
        isValid = false;
    }

    if (!isValid) {
        displayPasswordErrors(errors);
        return;
    }

    // Show loading
    const submitBtn = form.querySelector('button[type="submit"]');
    if (!submitBtn) {
        console.error('Submit button not found!');
        showToast('Tombol submit tidak ditemukan', 'error');
        return;
    }

    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengubah...';
    submitBtn.disabled = true;

    const formData = new FormData(form);

    // DEBUG: Log data yang akan dikirim
    console.log('Form action:', form.action);
    console.log('Form method:', form.method);

    // Gunakan form.action sebagai URL
    fetch(form.action, {
        method: 'POST', // Pastikan menggunakan POST
        body: formData,
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            console.log('Response status:', response.status);

            if (response.status === 422) {
                // Validation error
                return response.json().then(data => {
                    return { success: false, errors: data.errors };
                });
            }

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // TAMPILKAN TOAST SUKSES YANG BAGUS
                showToast('Password Anda telah berhasil diperbarui. Silakan login kembali dengan password baru.', 'success');

                // Tutup modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('changePasswordModal'));
                if (modal) {
                    modal.hide();
                }

                // Reset form
                form.reset();
                clearPasswordErrors();

                // Optional: Auto logout setelah 3 detik
                // setTimeout(() => {
                //     showToast('Anda akan logout dalam 3 detik...', 'info');
                // }, 2000);

            } else {
                // Display server errors
                if (data.errors) {
                    displayPasswordErrors(data.errors);

                    // Tampilkan toast error yang spesifik
                    if (data.errors.current_password) {
                        showToast('Password saat ini tidak sesuai. Silakan cek kembali.', 'error');
                    } else if (data.errors.new_password) {
                        showToast('Password baru tidak valid: ' + data.errors.new_password[0], 'error');
                    } else {
                        showToast('Terjadi kesalahan pada input form.', 'error');
                    }
                } else {
                    showToast('Gagal mengubah password. Silakan coba lagi.', 'error');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Koneksi terputus atau server error. Silakan coba lagi nanti.', 'error');
        })
        .finally(() => {
            // Restore button
            if (submitBtn) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
}

// Upload profile photo
function uploadProfilePhoto(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Validate
    if (!file.type.startsWith('image/')) {
        showToast('Harap pilih file gambar (JPG, PNG, GIF)', 'error');
        return;
    }

    if (file.size > 5 * 1024 * 1024) {
        showToast('Ukuran file maksimal 5MB', 'error');
        return;
    }

    // Show preview immediately
    const reader = new FileReader();
    reader.onload = function (e) {
        const avatar = document.getElementById('profileAvatar');
        if (avatar) {
            avatar.style.backgroundImage = `url(${e.target.result})`;
            avatar.style.backgroundSize = 'cover';
            avatar.style.backgroundPosition = 'center';
            avatar.textContent = '';
        }
    };
    reader.readAsDataURL(file);

    // Upload to server
    const formData = new FormData();
    formData.append('photo', file);
    formData.append('_token', getCsrfToken());

    // Show loading
    const cameraBtn = document.querySelector('.change-photo-btn');
    if (!cameraBtn) {
        console.error('Camera button not found!');
        showToast('Tombol camera tidak ditemukan', 'error');
        return;
    }

    const originalHtml = cameraBtn.innerHTML;
    cameraBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    cameraBtn.disabled = true;

    // Gunakan URL dari data attribute atau fallback
    const uploadUrl = cameraBtn.getAttribute('data-upload-url') ||
        document.querySelector('form#profileForm')?.getAttribute('data-photo-url') ||
        window.location.pathname + '/photo/upload';

    console.log('Upload URL:', uploadUrl);

    fetch(uploadUrl, {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`Upload gagal: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Photo upload response:', data);
            if (data.success) {
                showToast(data.message, 'success');
                // Update avatar dengan URL dari server
                if (data.photo_url) {
                    const avatar = document.getElementById('profileAvatar');
                    if (avatar) {
                        avatar.style.backgroundImage = `url(${data.photo_url}?t=${new Date().getTime()})`;
                        avatar.style.backgroundSize = 'cover';
                        avatar.style.backgroundPosition = 'center';
                        avatar.textContent = '';
                    }
                }
            } else {
                showToast(data.message || 'Gagal mengupload foto', 'error');
                // Reset preview jika gagal
                resetAvatarPreview();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Terjadi kesalahan saat mengupload foto', 'error');
            resetAvatarPreview();
        })
        .finally(() => {
            // Restore button
            if (cameraBtn) {
                cameraBtn.innerHTML = originalHtml;
                cameraBtn.disabled = false;
            }
            // Reset file input
            event.target.value = '';
        });
}

// Reset avatar preview
function resetAvatarPreview() {
    const avatar = document.getElementById('profileAvatar');
    if (avatar) {
        avatar.style.backgroundImage = '';
        const fullname = document.querySelector('input[name="fullname"]')?.value ||
            document.getElementById('profileName')?.textContent ||
            'US';
        avatar.textContent = fullname.substring(0, 2).toUpperCase();
    }
}

// Display password errors in form
function displayPasswordErrors(errors) {
    clearPasswordErrors();

    if (errors.current_password) {
        const element = document.getElementById('current_password');
        if (element) {
            element.classList.add('is-invalid');
            element.parentElement.insertAdjacentHTML('beforeend',
                `<div class="invalid-feedback">${errors.current_password[0]}</div>`
            );
            element.focus();
        }
    }

    if (errors.new_password) {
        const element = document.getElementById('new_password');
        if (element) {
            element.classList.add('is-invalid');
            element.parentElement.insertAdjacentHTML('beforeend',
                `<div class="invalid-feedback">${errors.new_password[0]}</div>`
            );
            if (!errors.current_password) element.focus();
        }
    }

    if (errors.new_password_confirmation) {
        const element = document.getElementById('new_password_confirmation');
        if (element) {
            element.classList.add('is-invalid');
            element.parentElement.insertAdjacentHTML('beforeend',
                `<div class="invalid-feedback">${errors.new_password_confirmation[0]}</div>`
            );
            if (!errors.current_password && !errors.new_password) element.focus();
        }
    }
}

// Clear password error messages
function clearPasswordErrors() {
    ['current_password', 'new_password', 'new_password_confirmation'].forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            element.classList.remove('is-invalid');
            const feedback = element.parentElement.querySelector('.invalid-feedback');
            if (feedback) feedback.remove();
        }
    });
}

// Update avatar initials
function updateAvatarInitials(name) {
    if (!name) return;
    const initials = name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
    const avatar = document.getElementById('profileAvatar');
    if (avatar && !avatar.style.backgroundImage) {
        avatar.textContent = initials;
    }
}

// Toggle theme
function toggleTheme() {
    const themeToggle = document.getElementById('themeToggle');
    if (!themeToggle) return;

    const theme = themeToggle.checked ? 'dark' : 'light';

    // Apply theme immediately
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);

    // Save to server
    const csrfToken = getCsrfToken();
    if (csrfToken) {
        fetch('/profile/theme', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ theme: theme })
        }).catch(error => console.error('Error saving theme:', error));
    }

    showToast(`Tema ${theme === 'dark' ? 'gelap' : 'terang'} diaktifkan`, 'success');
}

// Toast notification
// Toast notification sederhana
function showToast(message, type = 'info') {
    // Remove existing toasts
    document.querySelectorAll('.custom-toast').forEach(toast => {
        if (toast.parentNode) {
            document.body.removeChild(toast);
        }
    });

    // Warna berdasarkan type
    const colors = {
        success: '#10b981',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6'
    };

    const bgColor = colors[type] || colors.info;

    const toast = document.createElement('div');
    toast.className = 'custom-toast';
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        background: ${bgColor};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.3s ease-out;
        font-family: 'Segoe UI', sans-serif;
        display: flex;
        align-items: center;
        justify-content: space-between;
    `;

    toast.innerHTML = `
        <div style="flex-grow: 1;">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-triangle' : 'fa-info-circle'}" 
               style="margin-right: 10px;"></i>
            ${message}
        </div>
        <button type="button" onclick="this.parentElement.remove()" 
                style="background: none; border: none; color: white; font-size: 20px; cursor: pointer; margin-left: 15px; padding: 0;">
            &times;
        </button>
    `;

    document.body.appendChild(toast);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            document.body.removeChild(toast);
        }
    }, 5000);
}

// Add CSS animation for toast
if (!document.querySelector('#toast-animation-style')) {
    const style = document.createElement('style');
    style.id = 'toast-animation-style';
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        @keyframes progress {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }
        
        .btn-close-toast:hover {
            color: #6b7280 !important;
        }
        
        /* Dark theme support */
        [data-theme="dark"] .custom-toast {
            background: #2d2d2d;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
        
        [data-theme="dark"] .custom-toast div div:first-child {
            color: #f3f4f6 !important;
        }
        
        [data-theme="dark"] .custom-toast div div:last-child {
            color: #d1d5db !important;
        }
        
        [data-theme="dark"] .btn-close-toast {
            color: #9ca3af !important;
        }
        
        [data-theme="dark"] .btn-close-toast:hover {
            color: #ffffff !important;
        }
    `;
    document.head.appendChild(style);
}