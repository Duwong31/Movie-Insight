@extends('admin.layouts.app')

@section('title', $title ?? 'Manage Reviews')

@section('content')
<div class="container-fluid">
    <div class="page-header mb-3">
        <h1>{{ $title ?? 'Manage Reviews' }}</h1>
        <div class="page-header-actions">
            <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Review
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Filter Form --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Filters & Search</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Approved</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="user_id" class="form-label">User</label>
                    <select name="user_id" id="user_id" class="form-select">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->fullname }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="movie_id" class="form-label">Movie</label>
                    <select name="movie_id" id="movie_id" class="form-select">
                        <option value="">All Movies</option>
                        @foreach($movies as $movie)
                            <option value="{{ $movie->movie_id }}" {{ request('movie_id') == $movie->movie_id ? 'selected' : '' }}>
                                {{ $movie->movie_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="admin_edited" class="form-label">Admin Edited</label>
                    <select name="admin_edited" id="admin_edited" class="form-select">
                        <option value="">All</option>
                        <option value="1" {{ request('admin_edited') === '1' ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ request('admin_edited') === '0' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="search" class="form-label">Search Title/Content</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           placeholder="Search in title or content..." value="{{ request('search') }}">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card-table-container">
        <div class="table-responsive-custom">
            <table class="table table-striped table-hover table-movies mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>User</th>
                        <th>Movie</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Admin Edited</th>
                        <th>Submitted At</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>{{ $review->id }}</td>
                            <td>
                                <a href="{{ route('admin.reviews.show', $review->id) }}" title="View: {{ Str::limit($review->title, 50) }}">
                                    {{ Str::limit($review->title, 50) }}
                                </a>
                                @if($review->has_spoiler)
                                    <span class="badge bg-warning text-dark">Spoiler</span>
                                @endif
                            </td>
                            <td>{{ $review->user->fullname ?? 'Unknown User' }}</td>
                            <td>{{ $review->movie->movie_name ?? 'Unknown Movie' }}</td>
                            <td>
                                @if($review->rating_given)
                                    <span class="badge bg-info">{{ $review->rating_given }}/10</span>
                                @else
                                    <span class="text-muted">No rating</span>
                                @endif
                            </td>
                            <td>
                                @if($review->status == 1)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($review->admin_edited)
                                    <span class="badge bg-info" title="Edited by {{ $review->adminEditor->fullname ?? 'Admin' }} on {{ $review->admin_edit_timestamp->format('d/m/Y H:i') }}">
                                        <i class="fas fa-edit"></i> Yes
                                    </span>
                                @else
                                    <span class="text-muted">No</span>
                                @endif
                            </td>
                            <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-action btn-action-edit" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-action btn-action-edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($review->status == 0)
                                    <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline-block ms-1" onsubmit="return confirm('Approve this review?');">
                                        @csrf
                                        <button type="submit" class="btn btn-action btn-success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="d-inline-block ms-1" onsubmit="return confirm('Delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-action btn-action-delete" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">No reviews found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($reviews->hasPages())
            <div class="custom-pagination-container">
                {{ $reviews->appends(request()->query())->links('vendor.pagination.custom_pagination') }}
            </div>
        @endif
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

.badge {
    font-size: 0.75em;
}
</style>
@endsection