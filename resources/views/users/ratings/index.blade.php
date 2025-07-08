@extends('layouts.app')

@section('title', 'My Rated Movies - Movie Insight')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/user-ratings.css') }}"> {{-- File CSS riêng cho trang này --}}
@endpush

@section('content')
<div class="user-ratings-page">
    <div class="container">
        <div class="page-header">
            <h1>My Rated Movies</h1>
            <p>A list of all the movies and TV shows you've rated.</p>
        </div>

        @if($ratings->isEmpty())
            <div class="empty-state">
                <i class="fas fa-star-half-alt empty-icon"></i>
                <h2>You haven't rated any movies yet.</h2>
                <p>Start exploring and share your opinions!</p>
                <a href="{{ route('movies.list') }}" class="btn btn-primary">Browse Movies</a>
            </div>
        @else
            <div class="ratings-list">
                @foreach ($ratings as $rating)
                    @php
                        // $item có thể là movie hoặc tv show, tùy thuộc vào cấu trúc model của bạn
                        // Ở đây, chúng ta giả định $rating->movie là đối tượng Movie
                        $movie = $rating->movie;
                    @endphp
                    @if($movie) {{-- Kiểm tra xem movie có tồn tại không --}}
                        <div class="rating-item">
                            <div class="rating-item-poster">
                                <a href="{{ route('movie.detail', ['id' => $movie->movie_id]) }}">
                                    <img src="{{ $movie->movie_image ? asset('uploads/' . $movie->movie_image) : asset('img/placeholder-movie.jpg') }}" alt="{{ $movie->movie_name }}">
                                </a>
                            </div>
                            <div class="rating-item-details">
                                <h3>
                                    <a href="{{ route('movie.detail', ['id' => $movie->movie_id]) }}">{{ $movie->movie_name }}</a>
                                    @if($movie->release_date)
                                        <span class="release-year">({{ \Carbon\Carbon::parse($movie->release_date)->year }})</span>
                                    @endif
                                </h3>
                                <p class="item-type">
                                    {{-- Bạn có thể thêm cột 'type' (movie/tv_show) trong bảng movies/ratings --}}
                                    {{-- Hoặc kiểm tra dựa trên model nếu bạn có model riêng cho TV Show --}}
                                    Movie
                                </p>
                                <div class="your-rating-display">
                                    Your Rating:
                                    <span class="stars">★ {{ round($rating->rating) }}</span> 
                                </div>
                                @if($rating->updated_at) {{-- Hoặc created_at nếu bạn không có updated_at cho ratings --}}
                                <p class="rated-date">
                                    Rated on: {{ $rating->updated_at->format('M d, Y') }}
                                </p>
                                @endif

                                {{-- Nút để chỉnh sửa hoặc xóa rating nhanh (Tùy chọn) --}}
                                
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- Hiển thị link phân trang --}}
            <div class="pagination-links">
                {{ $ratings->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
    {{-- Nhúng script.js và ajax.js nếu các hàm rating được dùng chung --}}
    {{-- <script src="{{ asset('js/script.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/ajax.js') }}"></script> --}}
    <script>
        // Script riêng cho trang này (nếu cần, ví dụ xử lý nút "Change Rating" mở form)
        document.addEventListener('DOMContentLoaded', function() {
            // Logic mở/đóng form rating ngay trên danh sách này
            // Code này sẽ tương tự như logic trong script.js cho các form rating khác
            // Bạn có thể tái sử dụng hoặc viết lại cho context này
            // Ví dụ:
            document.querySelectorAll('.rate-link-on-list').forEach(button => {
                button.addEventListener('click', function() {
                    const movieId = this.dataset.movieId;
                    const form = document.querySelector(`.rating-form-on-list[data-movie-id="${movieId}"]`);
                    if (form) {
                        // Ẩn các form khác đang mở
                        document.querySelectorAll('.rating-form-on-list').forEach(f => {
                            if (f !== form) f.style.display = 'none';
                        });
                        // Toggle form hiện tại
                        form.style.display = form.style.display === 'none' ? 'block' : 'none';
                        // Khởi tạo sao cho form (nếu cần - copy từ script.js)
                        const currentRating = parseFloat(form.dataset.currentRating || '0');
                        form.dataset.selectedValue = currentRating > 0 ? currentRating.toString() : "0";
                        if (typeof updateStarsAndDisplay === 'function') { // Giả sử hàm này global
                           updateStarsAndDisplay(form, currentRating, false);
                        } else {
                            console.warn('updateStarsAndDisplay function not found. Stars might not initialize correctly.');
                        }
                    }
                });
            });

            // Xử lý nút close trong form
             document.querySelectorAll('.rating-form-on-list .close-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    this.closest('.rating-form-on-list').style.display = 'none';
                });
            });

            // Event delegation cho các nút submit/remove/star trong các form rating-form-on-list
            // Cần đảm bảo các hàm submitRating, removeRating, updateStarsAndDisplay từ script.js/ajax.js có thể truy cập được
            // hoặc bạn copy/adapt logic đó vào đây.
            document.querySelectorAll('.rating-form-on-list').forEach(form => {
                const stars = form.querySelectorAll('.star');
                const submitButton = form.querySelector('.btn-submit-rating');
                const removeButtonInForm = form.querySelector('.btn-remove-rating'); // Đổi tên để tránh trùng

                stars.forEach(star => {
                    star.addEventListener('mouseover', function() {
                        const hoverValue = parseInt(this.dataset.value);
                        if (typeof updateStarsAndDisplay === 'function') updateStarsAndDisplay(form, hoverValue, true);
                    });
                    star.addEventListener('mouseout', function() {
                        const valueToShow = parseInt(form.dataset.selectedValue || form.dataset.currentRating || '0');
                        if (typeof updateStarsAndDisplay === 'function') updateStarsAndDisplay(form, valueToShow, false);
                    });
                    star.addEventListener('click', function() {
                        const ratingValue = parseInt(this.dataset.value);
                        form.dataset.selectedValue = ratingValue.toString();
                        if (typeof updateStarsAndDisplay === 'function') updateStarsAndDisplay(form, ratingValue, false);
                        if (submitButton) submitButton.disabled = false;
                    });
                });

                if (submitButton) {
                    submitButton.addEventListener('click', function() {
                        const movieId = form.dataset.movieId;
                        const selectedValue = parseInt(form.dataset.selectedValue || '0');
                        if (selectedValue > 0 && typeof submitRating === 'function') {
                            this.disabled = true;
                            submitRating(movieId, selectedValue);
                            // Sau khi submit, bạn có thể muốn cập nhật text rating trên item hoặc reload phần đó
                        }
                    });
                }

                if (removeButtonInForm) {
                    removeButtonInForm.addEventListener('click', function() {
                        const movieId = form.dataset.movieId;
                        if (confirm('Are you sure you want to remove your rating for this movie?') && typeof removeRating === 'function') {
                            removeRating(movieId);
                            // Sau khi xóa, bạn có thể muốn ẩn item này hoặc reload
                            form.style.display = 'none'; // Ẩn form
                            // Tìm và cập nhật UI cho item tương ứng
                            const ratingItem = this.closest('.rating-item');
                            if(ratingItem) {
                                const yourRatingDisplay = ratingItem.querySelector('.your-rating-display');
                                if(yourRatingDisplay) yourRatingDisplay.innerHTML = 'Your Rating: <span class="stars">Not Rated</span>';
                                // Có thể xóa luôn item khỏi DOM hoặc hiển thị "Not Rated"
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush