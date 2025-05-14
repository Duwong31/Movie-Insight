@extends('admin.layouts.app')

@section('title', $title ?? 'Manage Movies')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ $title ?? 'Manage Movies' }}</h1>
        <a href="{{ route('admin.movies.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Add New Movie
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card-table-container">
        <div class="card-body">
            <table class="table table-striped table-hover table-movies mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Year</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movies as $movie)
                        <tr>
                            <td>{{ $movie->movie_id }}</td>
                            <td>
                                @if ($movie->movie_image)
                                    <img src="{{ asset('uploads/' . $movie->movie_image) }}" alt="{{ $movie->movie_name }}" width="50" height="75" style="object-fit: cover;">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $movie->movie_name }}</td>
                            <td>{{ $movie->release_date }}</td>
                            <td>
                                @php
                                    $statusClass = '';
                                    $statusText = ucwords(str_replace('_', ' ', $movie->status)); // Xử lý text trước

                                    if ($movie->status == 'in_theaters') {
                                        $statusClass = 'status-in-theaters';
                                    } elseif ($movie->status == 'streaming') {
                                        $statusClass = 'status-streaming';
                                    } elseif ($movie->status == 'coming soon') {
                                        $statusClass = 'status-coming-soon';
                                        // $statusText = 'Coming Soon'; // Đảm bảo viết hoa đúng nếu cần
                                    }
                                @endphp
                                <span class="badge status-badge {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.movies.edit', $movie->movie_id) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.movies.destroy', $movie->movie_id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this movie?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No movies found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($movies->hasPages())
        <div class="custom-pagination-container">
            {{ $movies->links('vendor.pagination.custom_pagination') }} {{-- Bootstrap 4 pagination styling mặc định --}}
        </div>
        @endif
    </div>
</div>
@endsection