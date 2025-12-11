// LEADERBOARD FUNCTIONALITY - COMPATIBLE VERSION

// Fungsi untuk menginisialisasi leaderboard
function initializeLeaderboard() {
    console.log('Initializing leaderboard functionality...');

    // 1. Initialize form submissions
    initializeFormSubmissions();

    // 2. Initialize pagination
    initializePagination();

    // 3. Initialize tab switching
    // initializeTabSwitching();

    // 4. Initialize podium animations
    initializePodiumAnimations();

    // 5. Initialize current user highlight
    initializeCurrentUserHighlight();

    // 6. Initialize button effects
    initializeButtonEffects();

    // 7. Initialize AOS animations if available
    initializeAOS();

    console.log('Leaderboard functionality initialized successfully');
}

// 1. Initialize form submissions
function initializeFormSubmissions() {
    // Cek elemen form forum
    const forumForm = document.getElementById('forumForm');
    if (forumForm) {
        forumForm.addEventListener('submit', function (e) {
            e.preventDefault();
            handleFormSubmit(this, 'forumSuccessMessage');
        });
    }

    // Cek elemen form job
    const jobForm = document.getElementById('jobForm');
    if (jobForm) {
        jobForm.addEventListener('submit', function (e) {
            e.preventDefault();
            handleFormSubmit(this, 'jobSuccessMessage');
        });
    }

    // Setup form input validation
    setupFormValidation();
}

// 2. Initialize pagination
function initializePagination() {
    const pageLinks = document.querySelectorAll('.page-link');
    if (pageLinks.length > 0) {
        pageLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                if (this.getAttribute('href') === '#') {
                    e.preventDefault();
                    showNotification('Fitur pagination sedang dalam pengembangan. Pada implementasi nyata, halaman akan berubah sesuai data.', 'info');
                }
            });
        });
    }
}

// 3. Initialize tab switching
// function initializeTabSwitching() {
//     // Main tabs (Leaderboard / Submit)
//     const leaderboardTabs = document.querySelectorAll('#leaderboardTabs .nav-link');
//     if (leaderboardTabs.length > 0) {
//         leaderboardTabs.forEach(tab => {
//             tab.addEventListener('click', function () {
//                 // Reset scroll position ketika ganti tab
//                 setTimeout(() => {
//                     window.scrollTo({
//                         top: document.querySelector('.main-content').offsetTop - 100,
//                         behavior: 'smooth'
//                     });
//                 }, 300);
//             });
//         });
//     }

//     // Submit tabs (Forum / Job)
//     const submitTabs = document.querySelectorAll('#submitTabs .nav-link');
//     if (submitTabs.length > 0) {
//         submitTabs.forEach(tab => {
//             tab.addEventListener('click', function () {
//                 // Animate form section
//                 const formSection = document.querySelector('.tab-pane.active .form-section');
//                 if (formSection) {
//                     formSection.style.opacity = '0.5';
//                     formSection.style.transform = 'translateY(10px)';

//                     setTimeout(() => {
//                         formSection.style.opacity = '1';
//                         formSection.style.transform = 'translateY(0)';
//                         formSection.style.transition = 'all 0.3s ease';
//                     }, 300);
//                 }
//             });
//         });
//     }
// }

// 4. Initialize podium animations
function initializePodiumAnimations() {
    const podiumStands = document.querySelectorAll('.podium-stand');
    podiumStands.forEach(stand => {
        stand.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-10px)';
            this.style.transition = 'transform 0.3s ease';
        });

        stand.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0)';
        });

        // Add click effect
        stand.addEventListener('click', function () {
            const podiumPlace = this.closest('.podium-place');
            const podiumName = podiumPlace.querySelector('.podium-name').textContent;
            const podiumPoints = podiumPlace.querySelector('.podium-points').textContent;

            showNotification(`${podiumName} - ${podiumPoints}`, 'info');
        });
    });
}

// 5. Initialize current user highlight
function initializeCurrentUserHighlight() {
    const currentUserRow = document.querySelector('.current-user');
    if (currentUserRow) {
        // Add initial animation
        setTimeout(() => {
            currentUserRow.style.transition = 'background-color 1.5s ease';
        }, 500);

        // Add pulsing animation
        let pulseState = false;
        setInterval(() => {
            if (pulseState) {
                currentUserRow.style.backgroundColor = 'rgba(250, 179, 0, 0.1)';
            } else {
                currentUserRow.style.backgroundColor = 'rgba(250, 179, 0, 0.15)';
            }
            pulseState = !pulseState;
        }, 1500);

        // Add click to scroll
        currentUserRow.addEventListener('click', function () {
            this.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });

        // Scroll to current user on load (only if not on submit tab)
        if (window.location.hash !== '#submit') {
            setTimeout(() => {
                currentUserRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 1000);
        }
    }
}

// 6. Initialize button effects
function initializeButtonEffects() {
    // Add ripple effect to all submit buttons
    const submitButtons = document.querySelectorAll('.submit-btn');
    submitButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            // Don't add ripple effect if button is disabled
            if (this.disabled) return;

            // Add ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.7);
                transform: scale(0);
                animation: ripple-animation 0.6s linear;
                width: ${size}px;
                height: ${size}px;
                top: ${y}px;
                left: ${x}px;
                pointer-events: none;
                z-index: 1;
            `;

            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);

            // Remove ripple after animation
            setTimeout(() => {
                if (ripple.parentNode === this) {
                    this.removeChild(ripple);
                }
            }, 600);
        });
    });

    // Add CSS for ripple animation if not already present
    if (!document.querySelector('#ripple-animation-style')) {
        const style = document.createElement('style');
        style.id = 'ripple-animation-style';
        style.textContent = `
            @keyframes ripple-animation {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
}

// 7. Initialize AOS animations
function initializeAOS() {
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true,
            offset: 100,
            disable: window.innerWidth < 768
        });
    }
}

// Setup form validation
function setupFormValidation() {
    // Add validation styles to required inputs
    const requiredInputs = document.querySelectorAll('input[required], textarea[required], select[required]');
    requiredInputs.forEach(input => {
        input.addEventListener('blur', function () {
            if (!this.value.trim()) {
                this.style.borderColor = '#dc3545';
                this.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
            } else {
                this.style.borderColor = '#28a745';
                this.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
            }
        });

        input.addEventListener('input', function () {
            this.style.borderColor = '#e9ecef';
            this.style.boxShadow = 'none';
        });
    });
}

// Fungsi untuk menangani submit form
function handleFormSubmit(form, successMessageId) {
    console.log('Submitting form:', form.id);

    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    const successMessage = document.getElementById(successMessageId);

    // Validasi form
    if (!validateForm(form)) {
        showNotification('Harap isi semua bidang yang wajib diisi!', 'error');
        return;
    }

    // Show loading state
    showLoadingState(submitButton);

    // Simulate API call
    setTimeout(() => {
        // Show success message
        if (successMessage) {
            showSuccessMessage(successMessage, form, submitButton, originalText);
        }
    }, 2000);
}

// Validate form function
function validateForm(form) {
    const requiredInputs = form.querySelectorAll('[required]');
    let isValid = true;

    requiredInputs.forEach(input => {
        if (!input.value.trim()) {
            isValid = false;
            input.style.borderColor = '#dc3545';
            input.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';

            // Reset on input
            input.addEventListener('input', function resetValidation() {
                this.style.borderColor = '#e9ecef';
                this.style.boxShadow = 'none';
                this.removeEventListener('input', resetValidation);
            });
        }
    });

    return isValid;
}

// Show loading state function
function showLoadingState(button) {
    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengirim...';
    button.disabled = true;
    button.style.opacity = '0.7';
    button.style.cursor = 'not-allowed';
}

// Show success message function
function showSuccessMessage(successMessage, form, submitButton, originalText) {
    successMessage.style.display = 'flex';

    // Scroll to success message
    successMessage.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
    });

    // Reset form
    form.reset();

    // Reset validation styles
    const requiredInputs = form.querySelectorAll('[required]');
    requiredInputs.forEach(input => {
        input.style.borderColor = '#e9ecef';
        input.style.boxShadow = 'none';
    });

    // Reset button
    submitButton.innerHTML = originalText;
    submitButton.disabled = false;
    submitButton.style.opacity = '1';
    submitButton.style.cursor = 'pointer';

    // Show notification
    const formType = form.id.includes('forum') ? 'forum' : 'lowongan kerja';
    showNotification(`Informasi ${formType} berhasil dikirim! Tim admin akan memverifikasi dalam 1-2 hari kerja.`, 'success');

    // Hide success message after 5 seconds
    setTimeout(() => {
        successMessage.style.display = 'none';
    }, 5000);
}

// Fungsi untuk menampilkan notifikasi
function showNotification(message, type = 'info') {
    // Fallback jika Bootstrap tidak tersedia
    if (typeof bootstrap === 'undefined' || !bootstrap.Toast) {
        console.log(`${type.toUpperCase()}: ${message}`);

        // Create custom notification
        const notification = document.createElement('div');
        notification.className = `custom-notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            </div>
        `;

        // Add styles
        if (!document.querySelector('#custom-notification-style')) {
            const style = document.createElement('style');
            style.id = 'custom-notification-style';
            style.textContent = `
                .custom-notification {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'};
                    color: white;
                    padding: 15px 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                    z-index: 9999;
                    animation: slideInRight 0.3s ease, fadeOut 0.3s ease 4.7s forwards;
                    max-width: 400px;
                }
                .custom-notification .notification-content {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                }
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes fadeOut {
                    to { opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        }

        document.body.appendChild(notification);

        // Remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);

        return;
    }

    // Gunakan Bootstrap Toast jika tersedia
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;

    // Tambahkan ke container toast
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '1060';
        document.body.appendChild(toastContainer);
    }

    toastContainer.appendChild(toast);

    // Inisialisasi dan tampilkan toast
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 5000
    });

    bsToast.show();

    // Hapus toast setelah ditutup
    toast.addEventListener('hidden.bs.toast', function () {
        if (toast.parentNode === toastContainer) {
            toastContainer.removeChild(toast);
        }
    });
}

// Tunggu DOM siap
document.addEventListener('DOMContentLoaded', function () {
    initializeLeaderboard();

    // Refresh AOS on load
    setTimeout(() => {
        if (typeof AOS !== 'undefined') {
            AOS.refresh();
        }
    }, 500);
});

// Handle window resize for AOS
window.addEventListener('resize', function () {
    if (typeof AOS !== 'undefined') {
        AOS.refreshHard();
    }
});

// Export untuk testing (opsional)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initializeLeaderboard,
        handleFormSubmit,
        showNotification
    };
}