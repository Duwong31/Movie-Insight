{{-- resources/views/movies/show.blade.php --}}
@extends('layouts.app') {{-- Kế thừa layout chính --}}

{{-- Đặt tiêu đề trang động --}}
@section('title', $movie->movie_name . ' - Movie Insight')

@section('content')
    {{-- Không cần thẻ <body> và include header/footer ở đây vì đã có trong layout --}}
    {{-- Biến $isLoggedIn, $loggedInUserId đã có sẵn từ header hoặc AppServiceProvider nếu cần --}}
    {{-- Biến $userRating được truyền từ Controller --}}

    <div class="movie-detail-body">
        <div class="movie-detail-container">
            <!-- Header với Poster và Thông tin phim -->
            <div class="movie-header">
                <div class="movie-info">
                    <h1>{{ $movie->movie_name }}</h1>
                    <p class="release-year">
                        {{-- Truy cập thuộc tính của $movie object --}}
                        {{ $movie->release_date }} {{-- Format năm nếu cần --}}
                         • {{ $movie->duration }}
                    </p>
                </div>
                <div class="rating-section">
                    <div class="rating">
                        <div>MI Rating</div>
                        {{-- Sử dụng cột ratings_avg_rating được tính bởi withAvg --}}
                        <div class="stars">★{{ number_format($movie->ratings_avg_rating ?? 0, 1) }}/10</div>
                    </div>
                    <div>
                        <!-- Your Rating -->
                        <div class="your-rating">
                            <div>Your Rating</div>
                             {{-- Nút rate, sử dụng $userRating từ Controller --}}
                             {{-- data-movie-id dùng $movie->movie_id (hoặc $movie->id nếu là khóa chính chuẩn) --}}
                            <button class="rate-link" data-movie-id="{{ $movie->movie_id }}">
                                {!! $userRating ? '★ ' . round($userRating) : '☆ Rate' !!} {{-- Dùng {!! !!} nếu muốn hiển thị HTML entity --}}
                            </button>
                        </div>

                        <!-- Rating Form (ẩn) - Giữ nguyên cấu trúc HTML và data-attribute -->
                        <div class="rating-form" data-movie-id="{{ $movie->movie_id }}" style="display: none;">
                            <span class="close-btn">X</span> {{-- Đảm bảo JS xử lý class này --}}
                            <div class="stars">
                                @for ($i = 1; $i <= 10; $i++)
                                    {{-- Thêm class 'selected' nếu $userRating tồn tại và >= $i --}}
                                    <span class="star {{ $userRating && $i <= round($userRating) ? 'selected' : '' }}" data-value="{{ $i }}">★</span>
                                @endfor
                            </div>
                             {{-- Hiện nút remove nếu đã có $userRating --}}
                            <button class="remove-rating-btn" data-movie-id="{{ $movie->movie_id }}" style="display: {{ $userRating ? 'block' : 'none' }};">Remove Rating</button> {{-- Thêm data-movie-id --}}
                        </div>

                    </div>
                </div>
            </div>

            <!-- Poster và Trailer -->
            <div class="movie-poster-trailer">
                 {{-- Sử dụng helper asset() cho ảnh --}}
                <img src="{{ $movie->movie_image ? asset('uploads/' . $movie->movie_image) : asset('img/placeholder-movie.jpg') }}" alt="{{ $movie->movie_name }}" class="movie-poster">
                 {{-- Trailer URL --}}
                <iframe src="{{ $movie->trailer_url }}" title="Trailer" class="movie-trailer" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

            <!-- Thể loại và Watchlist -->
            <div class="genres-watchlist">
                <div class="genres">
                    {{-- Chỉ sử dụng khối này nếu đã dùng with('genres') trong Controller --}}
                    @if($movie->relationLoaded('genres') && $movie->genres->isNotEmpty())
                        @foreach ($movie->genres as $genre)
                            {{-- Đảm bảo tên cột trong bảng genres là 'genres_id' và 'genres_name' --}}
                            {{-- Hoặc điều chỉnh $genre->id, $genre->name nếu tên cột khác --}}
                            <a href="{{ route('genres.show', ['id' => $genre->genres_id]) ?? '#' }}" class="genre-tag">
                                <span>{{ $genre->genres_name }}</span>
                            </a>
                        @endforeach
                    @else
                        {{-- Khối này chỉ hiển thị nếu phim thực sự không có thể loại nào trong bảng movie_genre --}}
                        <span>N/A</span>
                    @endif
                    {{-- XÓA KHỐI ELSEIF NÀY ĐI --}}
                    {{-- @elseif(!$genresFromIds->isEmpty())
                        @foreach ($genresFromIds as $genre)
                            <a href="{{ route('genres.show', ['id' => $genre->genres_id]) ?? '#' }}" class="genre-tag">
                                <span>{{ $genre->genres_name }}</span>
                            </a>
                        @endforeach --}}
                </div>
                {{-- Nút watchlist - giữ nguyên data-attribute cho JS --}}
                <button class="watchlist-button" data-movie-id="{{ $movie->movie_id }}">Add to Watchlist</button>
            </div>

            <!-- Mô tả phim -->
            <p class="movie-description">
                {{ $movie->movie_desc }}
                <hr width="100%" size="2px" align="center" color="gray">
            </p>

            <!-- Đạo diễn -->
            <div class="movie-director">
                <h3>Director</h3>
                 {{-- Có thể link đến trang tìm kiếm theo đạo diễn nếu muốn --}}
                <span>{{ $movie->director }}</span>
            </div>
            <hr width="100%" size="2px" align="center" color="gray">

            <!-- Diễn viên -->
            <div class="movie-cast">
                <h3>Stars</h3>
                {{-- Chỉ sử dụng khối này nếu đã dùng with('actors') trong Controller --}}
                @if($movie->relationLoaded('actors') && $movie->actors->isNotEmpty())
                    @foreach ($movie->actors as $actor)
                        {{-- Truy cập thuộc tính của Actor model --}}
                        {{-- Đảm bảo tên route 'celeb.detail' đã được định nghĩa --}}
                        <a href="{{ route('celeb.detail', ['id' => $actor->actors_id]) ?? '#' }}" class="actor-link">
                        {{-- Đảm bảo Actor model có cột/thuộc tính 'actor_name' --}}
                        <span>{{ $actor->actor_name }}</span>
                    </a>
                    @endforeach
                @else
                    {{-- Hiển thị nếu phim không có diễn viên nào trong bảng movie_actor --}}
                    <span>N/A</span>
                @endif
                {{-- XÓA KHỐI ELSEIF NÀY ĐI (NẾU CÓ) --}}
                {{-- @elseif(!$actorsFromIds->isEmpty())
                    @foreach ($actorsFromIds as $actor)
                        <a href="{{ route('celeb.detail', ['id' => $actor->actors_id]) ?? '#' }}" class="actor-link">
                        <span>{{ $actor->actor_name }}</span>
                    </a>
                    @endforeach
                @endif --}}
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/movie-detail.css') }}">
@endpush

@push('scripts')
{{-- Không cần nhúng script.js hay ajax.js ở đây nếu chúng đã có trong layout chính --}}
{{-- JS xử lý rating form, watchlist button đã có trong các file đó và sẽ hoạt động --}}
{{-- dựa trên các data-attribute (data-movie-id) --}}

<script>
    // Có thể thêm JS CỤ THỂ cho trang này nếu cần, ví dụ:
    // console.log('Movie Detail Page Loaded for movie ID: {{ $movie->movie_id }}');

    // Ví dụ: cập nhật trạng thái nút watchlist nếu cần (JS nên kiểm tra API)
    // const watchlistButton = document.querySelector('.watchlist-button[data-movie-id="{{ $movie->movie_id }}"]');
    // if (watchlistButton) {
    //     // Gọi API để kiểm tra trạng thái watchlist thực tế và cập nhật nút
    //     // fetch('/api/watchlist/status/{{ $movie->movie_id }}') ... then(data => { if(data.inWatchlist) ... })
    // }
</script>
@endpush