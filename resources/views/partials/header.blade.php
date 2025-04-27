{{-- resources/views/partials/header.blade.php --}}
<header id="header">
    <a href="{{ url('/') }}" class="logo">
        <img src="{{ asset('img/icons8-movie-30.png') }}" alt="Movie Insight Logo">
        <p>MoviesInsight</p>
    </a>
    <nav class="navigation">
        <input type="checkbox" class="menu-btn" id="menu-btn">
        <label for="menu-btn" class="menu-icon">
            <span class="nav-icon"></span>
        </label>
        <a class="menu-tag">Menu</a>
        <ul class="menu">
            <li><a href="{{ url('/movies') }}">Movies</a></li>
            <li><a href="{{ url('/tv-shows') }}">TV Shows</a></li>
            <li><a href="{{ url('/celebs') }}">Celebs</a></li>
            <li><a href="{{ url('/news') }}">News</a></li>
        </ul>

        {{-- Form tìm kiếm --}}
        <form action="{{ url('/') }}" method="GET" class="search-box">
            <div class="search-container">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" name="query" placeholder="Search Movie Insight" id="search-bar" autocomplete="off">
                {{-- Container cho suggestions vẫn giữ lại --}}
                <div id="suggestions" class="suggestions-list" style="display: none;"></div>
            </div>
        </form>

        {{-- Nút Watchlist --}}
        <button id="watchlist-btn"><i class="fa-solid fa-plus"></i> Watchlist</button>

        {{-- Nút Login và Sign up --}}
        <button id="login-btn">Login</button> {{-- Xóa onclick nếu xử lý trong JS --}}
        <button id="sign-up-btn">Sign up</button> {{-- Xóa onclick nếu xử lý trong JS --}}

        {{-- Phần Auth (tạm thời comment out) --}}
        {{-- @auth ... @else ... @endauth --}}

    </nav>
</header>


@push('scripts')
@endpush