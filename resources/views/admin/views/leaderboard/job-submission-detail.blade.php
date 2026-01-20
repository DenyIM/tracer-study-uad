@extends('admin.views.layouts.app')

@section('title', 'Job Submission Detail')
@section('page-title', 'Job Submission Details')
@section('page-subtitle', 'Review job vacancy submission')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Job Submission Details</h5>
                    <small class="text-muted">ID: #{{ $submission->id }}</small>
                </div>
                <div>
                    <a href="{{ route('admin.leaderboard.job.submissions') }}?status={{ $submission->status }}"
                        class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="submission-detail-card mb-4">
                <h5 class="mb-4"><i class="fas fa-briefcase text-primary me-2"></i>Job Information</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="detail-label">Company Name</div>
                        <div class="detail-value">{{ $submission->company_name }}</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="detail-label">Position</div>
                        <div class="detail-value">
                            <span class="badge bg-primary">{{ $submission->position }}</span>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="detail-label">Location</div>
                        <div class="detail-value">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ $submission->location }}
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="detail-label">Field</div>
                        <div class="detail-value">
                            <span class="badge bg-info">{{ ucfirst($submission->field) }}</span>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="detail-label">Job Description</div>
                        <div class="detail-value">
                            <div class="border rounded p-3 bg-light">
                                {{ $submission->job_description }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="detail-label">Qualifications</div>
                        <div class="detail-value">
                            <div class="border rounded p-3 bg-light">
                                {{ $submission->qualifications }}
                            </div>
                        </div>
                    </div>

                    @if ($submission->deadline)
                        <div class="col-md-6 mb-3">
                            <div class="detail-label">Application Deadline</div>
                            <div class="detail-value">
                                <i class="fas fa-calendar me-2"></i>
                                {{ date('d F Y', strtotime($submission->deadline)) }}
                            </div>
                        </div>
                    @endif

                    @if ($submission->link)
                        <div class="col-md-6 mb-3">
                            <div class="detail-label">Application Link</div>
                            <div class="detail-value">
                                <a href="{{ $submission->link }}" target="_blank" class="text-primary">
                                    <i class="fas fa-external-link-alt me-2"></i>
                                    {{ $submission->link }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($submission->contact)
                        <div class="col-md-12 mb-3">
                            <div class="detail-label">Contact Information</div>
                            <div class="detail-value">
                                <i class="fas fa-phone me-2"></i>
                                {{ $submission->contact }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if ($submission->admin_notes)
                <div class="submission-detail-card mb-4">
                    <h5 class="mb-4"><i class="fas fa-sticky-note text-warning me-2"></i>Admin Notes</h5>
                    <div class="border rounded p-3 bg-light">
                        {{ $submission->admin_notes }}
                    </div>
                </div>
            @endif

            <!-- Timeline Section -->
            @if ($submission->created_at || $submission->verified_at || $submission->updated_at)
                <div class="submission-detail-card mb-4">
                    <h5 class="mb-4"><i class="fas fa-history text-info me-2"></i>Submission Timeline</h5>

                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6>Submitted</h6>
                                <p class="text-muted mb-0">{{ $submission->created_at->format('d F Y, H:i:s') }}</p>
                                <small class="text-muted">({{ $submission->created_at->diffForHumans() }})</small>
                            </div>
                        </div>

                        @if ($submission->verified_at)
                            <div class="timeline-item">
                                <div
                                    class="timeline-marker bg-{{ $submission->status == 'approved' ? 'success' : 'danger' }}">
                                </div>
                                <div class="timeline-content">
                                    <h6>{{ $submission->status == 'approved' ? 'Approved' : 'Rejected' }}</h6>
                                    <p class="text-muted mb-0">{{ $submission->verified_at->format('d F Y, H:i:s') }}</p>
                                    <small class="text-muted">({{ $submission->verified_at->diffForHumans() }})</small>
                                    @if ($submission->verifier)
                                        <p class="mb-0"><small>By: {{ $submission->verifier->email }}</small></p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if (
                            $submission->updated_at &&
                                $submission->updated_at != $submission->created_at &&
                                $submission->updated_at != $submission->verified_at)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6>Last Updated</h6>
                                    <p class="text-muted mb-0">{{ $submission->updated_at->format('d F Y, H:i:s') }}</p>
                                    <small class="text-muted">({{ $submission->updated_at->diffForHumans() }})</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <!-- Submission Status -->
            <div class="submission-detail-card mb-4">
                <h5 class="mb-4"><i class="fas fa-flag text-info me-2"></i>Status Information</h5>

                <div class="mb-3">
                    <div class="detail-label">Current Status</div>
                    <div class="detail-value">
                        @if ($submission->status == 'pending')
                            <span class="badge-status badge-pending">Pending Review</span>
                        @elseif($submission->status == 'approved')
                            <span class="badge-status badge-approved">Approved</span>
                        @else
                            <span class="badge-status badge-rejected">Rejected</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <div class="detail-label">Submitted On</div>
                    <div class="detail-value">
                        {{ $submission->created_at->format('d F Y, H:i:s') }}
                    </div>
                </div>

                @if ($submission->verified_at)
                    <div class="mb-3">
                        <div class="detail-label">{{ $submission->status == 'approved' ? 'Approved' : 'Rejected' }} On
                        </div>
                        <div class="detail-value">
                            {{ $submission->verified_at->format('d F Y, H:i:s') }}
                        </div>
                    </div>
                @endif

                @if ($submission->verifier)
                    <div class="mb-3">
                        <div class="detail-label">{{ $submission->status == 'approved' ? 'Approved' : 'Rejected' }} By
                        </div>
                        <div class="detail-value">
                            {{ $submission->verifier->email }}
                        </div>
                    </div>
                @endif

                @if ($submission->points_awarded)
                    <div class="mb-3">
                        <div class="detail-label">Points Awarded</div>
                        <div class="detail-value">
                            <span class="badge bg-success">{{ number_format($submission->points_awarded) }} points</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Alumni Information -->
            <div class="submission-detail-card mb-4">
                <h5 class="mb-4"><i class="fas fa-user-graduate text-success me-2"></i>Alumni Information</h5>

                <div class="text-center mb-3">
                    @if ($submission->alumni->user->pp_url)
                        <img src="{{ $submission->alumni->user->pp_url }}" alt="{{ $submission->alumni->fullname }}"
                            class="rounded-circle mb-3" width="80" height="80">
                    @else
                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                            style="width: 80px; height: 80px; font-size: 1.5rem;">
                            {{ substr($submission->alumni->fullname, 0, 2) }}
                        </div>
                    @endif

                    <div class="fw-bold">{{ $submission->alumni->fullname }}</div>
                    <div class="text-muted">{{ $submission->alumni->nim }}</div>
                </div>

                <div class="mb-2">
                    <div class="detail-label">Study Program</div>
                    <div class="detail-value">{{ $submission->alumni->study_program }}</div>
                </div>

                <div class="mb-2">
                    <div class="detail-label">Graduation Year</div>
                    <div class="detail-value">
                        {{ $submission->alumni->graduation_date ? date('Y', strtotime($submission->alumni->graduation_date)) : '-' }}
                    </div>
                </div>

                <div class="mb-2">
                    <div class="detail-label">Current Points</div>
                    <div class="detail-value">
                        <span class="badge bg-success">{{ number_format($submission->alumni->points) }} pts</span>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('admin.leaderboard.alumni') }}?search={{ $submission->alumni->nim }}"
                        class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-chart-line me-1"></i> View Ranking
                    </a>
                </div>
            </div>

            <!-- Action Buttons -->
            @if ($submission->status == 'pending')
                <div class="submission-detail-card">
                    <h5 class="mb-4"><i class="fas fa-cogs text-warning me-2"></i>Actions</h5>

                    <div class="d-grid gap-2">
                        <button onclick="approveSubmission('job', {{ $submission->id }})" class="btn btn-approve btn-lg">
                            <i class="fas fa-check me-2"></i> Approve Submission
                        </button>

                        <button onclick="rejectSubmission('job', {{ $submission->id }})" class="btn btn-reject btn-lg">
                            <i class="fas fa-times me-2"></i> Reject Submission
                        </button>
                    </div>

                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Approving will award 3000 points to the alumni
                        </small>
                    </div>
                </div>
            @else
                <div class="submission-detail-card">
                    <h5 class="mb-4"><i class="fas fa-cogs text-warning me-2"></i>Actions</h5>

                    <div class="d-grid gap-2">
                        <button onclick="deleteSubmission('job', {{ $submission->id }})" class="btn btn-danger btn-lg">
                            <i class="fas fa-trash me-2"></i> Delete Submission
                        </button>

                        @if ($submission->status == 'approved' && $submission->link)
                            <a href="{{ $submission->link }}" target="_blank" class="btn btn-info btn-lg">
                                <i class="fas fa-external-link-alt me-2"></i> Visit Link
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
