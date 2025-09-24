@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0">Dashboard</h1>
</div>

<!-- Stat Cards Row -->
<div class="row">

    <!-- Total Users Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('admin.users.index') }}" class="card-link">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col">
                            <div class="text-xs text-primary mb-1">Total Users</div>
                            <div class="h5 mb-0 fw-bold">{{ $totalUsers }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Total Genres Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('admin.genres.index') }}" class="card-link">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col">
                            <div class="text-xs text-success mb-1">Total Genres</div>
                            <div class="h5 mb-0 fw-bold">{{ $totalGenres }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Total Movies Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('admin.movies.index') }}" class="card-link">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col">
                            <div class="text-xs text-info mb-1">Total Movies</div>
                            <div class="h5 mb-0 fw-bold">{{ $totalMovies }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-film fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Total TV Shows Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('admin.tvshows.index') }}" class="card-link">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col">
                            <div class="text-xs text-warning mb-1">TV Shows</div>
                            <div class="h5 mb-0 fw-bold">{{ $totalTVShows }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tv fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Total Celebrities Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <a href="{{ route('admin.actors.index') }}" class="card-link">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col">
                            <div class="text-xs text-danger mb-1">Celebrities</div>
                            <div class="h5 mb-0 fw-bold">{{ $totalActors }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

@endsection

@push('scripts')
{{-- <script type="text/javascript" src="{{ asset('js/ajaxWork.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('js/script.js') }}"></script> --}}
@endpush