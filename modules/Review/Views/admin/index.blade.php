@extends('admin.layouts.app')
@section('title', 'Pending Reviews')
@section('content')
<div class="card">
    <div class="card-header bg-danger text-white">
        <h4 class="mb-0">Pending Reviews</h4>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <table class="table table-admin-dark table-hover">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>User</th>
                    <th>Movie</th>
                    <th>Submitted At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td>{{ $review->title }}</td>
                        <td>{{ $review->user->fullname ?? 'Unknown' }}</td>
                        <td>{{ $review->movie->movie_name ?? 'Unknown' }}</td>
                        <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">No pending reviews.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
