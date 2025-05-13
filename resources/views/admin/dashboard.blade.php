@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
    <div class="col-sm-3">
        <div class="card">
            <i class="fa fa-users mb-2" style="font-size: 70px;"></i>
            <h4 style="color:white;">Total Users</h4>
            <h5 style="color:white;">{{ $totalUsers }}</h5>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <i class="fa fa-tags" style="font-size: 70px;"></i>
            <h4 style="color:white;">Genres</h4>
            <h5 style="color:white;">{{ $totalGenres }}</h5>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <i class="fa fa-film" style="font-size: 70px;"></i>
            <h4 style="color:white;">Movies</h4>
            <h5 style="color:white;">{{ $totalMovies }}</h5>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <i class="fa fa-tv mb-2" style="font-size: 70px;"></i>
            <h4 style="color:white;">TVShows</h4>
            <h5 style="color:white;">{{ $totalTVShows }}</h5>
        </div>
    </div>
</div>


{{--
@if(session('category_success'))
    <script> alert("Category Successfully Added")</script>
@elseif(session('category_error'))
    <script> alert("Adding Unsuccess")</script>
@endif
--}}
@endsection

@push('scripts')
{{-- <script type="text/javascript" src="{{ asset('js/ajaxWork.js') }}"></script> --}}
{{-- <script type="text/javascript" src="{{ asset('js/script.js') }}"></script> --}}
@endpush