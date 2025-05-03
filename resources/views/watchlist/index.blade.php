@extends('layouts.app') {{-- Hoặc layout chính của bạn --}}

@section('title', 'Your Watchlist') {{-- Đặt tiêu đề trang --}}

@push('styles')
    {{-- Bạn có thể muốn đổi tên file này nếu CSS áp dụng cho cả watchlist items --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/watchlist.css') }}"> --}}
    {{-- Hoặc giữ nguyên nếu nó chứa style cho .watchlist-container, .watchlist-items, .watchlist-item --}}
     <link rel="stylesheet" href="{{ asset('css/watchlist.css') }}">
@endpush

@section('content')
<div class="watchlist-container">
    <h1 class="watchlist-title">Your Watchlist</h1>

    {{-- XÓA THẺ DIV GÂY LỖI Ở DÒNG 13 --}}
    {{-- Thay vào đó, đây là container cho các items --}}
    <div class="watchlist-items"> {{-- Đổi class thành watchlist-items --}}
        @if ($movies->isNotEmpty())
            @foreach ($movies as $movie)
                {{-- Đây là div cho MỖI item trong watchlist --}}
                {{-- Sử dụng movie_id cho ID để nhất quán --}}
                <div id="watchlist-item-{{ $movie->movie_id }}" class="watchlist-item">
                    <div class="watchlist-movie-image">
                        <a href="{{ route('movie.detail', ['id' => $movie->movie_id]) }}">
                             {{-- Sử dụng asset() hoặc Storage::url() tùy cách lưu ảnh --}}
                             {{-- Ví dụ nếu ảnh trong public/uploads/ --}}
                             <img src="{{ $movie->movie_image ? asset('uploads/' . $movie->movie_image) : asset('img/placeholder-movie.jpg') }}" alt="{{ $movie->movie_name }}">
                        </a>
                    </div>
                    <div class="watchlist-movie-info">
                        <h2><a href="{{ route('movie.detail', ['id' => $movie->movie_id]) }}" style="color: inherit; text-decoration:none;">{{ $movie->movie_name }}</a></h2>
                        <div class="movie-meta">
                            <span class="movie-meta-star">★ {{ number_format($movie->ratings_avg_rating ?? 0, 1) }}/10</span>
                            <span>{{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }}</span> {{-- Chỉ hiển thị năm --}}
                        </div>
                        <p class="movie-description">{{ $movie->movie_desc }}</p>
                    </div>
                    <button class="remove-button"
                            title="Remove from Watchlist"
                            data-movie-id="{{ $movie->movie_id }}"
                            data-remove-url="{{ route('api.watchlist.remove') }}">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </div> {{-- Kết thúc div.watchlist-item bên trong loop --}}
            @endforeach
        @else
            <div class="empty-watchlist">
                <p>Your watchlist is empty.</p>
                <p>Start adding movies <a href="{{ route('movies.list') }}">here</a>!</p>
            </div>
        @endif
    </div> {{-- Kết thúc div.watchlist-items --}}
</div>
@endsection

{{-- Phần @push('scripts') giữ nguyên --}}
@push('scripts')
<script>
// Javascript của bạn ở đây (đảm bảo nó tìm đúng id="watchlist-item-xxx")
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = window.config.csrfToken; // Lấy từ config toàn cục

    document.querySelectorAll('.remove-button[data-remove-url]').forEach(button => {
        button.addEventListener('click', function() {
            const movieId = this.dataset.movieId;
            const removeUrl = this.dataset.removeUrl;
            // Javascript sẽ tìm đúng ID này vì giờ nó nằm trong vòng lặp
            const watchlistItem = document.getElementById(`watchlist-item-${movieId}`);

            // ... (phần còn lại của JS xử lý fetch) ...
            // Đảm bảo fetch dùng removeUrl và movieId lấy từ nút
             if (!movieId || !removeUrl || !watchlistItem) {
                console.error('Missing data for removal. MovieID:', movieId, 'RemoveURL:', removeUrl, 'WatchlistItem:', watchlistItem);
                alert('Could not remove movie. Data missing. Please try again.');
                return;
            }

            if (confirm('Are you sure you want to remove this movie from your watchlist?')) {
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                fetch(removeUrl, { // Dùng removeUrl từ data attribute
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ movie_id: movieId }) // Dùng movieId từ data attribute
                })
                .then(response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json().then(data => ({ status: response.status, body: data }));
                    } else {
                        return response.text().then(text => {
                            throw new Error(`Unexpected response: ${response.status} - ${text}`);
                        });
                    }
                })
                .then(({ status, body }) => {
                    if (body.success) {
                        watchlistItem.style.transition = 'opacity 0.5s ease';
                        watchlistItem.style.opacity = '0';
                        setTimeout(() => {
                            watchlistItem.remove();
                            if (document.querySelectorAll('.watchlist-item').length === 0) {
                                const container = document.querySelector('.watchlist-items');
                                if(container) {
                                    // Sử dụng route() của JS nếu bạn đã truyền nó qua config
                                    // Hoặc hardcode URL tạm thời
                                    const moviesListUrl = window.config?.urls?.moviesList || '/movies'; // Lấy từ config nếu có
                                    container.innerHTML = `
                                    <div class="empty-watchlist">
                                        <p>Your watchlist is empty.</p>
                                        <p>Start adding movies <a href="${moviesListUrl}">here</a>!</p>
                                    </div>`;
                                }
                            }
                        }, 500);
                    } else {
                        alert(body.message || 'Failed to remove movie. Please try again.');
                        this.disabled = false;
                        this.innerHTML = '<i class="fa-solid fa-trash-can"></i>';
                    }
                })
                .catch(error => {
                    console.error('Error removing from watchlist:', error);
                    alert('An error occurred while removing the movie. Please check the console and try again.');
                    this.disabled = false;
                    this.innerHTML = '<i class="fa-solid fa-trash-can"></i>';
                });
            }
        });
    });

    // Phần JS cho nút .add-watchlist-btn (nếu cần trên trang này) giữ nguyên
});
</script>
@endpush