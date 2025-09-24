@extends('layouts.app')

@section('title', $actor->actor_name . ' - Celebrity Details')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-img-container" style="height: 500px; overflow: hidden;">
                    <img src="{{ $actor->actors_image ? asset('uploads/' . $actor->actors_image) : asset('img/placeholder-actor.jpg') }}" 
                         class="card-img-top" 
                         alt="{{ $actor->actor_name }}"
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <div class="card-body text-center">
                    <h3 class="card-title">{{ $actor->actor_name }}</h3>
                    @if($actor->birth_date)
                        <p class="text-muted">
                            <i class="fas fa-birthday-cake"></i> 
                            {{ \Carbon\Carbon::parse($actor->birth_date)->format('M d, Y') }}
                        </p>
                    @endif
                    @if($actor->nationality)
                        <p class="text-muted">
                            <i class="fas fa-flag"></i> {{ $actor->nationality }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            @if($actor->biography)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-user"></i> Biography</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $actor->biography }}</p>
                    </div>
                </div>
            @endif

            @if($actor->movies && $actor->movies->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-film"></i> Movies & TV Shows ({{ $actor->movies->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($actor->movies as $movie)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex">
                                        <img src="{{ $movie->movie_image ? asset('uploads/' . $movie->movie_image) : asset('img/placeholder-movie.jpg') }}" 
                                             alt="{{ $movie->movie_name }}" 
                                             class="img-thumbnail me-3" 
                                             style="width: 80px; height: 120px; object-fit: cover;">
                                        <div>
                                            <h6>
                                                <a href="{{ route('movie.detail', $movie->movie_id) }}" class="text-decoration-none">
                                                    {{ $movie->movie_name }}
                                                </a>
                                            </h6>
                                            @if($movie->release_date)
                                                <p class="mb-1 text-muted">
                                                    <i class="fas fa-calendar"></i> {{ $movie->release_date }}
                                                </p>
                                            @endif
                                            @if($movie->ratings_avg_rating)
                                                <p class="mb-1">
                                                    <span class="text-warning">★ {{ number_format($movie->ratings_avg_rating, 1) }}/10</span>
                                                </p>
                                            @endif
                                            <span class="badge bg-{{ $movie->movie_type == 'isMovie' ? 'primary' : 'success' }}">
                                                {{ $movie->movie_type == 'isMovie' ? 'Movie' : 'TV Show' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-film fa-3x text-muted mb-3"></i>
                        <h5>No movies or TV shows found</h5>
                        <p class="text-muted">This celebrity doesn't have any movies or TV shows in our database yet.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <a href="{{ route('celebs.list') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Celebrities
            </a>
        </div>
    </div>
</div>
@endsection
