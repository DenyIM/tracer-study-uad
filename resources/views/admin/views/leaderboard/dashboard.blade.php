@extends('admin.views.layouts.app')

@section('title', 'Leaderboard Dashboard')
@section('page-title', 'Dashboard Leaderboard')
@section('page-subtitle', 'Sistem Poin dan Ranking Alumni')

@section('content')
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-value">{{ number_format($totalAlumni) }}</div>
                <div class="stats-label">Total Alumni</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="stats-value">{{ number_format($totalPoints) }}</div>
                <div class="stats-label">Total Points</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="stats-value">{{ number_format($pendingForumSubmissions) }}</div>
                <div class="stats-label">Pending Forum</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-info bg-opacity-10 text-info">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="stats-value">{{ number_format($pendingJobSubmissions) }}</div>
                <div class="stats-label">Pending Jobs</div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Top 3 Alumni -->
        <div class="col-md-6">
            <div class="table-container">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0"><i class="fas fa-trophy text-warning me-2"></i>Top 3 Alumni</h5>
                </div>
                <div class="p-3">
                    <div class="row">
                        @foreach ($topThreeAlumni as $index => $alumni)
                            <div class="col-md-4 mb-3">
                                <div class="text-center p-3 border rounded">
                                    <div class="mb-2">
                                        @if ($alumni->user->pp_url)
                                            <img src="{{ $alumni->user->pp_url }}" alt="{{ $alumni->fullname }}"
                                                class="rounded-circle" width="60" height="60">
                                        @else
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                                style="width: 60px; height: 60px; font-size: 1.2rem;">
                                                {{ substr($alumni->fullname, 0, 2) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="fw-bold">{{ $alumni->fullname }}</div>
                                    <div class="text-muted small">{{ $alumni->study_program }}</div>
                                    <div class="mt-2">
                                        <span class="badge bg-warning text-dark">{{ $index + 1 }}st</span>
                                        <span class="badge bg-success ms-1">{{ number_format($alumni->points) }} pts</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-md-6">
            <div class="table-container">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0"><i class="fas fa-chart-pie text-info me-2"></i>Quick Stats</h5>
                </div>
                <div class="p-3">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div
                                    class="avatar-sm bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <div class="fw-bold fs-5">{{ number_format($approvedForumSubmissions) }}</div>
                                    <div class="text-muted small">Approved Forum</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div
                                    class="avatar-sm bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <div class="fw-bold fs-5">{{ number_format($approvedJobSubmissions) }}</div>
                                    <div class="text-muted small">Approved Jobs</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div
                                    class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div>
                                    <div class="fw-bold fs-5">{{ number_format($totalPoints) }}</div>
                                    <div class="text-muted small">Total Points Awarded</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div
                                    class="avatar-sm bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    @php
                                        $totalPending = $pendingForumSubmissions + $pendingJobSubmissions;
                                    @endphp
                                    <div class="fw-bold fs-5">{{ number_format($totalPending) }}</div>
                                    <div class="text-muted small">Total Pending</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Forum Submissions -->
        <div class="col-md-6 mb-4">
            <div class="table-container">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-comments text-primary me-2"></i>Recent Forum Submissions</h5>
                    <a href="{{ route('admin.leaderboard.forum.submissions') }}" class="btn btn-sm btn-outline-primary">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Title</th>
                                <th>Alumni</th>
                                <th width="100">Status</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentForumSubmissions as $submission)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-semibold"
                                            style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $submission->title }}
                                        </div>
                                        <small class="text-muted">{{ $submission->category }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $submission->alumni->fullname }}</div>
                                        <small class="text-muted">{{ $submission->alumni->nim }}</small>
                                    </td>
                                    <td>
                                        <span class="badge-status badge-pending">Pending</span>
                                    </td>
                                    <td class="action-buttons">
                                        <a href="{{ route('admin.leaderboard.forum.show', $submission->id) }}"
                                            class="btn btn-view btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button onclick="approveSubmission('forum', {{ $submission->id }})"
                                            class="btn btn-approve btn-sm" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="rejectSubmission('forum', {{ $submission->id }})"
                                            class="btn btn-reject btn-sm" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-comments fa-2x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No pending forum submissions</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Job Submissions -->
        <div class="col-md-6 mb-4">
            <div class="table-container">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-briefcase text-success me-2"></i>Recent Job Submissions</h5>
                    <a href="{{ route('admin.leaderboard.job.submissions') }}" class="btn btn-sm btn-outline-success">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Position</th>
                                <th>Company</th>
                                <th width="100">Status</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentJobSubmissions as $submission)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-semibold"
                                            style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $submission->position }}
                                        </div>
                                        <small class="text-muted">{{ $submission->company_name }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $submission->alumni->fullname }}</div>
                                        <small class="text-muted">{{ $submission->alumni->nim }}</small>
                                    </td>
                                    <td>
                                        <span class="badge-status badge-pending">Pending</span>
                                    </td>
                                    <td class="action-buttons">
                                        <a href="{{ route('admin.leaderboard.job.show', $submission->id) }}"
                                            class="btn btn-view btn-sm" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button onclick="approveSubmission('job', {{ $submission->id }})"
                                            class="btn btn-approve btn-sm" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="rejectSubmission('job', {{ $submission->id }})"
                                            class="btn btn-reject btn-sm" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-briefcase fa-2x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No pending job submissions</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Points Awards -->
        <div class="col-12">
            <div class="table-container">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0"><i class="fas fa-award text-warning me-2"></i>Recent Points Activity</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Alumni</th>
                                <th>NIM</th>
                                <th>Study Program</th>
                                <th>Points</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPoints as $alumni)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $alumni->fullname }}</div>
                                    </td>
                                    <td>{{ $alumni->nim }}</td>
                                    <td>{{ $alumni->study_program }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ number_format($alumni->points) }} pts</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $alumni->updated_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-coins fa-2x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">No points activity yet</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="table-container">
                <div class="p-3 border-bottom">
                    <h5 class="mb-0"><i class="fas fa-bolt text-warning me-2"></i>Quick Actions</h5>
                </div>
                <div class="p-3">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.leaderboard.forum.submissions') }}?status=pending"
                                class="btn btn-warning w-100 d-flex align-items-center justify-content-center py-3">
                                <i class="fas fa-comments fa-2x me-3"></i>
                                <div class="text-start">
                                    <div class="fw-bold">Pending Forum</div>
                                    <small>{{ $pendingForumSubmissions }} submissions</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.leaderboard.job.submissions') }}?status=pending"
                                class="btn btn-info w-100 d-flex align-items-center justify-content-center py-3">
                                <i class="fas fa-briefcase fa-2x me-3"></i>
                                <div class="text-start">
                                    <div class="fw-bold">Pending Jobs</div>
                                    <small>{{ $pendingJobSubmissions }} submissions</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.leaderboard.alumni') }}"
                                class="btn btn-primary w-100 d-flex align-items-center justify-content-center py-3">
                                <i class="fas fa-trophy fa-2x me-3"></i>
                                <div class="text-start">
                                    <div class="fw-bold">View Rankings</div>
                                    <small>Alumni leaderboard</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-success w-100 d-flex align-items-center justify-content-center py-3"
                                onclick="location.reload()">
                                <i class="fas fa-sync-alt fa-2x me-3"></i>
                                <div class="text-start">
                                    <div class="fw-bold">Refresh Data</div>
                                    <small>Update statistics</small>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
