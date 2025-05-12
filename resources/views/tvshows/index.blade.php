<!-- @extends('layouts.app') -->
@section('title', $pageTitle ?? 'TV Shows')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/movie-list.css') }}">
@endpush
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="page-title" style="font-size: 1.8rem;">{{ $pageTitle ?? 'TV Shows' }}</h1>
        <div class="main-filter-dropdown">
            <button id="mainFilterBtn" type="button">
                TV Shows<i class="fas fa-caret-down"></i>
            </button>
            <div id="mainFilterDropdownContent" class="main-filter-dropdown-content">
            
                <a href="{{ route('movies.list', array_merge(request()->except('status', 'page'), ['status' => 'in_theaters'])) }}">
                    In Theaters
                </a>
                <a href="{{ route('movies.list', array_merge(request()->except('status', 'page'), ['status' => 'coming_soon'])) }}" class="{{ $status == 'coming_soon' ? 'active' : '' }}">
                    Coming Soon
                </a>
                <a href="{{ route('tvshows.list') }}" class="active">
                    TV Shows
                </a>
            </div>
        </div>
    </div>

    <div class="movie-grid">
        @forelse ($tvshows as $tvshow)
            @php
                $isTVShow = isset($tvshow->movie_type) ? $tvshow->movie_type === 'isTVShow' : false;
            @endphp
            @if ($isTVShow)
            <div class="movie-card" id="movie-card-{{ $tvshow->movie_id }}">
                <a href="{{ route('movie.detail', $tvshow->movie_id) }}">
                    <img src="{{ $tvshow->poster_path ? Storage::url($tvshow->poster_path) : asset('uploads/' . $tvshow->movie_image) }}"
                         alt="{{ $tvshow->title ?? $tvshow->movie_name }} Poster" class="movie-poster">
                </a>
                <div class="movie-info">
                    <h3 class="movie-title">
                        <a href="{{ route('movie.detail', $tvshow->movie_id) }}" style="color: inherit; text-decoration: none;">
                            {{ $tvshow->title ?? $tvshow->movie_name }}
                        </a>
                    </h3>
                    <div class="movie-scores">
                            @if(isset($tvshow->tomatometer_score))
                        <span class="tomatometer" title="Tomatometer">
                            <img src="{{ asset('img/rt-certified-fresh.svg') }}" alt="Tomatometer" style="width:16px; height:16px; vertical-align: text-bottom;">
                            {{ $tvshow->tomatometer_score }}%
                        </span>
                        @endif
                        @if(isset($tvshow->audience_score))
                        <span class="audience-score" title="Audience Score">
                             <img src="{{ asset('img/rt-upright.svg') }}" alt="Audience Score" style="width:16px; height:16px; vertical-align: text-bottom;">
                            {{ $tvshow->audience_score }}%
                        </span>
                        @endif
                    </div>
                    @if($tvshow->release_date)
                    <p class="movie-release">Opened {{ $tvshow->release_date}}</p>
                    @endif

                    <div class="watchlist-btn-container">
                        @auth
                            @php $isInUserWatchlist = in_array($tvshow->movie_id, $userWatchlistMovieIds ?? []); @endphp
                            <button
                                class="btn-watchlist {{ $isInUserWatchlist ? 'in-watchlist remove-watchlist-btn' : 'addToWatchlist-btn' }}"
                                data-movie-id="{{ $tvshow->movie_id }}">
                                <i class="fas {{ $isInUserWatchlist ? 'fa-check' : 'fa-plus' }}"></i>
                                Watchlist
                            </button>
                        @else
                            <a href="{{ route('login') }}?redirect={{ url()->full() }}" class="btn-watchlist">
                                <i class="fas fa-plus"></i> Watchlist
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="no-movies w-100">
                <p>No TV shows found matching your criteria. Try adjusting your filters.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination Links --}}
    @if ($tvshows->hasPages())
        <div class="d-flex justify-content-center">
            {{ $tvshows->links() }}
        </div>
    @endif

</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/movie-list.js') }}"></script>
@endpush 