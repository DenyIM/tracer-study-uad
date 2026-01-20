// MENU SELECTION FUNCTIONALITY
function initMenuSelection() {
    const careerMenu = document.getElementById('careerMenu');
    const businessMenu = document.getElementById('businessMenu');
    const careerForm = document.getElementById('careerForm');
    const businessForm = document.getElementById('businessForm');

    if (!careerMenu || !businessMenu) return;

    // Career menu click
    careerMenu.addEventListener('click', function () {
        careerMenu.classList.add('active');
        businessMenu.classList.remove('active');

        if (careerForm) careerForm.classList.remove('hidden');
        if (businessForm) businessForm.classList.add('hidden');

        // Scroll to form
        window.scrollTo({
            top: careerForm.offsetTop - 100,
            behavior: 'smooth'
        });
    });

    // Business menu click
    businessMenu.addEventListener('click', function () {
        businessMenu.classList.add('active');
        careerMenu.classList.remove('active');

        if (businessForm) businessForm.classList.remove('hidden');
        if (careerForm) careerForm.classList.add('hidden');

        // Scroll to form
        window.scrollTo({
            top: businessForm.offsetTop - 100,
            behavior: 'smooth'
        });
    });
}

// RADIO BUTTON HANDLING
function setupRadioButtons() {
    // Handle career form radio buttons
    const careerRadios = document.querySelectorAll('#careerMentorshipForm input[name="career_field"]');
    const otherCareerField = document.getElementById('other_career_field');

    careerRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            // Handle "other" field
            if (this.value === 'other_career' && otherCareerField) {
                otherCareerField.disabled = false;
                otherCareerField.required = true;
                setTimeout(() => otherCareerField.focus(), 100);
            } else if (otherCareerField) {
                otherCareerField.disabled = true;
                otherCareerField.required = false;
                otherCareerField.value = '';
            }

            // Update visual selection
            const options = document.querySelectorAll('#careerMentorshipForm .radio-option');
            options.forEach(option => option.classList.remove('selected'));
            if (this.checked) {
                this.parentElement.classList.add('selected');
            }
        });
    });

    // Handle business form radio buttons
    const businessRadios = document.querySelectorAll('#businessMentorshipForm input[name="business_type"]');
    const otherBusinessField = document.getElementById('other_business_type');

    businessRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            // Handle "other" field
            if (this.value === 'other_business' && otherBusinessField) {
                otherBusinessField.disabled = false;
                otherBusinessField.required = true;
                setTimeout(() => otherBusinessField.focus(), 100);
            } else if (otherBusinessField) {
                otherBusinessField.disabled = true;
                otherBusinessField.required = false;
                otherBusinessField.value = '';
            }

            // Update visual selection
            const options = document.querySelectorAll('#businessMentorshipForm .radio-option');
            options.forEach(option => option.classList.remove('selected'));
            if (this.checked) {
                this.parentElement.classList.add('selected');
            }
        });
    });
}

// WHATSAPP NUMBER FORMATTING
function setupWhatsAppFormatting() {
    const whatsappInputs = document.querySelectorAll('input[name="whatsapp"]');

    whatsappInputs.forEach(input => {
        input.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');

            // Format: 62XXXXXXXXXX
            if (value.startsWith('0')) {
                value = '62' + value.substring(1);
            }

            e.target.value = value;
        });
    });
}

// FORM SUBMISSION HANDLING
function setupFormSubmissions() {
    // Career form submission
    const careerForm = document.getElementById('careerMentorshipForm');
    if (careerForm) {
        careerForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            const successMessage = document.getElementById('careerSuccessMessage');

            // Validate other career field
            const otherCareerRadio = document.querySelector('#careerMentorshipForm input[value="other_career"]:checked');
            const otherCareerField = document.getElementById('other_career_field');

            if (otherCareerRadio && otherCareerField && (!otherCareerField.value || otherCareerField.value.trim() === '')) {
                alert('Silakan isi bidang karier lain yang Anda maksud.');
                if (otherCareerField) otherCareerField.focus();
                return;
            }

            // Show loading state
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengirim...';
            submitButton.disabled = true;

            // Simulate API call
            setTimeout(() => {
                // Show success message
                if (successMessage) {
                    successMessage.style.display = 'flex';
                }

                // Reset form
                this.reset();

                // Reset radio selections
                const careerOptions = document.querySelectorAll('#careerMentorshipForm .radio-option');
                careerOptions.forEach(option => option.classList.remove('selected'));

                // Disable other fields
                const otherCareerField = document.getElementById('other_career_field');
                if (otherCareerField) {
                    otherCareerField.disabled = true;
                    otherCareerField.value = '';
                }

                // Reset button
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;

                // Scroll to success message
                if (successMessage) {
                    successMessage.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }

                // Show success toast
                showSuccessToast('Permintaan mentorship karir berhasil dikirim!');
            }, 2000);
        });
    }

    // Business form submission
    const businessForm = document.getElementById('businessMentorshipForm');
    if (businessForm) {
        businessForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            const successMessage = document.getElementById('businessSuccessMessage');

            // Validate other business field
            const otherBusinessRadio = document.querySelector('#businessMentorshipForm input[value="other_business"]:checked');
            const otherBusinessField = document.getElementById('other_business_type');

            if (otherBusinessRadio && otherBusinessField && (!otherBusinessField.value || otherBusinessField.value.trim() === '')) {
                alert('Silakan isi jenis usaha lain yang Anda maksud.');
                if (otherBusinessField) otherBusinessField.focus();
                return;
            }

            // Show loading state
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Mengirim...';
            submitButton.disabled = true;

            // Simulate API call
            setTimeout(() => {
                // Show success message
                if (successMessage) {
                    successMessage.style.display = 'flex';
                }

                // Reset form
                this.reset();

                // Reset radio selections
                const businessOptions = document.querySelectorAll('#businessMentorshipForm .radio-option');
                businessOptions.forEach(option => option.classList.remove('selected'));

                // Disable other fields
                const otherBusinessField = document.getElementById('other_business_type');
                if (otherBusinessField) {
                    otherBusinessField.disabled = true;
                    otherBusinessField.value = '';
                }

                // Reset button
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;

                // Scroll to success message
                if (successMessage) {
                    successMessage.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }

                // Show success toast
                showSuccessToast('Permintaan mentorship bisnis berhasil dikirim!');
            }, 2000);
        });
    }
}

// SUCCESS TOAST FUNCTION
function showSuccessToast(message) {
    // Check if Bootstrap Toast is available
    if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
        // Create toast element
        const toastEl = document.createElement('div');
        toastEl.className = 'toast align-items-center text-white bg-success border-0';
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');

        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-check-circle me-2"></i> ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        // Add to container or create one
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }

        toastContainer.appendChild(toastEl);

        // Initialize and show toast
        const toast = new bootstrap.Toast(toastEl);
        toast.show();

        // Remove toast after it's hidden
        toastEl.addEventListener('hidden.bs.toast', function () {
            toastEl.remove();
        });
    } else {
        // Fallback to alert if Bootstrap not available
        alert(message);
    }
}

// AUTO-FILL USER DATA (Simulasi)
function autoFillUserData() {
    // In a real application, this would come from the server
    const userData = {
        name: "Deny Iqbal",
        nim: "20200811000",
        program_studi: "Informatika",
        tahun_lulus: "2022",
        email: "deny.iqbal@example.com"
    };

    // You can display this information if needed
    console.log('User data loaded:', userData);
}

// INITIALIZE EVERYTHING
document.addEventListener('DOMContentLoaded', function () {
    console.log('Mentor JS Loaded');

    // Initialize all features
    initMenuSelection();
    setupRadioButtons();
    setupWhatsAppFormatting();
    setupFormSubmissions();
    autoFillUserData();

    // Hide success messages initially
    const careerSuccess = document.getElementById('careerSuccessMessage');
    const businessSuccess = document.getElementById('businessSuccessMessage');

    if (careerSuccess) careerSuccess.style.display = 'none';
    if (businessSuccess) businessSuccess.style.display = 'none';

    // Ensure hidden forms are hidden
    const businessForm = document.getElementById('businessForm');
    if (businessForm) businessForm.classList.add('hidden');
});

// RESET FORM FUNCTION (if needed externally)
function resetForm(formType) {
    if (formType === 'career') {
        const form = document.getElementById('careerMentorshipForm');
        if (form) form.reset();

        const otherField = document.getElementById('other_career_field');
        if (otherField) {
            otherField.disabled = true;
            otherField.value = '';
        }

        const options = document.querySelectorAll('#careerMentorshipForm .radio-option');
        options.forEach(option => option.classList.remove('selected'));

        const successMsg = document.getElementById('careerSuccessMessage');
        if (successMsg) successMsg.style.display = 'none';
    } else if (formType === 'business') {
        const form = document.getElementById('businessMentorshipForm');
        if (form) form.reset();

        const otherField = document.getElementById('other_business_type');
        if (otherField) {
            otherField.disabled = true;
            otherField.value = '';
        }

        const options = document.querySelectorAll('#businessMentorshipForm .radio-option');
        options.forEach(option => option.classList.remove('selected'));

        const successMsg = document.getElementById('businessSuccessMessage');
        if (successMsg) successMsg.style.display = 'none';
    }
}