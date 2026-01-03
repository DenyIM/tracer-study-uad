// MAIN KUESIONER FUNCTIONALITY - COMPACT VERSION

// Status data
let currentStatus = 'belum'; // 'belum', 'sedang', 'selesai'
const questionnaireData = {
    kategori: 'Bekerja di Perusahaan/Instansi',
    progress: 60,
    kuesionerAktif: 'Kuesioner 2 dari 4',
    kuesionerTitle: 'Pengalaman Kerja & Karir',
    pertanyaanSelesai: '8 dari 15 pertanyaan'
};

// Initialize questionnaire
function initializeQuestionnaire() {
    console.log('Initializing compact questionnaire...');

    // Setup status navigation
    setupStatusNavigation();

    // Setup category cards
    setupCategoryCards();

    // Setup feature exploration
    setupFeatureExploration();

    // Load current status (simulate API call)
    setTimeout(() => {
        loadCurrentStatus();
    }, 800);

    console.log('Questionnaire initialized successfully');
}

// Setup status navigation
function setupStatusNavigation() {
    const statusButtons = document.querySelectorAll('.btn-status');

    statusButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const status = this.getAttribute('data-status');

            // Update active button
            statusButtons.forEach(btn => {
                btn.classList.remove('active');
                btn.style.transform = 'scale(1)';
            });

            this.classList.add('active');
            this.style.transform = 'scale(1.03)';

            // Show selected status
            showStatusView(status);

            // Update current status
            currentStatus = status;
        });
    });
}

// Show specific status view
function showStatusView(status) {
    // Hide all status sections with animation
    const statusSections = document.querySelectorAll('.status-section');
    statusSections.forEach(section => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(15px)';
        setTimeout(() => {
            section.classList.add('d-none');
        }, 250);
    });

    // Show selected section with animation
    setTimeout(() => {
        const selectedSection = document.getElementById(`status-${status}-mengerjakan`);
        if (selectedSection) {
            selectedSection.classList.remove('d-none');

            // Animate in
            setTimeout(() => {
                selectedSection.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                selectedSection.style.opacity = '1';
                selectedSection.style.transform = 'translateY(0)';

                // Update progress bar if needed
                if (status === 'sedang') {
                    animateProgressBar();
                }
            }, 10);
        }

        // Update data for status
        updateStatusData(status);
    }, 250);
}

// Animate progress bar
function animateProgressBar() {
    const progressBar = document.querySelector('.progress-bar');
    const progressText = document.getElementById('progress-percentage');

    if (progressBar && progressText) {
        // Reset width
        progressBar.style.width = '0%';

        // Animate to actual progress
        setTimeout(() => {
            progressBar.style.transition = 'width 1s cubic-bezier(0.4, 0, 0.2, 1)';
            progressBar.style.width = `${questionnaireData.progress}%`;

            // Animate percentage text
            let currentPercent = 0;
            const targetPercent = questionnaireData.progress;
            const increment = targetPercent / 40; // 40 steps

            const interval = setInterval(() => {
                currentPercent += increment;
                if (currentPercent >= targetPercent) {
                    currentPercent = targetPercent;
                    clearInterval(interval);
                }
                progressText.textContent = `${Math.round(currentPercent)}%`;
            }, 25);
        }, 400);
    }
}

// Update status data
function updateStatusData(status) {
    if (status === 'sedang') {
        // Update sedang mengerjakan data
        document.getElementById('kategori-dikerjakan').innerHTML =
            `Kategori: <strong>${questionnaireData.kategori}</strong>`;

        document.getElementById('kuesioner-aktif').textContent = questionnaireData.kuesionerAktif;
        document.getElementById('pertanyaan-selesai').textContent = questionnaireData.pertanyaanSelesai;
    }
}

// Setup category cards interactions
function setupCategoryCards() {
    const categoryCards = document.querySelectorAll('.category-card');

    categoryCards.forEach(card => {
        // Click effect
        card.addEventListener('click', function (e) {
            // Only animate if not already navigating
            if (!this.classList.contains('clicked')) {
                this.classList.add('clicked');

                // Animate badge
                const badge = this.querySelector('.badge');
                if (badge) {
                    badge.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Membuka...';
                }

                // Show loading state
                this.style.transform = 'translateY(-4px) scale(0.98)';

                // Reset after animation
                setTimeout(() => {
                    this.style.transform = 'translateY(-8px)';
                    if (badge) {
                        badge.innerHTML = '<i class="fas fa-arrow-right me-1"></i> Mulai';
                    }
                    this.classList.remove('clicked');
                }, 800);
            }
        });

        // Enhanced hover effects
        card.addEventListener('mouseenter', function () {
            if (!this.classList.contains('clicked')) {
                this.style.transform = 'translateY(-8px)';
                this.style.boxShadow = '0 12px 25px rgba(0, 0, 0, 0.12)';

                // Animate icon
                const icon = this.querySelector('.category-icon');
                if (icon) {
                    icon.style.transform = 'scale(1.08) rotate(8deg)';
                }
            }
        });

        card.addEventListener('mouseleave', function () {
            if (!this.classList.contains('clicked')) {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.06)';

                // Reset icon
                const icon = this.querySelector('.category-icon');
                if (icon) {
                    icon.style.transform = 'scale(1) rotate(0deg)';
                }
            }
        });
    });
}

// Setup feature exploration buttons
function setupFeatureExploration() {
    const exploreButtons = document.querySelectorAll('.btn-jelajahi-fitur');

    exploreButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            // Scroll to feature section
            const featureSection = document.getElementById('fitur-eksklusif');
            if (featureSection) {
                // Add highlight animation
                featureSection.classList.add('section-highlight');

                // Scroll to section
                featureSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                // Remove highlight after animation
                setTimeout(() => {
                    featureSection.classList.remove('section-highlight');
                }, 1500);
            }
        });
    });
}

// Load current status (simulated API call)
function loadCurrentStatus() {
    // In a real app, this would be an API call
    // For demo, we'll simulate loading and set to 'sedang'
    setTimeout(() => {
        const defaultStatus = 'sedang';
        const activeButton = document.querySelector(`.btn-status[data-status="${defaultStatus}"]`);

        if (activeButton) {
            // Update UI
            document.querySelectorAll('.btn-status').forEach(btn => {
                btn.classList.remove('active');
                btn.style.transform = 'scale(1)';
            });

            activeButton.classList.add('active');
            activeButton.style.transform = 'scale(1.03)';

            // Show status view
            showStatusView(defaultStatus);
            currentStatus = defaultStatus;
        }
    }, 800);
}

// Toast notification function (simple version)
function showToast(message, type = 'info') {
    // Create simple toast
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.textContent = message;

    // Style the toast
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 8px;
        color: white;
        font-size: 0.9rem;
        z-index: 1000;
        opacity: 0;
        transform: translateY(-20px);
        transition: all 0.3s ease;
    `;

    // Set background color based on type
    if (type === 'success') {
        toast.style.backgroundColor = '#28a745';
    } else if (type === 'error') {
        toast.style.backgroundColor = '#dc3545';
    } else {
        toast.style.backgroundColor = 'var(--primary-blue)';
    }

    // Add to body
    document.body.appendChild(toast);

    // Animate in
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateY(0)';
    }, 10);

    // Remove after delay
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';

        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    initializeQuestionnaire();

    // Add CSS for section highlight
    const style = document.createElement('style');
    style.textContent = `
        .section-highlight {
            animation: highlight-pulse 1.5s ease;
        }
        
        @keyframes highlight-pulse {
            0%, 100% { 
                box-shadow: 0 0 0 0 rgba(0, 51, 102, 0); 
            }
            50% { 
                box-shadow: 0 0 0 15px rgba(0, 51, 102, 0.08); 
            }
        }
        
        .toast-notification {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        }
    `;
    document.head.appendChild(style);
});

// Export for testing
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initializeQuestionnaire,
        showStatusView,
        animateProgressBar,
        showToast
    };
}