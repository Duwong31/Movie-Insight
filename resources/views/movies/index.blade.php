<!-- @extends('layouts.app') -->
@section('title', $pageTitle ?? 'Movies')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/movie-list.css') }}">
@endpush
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title" style="font-size: 1.8rem;">{{ $pageTitle }}</h1>

        {{-- Main Filter Dropdown (Giống ảnh) --}}
        <div class="main-filter-dropdown">
            <button id="mainFilterBtn" type="button">
                @if($status == 'in_theaters') In Theaters
                @elseif($status == 'coming_soon') Coming Soon
                @elseif($status == 'tv_shows') TV Shows
                @else All Movies
                @endif
                <i class="fas fa-caret-down"></i>
            </button>
            <div id="mainFilterDropdownContent" class="main-filter-dropdown-content">
                <a href="{{ route('movies.list', array_merge(request()->except('status', 'page'), ['status' => 'in_theaters'])) }}" class="{{ $status == 'in_theaters' ? 'active' : '' }}">In Theaters</a>
                <a href="{{ route('movies.list', array_merge(request()->except('status', 'page'), ['status' => 'coming_soon'])) }}" class="{{ $status == 'coming_soon' ? 'active' : '' }}">Coming Soon</a>
                <a href="{{ route('tvshows.list', array_merge(request()->except('status', 'page'))) }}" >TV Shows</a>
            </div>
        </div>
    </div>


    {{-- Form chứa các bộ lọc phụ --}}
    <form method="GET" action="{{ route('movies.list') }}">
        {{-- Giữ lại status hiện tại nếu nó không phải là filter chính trong form này --}}
        @if(request()->filled('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif

        <div class="filter-controls">
        <div class="form-group">
            <select name="sort_by"
                    id="sort_by"
                    class="form-control form-control-sm"
                    onchange="this.form.submit()">
                @php
                $sortOptions = [
                    'popularity.desc'     => 'Most Popular',
                    'release_date.desc'   => 'New Release',
                    'release_date.asc'    => 'Old Release',
                    'rating.desc'         => 'High Rating',
                ];
                @endphp

                <option value=""
                        hidden
                        {{ $sortBy === '' ? 'selected' : '' }}>
                Sort
                </option>

                @foreach($sortOptions as $value => $label)
                <option value="{{ $value }}"
                        {{ $sortBy === $value ? 'selected' : '' }}>
                    {{ $sortBy === $value
                        ? 'Sort: ' . $label
                        : $label
                    }}
                </option>
                @endforeach
            </select>
        </div>
            <div class="form-group" style="flex-direction: column; align-items: flex-start; min-width: 200px; position: relative;">
                <button type="button" id="toggle-genres-btn" style="background: #232323; color: #E0E0E0; border: 1px solid #444; border-radius: 5px; padding: 8px 16px; cursor: pointer; width: 100%; text-align: left;">Genres</button>
                <div id="genre-dropdown-menu" style="display: none; position: absolute; top: 110%; left: 0; z-index: 100; min-width: 220px; background: #232323; border-radius: 8px; border: 1px solid #444; box-shadow: 0 8px 24px rgba(0,0,0,0.25); padding: 14px 16px 12px 16px;">
                    <div id="genre-checkbox-list" style="max-height: 180px; overflow-y: auto;">
                        @php
                            $selectedGenres = request('genre_id', []);
                            if (!is_array($selectedGenres)) $selectedGenres = [$selectedGenres];
                            $selectedGenres = array_map('intval', $selectedGenres);
                        @endphp
                        @foreach ($genres as $genre)
                            <div style="margin-bottom: 6px;">
                                <input type="checkbox" name="genre_id[]" id="genre_{{ $genre->genres_id }}" value="{{ $genre->genres_id }}"
                                    {{ in_array($genre->genres_id, $selectedGenres) ? 'checked' : '' }}>
                                <label for="genre_{{ $genre->genres_id }}" style="color: #E0E0E0; font-size: 0.95rem; cursor: pointer;">{{ $genre->genres_name }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div id="genre-actions" style="display: flex; gap: 10px; margin-top: 10px;">
                        <button type="button" id="clear-genres-btn" style="background: none; border: none; color: #1da1f2; font-weight: 500; font-size: 1rem; padding: 6px 10px; border-radius: 0; box-shadow: none; cursor: pointer; white-space: nowrap;">Clear All</button>
                        <button type="submit" class="btn btn-primary" style="padding: 6px 18px; background: #e70634; color: #fff; border: none; border-radius: 5px;">Apply</button>
                    </div>
                </div>
            </div>

            <div class="form-group ml-auto" style="margin-left:auto;"> {{-- Đẩy nút Reset và Apply sang phải --}}
                 <a href="{{ route('movies.list', ['status' => request('status', 'in_theaters')]) }}" class="btn-reset mr-2">Reset Filters</a>
                 {{-- Nút Apply không cần thiết nếu dùng onchange --}}
                 {{-- <button type="submit" class="btn btn-primary btn-sm">Apply Filters</button> --}}
            </div>
        </div>
    </form>

    <div class="movie-grid">
        @forelse ($movies as $movie)
            @php
                $isMovie = isset($movie->movie_type) ? $movie->movie_type === 'isMovie' : false;
                $isTVShow = isset($movie->movie_type) ? $movie->movie_type === 'isTVShow' : false;
                $show = false;
                if (request()->routeIs('tvshows.list')) {
                    $show = $isTVShow;
                } else {
                    $show = $isMovie;
                }
            @endphp
            @if ($show)
            <div class="movie-card" id="movie-card-{{ $movie->movie_id }}">
                <a href="{{ route('movie.detail', $movie->movie_id) }}">
                    <img src="{{ $movie->poster_path ? Storage::url($movie->poster_path) : asset('uploads/' . $movie->movie_image) }}"
                         alt="{{ $movie->title ?? $movie->movie_name }} Poster" class="movie-poster">
                </a>
                <div class="movie-info" style="display: flex; flex-direction: column; gap: 4px;">
                    <div class="movie-rating">
                        <span class="rating-score" style="font-size: 1.1rem; font-weight: 500;">★ {{ number_format($movie->ratings_avg_rating ?? 0, 1) }}</span>
                    </div>
                    <h3 class="movie-title" style="margin: 0; font-size: 1rem; line-height: 1.2; word-wrap: break-word;">
                        <a href="{{ route('movie.detail', $movie->movie_id) }}" style="color: inherit; text-decoration: none;">
                            {{ $movie->title ?? $movie->movie_name }}
                        </a>
                    </h3>
                    @if($movie->release_date)
                    <p class="movie-release" style="margin: 0; color: #999; font-size: 0.9rem;">Opened {{ $movie->release_date }}</p>
                    @endif
                    <div class="watchlist-btn-container" style="margin-top: 4px;">
                        @auth
                            @php $isInUserWatchlist = in_array($movie->movie_id, $userWatchlistMovieIds); @endphp
                            <button class="btn-watchlist {{ $isInUserWatchlist ? 'in-watchlist removeWatchlist-btn' : 'add-watchlist-btn' }}"
                                    data-movie-id="{{ $movie->movie_id }}"
                                    style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #444; background: transparent; color: #fff; cursor: pointer;">
                                <i class="fas {{ $isInUserWatchlist ? 'fa-check' : 'fa-plus' }}"></i>
                                Watchlist
                            </button>
                        @else
                            <a href="{{ route('login') }}?redirect={{ url()->full() }}" 
                               class="btn-watchlist"
                               style="width: 100%; padding: 8px; border-radius: 4px; border: 1px solid #444; background: transparent; color: #fff; text-decoration: none; text-align: center; display: block;">
                                <i class="fas fa-plus"></i> Watchlist
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="no-movies w-100">
                <p>No movies found matching your criteria. Try adjusting your filters.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination Links --}}
    @if ($movies->hasPages())
        <div class="custom-pagination-container">
            {{ $movies->links('vendor.pagination.custom_pagination') }} {{-- Bootstrap 4 pagination styling mặc định --}}
        </div>
    @endif

</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/movie-list.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggle-genres-btn');
            const dropdownMenu = document.getElementById('genre-dropdown-menu');
            const clearBtn = document.getElementById('clear-genres-btn');
            // Toggle dropdown
            if (toggleBtn && dropdownMenu) {
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.style.display = dropdownMenu.style.display === 'none' ? 'block' : 'none';
                });
                // Đóng dropdown khi click ra ngoài
                document.addEventListener('click', function(e) {
                    if (!dropdownMenu.contains(e.target) && e.target !== toggleBtn) {
                        dropdownMenu.style.display = 'none';
                    }
                });
            }
            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    document.querySelectorAll('#genre-checkbox-list input[type=checkbox]').forEach(cb => cb.checked = false);
                });
            }
        });
    </script>
@endpush

