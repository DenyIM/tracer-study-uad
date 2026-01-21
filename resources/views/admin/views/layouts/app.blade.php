<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Alumni - @yield('title', 'Admin Panel')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --sidebar-bg: #1f4871;
            --sidebar-hover: #1b3753;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            background-color: var(--sidebar-bg);
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }

        .nav-link:hover {
            color: white;
            background-color: var(--sidebar-hover);
            text-decoration: none;
        }

        .nav-link.active {
            color: white;
            background-color: var(--primary-color);
        }

        .nav-link i {
            margin-right: 10px;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 250px;
            min-height: 100vh;
        }

        .top-navbar {
            background-color: white;
            padding: 15px 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .content-wrapper {
            padding: 25px;
        }

        /* Card Styles */
        .dashboard-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            font-weight: 600;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0 !important;
        }

        /* Table Styles */
        .data-table {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table th {
            border-top: none;
            font-weight: 600;
            background-color: #f8f9fa;
            padding: 15px;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
        }

        .table tr:hover {
            background-color: #f8f9fa;
        }

        /* Badge Styles */
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-success {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
        }

        .badge-warning {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .badge-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        /* Button Styles */
        .btn-action {
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 0.85rem;
            margin: 0 3px;
        }

        .btn-view {
            background-color: rgba(13, 110, 253, 0.1);
            color: var(--primary-color);
            border: 1px solid rgba(13, 110, 253, 0.2);
        }

        .btn-view:hover {
            background-color: #0d6efd;
            color: white;
        }

        .btn-edit {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }

        .btn-edit:hover {
            background-color: #ffc107;
            color: #000;
        }

        .btn-delete {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .btn-delete:hover {
            background-color: #dc3545;
            color: white;
        }

        /* Profile Image */
        .profile-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
        }

        .profile-img:hover {
            border-color: var(--primary-color);
            transform: scale(1.1);
        }

        .profile-img-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary-color);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .photo-upload-container {
            position: relative;
            display: inline-block;
        }

        .photo-upload-container .photo-upload-overlay {
            position: absolute;
            bottom: 0;
            right: 0;
            background: var(--primary-color);
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .photo-upload-container .photo-upload-overlay:hover {
            background: #0b5ed7;
            transform: scale(1.1);
        }

        /* Filter Section */
        .filter-section {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Avatar Styles */
        .avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* Progress Bar Thin */
        .progress-thin {
            height: 4px;
        }

        /* Table Hover */
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        /* Permission Restricted Styles */
        .btn-action:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background-color: #e9ecef !important;
            border-color: #dee2e6 !important;
            color: #6c757d !important;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .alert-danger .alert-heading {
            color: #842029;
        }

        .alert-info .alert-heading {
            color: #055160;
        }

        /* Badge Styles for Job Titles */
        .badge.bg-danger {
            background-color: #dc3545 !important;
        }

        .badge.bg-primary {
            background-color: #0d6efd !important;
        }

        .badge.bg-info {
            background-color: #0dcaf0 !important;
        }

        .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #000 !important;
        }

        .badge.bg-success {
            background-color: #198754 !important;
        }

        .badge.bg-secondary {
            background-color: #6c757d !important;
        }

        /* Tooltip Styles */
        .tooltip-inner {
            max-width: 300px;
            padding: 8px 12px;
            background-color: #333;
            border-radius: 6px;
            font-size: 0.875rem;
        }

        /* Form Styles */
        .form-control:disabled,
        .form-select:disabled {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        .input-group:has(input:disabled) .btn-outline-secondary {
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* ===== CSS untuk Leaderboard ===== */

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-card .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .stats-card .stats-value {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stats-card .stats-label {
            color: #6c757d;
            font-size: 0.95rem;
        }

        /* Badge Status */
        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-pending {
            background-color: rgba(253, 126, 20, 0.15);
            color: #fd7e14;
        }

        .badge-approved {
            background-color: rgba(25, 135, 84, 0.15);
            color: #198754;
        }

        .badge-rejected {
            background-color: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }

        /* Table Container */
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        /* Leaderboard Action Buttons */
        .btn-approve {
            background-color: rgba(25, 135, 84, 0.1);
            color: #198754;
            border: 1px solid rgba(25, 135, 84, 0.2);
        }

        .btn-approve:hover {
            background-color: #198754;
            color: white;
        }

        .btn-reject {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .btn-reject:hover {
            background-color: #dc3545;
            color: white;
        }

        /* Submission Detail */
        .submission-detail-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .submission-detail-card .detail-label {
            font-weight: 600;
            color: #1f4871;
            margin-bottom: 5px;
        }

        .submission-detail-card .detail-value {
            color: #495057;
            margin-bottom: 15px;
            padding-left: 10px;
        }

        /* Search Box */
        .search-box {
            max-width: 300px;
        }

        /* Pagination */
        .pagination .page-link {
            border-radius: 6px;
            margin: 0 2px;
            border: 1px solid #dee2e6;
            color: var(--primary-color);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color), #3b82f6);
            border-color: var(--primary-color);
            color: white;
        }

        /* Timeline Styles */
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -30px;
            top: 0;
            width: 15px;
            height: 15px;
            border-radius: 50%;
        }

        .timeline-content {
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }

        .timeline-content:last-child {
            border-bottom: none;
        }

        /* Checkbox untuk bulk action */
        .submission-checkbox {
            margin: 0;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
            }

            .sidebar .nav-link span,
            .sidebar .sidebar-header .logo-text {
                display: none;
            }

            .main-content {
                margin-left: 70px;
            }

            .stats-card .stats-value {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 576px) {
            .content-wrapper {
                padding: 15px;
            }

            .top-navbar {
                padding: 10px 15px;
            }

            .action-buttons .btn {
                padding: 4px 8px;
                font-size: 0.8rem;
                margin: 1px;
            }
        }

        /* Profile Dropdown */
        .dropdown-menu {
            border-radius: 10px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .dropdown-header {
            padding: 15px;
            background-color: #f8f9fa;
        }

        .dropdown-item {
            padding: 10px 15px;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background-color: rgba(13, 110, 253, 0.1);
            color: var(--primary-color);
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
        }

        /* Profile card styles */
        .profile-card {
            border-radius: 15px;
            overflow: hidden;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        .profile-info-item:hover {
            background-color: #f8f9fa;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.views.dashboard') }}" class="logo">
                <img src="{{ asset('logo-tracer-study.png') }}" style="width: 150px; height: auto;"
                    class="img-fluid rounded">
            </a>
        </div>

        <nav class="nav flex-column mt-4">
            <!-- Dashboard Utama -->
            <a href="{{ route('admin.views.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.views.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Tracer Study Dashboard</span>
            </a>

            <!-- Questionnaire Management -->
            <div class="nav-item">
                <a href="#questionnaireSubmenu"
                    class="nav-link {{ request()->routeIs('admin.questionnaire.*') ? 'active' : '' }}"
                    data-bs-toggle="collapse">
                    <i class="bi bi-clipboard-data"></i>
                    <span>Manajemen Kuesioner</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.questionnaire.*') ? 'show' : '' }} nav-dropdown"
                    id="questionnaireSubmenu">
                    <a href="{{ route('admin.questionnaire.categories') }}"
                        class="nav-link {{ request()->routeIs('admin.questionnaire.categories') ? 'active' : '' }}">
                        <i class="bi bi-folder"></i>
                        <span>Kategori</span>
                    </a>
                    <a href="{{ route('admin.questionnaire.statistics') }}"
                        class="nav-link {{ request()->routeIs('admin.questionnaire.statistics') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart"></i>
                        <span>Statistik</span>
                    </a>
                </div>
            </div>

            <!-- Leaderboard Management -->
            <li class="nav-item">
                <a href="#leaderboardSubmenu"
                    class="nav-link {{ request()->routeIs('admin.leaderboard.*') ? 'active' : '' }}"
                    data-bs-toggle="collapse">
                    <i class="fas fa-trophy"></i>
                    <span>Sistem Poin & Leaderboard</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.leaderboard.*') ? 'show' : '' }} nav-dropdown"
                    id="leaderboardSubmenu">
                    <a href="{{ route('admin.leaderboard.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.leaderboard.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard Leaderboard</span>
                    </a>
                    <a href="{{ route('admin.leaderboard.alumni') }}"
                        class="nav-link {{ request()->routeIs('admin.leaderboard.alumni') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Ranking Alumni</span>
                    </a>
                    <a href="{{ route('admin.leaderboard.forum.submissions') }}"
                        class="nav-link {{ request()->routeIs('admin.leaderboard.forum.*') ? 'active' : '' }}">
                        <i class="fas fa-comments"></i>
                        <span>Submisi Forum</span>
                        <span class="badge bg-warning ms-2" id="pendingForumCount">0</span>
                    </a>
                    <a href="{{ route('admin.leaderboard.job.submissions') }}"
                        class="nav-link {{ request()->routeIs('admin.leaderboard.job.*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase"></i>
                        <span>Submisi Lowongan</span>
                        <span class="badge bg-warning ms-2" id="pendingJobCount">0</span>
                    </a>
                </div>
            </li>

            <!-- User Management -->
            <div class="nav-item">
                <a href="#userSubmenu" class="nav-link {{ request()->routeIs('admin.views.users.*') ? 'active' : '' }}"
                    data-bs-toggle="collapse">
                    <i class="bi bi-people-fill"></i>
                    <span>Manajemen Pengguna</span>
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.views.users.*') ? 'show' : '' }} nav-dropdown"
                    id="userSubmenu">
                    <a href="{{ route('admin.views.users.alumni.index') }}"
                        class="nav-link {{ request()->routeIs('admin.views.users.alumni.*') ? 'active' : '' }}">
                        <i class="bi bi-mortarboard"></i>
                        <span>Alumni</span>
                        <span class="badge bg-primary ms-2">{{ App\Models\Alumni::count() }}</span>
                    </a>
                    <a href="{{ route('admin.views.users.admin.index') }}"
                        class="nav-link {{ request()->routeIs('admin.views.users.admin.*') ? 'active' : '' }}">
                        <i class="bi bi-shield-check"></i>
                        <span>Administrator</span>
                        <span class="badge bg-info ms-2">{{ App\Models\Admin::count() }}</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
                    @hasSection('page-subtitle')
                        <small class="text-muted">@yield('page-subtitle')</small>
                    @endif
                </div>

                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn p-0 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-flex align-items-center">
                                <img src="{{ auth()->user()->pp_url ? asset('storage/' . auth()->user()->pp_url) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->fullname) . '&background=0d6efd&color=fff' }}"
                                    alt="Admin" class="profile-img me-2">
                                <div class="text-start me-2 d-none d-md-block">
                                    <div class="fw-bold small">{{ auth()->user()->fullname ?? 'Administrator' }}</div>
                                    {{-- <div class="text-muted x-small">{{ auth()->user()->email }}</div> --}}
                                </div>
                                <i class="bi bi-chevron-down text-muted"></i>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width: 250px;">
                            <li>
                                <div class="dropdown-header">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ auth()->user()->pp_url ? asset('storage/' . auth()->user()->pp_url) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->fullname) . '&background=0d6efd&color=fff&size=64' }}"
                                            alt="Admin" class="rounded-circle me-2" width="40"
                                            height="40">
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ auth()->user()->fullname ?? 'Administrator' }}
                                            </h6>
                                            <small class="text-muted">{{ auth()->user()->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.views.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2 text-primary"></i>
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                    <i class="bi bi-person-circle me-2 text-primary"></i>
                                    Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('admin.views.users.admin.index') }}">
                                    <i class="bi bi-people me-2 text-primary"></i>
                                    Manajemen Admin
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Session Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Main Content -->
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        // Helper function untuk get CSRF token
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.content ||
                document.querySelector('input[name="_token"]')?.value;
        }

        // Fungsi untuk mengambil pending counts
        function fetchPendingCounts() {
            fetch("{{ route('admin.leaderboard.pending.counts') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const pendingForumBadge = document.getElementById('pendingForumCount');
                        const pendingJobBadge = document.getElementById('pendingJobCount');

                        if (pendingForumBadge) pendingForumBadge.textContent = data.pending_forum;
                        if (pendingJobBadge) pendingJobBadge.textContent = data.pending_job;
                    }
                })
                .catch(error => console.error('Error fetching pending counts:', error));
        }

        /* ===== JavaScript untuk Leaderboard ===== */

        // Approve submission
        window.approveSubmission = function(type, id) {
            if (!confirm(`Apakah Anda yakin ingin menyetujui submisi ${type} ini?`)) return;

            const url = type === 'forum' ?
                `/admin/leaderboard/forum-submissions/${id}/approve` :
                `/admin/leaderboard/job-submissions/${id}/approve`;

            const csrfToken = getCsrfToken();

            if (!csrfToken) {
                alert('Error: CSRF token tidak ditemukan. Silakan refresh halaman.');
                return;
            }

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error menyetujui submisi: ' + error.message);
                });
        }

        // Reject submission
        window.rejectSubmission = function(type, id) {
            const reason = prompt('Silakan masukkan alasan penolakan:');
            if (!reason) return;

            const csrfToken = getCsrfToken();

            if (!csrfToken) {
                alert('Error: CSRF token tidak ditemukan. Silakan refresh halaman.');
                return;
            }

            fetch(`/admin/leaderboard/submissions/${type}/${id}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        admin_notes: reason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error menolak submisi: ' + error.message);
                });
        }

        // Delete submission
        window.deleteSubmission = function(type, id) {
            if (!confirm(`Apakah Anda yakin ingin menghapus submisi ${type} ini?`)) return;

            const csrfToken = getCsrfToken();
            const url = type === 'forum' ?
                `/admin/leaderboard/forum-submissions/${id}` :
                `/admin/leaderboard/job-submissions/${id}`;

            fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // Redirect kembali ke list
                        if (type === 'forum') {
                            window.location.href =
                                "{{ route('admin.leaderboard.forum.submissions') }}?status=approved";
                        } else {
                            window.location.href =
                                "{{ route('admin.leaderboard.job.submissions') }}?status=approved";
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error menghapus submisi: ' + error.message);
                });
        }

        // Bulk approve
        window.bulkApprove = function(type) {
            const selectedIds = [];
            document.querySelectorAll('.submission-checkbox:checked').forEach(checkbox => {
                selectedIds.push(checkbox.value);
            });

            if (selectedIds.length === 0) {
                alert('Silakan pilih setidaknya satu submisi');
                return;
            }

            if (!confirm(`Setujui ${selectedIds.length} submisi yang dipilih?`)) return;

            const csrfToken = getCsrfToken();

            if (!csrfToken) {
                alert('Error: CSRF token tidak ditemukan. Silakan refresh halaman.');
                return;
            }

            fetch("/admin/leaderboard/bulk-approve", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        type: type,
                        ids: selectedIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error bulk approve: ' + error.message);
                });
        }

        // Edit alumni points
        window.editAlumniPoints = function(alumniId, currentPoints) {
            const newPoints = prompt('Masukkan poin baru untuk alumni ini:', currentPoints);
            if (newPoints === null || newPoints === '') return;

            const notes = prompt('Silakan masukkan catatan untuk perubahan ini:');
            if (notes === null) return;

            const csrfToken = getCsrfToken();

            if (!csrfToken) {
                alert('Error: CSRF token tidak ditemukan. Silakan refresh halaman.');
                return;
            }

            fetch(`/admin/leaderboard/alumni/${alumniId}/edit-points`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        points: parseInt(newPoints),
                        notes: notes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error mengupdate poin: ' + error.message);
                });
        }

        // Inisialisasi saat halaman load
        document.addEventListener('DOMContentLoaded', function() {
            // Load pending counts
            fetchPendingCounts();

            // Auto refresh pending counts setiap 30 detik
            setInterval(fetchPendingCounts, 30000);

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Select all checkbox functionality
            const selectAllCheckbox = document.getElementById('selectAll');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    const checkboxes = document.querySelectorAll('.submission-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateBulkActions();
                });
            }

            // Update bulk actions based on selection
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('submission-checkbox')) {
                    updateBulkActions();
                }
            });

            function updateBulkActions() {
                const selectedCount = document.querySelectorAll('.submission-checkbox:checked').length;
                const bulkApproveBtn = document.getElementById('bulkApproveBtn');

                if (bulkApproveBtn) bulkApproveBtn.disabled = selectedCount === 0;

                const selectedCountEl = document.getElementById('selectedCount');
                if (selectedCountEl) selectedCountEl.textContent = selectedCount;
            }

            // Confirm delete action untuk form biasa
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    if (form) {
                        if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                            form.submit();
                        }
                    }
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
