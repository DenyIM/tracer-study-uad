<!-- Leaderboard Table -->
<div class="leaderboard-table mb-5" data-aos="fade-up" id="leaderboardTable">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th width="80">Rank</th>
                    <th>Alumni</th>
                    <th width="150" class="text-end">Total Points</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($leaderboard as $index => $alumniData)
                    @php
                        $isCurrentUser = $currentUser && $alumniData->id == $currentUser->id;
                        $initials = substr($alumniData->fullname, 0, 2);
                    @endphp
                    <tr class="user-row {{ $isCurrentUser ? 'current-user current-user-highlight' : '' }}"
                        data-user-id="{{ $alumniData->id }}">
                        <td class="user-rank">
                            #{{ ($leaderboard->currentPage() - 1) * $leaderboard->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    @if ($alumniData->pp_url)
                                        <img src="{{ $alumniData->pp_url }}" alt="{{ $alumniData->fullname }}"
                                            class="avatar-img">
                                    @else
                                        {{ $initials }}
                                    @endif
                                </div>
                                <div class="user-details">
                                    <h6>{{ $alumniData->fullname }}</h6>
                                    <small>{{ $alumniData->study_program }}
                                        {{ date('Y', strtotime($alumniData->graduation_date)) }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-end">
                            <span class="points-badge">{{ number_format($alumniData->points, 0, ',', '.') }} pts</span>
                        </td>
                    </tr>
                @endforeach

                <!-- Show current user if not in current page -->
                @if ($currentUser && !$leaderboard->contains('id', $currentUser->id))
                    @php
                        $initialsCurrent = substr($currentUser->fullname, 0, 2);
                    @endphp
                    <tr class="current-user current-user-highlight" data-user-id="{{ $currentUser->id }}">
                        <td class="user-rank">
                            #{{ $currentUserRank }}
                        </td>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    @if ($currentUser->user->pp_url ?? false)
                                        <img src="{{ $currentUser->user->pp_url }}" alt="{{ $currentUser->fullname }}"
                                            class="avatar-img">
                                    @else
                                        {{ $initialsCurrent }}
                                    @endif
                                </div>
                                <div class="user-details">
                                    <h6>{{ $currentUser->fullname }}</h6>
                                    <small>{{ $currentUser->study_program }}
                                        {{ date('Y', strtotime($currentUser->graduation_date)) }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-end">
                            <span class="points-badge">{{ number_format($currentUser->points, 0, ',', '.') }}
                                pts</span>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($leaderboard->hasPages())
        <div class="p-3 border-top">
            <nav aria-label="Leaderboard pagination">
                <ul class="pagination justify-content-center mb-0">
                    {{-- Previous Page Link --}}
                    @if ($leaderboard->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link"
                                href="{{ $leaderboard->previousPageUrl() }}&search={{ $search }}&per_page={{ $perPage }}"
                                aria-label="Previous" onclick="scrollToTable()">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($leaderboard->getUrlRange(1, $leaderboard->lastPage()) as $page => $url)
                        @if ($page == $leaderboard->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ $url }}&search={{ $search }}&per_page={{ $perPage }}"
                                    onclick="scrollToTable()">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($leaderboard->hasMorePages())
                        <li class="page-item">
                            <a class="page-link"
                                href="{{ $leaderboard->nextPageUrl() }}&search={{ $search }}&per_page={{ $perPage }}"
                                aria-label="Next" onclick="scrollToTable()">
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

                {{-- Page Info --}}
                <div class="text-center mt-2">
                    <small class="text-muted">
                        Menampilkan {{ $leaderboard->firstItem() }} - {{ $leaderboard->lastItem() }} dari
                        {{ $leaderboard->total() }} alumni
                    </small>
                </div>
            </nav>
        </div>
    @endif
</div>
