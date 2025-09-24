@extends('layouts.app')

@section('title', 'Profile - ' . $user->fullname)

@push('styles')
<link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-user"></i> Profile</h3>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="profile-avatar mb-3">
                                @if($user->hasProfileImage())
                                    <img src="{{ $user->profile_image_url }}" 
                                         alt="{{ $user->fullname }}" 
                                         class="rounded-circle img-thumbnail"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <i class="fas fa-user-circle fa-5x text-muted"></i>
                                @endif
                            </div>
                            <h4>{{ $user->fullname }}</h4>
                            <p class="text-muted">{{ $user->email }}</p>
                            @if($user->phone)
                                <p class="text-muted"><i class="fas fa-phone"></i> {{ $user->phone }}</p>
                            @endif
                            <p class="text-muted">
                                <small>Thành viên từ {{ $user->created_at->format('M Y') }}</small>
                            </p>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="stat-card text-center p-3 mb-3 bg-light rounded">
                                        <h3 class="text-primary">{{ $ratingsCount }}</h3>
                                        <p class="mb-0">Ratings</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="stat-card text-center p-3 mb-3 bg-light rounded">
                                        <h3 class="text-success">{{ $watchlistCount }}</h3>
                                        <p class="mb-0">Watchlist</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="action-buttons">
                                <a href="{{ route('settings.account') }}" class="btn btn-primary">
                                    <i class="fas fa-cog"></i> Account Settings
                                </a>
                                <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#changeProfileImageModal">
                                    <i class="fas fa-camera"></i> Change Photo
                                </button>
                                <a href="{{ route('ratings.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-star"></i> My Ratings
                                </a>
                                <a href="{{ route('watchlist.index') }}" class="btn btn-outline-success">
                                    <i class="fas fa-bookmark"></i> My Watchlist
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($recentRatings->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5><i class="fas fa-star"></i> Recent Ratings</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($recentRatings as $rating)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <img src="{{ $rating->movie->movie_image ? asset('uploads/' . $rating->movie->movie_image) : asset('img/placeholder-movie.jpg') }}" 
                                     alt="{{ $rating->movie->movie_name }}" 
                                     class="img-thumbnail me-3" 
                                     style="width: 60px; height: 90px; object-fit: cover;">
                                <div>
                                    <h6><a href="{{ route('movie.detail', $rating->movie->movie_id) }}">{{ $rating->movie->movie_name }}</a></h6>
                                    <p class="mb-1">
                                        <span class="text-warning">★ {{ $rating->rating }}/10</span>
                                    </p>
                                    <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center">
                        <a href="{{ route('ratings.index') }}" class="btn btn-outline-primary btn-sm">View All Ratings</a>
                    </div>
                </div>
            </div>
            @endif

            @if($recentWatchlist->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5><i class="fas fa-bookmark"></i> Recent Watchlist</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($recentWatchlist as $movie)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <img src="{{ $movie->movie_image ? asset('uploads/' . $movie->movie_image) : asset('img/placeholder-movie.jpg') }}" 
                                     alt="{{ $movie->movie_name }}" 
                                     class="img-thumbnail me-3" 
                                     style="width: 60px; height: 90px; object-fit: cover;">
                                <div>
                                    <h6><a href="{{ route('movie.detail', $movie->movie_id) }}">{{ $movie->movie_name }}</a></h6>
                                    <p class="mb-1">
                                        @if($movie->ratings_avg_rating)
                                            <span class="text-warning">★ {{ number_format($movie->ratings_avg_rating, 1) }}/10</span>
                                        @else
                                            <span class="text-muted">No ratings yet</span>
                                        @endif
                                    </p>
                                    <small class="text-muted">Added 
                                        @if($movie->pivot->created_at instanceof \Carbon\Carbon)
                                            {{ $movie->pivot->created_at->diffForHumans() }}
                                        @else
                                            {{ \Carbon\Carbon::parse($movie->pivot->created_at)->diffForHumans() }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center">
                        <a href="{{ route('watchlist.index') }}" class="btn btn-outline-success btn-sm">View All Watchlist</a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Change Profile Image Modal -->
<div class="modal fade" id="changeProfileImageModal" tabindex="-1" aria-labelledby="changeProfileImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeProfileImageModalLabel">
                    <i class="fas fa-camera"></i> Change Profile Image
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.update-image') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div class="current-image">
                            @if($user->hasProfileImage())
                                <img src="{{ $user->profile_image_url }}" 
                                     alt="{{ $user->fullname }}" 
                                     class="rounded-circle img-thumbnail"
                                     style="width: 120px; height: 120px; object-fit: cover;"
                                     id="modal-profile-preview">
                            @else
                                <i class="fas fa-user-circle fa-5x text-muted" id="modal-profile-preview"></i>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="modal_profile_image" class="form-label">Choose New Image</label>
                        <input type="file" 
                               class="form-control" 
                               id="modal_profile_image" 
                               name="profile_image" 
                               accept="image/*"
                               onchange="previewModalImage(this)">
                        <small class="form-text text-muted">Choose JPG, PNG or GIF (max 2MB)</small>
                    </div>
                    
                    @if($user->hasProfileImage())
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="modal_remove_image" name="remove_profile_image" value="1">
                            <label class="form-check-label" for="modal_remove_image">
                                Remove current profile image
                            </label>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewModalImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const preview = document.getElementById('modal-profile-preview');
        
        reader.onload = function(e) {
            // Replace the icon/image with new preview
            preview.outerHTML = '<img src="' + e.target.result + '" alt="Profile Preview" class="rounded-circle img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;" id="modal-profile-preview">';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
