@if (!empty($podiumData) && $podiumData['first'])
    <div class="podium-container" data-aos="fade-up">
        <div class="podium">
            <!-- Second Place -->
            <div class="podium-place podium-2">
                <div class="podium-stand">
                    <div class="podium-content">
                        <div class="podium-avatar">{{ $this->getInitials($podiumData['second']->fullname) }}</div>
                        <div class="podium-name">{{ $podiumData['second']->fullname }}</div>
                        <div class="podium-points">{{ number_format($podiumData['second']->points) }} pts</div>
                    </div>
                </div>
                <div class="place-badge">2nd</div>
            </div>

            <!-- First Place -->
            <div class="podium-place podium-1">
                <div class="crown">
                    <i class="fas fa-crown"></i>
                </div>
                <div class="podium-stand">
                    <div class="podium-content">
                        <div class="podium-avatar">{{ $this->getInitials($podiumData['first']->fullname) }}</div>
                        <div class="podium-name">{{ $podiumData['first']->fullname }}</div>
                        <div class="podium-points">{{ number_format($podiumData['first']->points) }} pts</div>
                    </div>
                </div>
                <div class="place-badge">1st</div>
            </div>

            <!-- Third Place -->
            <div class="podium-place podium-3">
                <div class="podium-stand">
                    <div class="podium-content">
                        <div class="podium-avatar">{{ $this->getInitials($podiumData['third']->fullname) }}</div>
                        <div class="podium-name">{{ $podiumData['third']->fullname }}</div>
                        <div class="podium-points">{{ number_format($podiumData['third']->points) }} pts</div>
                    </div>
                </div>
                <div class="place-badge">3rd</div>
            </div>
        </div>
    </div>
@else
    <div class="podium-container" data-aos="fade-up">
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>
            Belum ada data podium. Mulai isi kuesioner untuk tampil di leaderboard!
        </div>
    </div>
@endif

@php
    // Helper function to get initials
    if (!function_exists('getInitials')) {
        function getInitials($name)
        {
            $words = explode(' ', $name);
            $initials = '';
            foreach ($words as $word) {
                if (!empty($word)) {
                    $initials .= strtoupper(substr($word, 0, 1));
                }
            }
            return substr($initials, 0, 2);
        }
    }
@endphp
