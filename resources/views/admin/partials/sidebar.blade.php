<div class="sidebar" id="mySidebar">
    <div class="side-header">
        <img src="{{ asset('images/logo.png') }}" width="120" height="120" alt="Movie Insight">
        <h5 style="margin-top:10px;">Hello, Admin</h5>
    </div>

    <hr style="border:1px solid; background-color:#8a7b6d; border-color:#3B3131;">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
    <a href="{{ route('admin.dashboard.index') }}"><i class="fa fa-home"></i> Dashboard</a>
    <a href="#" onclick="showCustomers()"><i class="fa fa-users"></i> Customers</a>
    <a href="#" onclick="showCategory()"><i class="fa fa-th-large"></i> Genres</a>
    <a href="#" onclick="showMovies()"><i class="fa fa-th"></i> Movies</a>
    <a href="#" onclick="showTVShows()"><i class="fa fa-th-large"></i> TV Shows</a>
</div>

<div id="main">
    {{-- Nút này sẽ nằm bên ngoài sidebar, trong main-content hoặc header nếu muốn --}}
    {{-- Nếu muốn nút này luôn ở góc trái, có thể đặt nó trong header hoặc cố định vị trí --}}
    <button class="openbtn" onclick="openNav()"><i class="fa fa-bars"></i> Menu</button>
</div>