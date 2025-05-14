@extends('admin.layouts.app')

@section('title', $title ?? 'Manage TV Shows')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ $title ?? 'Manage TV Shows' }}</h1>
        <a href="{{ route('admin.tvshows.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Add New TV Show
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
                    @forelse ($tvshows as $tvshow)
                        <tr>
                            <td>{{ $tvshow->movie_id }}</td>
                            <td>
                                @if ($tvshow->movie_image)
                                    <img src="{{ asset('uploads/' . $tvshow->movie_image) }}" alt="{{ $tvshow->movie_name }}" width="50" height="75" style="object-fit: cover;">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $tvshow->movie_name }}</td>
                            <td>{{ $tvshow->release_date }}</td>
                            <td>
                                @php
                                    $statusClass = '';
                                    $statusText = ucwords(str_replace('_', ' ', $tvshow->status)); // Xử lý text trước

                                    if ($tvshow->status == 'in_theaters') {
                                        $statusClass = 'status-in-theaters';
                                    } elseif ($tvshow->status == 'streaming') {
                                        $statusClass = 'status-streaming';
                                    } elseif ($tvshow->status == 'coming soon') {
                                        $statusClass = 'status-coming-soon';
                                        // $statusText = 'Coming Soon'; // Đảm bảo viết hoa đúng nếu cần
                                    }
                                @endphp
                                <span class="badge status-badge {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.tvshows.edit', $tvshow->movie_id) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.tvshows.destroy', $tvshow->movie_id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this tvshow?');">
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
                            <td colspan="7" class="text-center">No tvshows found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($tvshows->hasPages())
        <div class="custom-pagination-container">
            {{ $tvshows->links('vendor.pagination.custom_pagination') }} {{-- Bootstrap 4 pagination styling mặc định --}}
        </div>
        @endif
    </div>
</div>
@endsection