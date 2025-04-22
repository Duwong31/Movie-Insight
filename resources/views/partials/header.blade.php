{{-- resources/views/partials/header.blade.php --}}

<header id="header">
    {{-- Sử dụng url('/') cho trang chủ nếu chưa chắc route 'home' tồn tại --}}
    <a href="{{ url('/') }}" class="logo">
        <img src="{{ asset('img/icons8-movie-30.png') }}" alt="Movie Insight Logo">
        <p>Movies<br>Insight</p>
    </a>
    <nav class="navigation">
        <input type="checkbox" class="menu-btn" id="menu-btn">
        <label for="menu-btn" class="menu-icon">
            <span class="nav-icon"></span>
        </label>
        <a class="menu-tag">Menu</a>
        <ul class="menu">
            {{-- Sử dụng URL tĩnh hoặc # tạm thời cho các link menu --}}
            <li><a href="{{ url('/movies') }}">Movies</a></li> {{-- Hoặc href="#" --}}
            <li><a href="{{ url('/tv-shows') }}">TV Shows</a></li> {{-- Hoặc href="#" --}}
            <li><a href="{{ url('/celebs') }}">Celebs</a></li> {{-- Hoặc href="#" --}}
            <li><a href="{{ url('/news') }}">News</a></li> {{-- Hoặc href="#" --}}
        </ul>

        {{-- Form tìm kiếm - Action tạm thời trỏ về # hoặc route trang chủ --}}
        <form action="{{ url('/') }}" method="GET" class="search-box"> {{-- Hoặc action="#" --}}
            <div class="search-container">
                <input type="text" name="query" class="search-input" placeholder="Search Movie Insight" id="search-bar" autocomplete="off">
                <div id="suggestions" class="suggestions-list" style="display: none;"></div> {{-- Đảm bảo ẩn mặc định --}}
                {{-- Icon clear có thể cần JS --}}
            </div>
        </form>

        {{-- Nút Watchlist - Tạm thời chỉ là button, JS sẽ xử lý sau --}}
        <button id="watchlist-btn"><i class="fa-solid fa-plus"></i> Watchlist</button>

        {{-- Tạm thời hiển thị nút Login và Sign up --}}
        {{-- Thay thế bằng URL bạn sẽ dùng cho trang đăng nhập/đăng ký --}}
        <button id="login-btn" onclick="window.location.href='{{ url('/login-placeholder') }}'">Login</button> {{-- Hoặc dùng JS addEventListener --}}
        <button id="sign-up-btn" onclick="window.location.href='{{ url('/signup-placeholder') }}'">Sign up</button> {{-- Hoặc dùng JS addEventListener --}}

        {{-- ---- PHẦN AUTH TẠM THỜI COMMENT OUT ----
        @auth {{-- Kiểm tra xem người dùng đã đăng nhập chưa --}}
            <!-- Profile Dropdown -->
            {{-- <div class="dropdown text-end">
                <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-user"></i>
                </a>
                <ul class="dropdown-menu text-small">
                    <li><a class="dropdown-item" href="#">Your watchlist</a></li>
                    <li><a class="dropdown-item" href="#">Your ratings</a></li>
                    <li><a class="dropdown-item" href="#">Account Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="#" id="logout-form"> {{-- Action tạm thời --}}
                            {{-- @csrf --}} {{-- Cần cho form POST thật --}}
                            {{-- <a class="dropdown-item" href="#"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Đăng xuất
                            </a> --}}
                        {{-- </form>
                    </li>
                </ul>
            </div> --}}
        {{-- @else {{-- Nếu chưa đăng nhập --}}
            <!-- Login and Sign Up Buttons -->
             {{-- <button id="login-btn">Login</button>
             <button id="sign-up-btn">Sign up</button> --}}
        {{-- @endauth
        ---- KẾT THÚC PHẦN AUTH COMMENT OUT ---- --}}

    </nav>
</header>

{{-- Đẩy JS liên quan đến header (nếu có) vào stack scripts --}}
@push('scripts')
<script>
    // Tạm thời xử lý nút Watchlist: chỉ hiện thông báo (nếu chưa đăng nhập)
    document.getElementById('watchlist-btn')?.addEventListener('click', function() {
        // Khi có logic auth, bạn sẽ kiểm tra ở đây
        alert('Watchlist feature requires login (coming soon).');
        // Ví dụ khi có auth:
        // if (isLoggedIn) { // biến isLoggedIn lấy từ Blade như bước trước
        //     window.location.href = '{{ url("/watchlist-placeholder") }}'; // Thay bằng route thật
        // } else {
        //     window.location.href = '{{ url("/login-placeholder") }}'; // Thay bằng route thật
        // }
    });

    // Logic nút Login/Signup (nếu không dùng onclick inline)
    // document.getElementById('login-btn')?.addEventListener("click", function() {
    //     window.location.href = "{{ url('/login-placeholder') }}"; // Thay bằng route thật khi có
    // });
    // document.getElementById('sign-up-btn')?.addEventListener("click", function() {
    //      window.location.href = "{{ url('/signup-placeholder') }}"; // Thay bằng route thật khi có
    // });

    // --- Logic Search Suggestions (Tạm thời vô hiệu hóa fetch) ---
    const searchInputHeader = document.getElementById('search-bar');
    const suggestionsContainerHeader = document.getElementById('suggestions');

    if (searchInputHeader && suggestionsContainerHeader) {
        searchInputHeader.addEventListener('keyup', function() {
            const query = this.value;
            if (query.length < 2) {
                suggestionsContainerHeader.style.display = 'none';
                suggestionsContainerHeader.innerHTML = '';
                return;
            }
            console.log("Search query:", query); // Log để biết đang gõ
            // *** Tạm thời comment out phần fetch API ***
            /*
            const searchUrl = '{{ url("/api/search-placeholder") }}'; // Thay bằng API route thật

            fetch(`${searchUrl}?query=${encodeURIComponent(query)}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(response => response.ok ? response.json() : Promise.reject('Network error'))
            .then(data => {
                suggestionsContainerHeader.innerHTML = ""; // Clear previous
                if (data && data.length > 0) {
                     data.forEach(item => {
                        const div = document.createElement("div");
                        div.classList.add("suggestion-item");
                        div.innerHTML = `...`; // Tạo HTML suggestion item
                        div.onclick = () => { window.location.href = item.url; };
                        suggestionsContainerHeader.appendChild(div);
                    });
                    suggestionsContainerHeader.style.display = "block";
                } else {
                    suggestionsContainerHeader.style.display = "none";
                }
            })
            .catch(error => {
                console.error("Search Error:", error);
                suggestionsContainerHeader.style.display = "none";
            });
            */
            // Hiển thị tạm thời để test layout
            suggestionsContainerHeader.innerHTML = `<div class="suggestion-item"><i>Search for "${query}"... (API disabled)</i></div>`;
            suggestionsContainerHeader.style.display = "block";

        });

        // Đóng suggestions khi click ra ngoài
        document.addEventListener('click', function(event) {
            if (suggestionsContainerHeader && !searchInputHeader.contains(event.target) && !suggestionsContainerHeader.contains(event.target)) {
                suggestionsContainerHeader.style.display = 'none';
            }
        });
    }
</script>
@endpush