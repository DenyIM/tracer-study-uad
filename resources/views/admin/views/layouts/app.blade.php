<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Alumni - @yield('title', 'Admin Panel')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --sidebar-bg: #2c3e50;
            --sidebar-hover: #34495e;
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

        .btn-edit {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.2);
        }

        .btn-delete {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        /* Profile Image */
        .profile-img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 0.7rem;
            padding: 2px 6px;
            border-radius: 10px;
        }

        /* Filter Section */
        .filter-section {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.views.dashboard') }}" class="logo">
                <i class="bi bi-mortarboard-fill"></i>
                <span class="ms-2">AlumniSys</span>
            </a>
            <small class="text-muted d-block mt-2">Admin Panel</small>
        </div>

        <nav class="nav flex-column mt-4">
            <a href="{{ route('admin.views.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.views.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>

            <!-- User Management Section -->
            <div class="nav-item">
                <a href="#userSubmenu" class="nav-link {{ request()->routeIs('admin.views.users.*') ? 'active' : '' }}"
                    data-bs-toggle="collapse">
                    <i class="bi bi-people-fill"></i>
                    <span>Manajemen User</span>
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

            <div class="mt-auto p-3">
                <div class="d-flex align-items-center">
                    <img src="https://ui-avatars.com/api/?name=Admin+User&background=0d6efd&color=fff" alt="Admin"
                        class="profile-img">
                    <div class="ms-2">
                        <div class="fw-bold">Admin User</div>
                        <small class="text-muted">Administrator</small>
                    </div>
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
                </div>

                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <a href="#" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name=Admin+User&background=0d6efd&color=fff"
                                alt="Admin" class="profile-img">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">Halo, Admin</h6>
                            <a class="dropdown-item text-danger" href="#">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </a>
                        </div>
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
        // Confirm delete action
        document.addEventListener('DOMContentLoaded', function() {
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
