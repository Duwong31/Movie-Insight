{{-- resources/views/home/index.blade.php --}}

@extends('layouts.app')
@section('title', 'Movie Insight - Home')

@section('content')

{{-- NEWS SECTION & UP NEXT --}}
{{-- Sửa đổi wrapper này thành flex container --}}
<div class="news-upnext-wrapper">

    {{-- NEWS SLIDER (Giữ nguyên cấu trúc bên trong section#main-slider) --}}
    <div class="news-slider-container"> {{-- Thêm container riêng cho slider --}}
        <section id="main-slider">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @forelse ($news_array as $news_item)
                        <div class="swiper-slide">
                            {{-- Giữ nguyên cấu trúc .main-slider-box bên trong --}}
                            <a href="{{ $news_item->source_url ?? '#' }}" target="_blank" rel="noopener noreferrer">
                                <div class="main-slider-box">
                                    <div class="main-slider-img">
                                        <img src="{{ $news_item->image_url ? asset('uploads/' . $news_item->image_url) : asset('img/placeholder-news.jpg') }}" alt="{{ $news_item->title ?? 'News Image' }}">
                                    </div>
                                    <div class="main-slider-text">
                                        <div class="new-title">
                                            <strong>{{ $news_item->title ?? 'Untitled News' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            {{-- Kết thúc .main-slider-box --}}
                        </div>
                    @empty
                        {{-- Nội dung khi không có tin tức --}}
                        <div class="swiper-slide">
                           <div class="main-slider-box" style="/*...*/">
                                <p style="/*...*/">No news available at the moment.</p>
                           </div>
                        </div>
                    @endforelse
                </div>
                 {{-- Swiper Buttons --}}
                 <div class="swiper-button-prev"></div>
                 <div class="swiper-button-next"></div>
            </div>
        </section>
    </div> {{-- Kết thúc .news-slider-container --}}


    {{-- UP NEXT SECTION (MỚI) --}}
    <aside class="up-next-section">
        <h3 class="up-next-heading">Up next</h3>
        <div class="up-next-content-box">
            <div class="up-next-list">
                {{-- Bắt đầu một item ví dụ (Bạn sẽ lặp qua dữ liệu thực tế ở đây) --}}
                @php
                    // Giả sử bạn có một mảng $up_next_items từ Controller
                    $up_next_items = [
                        [ 'image' => 'the_accountant_2.jpg', 'duration' => '9:10', 'title' => "How Well Does 'The Accountant 2' Cast Know Each Other?", 'description' => 'Ben Affleck and Jon Bernthal Face Off', 'likes' => 193 ],
                        [ 'image' => 'wednesday.jpg', 'duration' => '2:06', 'title' => '"Wednesday" Returns to Netflix on August 6', 'description' => 'Watch the Trailer', 'likes' => '1.2K' ],
                        [ 'image' => 'on_the_scene.jpg', 'duration' => '5:01', 'title' => 'Diego Luna on Cassian\'s Growth in "Andor" Season 2', 'description' => 'Watch Our Star Wars Celebration Interview', 'likes' => 108 ],
                    ];
                @endphp

                @forelse ($up_next_items as $item) {{-- Thay $up_next_items bằng biến thực tế --}}
                <div class="up-next-item">
                    <div class="up-next-thumbnail">
                        {{-- Thay bằng ảnh thực tế --}}
                        <img src="{{ asset('img/placeholders/' . $item['image']) }}" alt="{{ $item['title'] }}">
                        <div class="thumbnail-overlay">
                            {{-- Giả sử bạn dùng Font Awesome --}}
                            <span class="play-icon"><i class="fas fa-play"></i></span>
                            <span class="duration">{{ $item['duration'] }}</span>
                        </div>
                    </div>
                    <div class="up-next-info">
                        <h5 class="up-next-title">{{ $item['title'] }}</h5>
                        <p class="up-next-description">{{ $item['description'] }}</p>
                        <div class="up-next-meta">
                            {{-- Icon Like (Font Awesome ví dụ) --}}
                            <span class="like-icon"><i class="fas fa-thumbs-up"></i></span>
                            <span class="like-count">{{ $item['likes'] }}</span>
                            {{-- Bỏ Icon Tim --}}
                        </div>
                    </div>
                </div>
                @empty
                <p class="no-up-next">Nothing up next currently.</p>
                @endforelse
                {{-- Kết thúc vòng lặp --}}

            </div>
        </div>
        <a href="#" class="browse-trailers-link">Browse trailers <i class="fas fa-chevron-right"></i></a>
    </aside>
    {{-- Kết thúc .up-next-section --}}

</div>

{{-- MOVIES SECTION --}}
<section id="movie-section" class="movie">
    <h2 class="movie-category">Movies</h2>
    {{-- Các nút này giờ sẽ được xử lý bởi home.js (giữ nguyên) --}}
    <button class="pre-btn"><</button>
    <button class="nxt-btn">></button>
    <div class="movie-container">
        {{-- Lặp qua mảng $movies_array từ Controller --}}
        {{-- Sử dụng @forelse để xử lý trường hợp không có phim nào --}}
        @forelse ($movies_array as $movie)
            @php
                // Lấy rating của người dùng hiện tại (nếu có) cho phim này
                // Giả định rằng $movie->user_rating chứa giá trị này từ Controller
                $userRating = $movie->userRating?->rating ?? null;
                $movieId = $movie->movie_id; // Hoặc $movie->id nếu bạn dùng ID mặc định của Laravel
                $isInWatchlist = Auth::check() && in_array($movieId, $userWatchlistMovieIds);
            @endphp
            <div class="movie-card" data-movie-id="{{ $movieId }}"> {{-- Thêm data-movie-id vào card chính --}}
                <div class="movie-image">
                    {{-- Sử dụng route() để tạo link chi tiết phim --}}
                    {{-- Đảm bảo bạn đã định nghĩa route tên 'movie.detail' trong web.php hoặc api.php --}}
                    <a href="{{ route('movie.detail', ['id' => $movieId]) }}">
                        {{-- Sử dụng asset() để tạo URL đúng cho ảnh trong thư mục public --}}
                        {{-- Giả sử ảnh nằm trong public/uploads/ --}}
                        <img src="{{ $movie->movie_image ? asset('uploads/' . $movie->movie_image) : asset('img/placeholder-movie.jpg') }}"
                             class="movie-thumb" alt="{{ $movie->movie_name ?? 'Movie Poster' }}">
                    </a>
                    {{-- Nút Add to Watchlist --}}
                    <button class="{{ $isInWatchlist ? 'remove-watchlist-btn' : 'addToWatchlist-btn' }}" data-movie-id="{{ $movieId }}">
                        @if($isInWatchlist)
                            <i class="fas fa-check"></i> Added
                        @else
                            Add to watchlist
                        @endif
                    </button>
                </div>
                <div class="movie-info">
                    <div class="movie-rating">
                        {{-- Hiển thị điểm trung bình --}}
                        <span class="rating-score">★ {{ number_format($movie->average_rating ?? 0, 1) }}</span>
                        {{-- Nút Rate --}}
                        <span class="rate-button">
                        @if ($userRating !== null) {{-- Kiểm tra xem có rating không (kể cả 0) --}}
                    {{-- Hiển thị điểm đã rate --}}
                            <a href="#" class="rate-link rated" data-movie-id="{{ $movieId }}">★ {{ round($userRating) }}</a>
                        @else
                            {{-- Hiển thị nút "Rate" --}}
                            <a href="#" class="rate-link" data-movie-id="{{ $movieId }}">☆ Rate</a>
                        @endif
                        </span>
                    </div>
                    {{-- Hiển thị tên phim --}}
                    <h4 class="movie-name">{{ $movie->movie_name ?? 'Untitled Movie' }}</h4>
                </div>

                {{-- Form đánh giá (ẩn ban đầu, được kích hoạt bởi JS) --}}
                {{-- Đặt data-movie-id ở đây để JS dễ dàng xác định form nào đang active --}}
                <div class="rating-form" data-movie-id="{{ $movie->movie_id }}" data-current-rating="{{ $userRating ?? 0 }}" style="display: none;">
                    <button class="close-btn">×</button> {{-- Nút đóng --}}

                    {{-- 1. Icon Ngôi Sao (Thêm mới) --}}
                    <div class="rating-form-icon">
                        <i class="fas fa-star"></i> {{-- Sử dụng Font Awesome solid star làm placeholder --}}
                        <span class="rating-value-display">?</span>
                    </div>

                    {{-- 2. Text "RATE THIS" (Thêm mới) --}}
                    <p class="rating-form-prompt">Rate This</p>

                    {{-- 3. Tên Phim (Giữ lại class cũ hoặc đổi tên) --}}
                    <h3 class="rating-form-name">{{ $movie->movie_name }}</h3>

                    {{-- 4. Dãy Sao (Giữ lại class cũ) --}}
                    <div class="stars">
                        @for ($i = 1; $i <= 10; $i++)
                            {{-- Sử dụng far fa-star (outline) làm mặc định --}}
                            <i class="far fa-star star" data-value="{{ $i }}"></i>
                        @endfor
                    </div>

                    {{-- 5. Nút Submit (Thêm mới - Nút này không tự gửi form, JS sẽ xử lý click sao) --}}
                    {{-- Nút này có thể không cần thiết nếu việc submit xảy ra ngay khi click sao --}}
                    {{-- Hoặc bạn có thể giữ lại để người dùng xác nhận --}}
                    <button type="button" class="btn btn-submit-rating">Rate</button>

                    {{-- Nút Remove Rating (Nếu đã rate) - Cần logic để hiển thị --}}
                    {{-- <button type="button" class="remove-rating-btn" data-movie-id="{{ $movie->id }}" style="display: {{ $userRating ? 'inline-block' : 'none' }};">Remove Rating</button> --}}
                    {{-- Element hiển thị rating hiện tại của user (nếu có) --}}
                    {{-- <p class="user-rating-display" data-movie-id="{{ $movie->id }}" style="display: {{ $userRating ? 'block' : 'none' }}; margin-top: 10px;">Your Rating: ★ {{ $userRating ?? '' }}/10</p> --}}

                </div>
            </div>
        @empty
            {{-- Hiển thị nếu không có phim nào trong $movies_array --}}
            <p style="color: white; padding: 20px; width: 100%; text-align: center;">No movies found.</p>
        @endforelse
    </div>
</section>

{{-- TV SHOWS SECTION --}}
<section id="tvshow-section" class="movie">
    <h2 class="movie-category">TV Shows</h2>
    {{-- Các nút này giờ sẽ được xử lý bởi home.js --}}
    <button class="pre-btn"><</button>
    <button class="nxt-btn">></button>
    <div class="movie-container">
        {{-- Lặp qua mảng $tv_shows_array từ Controller --}}
        @forelse ($tv_shows_array as $tvShow)
            @php
                // Lấy rating của người dùng hiện tại (nếu có) cho TV Show này
                // $tvShow->userRating được nạp sẵn từ Controller
                $userRating = $tvShow->userRating?->rating ?? null; // Sử dụng optional chaining và null coalesce cho an toàn
                $tvShowId = $tvShow->movie_id; // Giả sử movie_id là ID chính được sử dụng
            @endphp
            {{-- Sử dụng class và cấu trúc giống movie-card để đồng bộ style --}}
            {{-- Giả định JS cũng sẽ tìm data-movie-id cho các hành động rating/watchlist --}}
            <div class="movie-card" data-movie-id="{{ $tvShowId }}">
                <div class="movie-image">
                    {{-- Tạo link đến trang chi tiết --}}
                    {{-- Giả sử route 'movie.detail' dùng chung cho cả movie và tvshow bằng ID --}}
                    {{-- Nếu bạn có route riêng cho tvshow (vd: 'tvshow.detail'), hãy thay đổi ở đây --}}
                    <a href="{{ route('movie.detail', ['id' => $tvShowId]) }}">
                        {{-- Ảnh thumbnail --}}
                        <img src="{{ $tvShow->movie_image ? asset('uploads/' . $tvShow->movie_image) : asset('img/placeholder-movie.jpg') }}" {{-- Có thể tạo placeholder riêng cho TV Show nếu muốn --}}
                             class="movie-thumb" alt="{{ $tvShow->movie_name ?? 'TV Show Poster' }}">
                    </a>
                    {{-- Nút Add to Watchlist --}}
                    {{-- Giả sử logic watchlist giống với movie --}}
                    <button class="addToWatchlist-btn" data-movie-id="{{ $tvShowId }}">Add to watchlist</button>
                </div>
                <div class="movie-info">
                    <div class="movie-rating">
                        {{-- Điểm trung bình --}}
                        <span class="rating-score">★ {{ number_format($tvShow->average_rating ?? 0, 1) }}/10</span>
                        {{-- Nút Rate/Điểm đã rate --}}
                        <span class="rate-button">
                            @if ($userRating)
                                {{-- Hiển thị điểm đã rate --}}
                                <a href="#" class="rate-link rated" data-movie-id="{{ $tvShowId }}">★ {{ round($userRating) }}</a>
                            @else
                                {{-- Hiển thị nút Rate --}}
                                <a href="#" class="rate-link" data-movie-id="{{ $tvShowId }}">☆ Rate</a>
                            @endif
                        </span>
                    </div>
                    {{-- Tên TV Show --}}
                    <h4 class="movie-name">{{ $tvShow->movie_name ?? 'Untitled TV Show' }}</h4>
                </div>

                {{-- Form đánh giá (ẩn ban đầu) --}}
                {{-- Đảm bảo data-movie-id khớp để JS xử lý đúng form --}}
                <div class="rating-form" data-movie-id="{{ $tvShowId }}" style="display: none;">
                    <p class="rating-form-name">{{ $tvShow->movie_name ?? 'Untitled TV Show' }}</p>
                    <span class="close-rating-form-btn">X</span>
                    <div class="stars">
                        @for ($i = 1; $i <= 10; $i++)
                            <span class="star" data-value="{{ $i }}">★</span>
                        @endfor
                    </div>
                    {{-- Nút xóa đánh giá --}}
                    <button class="remove-rating-btn" data-movie-id="{{ $tvShowId }}" style="display: {{ $userRating ? 'inline-block' : 'none' }};">Remove Rating</button>
                </div>
            </div>
        @empty
            {{-- Hiển thị nếu không có TV Show nào --}}
            <p style="color: white; padding: 20px; width: 100%; text-align: center;">No TV shows found.</p>
        @endforelse
    </div>
</section>

    {{-- CELEBRITIES SECTION --}}
    <section id="celeb-section" class="movie">
         <h2 class="movie-category">Celebrities</h2>
         {{-- Các nút này giờ sẽ được xử lý bởi home.js --}}
        <button class="pre-btn"><</button>
        <button class="nxt-btn">></button>
        <div class="movie-container">
            {{-- Lặp qua mảng $actors_array từ Controller --}}
            @forelse ($actors_array as $actor)
                {{-- Sử dụng class 'movie-card' giống như file cũ để giữ style --}}
                <div class="movie-card">
                    <div class="celeb-image">
                        {{-- Tạo link đến trang chi tiết diễn viên --}}
                        {{-- Ưu tiên dùng route() nếu đã định nghĩa route 'celeb.detail' --}}
                        {{-- Nếu chưa có route, dùng url() hoặc để '#' tạm thời --}}
                        <a href="{{ route('celeb.detail', ['id' => $actor->actors_id]) ?? '#' }}">
                            <img src="{{ $actor->actors_image ? asset('uploads/' . $actor->actors_image) : asset('img/placeholder-actor.jpg') }}"
                                 class="movie-thumb" alt="{{ $actor->actor_name ?? 'Actor Image' }}"> 
                        </a>
                    </div>
                    <div class="movie-info">
                        {{-- Hiển thị tên diễn viên --}}
                        <h4 class="celeb-name">{{ $actor->actor_name ?? 'Unknown Actor' }}</h4>
                    </div>
                </div>
            @empty
                {{-- Hiển thị nếu không có diễn viên nào --}}
                <p style="color: white; padding: 20px; width: 100%; text-align: center;">No celebrities found.</p>
            @endforelse
        </div>
    </section>
    {{-- What to Watch Section --}}
<section id="what-to-watch-section" class="movie">
    <div class="what-to-watch-container">
        {{-- Tiêu đề chính --}}
        <h2 class="movie-category">What to watch</h2>
        {{-- Thêm nút prev và next --}}
            <button class="pre-btn"><</button>
            <button class="nxt-btn">></button>
        {{-- Kiểm tra nếu người dùng chưa đăng nhập --}}
        @guest
            {{-- Link "From your Watchlist" (có thể dẫn đến trang đăng nhập hoặc thông báo) --}}
            <a href="{{ route('login') }}" class="what-to-watch-link">
                <span class="link-divider">|</span> From your Watchlist <i class="fas fa-chevron-right"></i>
            </a>

            {{-- Khu vực nhắc đăng nhập --}}
            <div class="watchlist-prompt">
                <div class="prompt-icon-container">
                    {{-- Icon bookmark/add (Sử dụng Font Awesome) --}}
                    <span class="prompt-icon"><i class="fas fa-plus"></i></span>
                </div>
                <h3 class="prompt-title">Log in to access your Watchlist</h3>
                <p class="prompt-description">Save shows and movies to keep track of what you want to watch.</p>
                {{-- Nút đăng nhập --}}
                <a href="{{ route('login') }}" class="btn btn-sign-in-prompt">
                    Log In
                </a>
            </div>
        @endguest

        {{-- Kiểm tra nếu người dùng ĐÃ đăng nhập --}}
        @auth
            {{-- Link đến trang Watchlist thực tế --}}
            <a href="{{ route('watchlist.index') }}" class="what-to-watch-link">
                <span class="link-divider">|</span> From your Watchlist <i class="fas fa-chevron-right"></i>
            </a>

            

            {{-- Hiển thị các phim trong watchlist --}}
            <div class="movie-container">
                @forelse (Auth::user()->watchlistMovies()->withAvg('ratings', 'rating')->get() as $movie)
                    @php
                        $userRating = $movie->userRating?->rating ?? null;
                        $movieId = $movie->movie_id;
                    @endphp
                    <div class="movie-card" data-movie-id="{{ $movieId }}">
                        <div class="movie-image">
                            <a href="{{ route('movie.detail', ['id' => $movieId]) }}">
                                <img src="{{ $movie->movie_image ? asset('uploads/' . $movie->movie_image) : asset('img/placeholder-movie.jpg') }}"
                                     class="movie-thumb" alt="{{ $movie->movie_name ?? 'Movie Poster' }}">
                            </a>
                            <button class="addToWatchlist-btn" data-movie-id="{{ $movieId }}">Remove from watchlist</button>
                        </div>
                        <div class="movie-info">
                            <div class="movie-rating">
                                <span class="rating-score">★ {{ number_format($movie->ratings_avg_rating ?? 0, 1) }}</span>
                                <span class="rate-button">
                                    @if ($userRating !== null)
                                        <a href="#" class="rate-link rated" data-movie-id="{{ $movieId }}">★ {{ round($userRating) }}</a>
                                    @else
                                        <a href="#" class="rate-link" data-movie-id="{{ $movieId }}">☆ Rate</a>
                                    @endif
                                </span>
                            </div>
                            <h4 class="movie-name">{{ $movie->movie_name ?? 'Untitled Movie' }}</h4>
                        </div>

                        {{-- Form đánh giá (ẩn ban đầu) --}}
                        <div class="rating-form" data-movie-id="{{ $movieId }}" data-current-rating="{{ $userRating ?? 0 }}" style="display: none;">
                            <button class="close-btn">×</button>
                            <div class="rating-form-icon">
                                <i class="fas fa-star"></i>
                                <span class="rating-value-display">?</span>
                            </div>
                            <p class="rating-form-prompt">Rate This</p>
                            <h3 class="rating-form-name">{{ $movie->movie_name }}</h3>
                            <div class="stars">
                                @for ($i = 1; $i <= 10; $i++)
                                    <i class="far fa-star star" data-value="{{ $i }}"></i>
                                @endfor
                            </div>
                            <button type="button" class="btn btn-submit-rating">Rate</button>
                        </div>
                    </div>
                @empty
                    <p style="color: white; padding: 20px; width: 100%; text-align: center;">Your watchlist is empty. Add some movies to get started!</p>
                @endforelse
            </div>
        @endauth
    </div>
</section>
@endsection


@push('scripts')
{{-- Nhúng file JS dành riêng cho trang chủ SAU KHI định nghĩa biến --}}
<script src="{{ asset('js/home.js') }}"></script>

{{-- Nhúng thư viện Swiper (nếu chưa có trong layout chính) --}}
{{-- Ví dụ: <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script> --}}
{{-- Đảm bảo thư viện Swiper được tải TRƯỚC home.js --}}

@endpush