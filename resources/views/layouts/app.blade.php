{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('img/icons8-movie-30.png') }}">
    {{-- ... fonts ... --}}
    <!-- <link href="{{ asset('fonts/Awesome/css/all.css') }}" rel="stylesheet"> -->

    {{-- CSS Files --}}
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/swiper.css') }}">
    {{-- Đảm bảo styles.css được load sau cùng để ghi đè nếu cần --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('css/movie-list.css') }}"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('styles')
</head>
<body>
    @include('partials.header') {{-- Include header đã chỉnh sửa --}}

    <main>
        @yield('content')
    </main>

    @include('partials.footer') 
    {{-- JS Files --}}
    <script>
        // Pass data from PHP (Blade) to JavaScript
        window.config = {
         isLoggedIn: @json(Auth::check()),
         loggedInUserId: @json(Auth::id()),
         csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'), // Phẩy ở đây ĐÚNG
         urls: { // Bắt đầu đối tượng urls
             addToWatchlist: "{{ route('api.watchlist.add') }}", // Phẩy ĐÚNG
             removeFromWatchlist: "{{ route('api.watchlist.remove') }}", // Phẩy ĐÚNG
             rateMovie: "{{ route('api.rate.movie') }}", // Phẩy ĐÚNG
             removeRating: "{{ route('api.remove.rating') }}", // Phẩy ĐÚNG
             searchSuggest: "{{ route('search') }}", // Phẩy ĐÚNG
             login: "{{ route('login') }}", // Phẩy ĐÚNG
             register: "{{ route('register') }}" // Mục cuối trong urls -> KHÔNG CÓ PHẨY
         } // Kết thúc đối tượng urls -> KHÔNG CÓ PHẨY sau dấu } này
        };
    </script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/ajax.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script> {{-- Đảm bảo file này tồn tại và đã cập nhật --}}

    @stack('scripts') {{-- Chứa JS từ header và các trang con --}}
</body>
</html>