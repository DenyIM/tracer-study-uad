@extends('admin.views.layouts.app')

@section('title', 'Job Submissions')
@section('page-title', 'Job Submissions Management')
@section('page-subtitle', 'Review and approve job vacancy submissions')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Job Submissions</h5>
                    <small class="text-muted">Manage alumni job vacancy submissions</small>
                </div>
                <div class="d-flex gap-2">
                    <div class="btn-group" role="group">
                        <a href="?status=pending" class="btn btn-outline-warning {{ $status == 'pending' ? 'active' : '' }}">
                            <i class="fas fa-clock me-1"></i> Pending
                            <span class="badge bg-warning text-dark ms-1">{{ $stats['pending'] }}</span>
                        </a>
                        <a href="?status=approved"
                            class="btn btn-outline-success {{ $status == 'approved' ? 'active' : '' }}">
                            <i class="fas fa-check me-1"></i> Approved
                            <span class="badge bg-success ms-1">{{ $stats['approved'] }}</span>
                        </a>
                        <a href="?status=rejected"
                            class="btn btn-outline-danger {{ $status == 'rejected' ? 'active' : '' }}">
                            <i class="fas fa-times me-1"></i> Rejected
                            <span class="badge bg-danger ms-1">{{ $stats['rejected'] }}</span>
                        </a>
                    </div>

                    @if ($status == 'pending')
                        <button class="btn btn-success" onclick="bulkApprove('job')" id="bulkApproveBtn" disabled>
                            <i class="fas fa-check-double me-1"></i> Approve Selected
                            <span class="badge bg-light text-dark ms-1" id="selectedCount">0</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <form method="GET" class="d-flex search-box">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Search submissions..."
                        value="{{ $search }}">
                    <input type="hidden" name="status" value="{{ $status }}">
                    @if ($search)
                        <a href="?status={{ $status }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-4 text-end">
            <select name="per_page" class="form-select per-page-select d-inline-block"
                onchange="window.location = '?status={{ $status }}&search={{ $search }}&per_page=' + this.value">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 per page</option>
                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 per page</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 per page</option>
            </select>
        </div>
    </div>

    <div class="table-container mb-4">
        @if ($status == 'pending')
            <div class="p-3 border-bottom bg-light">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                    <label class="form-check-label" for="selectAll">
                        Select all submissions on this page
                    </label>
                </div>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        @if ($status == 'pending')
                            <th width="30"></th>
                        @endif
                        <th width="80">#</th>
                        <th>Position & Company</th>
                        <th>Alumni</th>
                        <th width="120">Submitted</th>
                        <th width="100">Status</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($submissions as $submission)
                        <tr>
                            @if ($status == 'pending')
                                <td>
                                    <input type="checkbox" class="form-check-input submission-checkbox"
                                        value="{{ $submission->id }}">
                                </td>
                            @endif
                            <td>{{ $loop->iteration + ($submissions->currentPage() - 1) * $submissions->perPage() }}</td>
                            <td>
                                <div class="fw-semibold"
                                    style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    {{ $submission->position }}
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-building me-1"></i>{{ $submission->company_name }}
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $submission->location }}
                                    @if ($submission->field)
                                        | <i class="fas fa-tag me-1"></i>{{ ucfirst($submission->field) }}
                                    @endif
                                </small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $submission->alumni->fullname }}</div>
                                <small class="text-muted">{{ $submission->alumni->nim }}</small>
                                <br>
                                <small class="text-muted">{{ $submission->alumni->study_program }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $submission->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                @if ($submission->status == 'pending')
                                    <span class="badge-status badge-pending">Pending</span>
                                @elseif($submission->status == 'approved')
                                    <span class="badge-status badge-approved">Approved</span>
                                    @if ($submission->points_awarded)
                                        <br><small class="text-success">+{{ $submission->points_awarded }} pts</small>
                                    @endif
                                @else
                                    <span class="badge-status badge-rejected">Rejected</span>
                                @endif
                            </td>
                            <td class="action-buttons">
                                <a href="{{ route('admin.leaderboard.job.show', $submission->id) }}"
                                    class="btn btn-view btn-sm" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if ($submission->status == 'pending')
                                    <button onclick="approveSubmission('job', {{ $submission->id }})"
                                        class="btn btn-approve btn-sm" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button onclick="rejectSubmission('job', {{ $submission->id }})"
                                        class="btn btn-reject btn-sm" title="Reject">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif

                                <!-- Tampilkan tombol delete untuk semua status kecuali pending -->
                                @if ($submission->status != 'pending')
                                    <button onclick="deleteSubmission('job', {{ $submission->id }})"
                                        class="btn btn-danger btn-sm" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif

                                @if ($submission->status == 'approved' && $submission->link)
                                    <a href="{{ $submission->link }}" target="_blank" class="btn btn-info btn-sm"
                                        title="Visit Link">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $status == 'pending' ? 7 : 6 }}" class="text-center py-4">
                                @if ($status == 'pending')
                                    <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No pending job submissions</p>
                                @elseif($status == 'approved')
                                    <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                                    <p class="text-muted mb-0">No approved job submissions</p>
                                @else
                                    <i class="fas fa-times-circle fa-2x text-danger mb-3"></i>
                                    <p class="text-muted mb-0">No rejected job submissions</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($submissions->hasPages())
            <div class="p-3 border-top">
                <nav aria-label="Pagination">
                    <ul class="pagination justify-content-center mb-0">
                        {{-- Previous Page Link --}}
                        @if ($submissions->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ $submissions->previousPageUrl() }}&status={{ $status }}&search={{ $search }}&per_page={{ $perPage }}"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @for ($i = 1; $i <= $submissions->lastPage(); $i++)
                            @if ($i == $submissions->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link">{{ $i }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ $submissions->url($i) }}&status={{ $status }}&search={{ $search }}&per_page={{ $perPage }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor

                        {{-- Next Page Link --}}
                        @if ($submissions->hasMorePages())
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ $submissions->nextPageUrl() }}&status={{ $status }}&search={{ $search }}&per_page={{ $perPage }}"
                                    aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </span>
                            </li>
                        @endif
                    </ul>

                    <div class="text-center mt-2">
                        <small class="text-muted">
                            Showing {{ $submissions->firstItem() }} - {{ $submissions->lastItem() }} of
                            {{ $submissions->total() }} submissions
                        </small>
                    </div>
                </nav>
            </div>
        @endif
    </div>

    <!-- Stats Summary -->
    <div class="row">
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-value">{{ number_format($stats['pending']) }}</div>
                <div class="stats-label">Pending Submissions</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-value">{{ number_format($stats['approved']) }}</div>
                <div class="stats-label">Approved Submissions</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-icon bg-danger bg-opacity-10 text-danger">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stats-value">{{ number_format($stats['rejected']) }}</div>
                <div class="stats-label">Rejected Submissions</div>
            </div>
        </div>
    </div>
@endsection
