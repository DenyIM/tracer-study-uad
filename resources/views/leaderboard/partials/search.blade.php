<!-- Search and Filter Section -->
<div class="row mb-4" data-aos="fade-up">
    <div class="col-md-8">
        <form id="searchForm" class="d-flex gap-2" method="GET" action="{{ route('leaderboard') }}">
            @csrf
            <div class="input-group search-box">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="search" class="form-control" placeholder="Cari alumni..."
                    value="{{ $search }}">
                @if ($search)
                    <a href="{{ route('leaderboard') }}" class="btn btn-outline-secondary" id="clearSearch">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
            <input type="hidden" name="per_page" value="{{ $perPage }}">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search me-1"></i> Cari
            </button>
        </form>
    </div>
    <div class="col-md-4 text-end">
        <form id="perPageForm" method="GET" action="{{ route('leaderboard') }}" class="d-inline-block">
            <input type="hidden" name="search" value="{{ $search }}">
            <select name="per_page" class="form-select per-page-select" onchange="this.form.submit()">
                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10 per halaman</option>
                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20 per halaman</option>
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50 per halaman</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100 per halaman</option>
            </select>
        </form>
    </div>
</div>
