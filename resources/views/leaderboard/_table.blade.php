<div class="leaderboard-table mb-5" data-aos="fade-up">
    <div class="table-responsive">
        <table class="table table-hover mb-0" id="leaderboardTable">
            <thead>
                <tr>
                    <th width="80">Rank</th>
                    <th>Alumni</th>
                    <th width="150" class="text-end">Total Points</th>
                </tr>
            </thead>
            <tbody id="leaderboardTableBody">
                @foreach ($topAlumni as $index => $alumni)
                    @php
                        $rank = ($topAlumni->currentPage() - 1) * $topAlumni->perPage() + $index + 1;
                        $isCurrentUser = $currentUser && $alumni->id == $currentUser->id;
                        $rowClass = $isCurrentUser ? 'current-user' : '';
                    @endphp
                    <tr class="{{ $rowClass }}" data-alumni-id="{{ $alumni->id }}">
                        <td class="user-rank">{{ $rank }}</td>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">{{ getInitials($alumni->fullname) }}</div>
                                <div class="user-details">
                                    <h6>{{ $alumni->fullname }}</h6>
                                    <small>{{ $alumni->study_program }}
                                        {{ $alumni->graduation_date ? $alumni->graduation_date->format('Y') : '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="text-end"><span class="points-badge">{{ number_format($alumni->points) }} pts</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($topAlumni->hasPages())
        <div class="p-3 border-top">
            <nav aria-label="Leaderboard pagination">
                {{ $topAlumni->links('pagination::bootstrap-5') }}
            </nav>
        </div>
    @endif
</div>
