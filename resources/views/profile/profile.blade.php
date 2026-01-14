<x-app-layout>
    @section('title', 'Profile')

    <div class="main-content">
        <div class="profile-header-section" data-aos="fade-down">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-5 fw-bold mb-3">Profil Saya</h1>
                        <p class="lead mb-0">Kelola informasi profil dan pengaturan akun Anda</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="hero-icon">
                            <i class="fas fa-user-circle" style="font-size: 6rem; opacity: 0.8;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container profile-container">
            @include('profile.partials.profile-info')
            @include('profile.partials.account-settings')
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    @endpush

    @push('scripts')
        <script>
            // Debug: Cek route password
            console.log('Password update route:', "{{ route('profile.password.update') }}");
            console.log('CSRF Token:', "{{ csrf_token() }}");
        </script>
        <script src="{{ asset('js/profile.js') }}"></script>
    @endpush
</x-app-layout>
