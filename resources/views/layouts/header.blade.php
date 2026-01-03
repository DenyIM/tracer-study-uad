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
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('main') ? 'active' : '' }}"
                                    href="{{ route('main') }}">
                                    <i class="fas fa-clipboard-list me-1"></i> Kuesioner
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('nav-leaderboard') ? 'active' : '' }}"
                                    href="{{ route('nav-leaderboard') }}">
                                    <i class="fas fa-crown me-1"></i> Leaderboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('nav-forum') ? 'active' : '' }}"
                                    href="{{ route('nav-forum') }}">
                                    <i class="fas fa-comments me-1"></i> Forum
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('nav-mentor') ? 'active' : '' }}"
                                    href="{{ route('nav-mentor') }}">
                                    <i class="fas fa-chalkboard-teacher me-1"></i> Mentorship
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('nav-lowongan') ? 'active' : '' }}"
                                    href="{{ route('nav-lowongan') }}">
                                    <i class="fas fa-briefcase me-1"></i> Lowongan Kerja
                                </a>
                            </li>
                        @endif
                    @endguest
                </ul>

                <div class="d-flex align-items-center">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-primary-custom me-2" data-aos="zoom-in"
                            data-aos-delay="400">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary-custom" data-aos="zoom-in"
                            data-aos-delay="500">Daftar</a>
                    @else
                        @if (auth()->user()->role == 'alumni')
                            <div class="notification-header">
                                <span>Notifikasi</span>
                                <div class="d-flex align-items-center">
                                    <button class="notification-refresh-btn me-2" id="notificationRefreshBtn"
                                        title="Muat Ulang Notifikasi">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <div class="notification-count" id="notificationCount">5</div>
                                </div>
                            </div>

                            <div class="notification-list" id="notificationList">
                                <!-- Notifikasi akan dimuat di sini -->
                            </div>

                            @php
                                $user = auth()->user();
                                $fullname = optional($user->alumni)->fullname ?? 'User';
                                $initials = $fullname
                                    ? strtoupper(substr($fullname, 0, 2))
                                    : strtoupper(substr($user->email, 0, 2));

                                // Data tambahan untuk profil alumni
                                $study_program = optional($user->alumni)->study_program ?? '';
                                $graduation_year = optional($user->alumni)->graduation_date
                                    ? date('Y', strtotime($user->alumni->graduation_date))
                                    : '';
                                $points = optional($user->alumni)->points ?? 0;
                            @endphp

                            <div class="dropdown">
                                <button class="btn btn-outline-primary d-flex align-items-center" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-avatar me-2">{{ $initials }}</div>
                                    <span>{{ $fullname }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end profile-dropdown">
                                    <li>
                                        <div class="profile-header px-3 py-2">
                                            <div class="user-avatar-large mb-2">{{ $initials }}</div>
                                            <div class="profile-info">
                                                <div class="profile-name">{{ $fullname }}</div>
                                                @if ($study_program && $graduation_year)
                                                    <div class="profile-role text-muted">
                                                        {{ $study_program }} {{ $graduation_year }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <hr class="dropdown-divider mx-3">
                                    </li>

                                    <li class="px-3 py-2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <i class="fas fa-coins text-warning me-2"></i>
                                                <span class="fw-semibold">{{ number_format($points) }}</span> Points
                                            </div>
                                            <small class="text-muted">Ranking:
                                                #{{ optional($user->alumni)->ranking ?? '-' }}</small>
                                        </div>
                                    </li>

                                    <li>
                                        <hr class="dropdown-divider mx-3">
                                    </li>

                                    {{-- MENU ITEMS --}}
                                    <li>
                                        <a class="dropdown-item" href="{{ route('nav-profil') }}">
                                            <i class="fas fa-user me-2"></i> Profil Saya
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('nav-bookmark') }}">
                                            <i class="fas fa-bookmark me-2"></i> Bookmark
                                        </a>
                                    </li>

                                    <li>
                                        <hr class="dropdown-divider mx-3">
                                    </li>

                                    <li>
                                        <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                            data-bs-target="#logoutModal">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
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
