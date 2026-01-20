@extends('admin.views.layouts.app')

@section('title', 'Alumni Leaderboard')
@section('page-title', 'Alumni Rankings')
@section('page-subtitle', 'Manage alumni points and rankings')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Alumni Leaderboard</h5>
                    <small class="text-muted">Total {{ $alumni->total() }} alumni registered</small>
                </div>
                <div class="d-flex gap-2">
                    <form method="GET" class="d-flex search-box">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Search alumni..."
                                value="{{ $search }}">
                            @if ($search)
                                <a href="{{ route('admin.leaderboard.alumni') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                    <select name="per_page" class="form-select per-page-select" onchange="this.form.submit()">
                        <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 per page</option>
                        <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 per page</option>
                        <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 per page</option>
                        <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 per page</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="table-container mb-4">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="80">Rank</th>
                        <th>Alumni</th>
                        <th width="150">Study Program</th>
                        <th width="120">Graduation</th>
                        <th width="150" class="text-end">Points</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rankedAlumni as $alumniData)
                        @php
                            $initials = substr($alumniData->fullname, 0, 2);
                            $rank = ($alumni->currentPage() - 1) * $alumni->perPage() + $loop->iteration;
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if ($rank <= 3)
                                        <span class="badge bg-warning text-dark me-2">{{ $rank }}</span>
                                    @else
                                        <span class="text-muted me-2">#{{ $rank }}</span>
                                    @endif
                                    @if ($rank == 1)
                                        <i class="fas fa-crown text-warning"></i>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if ($alumniData->user->pp_url)
                                            <img src="{{ $alumniData->user->pp_url }}" alt="{{ $alumniData->fullname }}"
                                                class="rounded-circle" width="40" height="40">
                                        @else
                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; font-size: 1rem;">
                                                {{ $initials }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $alumniData->fullname }}</div>
                                        <small class="text-muted">{{ $alumniData->nim }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $alumniData->study_program }}</td>
                            <td>
                                <small
                                    class="text-muted">{{ $alumniData->graduation_date ? date('Y', strtotime($alumniData->graduation_date)) : '-' }}</small>
                            </td>
                            <td class="text-end">
                                <span class="badge bg-success">{{ number_format($alumniData->points) }} pts</span>
                            </td>
                            <td class="action-buttons">
                                <button onclick="editAlumniPoints({{ $alumniData->id }}, {{ $alumniData->points }})"
                                    class="btn btn-edit btn-sm" title="Edit Points">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="{{ route('admin.views.users.alumni.show', $alumniData->id) }}"
                                    class="btn btn-view btn-sm" title="View Profile" target="_blank">
                                    <i class="fas fa-user"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-users fa-2x text-muted mb-3"></i>
                                <p class="text-muted mb-0">No alumni found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($alumni->hasPages())
            <div class="p-3 border-top">
                <nav aria-label="Pagination">
                    <ul class="pagination justify-content-center mb-0">
                        {{-- Previous Page Link --}}
                        @if ($alumni->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ $alumni->previousPageUrl() }}&search={{ $search }}&per_page={{ $perPage }}"
                                    aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @for ($i = 1; $i <= $alumni->lastPage(); $i++)
                            @if ($i == $alumni->currentPage())
                                <li class="page-item active" aria-current="page">
                                    <span class="page-link">{{ $i }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ $alumni->url($i) }}&search={{ $search }}&per_page={{ $perPage }}">{{ $i }}</a>
                                </li>
                            @endif
                        @endfor

                        {{-- Next Page Link --}}
                        @if ($alumni->hasMorePages())
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ $alumni->nextPageUrl() }}&search={{ $search }}&per_page={{ $perPage }}"
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
                            Showing {{ $alumni->firstItem() }} - {{ $alumni->lastItem() }} of {{ $alumni->total() }}
                            alumni
                        </small>
                    </div>
                </nav>
            </div>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-primary bg-opacity-10 text-primary">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stats-value">{{ number_format($alumni->first()->points ?? 0) }}</div>
                <div class="stats-label">Top Alumni Points</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-success bg-opacity-10 text-success">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stats-value">{{ number_format($alumni->avg('points') ?? 0) }}</div>
                <div class="stats-label">Average Points</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-info bg-opacity-10 text-info">
                    <i class="fas fa-users"></i>
                </div>
                @php
                    $activeAlumni = $alumni->where('points', '>', 0)->count();
                    $activePercentage = $alumni->count() > 0 ? round(($activeAlumni / $alumni->count()) * 100) : 0;
                @endphp
                <div class="stats-value">{{ $activePercentage }}%</div>
                <div class="stats-label">Active Alumni</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-icon bg-warning bg-opacity-10 text-warning">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="stats-value">{{ number_format($alumni->sum('points')) }}</div>
                <div class="stats-label">Total Points</div>
            </div>
        </div>
    </div>
@endsection
