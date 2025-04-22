{{-- resources/views/home/index.blade.php --}}

@extends('layouts.app') {{-- Kế thừa layout chính --}}

@section('title', 'Movie Insight - Home') {{-- Đặt tiêu đề riêng cho trang này --}}

@section('content') {{-- Bắt đầu nội dung chính --}}

    {{-- NEWS SECTION --}}
    <section id="main-slider">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                {{-- Dữ liệu $news_array sẽ được truyền từ Controller --}}
                @forelse ($news_array as $news)
                    <div class="swiper-slide">
                        {{-- Nên tạo route chi tiết cho news thay vì link trực tiếp --}}
                        <a href="{{ $news->source_url ?? '#' }}"> {{-- Giả sử có cột source_url --}}
                            <div class="main-slider-box">
                                <div class="main-slider-img">
                                    {{-- Giả sử image_url lưu đường dẫn tương đối từ public/admin --}}
                                    <img src="{{ asset('admin/' . $news->image_url) }}" alt="{{ $news->title }}">
                                </div>
                                <div class="main-slider-text">
                                    <div class="new-title">
                                        <strong>{{ $news->title }}</strong>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="swiper-slide">
                       <p style="text-align: center; color: white; padding: 50px;">No news available.</p>
                    </div>
                @endforelse
            </div>
             {{-- Swiper Navigation (nếu không đặt trong layout) --}}
            {{-- <div class="swiper-button-prev"></div> --}}
            {{-- <div class="swiper-button-next"></div> --}}
             {{-- Swiper Pagination (nếu không đặt trong layout) --}}
             {{-- <div class="swiper-pagination"></div> --}}
        </div>
         <div class="slider-btns">
             <div class="swiper-button-prev"></div>
             <div class="swiper-button-next"></div>
         </div>
    </section>

    {{-- MOVIES SECTION --}}
    <section id="movie-section" class="movie">
        <h2 class="movie-category">Movies</h2>
        <button class="pre-btn"><</button> {{-- Sử dụng < thay vì < --}}
        <button class="nxt-btn">></button> {{-- Sử dụng > thay vì > --}}
        <div class="movie-container">
            {{-- Dữ liệu $movies_array sẽ được truyền từ Controller --}}
            @forelse ($movies_array as $movie)
                <div class="movie-card" data-movie-id="{{ $movie->movie_id }}"> {{-- Thêm data-movie-id ở đây để JS dễ tìm --}}
                    <div class="movie-image">
                         {{-- Tạo route chi tiết phim --}}
                        <a href="{{ route('movie.detail', ['id' => $movie->movie_id]) }}"> {{-- Ví dụ tên route --}}
                            <img src="{{ asset('admin/' . $movie->movie_image) }}" class="movie-thumb" alt="{{ $movie->movie_name }}">
                        </a>
                        {{-- Nút Add to Watchlist - Logic JS cần cập nhật URL API --}}
                        {{-- Cần kiểm tra xem phim đã có trong watchlist của user chưa --}}
                        <button class="addToWatchlist-btn" data-movie-id="{{ $movie->movie_id }}">
                            {{ $movie->isInWatchlist ? 'In Watchlist' : 'Add to watchlist' }} {{-- Biến này cần truyền từ Controller --}}
                        </button>
                    </div>
                    <div class="movie-info">
                        <div class="movie-rating">
                            <span class="rating-score">★ {{ number_format($movie->average_rating ?? 0, 1) }}/10</span>
                            <span class="rate-button">
                                @if($movie->user_rating)
                                    <a href="#" class="rate-link rated" data-movie-id="{{ $movie->movie_id }}">★ {{ round($movie->user_rating) }}</a>
                                @else
                                    <a href="#" class="rate-link" data-movie-id="{{ $movie->movie_id }}">☆ Rate</a>
                                @endif
                            </span>
                        </div>
                        <h4 class="movie-name">{{ $movie->movie_name }}</h4>
                    </div>
                    {{-- Rating Form (Nên tách thành Blade Component) --}}
                    <div data-movie-id="{{ $movie->movie_id }}" class="rating-form" style="display: none;">
                        <p class="rating-form-name">{{ $movie->movie_name }}</p>
                        <span class="close-btn">X</span>
                        <div class="stars">
                            @for ($i = 1; $i <= 10; $i++)
                                <span class="star" data-value="{{ $i }}">★</span>
                            @endfor
                        </div>
                         {{-- Nút Remove Rating sẽ được JS hiển thị/ẩn --}}
                        <button class="remove-rating-btn" style="display: none;">Remove Rating</button>
                    </div>
                </div>
            @empty
                <p style="color: white; padding: 20px;">No movies available.</p>
            @endforelse
        </div>
    </section>

    {{-- TV SHOWS SECTION --}}
    <section id="tvshow-section" class="movie">
         <h2 class="movie-category">TV Shows</h2>
        <button class="pre-btn"><</button>
        <button class="nxt-btn">></button>
        <div class="movie-container">
            {{-- Dữ liệu $tv_shows_array sẽ được truyền từ Controller --}}
            @forelse ($tv_shows_array as $tvShow)
                <div class="movie-card" data-movie-id="{{ $tvShow->movie_id }}">
                    <div class="movie-image">
                        <a href="{{ route('tvshow.detail', ['id' => $tvShow->movie_id]) }}"> {{-- Ví dụ tên route --}}
                            <img src="{{ asset('admin/' . $tvShow->movie_image) }}" class="movie-thumb" alt="{{ $tvShow->movie_name }}">
                        </a>
                        <button class="addToWatchlist-btn" data-movie-id="{{ $tvShow->movie_id }}">
                            {{ $tvShow->isInWatchlist ? 'In Watchlist' : 'Add to watchlist' }}
                        </button>
                    </div>
                    <div class="movie-info">
                        <div class="movie-rating">
                            <span class="rating-score">★ {{ number_format($tvShow->average_rating ?? 0, 1) }}/10</span>
                            <span class="rate-button">
                                 @if($tvShow->user_rating)
                                    <a href="#" class="rate-link rated" data-movie-id="{{ $tvShow->movie_id }}">★ {{ round($tvShow->user_rating) }}</a>
                                @else
                                    <a href="#" class="rate-link" data-movie-id="{{ $tvShow->movie_id }}">☆ Rate</a>
                                @endif
                            </span>
                        </div>
                        <h4 class="movie-name">{{ $tvShow->movie_name }}</h4>
                    </div>
                     {{-- Rating Form --}}
                    <div data-movie-id="{{ $tvShow->movie_id }}" class="rating-form" style="display: none;">
                        <p class="rating-form-name">{{ $tvShow->movie_name }}</p>
                        <span class="close-btn">X</span>
                        <div class="stars">
                            @for ($i = 1; $i <= 10; $i++)
                                <span class="star" data-value="{{ $i }}">★</span>
                            @endfor
                        </div>
                        <button class="remove-rating-btn" style="display: none;">Remove Rating</button>
                    </div>
                </div>
            @empty
                <p style="color: white; padding: 20px;">No TV shows available.</p>
            @endforelse
        </div>
    </section>

    {{-- CELEBRITIES SECTION --}}
    <section id="celeb-section" class="movie">
         <h2 class="movie-category">Celebrities</h2>
        <button class="pre-btn"><</button>
        <button class="nxt-btn">></button>
        <div class="movie-container">
             {{-- Dữ liệu $actors_array sẽ được truyền từ Controller --}}
            @forelse ($actors_array as $actor)
                 <div class="movie-card"> {{-- Có thể đổi class nếu cần style riêng --}}
                    <div class="celeb-image">
                         {{-- Ví dụ tên route --}}
                        <a href="{{ route('celeb.detail', ['id' => $actor->actors_id]) }}">
                            <img src="{{ asset('admin/' . $actor->actors_image) }}" class="movie-thumb" alt="{{ $actor->actor_name }}">
                        </a>
                    </div>
                    <div class="movie-info">
                        <h4 class="celeb-name">{{ $actor->actor_name }}</h4>
                    </div>
                </div>
            @empty
                 <p style="color: white; padding: 20px;">No celebrities found.</p>
            @endforelse
        </div>
    </section>

@endsection {{-- Kết thúc nội dung chính --}}


@push('scripts')
<script> // <--- THẺ SCRIPT MỞ ĐẦU QUAN TRỌNG

    const isLoggedIn = @json(Auth::check()); // Kiểm tra đăng nhập và chuyển thành true/false JS
    const loggedInUserId = @json(Auth::id()); // Lấy ID user nếu đã đăng nhập, null nếu chưa
    const csrfToken = '{{ csrf_token() }}'; // Lấy CSRF token cho AJAX POST/PUT/DELETE

    // Các URL API cho rating/watchlist cần được định nghĩa trong routes/api.php
    // *** SỬ DỤNG route() HELPER LÀ TỐT NHẤT NẾU ĐÃ ĐỊNH NGHĨA ROUTE ***
    const rateMovieUrl = '{{ route("api.rate.movie", [], false) }}'; // false để lấy relative URL nếu muốn, hoặc bỏ đi để lấy full URL
    const removeRatingUrl = '{{ route("api.remove.rating", [], false) }}';
    const addToWatchlistUrl = '{{ route("api.watchlist.add", [], false) }}';
    const removeFromWatchlistUrl = '{{ route("api.watchlist.remove", [], false) }}';

    // Hoặc nếu chưa chắc chắn có route, dùng url() tạm thời:
    // const rateMovieUrl = '{{ url("/api/rate/movie") }}';
    // const removeRatingUrl = '{{ url("/api/remove-rating") }}';
    // const addToWatchlistUrl = '{{ url("/api/watchlist/add") }}';
    // const removeFromWatchlistUrl = '{{ url("/api/watchlist/remove") }}';


    // **LƯU Ý:** Toàn bộ code trong script.js và ajax.js cần được xem xét lại:
    // 1. Thay thế các URL fetch bằng các biến URL API đã định nghĩa ở trên.
    // 2. Sử dụng biến isLoggedIn, loggedInUserId thay vì lấy từ PHP global.
    // 3. Thêm CSRF token vào header của các request POST/PUT/DELETE. Ví dụ:
       // headers: {
       //     'Content-Type': 'application/json',
       //     'Accept': 'application/json',
       //     'X-CSRF-TOKEN': csrfToken
       // },
    // 4. Cập nhật cách xử lý response JSON từ API Laravel.

    // --- Code khởi tạo Swiper và slider buttons (nếu có) ---
     var swiper = new Swiper(".mySwiper", {
        // ... cấu hình swiper ...
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        // ... breakpoints ...
    });

    const productContainers = [...document.querySelectorAll('.movie-container')];
    const nxtBtn = [...document.querySelectorAll('.nxt-btn')];
    const preBtn = [...document.querySelectorAll('.pre-btn')];

    productContainers.forEach((item, i) => {
        if (preBtn[i] && nxtBtn[i]) {
            let containerDimensions = item.getBoundingClientRect();
            let containerWidth = containerDimensions.width * 0.8;
            nxtBtn[i].addEventListener('click', () => { item.scrollLeft += containerWidth; });
            preBtn[i].addEventListener('click', () => { item.scrollLeft -= containerWidth; });
        }
    });

    // --- Các logic JS khác cần thiết cho trang này ---

</script> // <--- THẺ SCRIPT ĐÓNG LẠI QUAN TRỌNG
@endpush