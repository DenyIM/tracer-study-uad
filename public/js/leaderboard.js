// LEADERBOARD FUNCTIONALITY - SIMPLE WORKING VERSION

let isSubmitting = false;

// Fungsi untuk menginisialisasi leaderboard
function initializeLeaderboard() {
    console.log('Initializing leaderboard functionality...');

    // 1. Initialize form submissions
    initializeFormSubmissions();

    // 2. Initialize pagination
    initializePagination();

    // 3. Initialize podium animations
    initializePodiumAnimations();

    // 4. Initialize button effects
    initializeButtonEffects();

    // 5. Initialize AOS animations if available
    initializeAOS();

    // 6. Initialize search functionality
    initializeSearch();

    console.log('Leaderboard functionality initialized successfully');
}

// 1. Initialize form submissions
function initializeFormSubmissions() {
    // Forum form
    const forumForm = document.getElementById('forumForm');
    if (forumForm) {
        forumForm.addEventListener('submit', function (e) {
            e.preventDefault();
            handleFormSubmit(this, 'forumSuccessMessage', 'forum');
        });
    }

    // Job form
    const jobForm = document.getElementById('jobForm');
    if (jobForm) {
        jobForm.addEventListener('submit', function (e) {
            e.preventDefault();
            handleFormSubmit(this, 'jobSuccessMessage', 'job');
        });
    }
}

// 2. Initialize pagination
function initializePagination() {
    const pageLinks = document.querySelectorAll('.pagination .page-link:not([href="#"])');
    if (pageLinks.length > 0) {
        pageLinks.forEach(link => {
            link.addEventListener('click', function () {
                setTimeout(() => {
                    scrollToTable();
                }, 100);
            });
        });
    }
}

// 3. Initialize podium animations
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
    });
}

// 4. Initialize button effects
function initializeButtonEffects() {
    // Ripple effect
    const submitButtons = document.querySelectorAll('.submit-btn');
    submitButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            if (this.disabled) return;

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

            setTimeout(() => {
                if (ripple.parentNode === this) {
                    this.removeChild(ripple);
                }
            }, 600);
        });
    });

    // Add ripple animation style
    if (!document.querySelector('#ripple-animation-style')) {
        const style = document.createElement('style');
        style.id = 'ripple-animation-style';
        style.textContent = `
            @keyframes ripple-animation {
                to { transform: scale(4); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }
}

// 5. Initialize AOS animations
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

// 6. Initialize search functionality
function initializeSearch() {
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function () {
            setTimeout(() => {
                scrollToTable();
            }, 100);
        });
    }
}

// Fungsi utama untuk handle form submit
function handleFormSubmit(form, successMessageId, type) {
    console.log('Submitting', type, 'form...');

    if (isSubmitting) {
        showNotification('Sedang mengirim data, harap tunggu...', 'warning');
        return;
    }

    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    const successMessage = document.getElementById(successMessageId);

    // Validasi form sederhana
    if (!validateFormSimple(form)) {
        showNotification('Harap isi semua field yang wajib diisi!', 'error');
        return;
    }

    // Set loading state
    isSubmitting = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengirim...';
    submitButton.disabled = true;

    // Collect form data
    const formData = new FormData(form);
    const data = {};

    // Convert FormData to plain object
    for (let [key, value] of formData.entries()) {
        // Skip CSRF token dan method
        if (key === '_token' || key === '_method') continue;
        data[key] = value;
    }

    console.log('Data to send:', data);

    // Determine endpoint
    const endpoint = type === 'forum'
        ? '/leaderboard/submit-forum'
        : '/leaderboard/submit-job';

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
        form.querySelector('input[name="_token"]')?.value;

    // Send request menggunakan Fetch API
    fetch(endpoint, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken || '',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json().then(data => ({
                status: response.status,
                ok: response.ok,
                data: data
            }));
        })
        .then(result => {
            console.log('Response:', result);

            if (result.ok) {
                // Success
                showSuccessMessage(successMessage, form, submitButton, originalText, result.data.message);
                showNotification(result.data.message, 'success');
            } else {
                // Error handling
                if (result.status === 422 && result.data.errors) {
                    // Show validation errors
                    let errorMessages = [];
                    Object.values(result.data.errors).forEach(errors => {
                        errorMessages = errorMessages.concat(errors);
                    });
                    showNotification(errorMessages.join('<br>'), 'error');
                } else {
                    showNotification(result.data.message || 'Gagal mengirim data!', 'error');
                }
            }

            // Reset button state
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
            isSubmitting = false;
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat mengirim data!', 'error');

            // Reset button state
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
            isSubmitting = false;
        });
}

// Simple form validation
function validateFormSimple(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    return isValid;
}

// Show success message
function showSuccessMessage(successMessage, form, submitButton, originalText, message) {
    if (successMessage) {
        successMessage.style.display = 'flex';
        if (successMessage.querySelector('p')) {
            successMessage.querySelector('p').textContent = message;
        }

        // Scroll to success message
        successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });

        // Reset form
        setTimeout(() => {
            form.reset();
            const invalidFields = form.querySelectorAll('.is-invalid');
            invalidFields.forEach(field => field.classList.remove('is-invalid'));
        }, 100);

        // Hide success message after 5 seconds
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 5000);
    }
}

// Show notification
function showNotification(message, type = 'info') {
    // Remove existing notifications
    document.querySelectorAll('.custom-notification').forEach(notification => {
        notification.remove();
    });

    // Create notification
    const notification = document.createElement('div');
    notification.className = `custom-notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;

    // Add styles if needed
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

    // Remove after delay
    setTimeout(() => {
        if (notification.parentNode) {
            notification.parentNode.removeChild(notification);
        }
    }, 5000);
}

// Scroll to table
function scrollToTable() {
    const tableElement = document.getElementById('leaderboardTable');
    if (tableElement) {
        setTimeout(() => {
            tableElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }, 100);
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function () {
    initializeLeaderboard();

    // Auto scroll to table if URL has parameters
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search') || urlParams.has('per_page') || urlParams.has('page')) {
        setTimeout(() => {
            scrollToTable();
        }, 300);
    }
});

// Make functions globally available
window.scrollToTable = scrollToTable;
window.showNotification = showNotification;