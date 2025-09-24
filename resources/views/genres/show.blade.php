@extends('layouts.app')

@section('title', $genre->genres_name . ' Movies & TV Shows')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-tags"></i> {{ $genre->genres_name }}</h2>
                <span class="badge bg-primary fs-6">{{ $movies->total() }} {{ Str::plural('item', $movies->total()) }}</span>
            </div>

            @if($movies->count() > 0)
                <div class="row">
                    @foreach($movies as $movie)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100">
                                <div class="card-img-container" style="height: 300px; overflow: hidden;">
                                    <img src="{{ $movie->movie_image ? asset('uploads/' . $movie->movie_image) : asset('img/placeholder-movie.jpg') }}" 
                                         class="card-img-top" 
                                         alt="{{ $movie->movie_name }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">{{ $movie->movie_name }}</h6>
                                    
                                    <div class="mb-2">
                                        @if($movie->ratings_avg_rating)
                                            <span class="text-warning">★ {{ number_format($movie->ratings_avg_rating, 1) }}/10</span>
                                        @else
                                            <span class="text-muted">No ratings yet</span>
                                        @endif
                                    </div>

                                    @if($movie->release_date)
                                        <p class="text-muted mb-2">
                                            <i class="fas fa-calendar"></i> {{ $movie->release_date }}
                                        </p>
                                    @endif

                                    <div class="mb-2">
                                        <span class="badge bg-{{ $movie->movie_type == 'isMovie' ? 'primary' : 'success' }}">
                                            {{ $movie->movie_type == 'isMovie' ? 'Movie' : 'TV Show' }}
                                        </span>
                                        @if($movie->status)
                                            <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $movie->status)) }}</span>
                                        @endif
                                    </div>

                                    <div class="mt-auto">
                                        <a href="{{ route('movie.detail', $movie->movie_id) }}" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($movies->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $movies->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-film fa-3x text-muted mb-3"></i>
                    <h4>No movies or TV shows found</h4>
                    <p class="text-muted">There are no movies or TV shows in the {{ $genre->genres_name }} genre yet.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="fas fa-home"></i> Back to Home
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <a href="{{ route('home') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>
</div>
@endsection
