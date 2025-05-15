@extends('admin.layouts.app') {{-- Sử dụng layout admin của bạn --}}

@section('title', 'Manage Genres')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Manage Genres</h1>
    <a href="{{ route('admin.genres.create') }}" class="btn btn-primary">
        <i class="fa fa-plus"></i> Add New Genre
    </a>
</div>

{{-- Thông báo thành công/lỗi --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Form tìm kiếm --}}
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.genres.index') }}" class="row g-3 align-items-center">
            <div class="col-md-10">
                <input type="text" name="search" class="form-control" placeholder="Search by genre name..." value="{{ $search ?? '' }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-info w-100"><i class="fa fa-search"></i> Search</button>
            </div>
        </form>
    </div>
</div>

<div class="card genre-card-table">
    <div class="card-header">
        Genre List
    </div>
    <div class="card-body">
        @if($genres->isEmpty())
            <div class="alert alert-info">No genres found.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Movies Count</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($genres as $genre)
                        <tr>
                            <th scope="row">{{ $genre->genres_id }}</th>
                            <td>{{ $genre->genres_name }}</td>
                            <td>{{ $genre->movies_count ?? $genre->movies()->count() }}</td> {{-- Giả sử bạn có thể eager load movies_count --}}
                            <td>
                                <a href="{{ route('admin.genres.edit', $genre) }}" class="btn-action btn-action-edit me-1" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <form id="delete-genre-{{ $genre->genres_id }}"
                                        action="{{ route('admin.genres.destroy', $genre) }}"
                                        method="POST" style="display: inline-block;"> {{-- Cho form inline --}}
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action btn-action-delete" title="Delete"
                                            onclick="if(confirm('Are you sure you want to delete this genre: {{ $genre->genres_name }}? This action cannot be undone.')) { this.closest('form').submit(); }">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- Phân trang --}}
            <div class="custom-pagination-container">
                {{ $genres->links('vendor.pagination.custom_pagination') }} {{-- Bootstrap 4 pagination styling mặc định --}}
            </div>
        @endif
    </div>
</div>
@endsection