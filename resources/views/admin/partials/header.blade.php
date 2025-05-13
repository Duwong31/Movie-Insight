<nav class="navbar navbar-expand-lg navbar-light px-5" style="background-color: #3B3131;">
    <a class="navbar-brand ml-5" href="{{ route('admin.dashboard.index') }}">
        {{-- Sửa đường dẫn ảnh cho phù hợp với thư mục public của Laravel --}}
        <img src="{{ asset('images/logo.png') }}" width="80" height="80" alt="Movie Insight">
    </a>
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0"></ul>

    <div class="user-cart">
        @auth {{-- Kiểm tra người dùng đã đăng nhập chưa --}}
            @if(auth()->user()->role == 1) {{-- Giả sử role 1 là admin --}}
                <a href="#" style="text-decoration:none;">
                    <i class="fa fa-user mr-5" style="font-size:30px; color:#fff;" aria-hidden="true"></i>
                    {{-- <span class="ml-2" style="color: #fff;">{{ auth()->user()->name }}</span> --}}
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       style="text-decoration:none; color: #fff; margin-left: 15px;">
                        <i class="fa fa-sign-out" style="font-size:30px;" aria-hidden="true"></i>
                    </a>
                </form>
            @endif
        @else
            <a href="{{ route('login') }}" style="text-decoration:none;">
                <i class="fa fa-sign-in mr-5" style="font-size:30px; color:#fff;" aria-hidden="true"></i>
            </a>
        @endauth
    </div>
</nav>