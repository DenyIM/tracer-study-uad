<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Leaderboard - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary-blue: #003366;
            --secondary-blue: #3b82f6;
            --accent-yellow: #fab300;
            --light-blue: #f0f7ff;
            --success-green: #198754;
            --warning-orange: #fd7e14;
            --danger-red: #dc3545;
        }

        .leaderboard-admin-sidebar {
            background-color: var(--primary-blue);
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
        }

        .leaderboard-admin-sidebar .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .leaderboard-admin-sidebar .sidebar-header .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .leaderboard-admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            margin: 2px 0;
        }

        .leaderboard-admin-sidebar .nav-link:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: var(--accent-yellow);
            text-decoration: none;
        }

        .leaderboard-admin-sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.15);
            border-left-color: var(--accent-yellow);
        }

        .leaderboard-admin-sidebar .nav-link i {
            margin-right: 12px;
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }

        .leaderboard-admin-main {
            margin-left: 280px;
            min-height: 100vh;
            background-color: #f5f7fb;
        }

        .leaderboard-admin-topbar {
            background-color: white;
            padding: 18px 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .leaderboard-admin-content {
            padding: 30px;
        }

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

        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            padding: 16px;
            color: var(--primary-blue);
        }

        .table td {
            padding: 16px;
            vertical-align: middle;
            border-color: #f0f0f0;
        }

        .table tr:hover {
            background-color: #f8f9fa;
        }

        .action-buttons .btn {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            margin: 0 3px;
        }

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

        .btn-view {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
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

        .search-box {
            max-width: 300px;
        }

        .pagination .page-link {
            border-radius: 6px;
            margin: 0 2px;
            border: 1px solid #dee2e6;
            color: var(--primary-blue);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-color: var(--primary-blue);
            color: white;
        }

        .submission-detail-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .submission-detail-card .detail-label {
            font-weight: 600;
            color: var(--primary-blue);
            margin-bottom: 5px;
        }

        .submission-detail-card .detail-value {
            color: #495057;
            margin-bottom: 15px;
            padding-left: 10px;
        }

        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 20px 25px;
        }

        .modal-title {
            font-weight: 600;
        }

        .btn-close-white {
            filter: brightness(0) invert(1);
        }

        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        @media (max-width: 992px) {
            .leaderboard-admin-sidebar {
                width: 250px;
            }

            .leaderboard-admin-main {
                margin-left: 250px;
            }
        }

        @media (max-width: 768px) {
            .leaderboard-admin-sidebar {
                width: 70px;
                overflow: hidden;
            }

            .leaderboard-admin-sidebar:hover {
                width: 250px;
            }

            .leaderboard-admin-sidebar .nav-link span {
                opacity: 0;
                transition: opacity 0.3s;
            }

            .leaderboard-admin-sidebar:hover .nav-link span {
                opacity: 1;
            }

            .leaderboard-admin-sidebar .sidebar-header .logo-text {
                display: none;
            }

            .leaderboard-admin-sidebar:hover .sidebar-header .logo-text {
                display: inline;
            }

            .leaderboard-admin-main {
                margin-left: 70px;
            }

            .stats-card {
                padding: 20px;
            }

            .stats-card .stats-value {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 576px) {
            .leaderboard-admin-content {
                padding: 20px 15px;
            }

            .leaderboard-admin-topbar {
                padding: 15px 20px;
            }

            .table-responsive {
                font-size: 0.9rem;
            }

            .action-buttons .btn {
                padding: 4px 8px;
                font-size: 0.8rem;
                margin: 1px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Sidebar -->
    <div class="leaderboard-admin-sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.leaderboard.dashboard') }}"
                class="logo d-flex align-items-center justify-content-center">
                <i class="fas fa-trophy me-2"></i>
                <span class="logo-text">Leaderboard Admin</span>
            </a>
        </div>

        <nav class="nav flex-column mt-4">
            <a href="{{ route('admin.leaderboard.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.leaderboard.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.leaderboard.alumni') }}"
                class="nav-link {{ request()->routeIs('admin.leaderboard.alumni') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Ranking Alumni</span>
            </a>

            <div class="nav-item">
                <a href="#forumSubmenu"
                    class="nav-link {{ request()->routeIs('admin.leaderboard.forum.*') ? 'active' : '' }}"
                    data-bs-toggle="collapse">
                    <i class="fas fa-comments"></i>
                    <span>Forum Submissions</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.leaderboard.forum.*') ? 'show' : '' }}"
                    id="forumSubmenu">
                    <a href="{{ route('admin.leaderboard.forum.submissions') }}?status=pending"
                        class="nav-link {{ request()->get('status') == 'pending' ? 'active' : '' }}">
                        <i class="fas fa-clock"></i>
                        <span>Pending</span>
                        <span class="badge bg-warning ms-2" id="pendingForumBadge">0</span>
                    </a>
                    <a href="{{ route('admin.leaderboard.forum.submissions') }}?status=approved"
                        class="nav-link {{ request()->get('status') == 'approved' ? 'active' : '' }}">
                        <i class="fas fa-check-circle"></i>
                        <span>Approved</span>
                    </a>
                    <a href="{{ route('admin.leaderboard.forum.submissions') }}?status=rejected"
                        class="nav-link {{ request()->get('status') == 'rejected' ? 'active' : '' }}">
                        <i class="fas fa-times-circle"></i>
                        <span>Rejected</span>
                    </a>
                </div>
            </div>

            <div class="nav-item">
                <a href="#jobSubmenu"
                    class="nav-link {{ request()->routeIs('admin.leaderboard.job.*') ? 'active' : '' }}"
                    data-bs-toggle="collapse">
                    <i class="fas fa-briefcase"></i>
                    <span>Job Submissions</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse {{ request()->routeIs('admin.leaderboard.job.*') ? 'show' : '' }}"
                    id="jobSubmenu">
                    <a href="{{ route('admin.leaderboard.job.submissions') }}?status=pending"
                        class="nav-link {{ request()->get('status') == 'pending' ? 'active' : '' }}">
                        <i class="fas fa-clock"></i>
                        <span>Pending</span>
                        <span class="badge bg-warning ms-2" id="pendingJobBadge">0</span>
                    </a>
                    <a href="{{ route('admin.leaderboard.job.submissions') }}?status=approved"
                        class="nav-link {{ request()->get('status') == 'approved' ? 'active' : '' }}">
                        <i class="fas fa-check-circle"></i>
                        <span>Approved</span>
                    </a>
                    <a href="{{ route('admin.leaderboard.job.submissions') }}?status=rejected"
                        class="nav-link {{ request()->get('status') == 'rejected' ? 'active' : '' }}">
                        <i class="fas fa-times-circle"></i>
                        <span>Rejected</span>
                    </a>
                </div>
            </div>

            <div class="mt-auto p-3">
                <div class="d-flex align-items-center">
                    <div
                        class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                        <i class="fas fa-user text-primary"></i>
                    </div>
                    <div>
                        {{-- <div class="fw-bold">{{ Auth::user()->email }}</div> --}}
                        <small class="text-light">Administrator</small>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="leaderboard-admin-main">
        <!-- Topbar -->
        <nav class="leaderboard-admin-topbar">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
                    <small class="text-muted">@yield('page-subtitle', 'Leaderboard Management System')</small>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-1"></i> Settings
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user-cog me-2"></i> Profile
                            </a>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-bell me-2"></i> Notifications
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="leaderboard-admin-content">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load pending counts
            fetchPendingCounts();

            // Auto refresh pending counts every 30 seconds
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
                const bulkRejectBtn = document.getElementById('bulkRejectBtn');

                if (bulkApproveBtn) bulkApproveBtn.disabled = selectedCount === 0;
                if (bulkRejectBtn) bulkRejectBtn.disabled = selectedCount === 0;

                const selectedCountEl = document.getElementById('selectedCount');
                if (selectedCountEl) selectedCountEl.textContent = selectedCount;
            }
        });

        function fetchPendingCounts() {
            fetch("{{ route('admin.leaderboard.statistics') }}")
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const pendingForumBadge = document.getElementById('pendingForumBadge');
                        const pendingJobBadge = document.getElementById('pendingJobBadge');

                        // You can update these based on your actual data structure
                        if (pendingForumBadge) pendingForumBadge.textContent = '0';
                        if (pendingJobBadge) pendingJobBadge.textContent = '0';
                    }
                })
                .catch(error => console.error('Error fetching pending counts:', error));
        }

        // Approve submission
        window.approveSubmission = function(type, id) {
            if (!confirm(`Are you sure you want to approve this ${type} submission?`)) return;

            const url = type === 'forum' ?
                `/admin/leaderboard/forum-submissions/${id}/approve` :
                `/admin/leaderboard/job-submissions/${id}/approve`;

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
                    alert('Error approving submission: ' + error.message);
                });
        }

        // Reject submission
        window.rejectSubmission = function(type, id) {
            const reason = prompt('Please enter rejection reason:');
            if (!reason) return;

            fetch(`/admin/leaderboard/submissions/${type}/${id}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
                    alert('Error rejecting submission: ' + error.message);
                });
        }

        // Bulk approve
        window.bulkApprove = function(type) {
            const selectedIds = [];
            document.querySelectorAll('.submission-checkbox:checked').forEach(checkbox => {
                selectedIds.push(checkbox.value);
            });

            if (selectedIds.length === 0) {
                alert('Please select at least one submission');
                return;
            }

            if (!confirm(`Approve ${selectedIds.length} selected submissions?`)) return;

            fetch("{{ route('admin.leaderboard.bulk.approve') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
                    alert('Error bulk approving: ' + error.message);
                });
        }

        // Edit alumni points
        window.editAlumniPoints = function(alumniId, currentPoints) {
            const newPoints = prompt('Enter new points for this alumni:', currentPoints);
            if (newPoints === null || newPoints === '') return;

            const notes = prompt('Please enter notes for this adjustment:');
            if (notes === null) return;

            fetch(`/admin/leaderboard/alumni/${alumniId}/edit-points`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
                    alert('Error updating points: ' + error.message);
                });
        }
    </script>

    @stack('scripts')
</body>

</html>
