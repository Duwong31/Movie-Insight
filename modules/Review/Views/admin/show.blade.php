@extends('admin.layouts.app')
@section('title', 'Review Detail')
@section('content')
<div class="container-fluid">
    <div class="page-header mb-3">
        <h1>Review Detail</h1>
        <div class="page-header-actions">
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Reviews
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Review Content</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>User:</strong> {{ $review->user->fullname ?? 'Unknown' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Submitted:</strong> {{ $review->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Status:</strong> 
                            @if($review->status == 1)
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Contains Spoilers:</strong> 
                            @if($review->has_spoiler)
                                <span class="badge bg-warning text-dark">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </div>
                    </div>

                    @if($review->rating_given)
                        <div class="mb-3">
                            <strong>User Rating:</strong> 
                            <span class="badge bg-info">{{ $review->rating_given }}/10</span>
                        </div>
                    @endif

                    <h6 class="mt-4">{{ $review->title }}</h6>
                    <div class="review-content-box p-3 bg-light border rounded">
                        {{ $review->content }}
                    </div>
                </div>
            </div>

            {{-- Admin Edit History --}}
            @if($review->admin_edited)
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-edit"></i> Admin Edit History</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Edited by:</strong> {{ $review->adminEditor->fullname ?? 'Unknown Admin' }}
                            </div>
                            <div class="col-md-6">
                                <strong>Edit Date:</strong> {{ $review->admin_edit_timestamp->format('d/m/Y H:i:s') }}
                            </div>
                        </div>
                        <div class="mt-2">
                            <strong>Reason:</strong> {{ $review->admin_edit_reason }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            {{-- Movie Information --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Movie Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img src="{{ $review->movie->movie_image ? asset('uploads/' . $review->movie->movie_image) : asset('img/placeholder-movie.jpg') }}" 
                             alt="{{ $review->movie->movie_name }}" 
                             style="width: 150px; height: 210px; object-fit: cover; border-radius: 8px;">
                    </div>
                    <h6>{{ $review->movie->movie_name }}</h6>
                    <p class="text-muted small">Release: {{ $review->movie->release_date }}</p>
                    @if(isset($review->movie->ratings_avg_rating))
                        <p class="small">
                            Average Rating: 
                            <span class="badge bg-warning text-dark">{{ number_format($review->movie->ratings_avg_rating, 1) }}/10</span>
                        </p>
                    @endif
                </div>
            </div>

            {{-- Actions --}}
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Review
                        </a>
                        
                        @if($review->status == 0)
                            <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" onsubmit="return confirm('Approve this review?');">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check"></i> Approve Review
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> Delete Review
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-header-actions {
    flex-shrink: 0;
}

.review-content-box {
    line-height: 1.6;
    font-size: 0.95rem;
}
</style>
@endsection 