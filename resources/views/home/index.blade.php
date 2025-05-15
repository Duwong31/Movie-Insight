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
    <button class="pre-btn"><</button>
    <button class="nxt-btn">></button>
    <div class="movie-container">
        @forelse ($movies_array as $movie)
            @php
                $userRating = $movie->userRating?->rating ?? null;
                $movieId = $movie->movie_id;
                $isInWatchlist = Auth::check() && in_array($movieId, $userWatchlistMovieIds);
            @endphp
            <div class="movie-card" data-movie-id="{{ $movieId }}">
                <div class="movie-image">
                    <a href="{{ route('movie.detail', ['id' => $movieId]) }}">
                        <img src="{{ $movie->movie_image ? asset('uploads/' . $movie->movie_image) : asset('img/placeholder-movie.jpg') }}"
                             class="movie-thumb" alt="{{ $movie->movie_name ?? 'Movie Poster' }}">
                    </a>
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
                        <span class="rating-score">★ {{ number_format($movie->average_rating ?? 0, 1) }}</span>
                        <span class="rate-button">
                        @if ($userRating !== null && $userRating >= 0)
                            <a href="#" class="rate-link rated" data-movie-id="{{ $movieId }}">★ {{ round($userRating) }}</a>
                        @else
                            <a href="#" class="rate-link" data-movie-id="{{ $movieId }}">☆ Rate</a>
                        @endif
                        </span>
                    </div>
                    <h4 class="movie-name">{{ $movie->movie_name ?? 'Untitled Movie' }}</h4>
                </div>

                <div class="rating-form" data-movie-id="{{ $movie->movie_id }}" data-current-rating="{{ ($userRating !== null && $userRating >=0) ? $userRating : 0 }}" style="display: none;">
                    <button class="close-btn">×</button>
                    <div class="rating-form-icon">
                        <i class="fas fa-star"></i>
                        <span class="rating-value-display">{{ ($userRating !== null && $userRating >=0) ? round($userRating) : '?' }}</span>
                    </div>
                    <p class="rating-form-prompt">Rate This</p>
                    <h3 class="rating-form-name">{{ $movie->movie_name }}</h3>
                    <div class="stars">
                        @for ($i = 1; $i <= 10; $i++)
                            <i class="far fa-star star" data-value="{{ $i }}"></i>
                        @endfor
                    </div>
                    <button type="button" class="btn btn-submit-rating">Rate</button>
                    <button type="button" class="btn btn-remove-rating" data-movie-id="{{ $movie->movie_id }}" style="display: {{ ($userRating !== null && $userRating >=0) ? 'inline-block' : 'none' }}; margin-top: 10px;">
                        Remove Rating
                    </button>
                </div>
            </div>
        @empty
            <p style="color: white; padding: 20px; width: 100%; text-align: center;">No movies found.</p>
        @endforelse
    </div>
</section>

{{-- TV SHOWS SECTION --}}
<section id="tvshow-section" class="movie">
    <h2 class="movie-category">TV Shows</h2>
    <button class="pre-btn"><</button>
    <button class="nxt-btn">></button>
    <div class="movie-container">
        @forelse ($tv_shows_array as $tvShow)
            @php
                $userRating = $tvShow->userRating?->rating ?? null;
                $tvShowId = $tvShow->movie_id;
            @endphp
            <div class="movie-card" data-movie-id="{{ $tvShowId }}">
                <div class="movie-image">
                    <a href="{{ route('movie.detail', ['id' => $tvShowId]) }}">
                        <img src="{{ $tvShow->movie_image ? asset('uploads/' . $tvShow->movie_image) : asset('img/placeholder-movie.jpg') }}"
                             class="movie-thumb" alt="{{ $tvShow->movie_name ?? 'TV Show Poster' }}">
                    </a>
                    <button class="addToWatchlist-btn" data-movie-id="{{ $tvShowId }}">Add to watchlist</button>
                </div>
                <div class="movie-info">
                    <div class="movie-rating">
                        <span class="rating-score">★ {{ number_format($tvShow->average_rating ?? 0, 1) }}/10</span>
                        <span class="rate-button">
                            @if ($userRating !== null && $userRating >= 0)
                                <a href="#" class="rate-link rated" data-movie-id="{{ $tvShowId }}">★ {{ round($userRating) }}</a>
                            @else
                                <a href="#" class="rate-link" data-movie-id="{{ $tvShowId }}">☆ Rate</a>
                            @endif
                        </span>
                    </div>
                    <h4 class="movie-name">{{ $tvShow->movie_name ?? 'Untitled TV Show' }}</h4>
                </div>

                <div class="rating-form" data-movie-id="{{ $tvShowId }}" data-current-rating="{{ ($userRating !== null && $userRating >=0) ? $userRating : 0 }}" style="display: none;">
                    <button class="close-btn">×</button>
                    <div class="rating-form-icon">
                        <i class="fas fa-star"></i>
                        <span class="rating-value-display">{{ ($userRating !== null && $userRating >=0) ? round($userRating) : '?' }}</span>
                    </div>
                    <p class="rating-form-prompt">Rate This</p>
                    <h3 class="rating-form-name">{{ $tvShow->movie_name ?? 'Untitled TV Show' }}</h3>
                    <div class="stars">
                        @for ($i = 1; $i <= 10; $i++)
                            <i class="far fa-star star" data-value="{{ $i }}"></i>
                        @endfor
                    </div>
                    <button type="button" class="btn btn-submit-rating">Rate</button>
                    <button type="button" class="btn btn-remove-rating" data-movie-id="{{ $tvShowId }}" style="display: {{ ($userRating !== null && $userRating >=0) ? 'inline-block' : 'none' }}; margin-top: 10px;">
                        Remove Rating
                    </button>
                </div>
            </div>
        @empty
            <p style="color: white; padding: 20px; width: 100%; text-align: center;">No TV shows found.</p>
        @endforelse
    </div>
</section>

    {{-- CELEBRITIES SECTION --}}
    <section id="celeb-section" class="movie">
         <h2 class="movie-category">Celebrities</h2>
        <button class="pre-btn"><</button>
        <button class="nxt-btn">></button>
        <div class="movie-container">
            @forelse ($actors_array as $actor)
                <div class="movie-card">
                    <div class="celeb-image">
                        <a href="{{ route('celeb.detail', ['id' => $actor->actors_id]) ?? '#' }}">
                            <img src="{{ $actor->actors_image ? asset('uploads/' . $actor->actors_image) : asset('img/placeholder-actor.jpg') }}"
                                 class="movie-thumb" alt="{{ $actor->actor_name ?? 'Actor Image' }}"> 
                        </a>
                    </div>
                    <div class="movie-info">
                        <h4 class="celeb-name">{{ $actor->actor_name ?? 'Unknown Actor' }}</h4>
                    </div>
                </div>
            @empty
                <p style="color: white; padding: 20px; width: 100%; text-align: center;">No celebrities found.</p>
            @endforelse
        </div>
    </section>
    {{-- What to Watch Section --}}
<section id="what-to-watch-section" class="movie">
    <div class="what-to-watch-container">
        <h2 class="movie-category">What to watch</h2>
            <button class="pre-btn"><</button>
            <button class="nxt-btn">></button>
        @guest
            <a href="{{ route('login') }}" class="what-to-watch-link">
                <span class="link-divider">|</span> From your Watchlist <i class="fas fa-chevron-right"></i>
            </a>
            <div class="watchlist-prompt">
                <div class="prompt-icon-container">
                    <span class="prompt-icon"><i class="fas fa-plus"></i></span>
                </div>
                <h3 class="prompt-title">Log in to access your Watchlist</h3>
                <p class="prompt-description">Save shows and movies to keep track of what you want to watch.</p>
                <a href="{{ route('login') }}" class="btn btn-sign-in-prompt">
                    Log In
                </a>
            </div>
        @endguest

        @auth
            <a href="{{ route('watchlist.index') }}" class="what-to-watch-link">
                <span class="link-divider">|</span> From your Watchlist <i class="fas fa-chevron-right"></i>
            </a>
            <div class="movie-container">
                @forelse (Auth::user()->watchlistMovies()->withAvg('ratings', 'rating')->get() as $movie)
                    @php
                        // Lấy rating của user cho phim này từ quan hệ ratings (đã load sẵn hoặc eager load)
                        // Cần đảm bảo $movie->userRating được thiết lập đúng trong controller nếu không dùng eager load trực tiếp ở đây
                        $userSpecificRating = $movie->ratings()->where('user_id', Auth::id())->first();
                        $userRating = $userSpecificRating ? $userSpecificRating->rating : null;
                        $movieId = $movie->movie_id;
                    @endphp
                    <div class="movie-card" data-movie-id="{{ $movieId }}">
                        <div class="movie-image">
                            <a href="{{ route('movie.detail', ['id' => $movieId]) }}">
                                <img src="{{ $movie->movie_image ? asset('uploads/' . $movie->movie_image) : asset('img/placeholder-movie.jpg') }}"
                                     class="movie-thumb" alt="{{ $movie->movie_name ?? 'Movie Poster' }}">
                            </a>
                            {{-- Nút này nên là remove vì đang trong watchlist --}}
                            <button class="remove-watchlist-btn" data-movie-id="{{ $movieId }}"><i class="fas fa-check"></i> Added</button>
                        </div>
                        <div class="movie-info">
                            <div class="movie-rating">
                                <span class="rating-score">★ {{ number_format($movie->ratings_avg_rating ?? 0, 1) }}</span>
                                <span class="rate-button">
                                    @if ($userRating !== null && $userRating >= 0)
                                        <a href="#" class="rate-link rated" data-movie-id="{{ $movieId }}">★ {{ round($userRating) }}</a>
                                    @else
                                        <a href="#" class="rate-link" data-movie-id="{{ $movieId }}">☆ Rate</a>
                                    @endif
                                </span>
                            </div>
                            <h4 class="movie-name">{{ $movie->movie_name ?? 'Untitled Movie' }}</h4>
                        </div>

                        <div class="rating-form" data-movie-id="{{ $movieId }}" data-current-rating="{{ ($userRating !== null && $userRating >=0) ? $userRating : 0 }}" style="display: none;">
                            <button class="close-btn">×</button>
                            <div class="rating-form-icon">
                                <i class="fas fa-star"></i>
                                <span class="rating-value-display">{{ ($userRating !== null && $userRating >=0) ? round($userRating) : '?' }}</span>
                            </div>
                            <p class="rating-form-prompt">Rate This</p>
                            <h3 class="rating-form-name">{{ $movie->movie_name }}</h3>
                            <div class="stars">
                                @for ($i = 1; $i <= 10; $i++)
                                    <i class="far fa-star star" data-value="{{ $i }}"></i>
                                @endfor
                            </div>
                            <button type="button" class="btn btn-submit-rating">Rate</button>
                            <button type="button" class="btn btn-remove-rating" data-movie-id="{{ $movieId }}" style="display: {{ ($userRating !== null && $userRating >=0) ? 'inline-block' : 'none' }}; color: #ff6347; margin-top: 10px;">
                                Remove Rating
                            </button>
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
<script src="{{ asset('js/home.js') }}"></script>
@endpush