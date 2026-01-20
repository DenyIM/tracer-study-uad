@php
    use App\Helpers\RankingHelper;
@endphp

<header class="sticky-top bg-white shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="{{ auth()->check() ? route('main') : url('/') }}">
                <img src="{{ asset('logo-tracer-study.png') }}" style="width: 150px; height: auto;"
                    class="img-fluid rounded">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @guest
                        <li class="nav-item" data-aos="fade-down" data-aos-delay="100">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                                href="{{ url('/') }}">Beranda</a>
                        </li>
                        <li class="nav-item" data-aos="fade-down" data-aos-delay="200">
                            <a class="nav-link" href="#tentang">Tentang</a>
                        </li>
                        <li class="nav-item" data-aos="fade-down" data-aos-delay="300">
                            <a class="nav-link" href="#faq">FAQ</a>
                        </li>
                    @else
                        @if (auth()->user()->role == 'alumni')
                            @php
                                $user = auth()->user();
                                $alumni = $user->alumni;
                                $statusQuestionnaire = $alumni ? $alumni->statuses()->first() : null;
                                $isCompleted = false;
                                $progressParts = [
                                    'part1' => false,
                                    'part2' => false,
                                    'part3' => false,
                                    'part4' => false,
                                ];

                                // Hitung ranking menggunakan helper
                                $ranking = $alumni ? RankingHelper::getAlumniRank($alumni->id) : 1;
                                $totalParticipants = RankingHelper::getTotalParticipants();
                                $points = $alumni->points ?? 0;

                                // Progress logic
                                if ($statusQuestionnaire) {
                                    $totalAnswered = $alumni
                                        ->answers()
                                        ->whereHas('question.questionnaire', function ($q) use ($statusQuestionnaire) {
                                            $q->where('category_id', $statusQuestionnaire->category_id);
                                        })
                                        ->count();

                                    $totalQuestions = \App\Models\Question::whereHas('questionnaire', function (
                                        $q,
                                    ) use ($statusQuestionnaire) {
                                        $q->where('category_id', $statusQuestionnaire->category_id);
                                    })->count();

                                    $progressPercentage =
                                        $totalQuestions > 0 ? ($totalAnswered / $totalQuestions) * 100 : 0;

                                    $progressParts['part1'] = $progressPercentage >= 25;
                                    $progressParts['part2'] = $progressPercentage >= 50;
                                    $progressParts['part3'] = $progressPercentage >= 75;
                                    $progressParts['part4'] = $progressPercentage >= 100;
                                    $isCompleted = $progressPercentage >= 100;
                                }
                            @endphp

                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('main') ? 'active' : '' }}"
                                    href="{{ route('main') }}">
                                    <i class="fas fa-clipboard-list me-1"></i> Kuesioner
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('leaderboard') ? 'active' : '' }} 
                                    {{ !$progressParts['part1'] ? 'disabled text-muted' : '' }}"
                                    href="{{ $progressParts['part1'] ? route('leaderboard') : '#' }}"
                                    @if (!$progressParts['part1']) onclick="return false;" @endif>
                                    <i class="fas fa-crown me-1"></i> Leaderboard
                                    @if (!$progressParts['part1'])
                                        <span class="badge bg-warning ms-1"><i class="fas fa-lock"></i></span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('nav-forum') ? 'active' : '' }}
                                    {{ !$progressParts['part2'] ? 'disabled text-muted' : '' }}"
                                    href="{{ $progressParts['part2'] ? route('nav-forum') : '#' }}"
                                    @if (!$progressParts['part2']) onclick="return false;" @endif>
                                    <i class="fas fa-comments me-1"></i> Forum
                                    @if (!$progressParts['part2'])
                                        <span class="badge bg-warning ms-1"><i class="fas fa-lock"></i></span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('nav-mentor') ? 'active' : '' }}
                                    {{ !$progressParts['part3'] ? 'disabled text-muted' : '' }}"
                                    href="{{ $progressParts['part3'] ? route('nav-mentor') : '#' }}"
                                    @if (!$progressParts['part3']) onclick="return false;" @endif>
                                    <i class="fas fa-chalkboard-teacher me-1"></i> Mentorship
                                    @if (!$progressParts['part3'])
                                        <span class="badge bg-warning ms-1"><i class="fas fa-lock"></i></span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('nav-lowongan') ? 'active' : '' }}
                                    {{ !$progressParts['part4'] ? 'disabled text-muted' : '' }}"
                                    href="{{ $progressParts['part4'] ? route('nav-lowongan') : '#' }}"
                                    @if (!$progressParts['part4']) onclick="return false;" @endif>
                                    <i class="fas fa-briefcase me-1"></i> Lowongan Kerja
                                    @if (!$progressParts['part4'])
                                        <span class="badge bg-warning ms-1"><i class="fas fa-lock"></i></span>
                                    @endif
                                </a>
                            </li>
                        @endif
                    @endguest
                </ul>

                <div class="d-flex align-items-center">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2" data-aos="zoom-in"
                            data-aos-delay="400">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary" data-aos="zoom-in"
                            data-aos-delay="500">Daftar</a>
                    @else
                        @if (auth()->user()->role == 'alumni')
                            <!-- Notifikasi Dropdown -->
                            <div class="dropdown me-3">
                                <button class="btn btn-outline-secondary position-relative dropdown-toggle" type="button"
                                    id="notificationDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-bell"></i>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        5
                                        <span class="visually-hidden">notifications</span>
                                    </span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end p-0" aria-labelledby="notificationDropdownBtn"
                                    style="min-width: 300px;">
                                    <div class="p-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-bold">Notifikasi</h6>
                                            <div class="d-flex align-items-center">
                                                <button class="btn btn-sm btn-outline-secondary me-2"
                                                    id="notificationRefreshBtn" title="Muat Ulang Notifikasi">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                                <span class="badge bg-primary">5</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2" id="notificationList">
                                        <div class="dropdown-item p-3">
                                            <p class="mb-0 text-muted">Tidak ada notifikasi</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @php
                                $user = auth()->user();
                                $alumni = $user->alumni;
                                $fullname = $alumni->fullname ?? 'User';
                                $initials = $fullname
                                    ? strtoupper(substr($fullname, 0, 2))
                                    : strtoupper(substr($user->email, 0, 2));
                                $study_program = $alumni->study_program ?? '';
                                $graduation_year = $alumni->graduation_date
                                    ? date('Y', strtotime($alumni->graduation_date))
                                    : '';
                                $points = $alumni->points ?? 0;

                                // Hitung ranking menggunakan helper
                                $ranking = $alumni ? RankingHelper::getAlumniRank($alumni->id) : 1;
                                $totalParticipants = RankingHelper::getTotalParticipants();

                                // Cek apakah ada foto profil
                                $hasProfilePhoto = !empty($user->pp_url);
                                $profilePhotoUrl = $hasProfilePhoto ? asset('storage/' . $user->pp_url) : '';

                                // CSS untuk avatar dengan background image
                                $avatarStyle = $hasProfilePhoto
                                    ? "background-image: url('$profilePhotoUrl'); background-size: cover; background-position: center; background-color: transparent;"
                                    : 'background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));';

                                $avatarTextStyle = $hasProfilePhoto
                                    ? 'color: transparent;' // Transparan karena ada background image
                                    : 'color: white;'; // Putih untuk gradient background
                            @endphp

                            <!-- Profil Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-outline-primary d-flex align-items-center dropdown-toggle"
                                    type="button" id="profileDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-2"
                                        style="width: 32px; height: 32px; font-size: 12px; font-weight: bold; {{ $avatarStyle }} {{ $avatarTextStyle ?? '' }}">
                                        @if (!$hasProfilePhoto)
                                            {{ $initials }}
                                        @endif
                                    </div>
                                    <span>{{ Str::limit($fullname, 15) }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-0"
                                    aria-labelledby="profileDropdownBtn" style="min-width: 250px;">
                                    <li>
                                        <div class="p-3 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-3"
                                                    style="width: 48px; height: 48px; font-size: 18px; font-weight: bold; {{ $avatarStyle }} {{ $avatarTextStyle ?? '' }}">
                                                    @if (!$hasProfilePhoto)
                                                        {{ $initials }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $fullname }}</div>
                                                    @if ($study_program && $graduation_year)
                                                        <small class="text-muted">{{ $study_program }}
                                                            {{ $graduation_year }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="p-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-coins text-warning me-2"></i>
                                                <span class="fw-bold">{{ number_format($points) }}</span> Poin
                                            </div>
                                            <span class="badge bg-secondary">Rank #{{ $ranking }}</span>
                                        </div>
                                    </li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2"
                                            href="{{ route('nav-profile') }}">
                                            <i class="fas fa-user text-primary me-2"></i>
                                            <span>Profil Saya</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2"
                                            href="{{ route('nav-bookmark') }}">
                                            <i class="fas fa-bookmark text-primary me-2"></i>
                                            <span>Bookmark</span>
                                        </a>
                                    </li>

                                    <li>
                                        <hr class="dropdown-divider m-0">
                                    </li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2 text-danger" href="#"
                                            data-bs-toggle="modal" data-bs-target="#logoutModal">
                                            <i class="fas fa-sign-out-alt me-2"></i>
                                            <span>Logout</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </nav>
</header>

@auth
    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-sign-out-alt me-2"></i> Konfirmasi Logout
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin keluar dari sistem?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-danger">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endauth

<!-- Script untuk refresh foto profil setelah upload -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi semua dropdown
        var dropdownElements = document.querySelectorAll('.dropdown-toggle');
        dropdownElements.forEach(function(dropdownElement) {
            new bootstrap.Dropdown(dropdownElement);
        });

        // Tambahkan tooltip untuk menu terkunci
        var lockedMenuItems = document.querySelectorAll('.nav-link.disabled');
        lockedMenuItems.forEach(function(item) {
            if (item.classList.contains('disabled')) {
                item.setAttribute('title',
                    'Selesaikan kuesioner bagian sebelumnya untuk membuka fitur ini');
                item.setAttribute('data-bs-toggle', 'tooltip');
                item.setAttribute('data-bs-placement', 'bottom');
            }
        });

        // Inisialisasi tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Fungsi untuk update avatar di header
        window.updateHeaderAvatar = function(photoUrl) {
            const timestamp = new Date().getTime();
            const avatarUrl = photoUrl + '?t=' + timestamp;

            // Update avatar kecil di tombol dropdown
            const smallAvatar = document.querySelector('#profileDropdownBtn .rounded-circle');
            if (smallAvatar) {
                smallAvatar.style.backgroundImage = `url(${avatarUrl})`;
                smallAvatar.style.backgroundSize = 'cover';
                smallAvatar.style.backgroundPosition = 'center';
                // Hapus teks initials jika ada
                if (smallAvatar.textContent) {
                    smallAvatar.textContent = '';
                }
            }

            // Update avatar besar di dropdown menu
            const largeAvatar = document.querySelector('.dropdown-menu .rounded-circle');
            if (largeAvatar) {
                largeAvatar.style.backgroundImage = `url(${avatarUrl})`;
                largeAvatar.style.backgroundSize = 'cover';
                largeAvatar.style.backgroundPosition = 'center';
                // Hapus teks initials jika ada
                if (largeAvatar.textContent) {
                    largeAvatar.textContent = '';
                }
            }
        };

        // Jika ada pesan dari profile page setelah upload foto
        if (sessionStorage.getItem('photoUploaded')) {
            // Tunggu sebentar untuk memastikan halaman sudah load
            setTimeout(() => {
                // Ambil URL foto dari session storage atau reload data
                const photoUrl = sessionStorage.getItem('lastPhotoUrl');
                if (photoUrl) {
                    window.updateHeaderAvatar(photoUrl);
                }

                // Hapus data dari session storage
                sessionStorage.removeItem('photoUploaded');
                sessionStorage.removeItem('lastPhotoUrl');
            }, 500);
        }
    });
</script>
