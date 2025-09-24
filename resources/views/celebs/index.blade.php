@extends('layouts.app')

@section('title', 'Celebrities')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-star"></i> Celebrities</h2>
                
                <!-- Search Form -->
                <form method="GET" action="{{ route('celebs.list') }}" class="d-flex">
                    <input type="text" 
                           name="search" 
                           class="form-control me-2" 
                           placeholder="Search celebrities..." 
                           value="{{ $search ?? '' }}"
                           style="width: 300px;">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            @if($search)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    Showing results for: <strong>{{ $search }}</strong>
                    <a href="{{ route('celebs.list') }}" class="btn btn-sm btn-outline-secondary ms-2">Clear</a>
                </div>
            @endif

            <div class="row">
                @forelse($actors as $actor)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card h-100">
                            <div class="card-img-container" style="height: 300px; overflow: hidden;">
                                <img src="{{ $actor->actors_image ? asset('uploads/' . $actor->actors_image) : asset('img/placeholder-actor.jpg') }}" 
                                     class="card-img-top" 
                                     alt="{{ $actor->actor_name }}"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title">{{ $actor->actor_name }}</h5>
                                <a href="{{ route('celeb.detail', $actor->actors_id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                            <h4>No celebrities found</h4>
                            @if($search)
                                <p class="text-muted">Try adjusting your search terms</p>
                                <a href="{{ route('celebs.list') }}" class="btn btn-primary">View All Celebrities</a>
                            @else
                                <p class="text-muted">No celebrities available at the moment</p>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($actors->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $actors->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
