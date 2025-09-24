@extends('layouts.app')

@section('title', 'Movie News')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-newspaper"></i> Movie News</h2>
                
                <!-- Search Form -->
                <form method="GET" action="{{ route('news.index') }}" class="d-flex">
                    <input type="text" 
                           name="search" 
                           class="form-control me-2" 
                           placeholder="Search news..." 
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
                    <a href="{{ route('news.index') }}" class="btn btn-sm btn-outline-secondary ms-2">Clear</a>
                </div>
            @endif

            <div class="row">
                @forelse($news as $newsItem)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100">
                            @if($newsItem->image_url)
                                <div class="card-img-container" style="height: 200px; overflow: hidden;">
                                    <img src="{{ asset('uploads/' . $newsItem->image_url) }}" 
                                         class="card-img-top" 
                                         alt="{{ $newsItem->title }}"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            @endif
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $newsItem->title }}</h5>
                                @if($newsItem->content)
                                    <p class="card-text flex-grow-1">
                                        {{ Str::limit(strip_tags($newsItem->content), 150) }}
                                    </p>
                                @endif
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> 
                                            {{ $newsItem->created_at->diffForHumans() }}
                                        </small>
                                        @if($newsItem->source_url)
                                            <a href="{{ $newsItem->source_url }}" 
                                               target="_blank" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-external-link-alt"></i> Read More
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                            <h4>No news found</h4>
                            @if($search)
                                <p class="text-muted">Try adjusting your search terms</p>
                                <a href="{{ route('news.index') }}" class="btn btn-primary">View All News</a>
                            @else
                                <p class="text-muted">No news available at the moment</p>
                            @endif
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($news->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $news->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
