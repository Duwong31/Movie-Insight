<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - MovieInsight Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background-color: #1a1a1a;
            color: #fff;
        }
        .card {
            background-color: #2d2d2d;
            border: none;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card i {
            color: #e70634;
        }
        /* Sidebar styles */
        .sidebar {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: #2d2d2d;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }
        .sidebar a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 16px;
            color: #fff;
            display: block;
            transition: 0.3s;
        }
        .sidebar a:hover {
            color: #e70634;
        }
        .sidebar .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
            margin-left: 50px;
        }
        .openbtn {
            font-size: 20px;
            cursor: pointer;
            background-color: transparent;
            color: white;
            padding: 10px 15px;
            border: none;
        }
        .openbtn:hover {
            color: #e70634;
        }
        #main {
            transition: margin-left .5s;
            padding: 16px;
        }
        @media screen and (max-height: 450px) {
            .sidebar {padding-top: 15px;}
            .sidebar a {font-size: 18px;}
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <button class="openbtn" onclick="openNav()"><i class="fa fa-bars"></i></button>
            <a class="navbar-brand" href="{{ route('admin.index') }}">MovieInsight Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.index') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                </ul>
                <!-- Thêm dropdown menu cho admin -->
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fa-user"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fa fa-key"></i> Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div id="mySidebar" class="sidebar">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="{{ route('admin.index') }}"><i class="fa fa-home"></i> Dashboard</a>
        <a href="#"><i class="fa fa-users"></i> Users</a>
        <a href="#"><i class="fa fa-film"></i> Movies</a>
        <a href="#"><i class="fa fa-tv"></i> TV Shows</a>
        <a href="#"><i class="fa fa-tags"></i> Genres</a>
        <a href="#"><i class="fa fa-newspaper"></i> News</a>
        <a href="#"><i class="fa fa-comments"></i> Comments</a>
        <a href="#"><i class="fa fa-star"></i> Celebrity</a>
        <a href="#"><i class="fa fa-cog"></i> Settings</a>
    </div>

    <div id="main">
        <div class="container mt-4">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Sidebar Script -->
    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }
    </script>
    
    @stack('scripts')
</body>
</html>
