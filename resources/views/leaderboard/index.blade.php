<x-app-layout>
    @section('title', 'Leaderboard Alumni')

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/leaderboard.css') }}">
    @endpush

    <section class="leaderboard-header">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3" data-aos="fade-up">Leaderboard Alumni</h1>
            <p class="lead mb-4" data-aos="fade-up" data-aos-delay="100">Kompetisi sehat untuk berkontribusi bagi almamater
                dan dapatkan rewards eksklusif!</p>
            <div class="points-badge d-inline-block px-4 py-2" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-coins me-2"></i>Total Points Anda: <strong
                    id="userPoints">{{ number_format($userPoints) }}</strong>
            </div>
        </div>
    </section>

    <div class="main-content">
        <div class="container py-5">
            <!-- Judul Section Leaderboard -->
            <div class="section-header mb-4" data-aos="fade-up">
                <h2 class="fw-bold mb-3" style="color: var(--primary-blue);">
                    <i class="fas fa-trophy me-2"></i>Peringkat Leaderboard
                </h2>
                <p class="text-muted">Lihat peringkat alumni teratas dan perjuangkan posisi terbaik Anda!</p>
            </div>

            <!-- Podium Section -->
            @include('leaderboard._podium', ['podiumData' => $podiumData])

            <!-- Benefits Section -->
            @include('leaderboard._benefits')

            <!-- Leaderboard Table -->
            @include('leaderboard._table', [
                'topAlumni' => $topAlumni,
                'currentUser' => $currentUser,
                'currentUserRank' => $currentUserRank,
                'totalParticipants' => $totalParticipants,
            ])

            <!-- Section Kirim Informasi -->
            @include('leaderboard._submit-form')
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('js/leaderboard.js') }}"></script>
        <script>
            // Global variables for JavaScript
            window.leaderboardConfig = {
                currentUserId: {{ $currentUser->id ?? 0 }},
                currentUserRank: {{ $currentUserRank ?? 0 }},
                submitForumUrl: "{{ route('leaderboard.submit.forum') }}",
                submitJobUrl: "{{ route('leaderboard.submit.job') }}",
                getDataUrl: "{{ route('leaderboard.data') }}",
                csrfToken: "{{ csrf_token() }}"
            };
        </script>
    @endpush
</x-app-layout>
