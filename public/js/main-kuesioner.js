// MAIN KUESIONER FUNCTIONALITY
document.addEventListener('DOMContentLoaded', function () {
    console.log('Dashboard kuesioner loaded');

    // Initialize category selection
    initializeCategorySelection();

    // Initialize questionnaire modal
    initializeQuestionnaireModal();

    // Initialize feature cards
    initializeFeatureCards();

    // Initialize progress animation
    initializeProgressAnimation();

    // Initialize toast notifications
    initializeToastNotifications();
});

function initializeCategorySelection() {
    const categoryCards = document.querySelectorAll('.category-card');
    const categoryRadios = document.querySelectorAll('.category-radio');

    if (categoryCards.length === 0) return;

    categoryCards.forEach((card, index) => {
        card.addEventListener('click', function () {
            // Remove selected class from all cards
            categoryCards.forEach(c => {
                c.style.borderColor = 'transparent';
                c.style.backgroundColor = 'white';
            });

            // Add selected style to clicked card
            this.style.borderColor = 'var(--accent-yellow)';
            this.style.backgroundColor = 'rgba(250, 179, 0, 0.1)';

            // Check the corresponding radio button
            if (categoryRadios[index]) {
                categoryRadios.forEach(radio => radio.checked = false);
                categoryRadios[index].checked = true;
            }
        });
    });
}

function initializeQuestionnaireModal() {
    const modal = document.getElementById('categoryModal');
    if (!modal) return;

    const startButtons = modal.querySelectorAll('.btn-start-kuesioner');
    startButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const questionnaireNumber = this.getAttribute('data-kuesioner');

            // Get the current category
            const categoryTitle = document.getElementById('modalCategoryTitle')?.textContent || 'Kategori';

            // Show confirmation
            if (confirm(`Mulai mengisi Kuesioner ${questionnaireNumber} untuk kategori: ${categoryTitle}?`)) {
                // Close modal
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) modalInstance.hide();

                // Simulate starting questionnaire (redirect would happen here)
                console.log(`Starting questionnaire ${questionnaireNumber} for category: ${categoryTitle}`);

                // Show loading toast
                showToast(`Memulai Kuesioner ${questionnaireNumber}...`, 'info');

                // In production, this would redirect to the questionnaire page
                // window.location.href = `/questionnaire/fill/${categorySlug}/${questionnaireSlug}`;
            }
        });
    });
}

function initializeFeatureCards() {
    const featureCards = document.querySelectorAll('.feature-card');
    featureCards.forEach(card => {
        card.addEventListener('mouseenter', function () {
            if (!this.classList.contains('feature-locked')) {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.15)';
            }
        });

        card.addEventListener('mouseleave', function () {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
}

function initializeProgressAnimation() {
    const progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
        // Animate progress bar on load
        setTimeout(() => {
            const width = progressBar.style.width;
            progressBar.style.width = '0%';
            setTimeout(() => {
                progressBar.style.transition = 'width 1.5s ease-in-out';
                progressBar.style.width = width;
            }, 100);
        }, 500);
    }
}

function initializeToastNotifications() {
    // Check for session messages
    const successMessage = document.querySelector('[data-success-message]');
    const errorMessage = document.querySelector('[data-error-message]');
    const infoMessage = document.querySelector('[data-info-message]');

    if (successMessage) {
        showToast(successMessage.getAttribute('data-success-message'), 'success');
    }

    if (errorMessage) {
        showToast(errorMessage.getAttribute('data-error-message'), 'warning');
    }

    if (infoMessage) {
        showToast(infoMessage.getAttribute('data-info-message'), 'info');
    }
}

function showToast(message, type = 'info') {
    // Create toast container if it doesn't exist
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // Create toast element
    const toastId = 'toast-' + Date.now();
    const icon = type === 'success' ? 'fa-check-circle' :
        type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
    const bgColor = type === 'success' ? 'bg-success' :
        type === 'warning' ? 'bg-warning' : 'bg-info';

    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `toast align-items-center text-white ${bgColor} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');

    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas ${icon} me-2"></i> ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;

    toastContainer.appendChild(toast);

    // Initialize and show toast
    const bsToast = new bootstrap.Toast(toast, {
        autohide: true,
        delay: 5000
    });

    bsToast.show();

    // Remove toast from DOM after it's hidden
    toast.addEventListener('hidden.bs.toast', function () {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    });

    return toastId;
}

// Export for testing (optional)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initializeCategorySelection,
        initializeQuestionnaireModal,
        initializeFeatureCards,
        initializeProgressAnimation,
        showToast
    };
}