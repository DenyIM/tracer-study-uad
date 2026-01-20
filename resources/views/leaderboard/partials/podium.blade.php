<!-- Podium Section -->
<div class="podium-container" data-aos="fade-up">
    <div class="podium">
        @php
            $podiumPositions = [
                2 => ['class' => 'podium-2', 'badge' => '2nd'],
                1 => ['class' => 'podium-1', 'badge' => '1st', 'crown' => true],
                3 => ['class' => 'podium-3', 'badge' => '3rd'],
            ];
        @endphp

        @foreach ($podiumPositions as $index => $position)
            @if (isset($topThree[$index - 1]))
                @php
                    $user = $topThree[$index - 1];
                    $initials = substr($user->fullname, 0, 2);
                @endphp
                <div class="podium-place {{ $position['class'] }}">
                    @if (isset($position['crown']) && $position['crown'])
                        <div class="crown">
                            <i class="fas fa-crown"></i>
                        </div>
                    @endif
                    <div class="podium-stand">
                        <div class="podium-content">
                            <div class="podium-avatar">
                                @if ($user->pp_url)
                                    <img src="{{ $user->pp_url }}" alt="{{ $user->fullname }}" class="avatar-img">
                                @else
                                    {{ $initials }}
                                @endif
                            </div>
                            <div class="podium-name">{{ $user->fullname }}</div>
                            <div class="podium-points">{{ number_format($user->points, 0, ',', '.') }} pts</div>
                        </div>
                    </div>
                    <div class="place-badge">{{ $position['badge'] }}</div>
                </div>
            @else
                <div class="podium-place {{ $position['class'] }}">
                    <div class="podium-stand">
                        <div class="podium-content">
                            <div class="podium-avatar">-</div>
                            <div class="podium-name">Tidak Ada</div>
                            <div class="podium-points">0 pts</div>
                        </div>
                    </div>
                    <div class="place-badge">{{ $position['badge'] }}</div>
                </div>
            @endif
        @endforeach
    </div>
</div>
