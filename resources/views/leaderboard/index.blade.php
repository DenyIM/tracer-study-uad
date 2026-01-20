<x-app-layout>
    @section('title', 'Leaderboard Alumni')

    <section class="leaderboard-header">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3" data-aos="fade-up">Leaderboard Alumni</h1>
            <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">Kompetisi sehat untuk berkontribusi bagi almamater
                dan dapatkan rewards eksklusif!</p>
            <div class="points-badge d-inline-block px-4 py-2" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-coins me-2"></i>Total Points Anda:
                <strong>{{ number_format($currentUser->points ?? 0, 0, ',', '.') }}</strong>
            </div>
        </div>
    </section>

    <div class="main-content">
        <div class="container py-5">
            <!-- Judul Section Leaderboard -->
            <div class="section-header mb-4" data-aos="fade-up">
                <h2 class="fw-bold mb-3" style="color: var(--primary-blue);">
                    <i class="fas fa-trophy me-2"></i>Peringkat Leaderboard
                </h2>
                <p class="text-muted">Lihat peringkat alumni teratas dan perjuangkan posisi terbaik Anda!</p>
            </div>

            <!-- Podium Section -->
            @include('leaderboard.partials.podium')

            <!-- Benefits Section -->
            @include('leaderboard.partials.benefits')

            <!-- Search and Filter Section -->
            @include('leaderboard.partials.search')

            <!-- Leaderboard Table -->
            @include('leaderboard.partials.table')

            <!-- Points Information Section -->
            @include('leaderboard.partials.points-info')

            <!-- Submission Forms Section -->
            @include('leaderboard.partials.forms')
        </div>
    </div>

    <!-- User Detail Modal -->
    <div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userDetailModalLabel">Detail Alumni</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="userDetailContent">
                    <!-- Content akan diisi via JavaScript -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/leaderboard.css') }}">
        <style>
            :root {
                --primary-blue: #003366;
                --secondary-blue: #3b82f6;
                --accent-yellow: #fab300;
                --light-blue: #f0f7ff;
            }

            .user-row {
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .user-row:hover {
                background-color: rgba(0, 51, 102, 0.05) !important;
            }

            .avatar-container {
                width: 50px;
                height: 50px;
                border-radius: 50%;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: center;
                background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
                color: white;
                font-weight: bold;
            }

            .avatar-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .search-box {
                max-width: 400px;
            }

            .per-page-select {
                width: auto;
            }

            .current-user-highlight {
                animation: pulse-highlight 2s infinite;
                position: relative;
            }

            @keyframes pulse-highlight {
                0% {
                    box-shadow: 0 0 0 0 rgba(250, 179, 0, 0.4);
                }

                70% {
                    box-shadow: 0 0 0 10px rgba(250, 179, 0, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(250, 179, 0, 0);
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script src="{{ asset('js/leaderboard.js') }}"></script>
        <script>
            // Debug script
            document.addEventListener('DOMContentLoaded', function() {
                console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));

                // Test data sebelum submit
                const testFormData = () => {
                    console.log('=== Testing Form Data ===');

                    // Forum form
                    const forumForm = document.getElementById('forumForm');
                    if (forumForm) {
                        console.log('Forum Form Fields:');
                        const forumData = new FormData(forumForm);
                        for (let [key, value] of forumData.entries()) {
                            console.log(`${key}: ${value}`);
                        }
                    }

                    // Job form
                    const jobForm = document.getElementById('jobForm');
                    if (jobForm) {
                        console.log('Job Form Fields:');
                        const jobData = new FormData(jobForm);
                        for (let [key, value] of jobData.entries()) {
                            console.log(`${key}: ${value}`);
                        }
                    }

                    console.log('=== End Testing ===');
                };

                // Jalankan test setelah 1 detik
                setTimeout(testFormData, 1000);

                // User detail modal
                const userRows = document.querySelectorAll('.user-row[data-user-id]');
                userRows.forEach(row => {
                    row.addEventListener('click', function() {
                        const userId = this.getAttribute('data-user-id');
                        loadUserDetail(userId);
                    });
                });
            });

            // Load user detail function
            function loadUserDetail(userId) {
                // ... kode sebelumnya ...
            }

            // Manual form test function
            window.testSubmit = function(type) {
                const form = type === 'forum' ?
                    document.getElementById('forumForm') :
                    document.getElementById('jobForm');

                if (!form) {
                    console.error('Form not found');
                    return;
                }

                // Isi data test
                if (type === 'forum') {
                    form.querySelector('[name="category"]').value = 'seminar';
                    form.querySelector('[name="title"]').value = 'Test Seminar ' + Date.now();
                    form.querySelector('[name="description"]').value = 'Test description for seminar';
                } else {
                    form.querySelector('[name="company_name"]').value = 'Test Company ' + Date.now();
                    form.querySelector('[name="position"]').value = 'Test Position';
                    form.querySelector('[name="location"]').value = 'Jakarta';
                    form.querySelector('[name="job_description"]').value = 'Test job description';
                    form.querySelector('[name="qualifications"]').value = 'Test qualifications';
                    form.querySelector('[name="field"]').value = 'it';
                    form.querySelector('[name="link"]').value = 'https://example.com';
                }

                // Submit form
                const event = new Event('submit', {
                    bubbles: true,
                    cancelable: true
                });
                form.dispatchEvent(event);
            };
        </script>
    @endpush
</x-app-layout>
