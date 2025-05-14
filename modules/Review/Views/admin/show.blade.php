@extends('admin.layouts.app')
@section('title', 'Review Detail')
@section('content')
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Review Detail</h4>
    </div>
    <div class="card-body">
        <h5 class="mb-3">Movie Information</h5>
        <div class="d-flex align-items-center mb-3">
            <img src="{{ $review->movie->movie_image ? asset('uploads/' . $review->movie->movie_image) : asset('img/placeholder-movie.jpg') }}" alt="{{ $review->movie->movie_name }}" style="width: 100px; height: 140px; object-fit: cover; border-radius: 8px; margin-right: 20px;">
            <div>
                <h5 class="mb-1">{{ $review->movie->movie_name }}</h5>
                <div>Release: {{ $review->movie->release_date }}</div>
                <div>Avg. Rating: <span class="badge bg-warning text-dark">{{ number_format($review->movie->ratings_avg_rating ?? 0, 1) }}/10</span></div>
            </div>
        </div>
        <hr>
        <h5 class="mb-3">Review Content</h5>
        <div class="mb-2"><strong>User:</strong> {{ $review->user->fullname ?? 'Unknown' }}</div>
        <div class="mb-2"><strong>Title:</strong> {{ $review->title }}</div>
        <div class="mb-2"><strong>Submitted At:</strong> {{ $review->created_at->format('d/m/Y H:i') }}</div>
        <div class="mb-2"><strong>Spoiler:</strong> {{ $review->has_spoiler ? 'Yes' : 'No' }}</div>
        <div class="mb-3"><strong>Content:</strong><br><div class="review-content-box">{{ $review->content }}</div></div>
        <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">Approve</button>
        </form>
        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this review?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary ms-2">Back to List</a>
    </div>
</div>
@endsection 