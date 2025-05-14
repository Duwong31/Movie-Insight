@extends('admin.layouts.app')

@section('title', $title ?? 'Pending Reviews') {{-- Sử dụng biến $title nếu được truyền từ controller --}}

@section('content')
<div class="container-fluid"> {{-- Thêm container-fluid cho layout rộng hơn --}}
    <div class="page-header mb-3"> {{-- Thêm page-header cho tiêu đề --}}
        <h1>{{ $title ?? 'Pending Reviews' }}</h1>
        {{-- Có thể thêm nút hoặc thông tin khác ở đây nếu cần --}}
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card-table-container"> {{-- Sử dụng class card-table-container để đồng bộ style --}}
        {{-- Không cần card-header riêng nữa nếu đã có .page-header --}}
        {{-- <div class="card-header bg-danger text-white">
            <h4 class="mb-0">Pending Reviews</h4>
        </div> --}}
        <div class="table-responsive-custom"> {{-- Cho phép cuộn ngang --}}
            <table class="table table-striped table-hover table-movies mb-0"> {{-- Sử dụng class table-movies để đồng bộ style --}}
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>User</th>
                        <th>Movie</th>
                        <th>Submitted At</th>
                        <th class="text-end">Action</th>
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
                            </td>
                            <td>{{ $review->user->fullname ?? 'Unknown User' }}</td>
                            <td>{{ $review->movie->movie_name ?? 'Unknown Movie' }}</td>
                            <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-action btn-action-edit" title="View & Approve">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                {{-- Nút Approve có thể đặt ở trang show, hoặc ở đây nếu muốn approve nhanh --}}
                                {{-- <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline-block ms-1" onsubmit="return confirm('Approve this review?');">
                                    @csrf
                                    <button type="submit" class="btn btn-action btn-success" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form> --}}
                    
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No pending reviews.</td> {{-- Cập nhật colspan --}}
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($reviews->hasPages()) {{-- Chỉ hiển thị nếu có nhiều trang --}}
            <div class="custom-pagination-container">
                {{ $reviews->links('vendor.pagination.custom_pagination') }}
            </div>
        @endif
    </div> {{-- End card-table-container --}}
</div> {{-- End container-fluid --}}
@endsection