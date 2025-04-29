{{-- resources/views/partials/header.blade.php --}}
<header id="header">
    <a href="{{ route('home') }}" class="logo"> {{-- Sử dụng route --}}
        <img src="{{ asset('img/icons8-movie-30.png') }}" alt="Movie Insight Logo">
        <p>MoviesInsight</p>
    </a>
    <nav class="navigation">
        {{-- Mobile menu giữ nguyên --}}
        <input type="checkbox" class="menu-btn" id="menu-btn">
        <label for="menu-btn" class="menu-icon">
            <span class="nav-icon"></span>
        </label>
        <a class="menu-tag">Menu</a>
        {{-- Desktop menu giữ nguyên --}}
        <ul class="menu">
             {{-- Sử dụng route nếu có tên --}}
            <li><a href="{{ route('movies.list') ?? url('/movies') }}">Movies</a></li>
            <li><a href="{{ route('tvshows.list') ?? url('/tv-shows') }}">TV Shows</a></li>
            <li><a href="{{ route('celebs.list') ?? url('/celebs') }}">Celebs</a></li>
            <li><a href="{{ route('news.index') ?? url('/news') }}">News</a></li>
        </ul>

        {{-- Form tìm kiếm giữ nguyên --}}
        <form action="{{ route('search') ?? url('/') }}" method="GET" class="search-box">
            <div class="search-container">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" name="query" placeholder="Search Movie Insight" id="search-bar" autocomplete="off">
                <div id="suggestions" class="suggestions-list" style="display: none;"></div>
            </div>
        </form>

        {{-- ========== PHẦN THAY ĐỔI BẮT ĐẦU TỪ ĐÂY ========== --}}

        @guest
            {{-- Guest State: Hiển thị nút Watchlist, Login, Sign up --}}
            {{-- Giữ nguyên ID để CSS áp dụng. Chuyển thành <a> để điều hướng --}}

            {{-- Nút Watchlist (khi chưa đăng nhập, có thể link tới login hoặc hiển thị thông báo) --}}
            <a href="{{ route('login') }}" id="watchlist-btn"> {{-- Giữ ID, đổi href --}}
                <i class="fa-solid fa-plus"></i> Watchlist
            </a>
            {{-- Hoặc nếu bạn muốn giữ là button và dùng JS: --}}
            {{-- <button id="watchlist-btn" onclick="window.location.href='{{ route('login') }}'">...</button> --}}


            {{-- Nút Login --}}
            @if (Route::has('login'))
                <a href="{{ route('login') }}" id="login-btn">Login</a> {{-- Giữ ID, đổi thành <a> --}}
            @endif

            {{-- Nút Sign up --}}
            @if (Route::has('register'))
                <a href="{{ route('register') }}" id="sign-up-btn">Sign up</a> {{-- Giữ ID, đổi thành <a> --}}
            @endif

        @else
            {{-- Auth State: Hiển thị nút Watchlist (trỏ đúng link), Tên User, Logout --}}

            {{-- Nút Watchlist (trỏ đến trang watchlist thực tế) --}}
            <a href="{{ route('watchlist.index') ?? '#' }}" id="watchlist-btn"> {{-- Giữ ID, đổi href --}}
                 <i class="fa-solid fa-list-check"></i> Watchlist {{-- Đổi icon nếu muốn --}}
            </a>

            {{-- Hiển thị Tên User và Logout --}}
            {{-- Sử dụng cấu trúc .dropdown mà CSS của bạn đã có --}}
            <div class="dropdown">
                {{-- Link kích hoạt dropdown (CSS của bạn style thẻ <a> đầu tiên trong .dropdown) --}}
                {{-- Chúng ta sẽ hiển thị tên user ở đây --}}
                <a href="#" class="dropdown-toggle-placeholder"> {{-- Thêm class nếu cần target JS, nhưng CSS có thể đã đủ --}}
                    <i class="fa-regular fa-user" style="margin-right: 5px;"></i> {{-- Thêm icon user nếu muốn --}}
                    <span>{{ Auth::user()->fullname ?? Auth::user()->name }}</span>
                </a>

                {{-- Menu dropdown (CSS của bạn style .dropdown-menu) --}}
                <ul class="dropdown-menu">
                    {{-- <li><a class="dropdown-item" href="#">Profile</a></li> --}}
                    {{-- <li><hr class="dropdown-divider"></li> --}}
                    <li>
                         {{-- Form logout ẩn --}}
                         <form id="logout-form-header" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                         {{-- Link Logout (sẽ submit form trên) --}}
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                         document.getElementById('logout-form-header').submit();">
                            {{ __('Logout') }}
                        </a>
                    </li>
                </ul>
            </div>

        @endguest
         {{-- ========== PHẦN THAY ĐỔI KẾT THÚC TẠI ĐÂY ========== --}}

    </nav>
</header>

{{-- Không cần push script hay style gì thêm từ đây cho phần header auth này --}}
{{-- Đảm bảo các script/style cần thiết khác (cho menu mobile, search,...) đã được load ở layout chính --}}