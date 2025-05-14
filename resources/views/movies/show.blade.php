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
                            <button class="remove-rating-btn" data-movie-id="{{ $movie->movie_id }}" style="display: {{ $userRating ? 'block' : 'none' }}">Remove Rating</button> {{-- Thêm data-movie-id --}}
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
    {{-- USER REVIEWS SECTION --}}
    <div class="user-reviews-section">
        <div class="user-reviews-header">
            <div class="user-reviews-title-group">
                <h2>User Reviews</h2>
                <span class="review-count">{{ number_format(count($reviews)) }}</span>
                <a href="{{-- {{ route('movie.reviews', ['id' => $movie->movie_id]) }} --}}#" class="see-all-reviews-btn" title="Xem tất cả review">
                    >
                </a>
            </div>
            @auth
                <button id="addReviewBtn" class="button-primary">+ Review</button>
            @else
                <p><a href="{{ route('login') }}">Log in</a> to add a review.</p>
            @endauth
        </div>

        <div class="reviews-slider-wrapper" style="position:relative;">
            <button class="prev-review-btn" style="position:absolute;left:0;top:40%;z-index:2;">&#60;</button>
            <div class="reviews-slider" style="overflow:hidden;">
                <div class="reviews-slider-inner" style="display:flex;transition:transform 0.3s;">
                    @foreach ($reviews as $review)
                        <div class="review-item" style="min-width:25%;box-sizing:border-box;">
                            <div class="review-item-header">
                                @php
                                    $userRating = $ratings[$review->user_id] ?? null;
                                @endphp
                                @if($userRating)
                                    <span class="review-score">{{ $userRating }}</span>
                                @endif
                                <strong>{{ $review->user->fullname ?? 'Unknown User' }}</strong>
                            </div>
                            <h4>{{ $review->title }}</h4>
                            @if($review->has_spoiler)
                                <p class="spoiler-warning"><strong>Warning: This review may contain spoilers!</strong></p>
                            @endif
                            <p class="review-content">{{ Str::limit($review->content, 300) }}
                                @if(strlen($review->content) > 300)
                                    <a href="#" class="read-more-review" data-fulltext="{{ nl2br(e($review->content)) }}">Read more</a>
                                @endif
                            </p>
                            <div class="full-review-content" style="display:none;"></div>
                        </div>
                    @endforeach
                </div>
            </div>
            <button class="next-review-btn" style="position:absolute;right:0;top:40%;z-index:2;">&#62;</button>
        </div>
        @if(count($reviews) == 0)
            <p id="noReviewsMessage">No reviews yet. Be the first to write one!</p>
        @endif
    </div>

    {{-- ADD REVIEW SIDEBAR (Initially Hidden) --}}
    @auth
    <div id="reviewSidebar" class="review-sidebar">
        <div class="review-sidebar-header">
            <h3>Add User Review for {{ $movie->movie_name }}</h3>
            <button id="closeReviewSidebar" class="close-btn">×</button>
        </div>
        <form id="reviewForm" data-movie-id="{{ $movie->movie_id }}" data-initial-user-rating="{{ $userRating ? round($userRating) : '' }}"> {{-- Use movie->id or movie->movie_id --}}
            @csrf
            <div class="form-group">
                <label>Your rating</label>
                <div class="stars-input">
                    @for ($i = 1; $i <= 10; $i++)
                        {{-- Pre-select based on $userRating (the main rating for the movie) --}}
                        <span class="sidebar-star {{ $userRating && $i <= round($userRating) ? 'selected' : '' }}" data-value="{{ $i }}">☆</span>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="reviewRatingInput" value="{{ $userRating ? round($userRating) : '' }}">
                <small class="rating-display">{{ $userRating ? round($userRating).'/10' : '?/10' }}</small>
            </div>

            <div class="form-group">
                <label for="reviewTitle">Title of your review <span class="required">*</span></label>
                <input type="text" id="reviewTitle" name="title" required>
            </div>

            <div class="form-group">
                <label for="reviewContent">Review <span class="required">*</span></label>
                <textarea id="reviewContent" name="content" rows="10" required minlength="400"></textarea>
                <small>Minimum 400 characters. <span id="charCount">0</span>/400</small>
            </div>

            <div class="form-group">
                <p>Does this User Review contain spoilers? <span class="required">*</span></p>
                <label><input type="radio" name="has_spoiler" value="1" required> Yes</label>
                <label><input type="radio" name="has_spoiler" value="0" required checked> No</label>
            </div>
            <div id="reviewFormErrors" class="form-errors" style="display:none;"></div>
            <div class="form-actions">
                <button type="submit" class="button-primary">Submit</button>
                <button type="button" id="cancelReviewSidebar" class="button-secondary">Cancel</button>
            </div>
        </form>
    </div>
    <div id="reviewSidebarOverlay" class="review-sidebar-overlay"></div>
    @endauth
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/movie-detail.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/movie-detail.js') }}"></script>
@endpush