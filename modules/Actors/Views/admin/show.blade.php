@extends('admin.layouts.app')

@section('title', $actor->actor_name . ' - Celebrity Details')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{ $actor->actor_name }}</h1>
    <div>
        <a href="{{ route('admin.actors.edit', $actor) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Celebrity
        </a>
        <a href="{{ route('admin.actors.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <img src="{{ $actor->actors_image ? asset('storage/' . $actor->actors_image) : asset('img/placeholder-actor.jpg') }}" 
                     alt="{{ $actor->actor_name }}" 
                     class="img-fluid rounded mb-3"
                     style="max-height: 400px; object-fit: cover;">
                
                <h4 class="card-title">{{ $actor->actor_name }}</h4>
                
                @if($actor->nationality)
                    <p class="text-muted mb-2">
                        <i class="fas fa-flag"></i> {{ $actor->nationality }}
                    </p>
                @endif
                
                @if($actor->birth_date)
                    <p class="text-muted mb-2">
                        <i class="fas fa-birthday-cake"></i> 
                        {{ \Carbon\Carbon::parse($actor->birth_date)->format('M d, Y') }}
                        ({{ \Carbon\Carbon::parse($actor->birth_date)->age }} years old)
                    </p>
                @endif
                
                <div class="mt-3">
                    <span class="badge bg-primary fs-6">
                        <i class="fas fa-film"></i> {{ $actor->movies->count() }} Movies/Shows
                    </span>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog"></i> Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.actors.edit', $actor) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit"></i> Edit Celebrity
                    </a>
                    <a href="{{ route('celeb.detail', $actor->actors_id) }}" 
                       class="btn btn-outline-info" target="_blank">
                        <i class="fas fa-external-link-alt"></i> View Public Page
                    </a>
                    <form action="{{ route('admin.actors.destroy', $actor) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this celebrity? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash"></i> Delete Celebrity
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8">
        @if($actor->biography)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user"></i> Biography
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $actor->biography }}</p>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-film"></i> Movies & TV Shows ({{ $actor->movies->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($actor->movies->count() > 0)
                    <div class="row">
                        @foreach($actor->movies as $movie)
                            <div class="col-md-6 mb-3">
                                <div class="card border">
                                    <div class="card-body p-3">
                                        <div class="d-flex">
                                            <img src="{{ $movie->movie_image ? asset('uploads/' . $movie->movie_image) : asset('img/placeholder-movie.jpg') }}" 
                                                 alt="{{ $movie->movie_name }}" 
                                                 class="img-thumbnail me-3" 
                                                 style="width: 80px; height: 120px; object-fit: cover;">
                                            <div class="flex-grow-1">
                                                <h6 class="card-title mb-2">
                                                    <a href="{{ route('movie.detail', $movie->movie_id) }}" 
                                                       class="text-decoration-none" target="_blank">
                                                        {{ $movie->movie_name }}
                                                    </a>
                                                </h6>
                                                
                                                @if($movie->release_date)
                                                    <p class="card-text mb-1">
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar"></i> {{ $movie->release_date }}
                                                        </small>
                                                    </p>
                                                @endif
                                                
                                                @if($movie->ratings_avg_rating)
                                                    <p class="card-text mb-1">
                                                        <small class="text-warning">
                                                            <i class="fas fa-star"></i> {{ number_format($movie->ratings_avg_rating, 1) }}/10
                                                        </small>
                                                    </p>
                                                @endif
                                                
                                                <span class="badge bg-{{ $movie->movie_type == 'isMovie' ? 'primary' : 'success' }}">
                                                    {{ $movie->movie_type == 'isMovie' ? 'Movie' : 'TV Show' }}
                                                </span>
                                                
                                                <span class="badge bg-secondary">
                                                    {{ ucfirst(str_replace('_', ' ', $movie->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-film fa-3x text-muted mb-3"></i>
                        <h5>No movies or TV shows found</h5>
                        <p class="text-muted">This celebrity doesn't have any movies or TV shows in the database yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
