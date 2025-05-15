@extends('layouts.app') {{-- Hoặc layout chính của bạn --}}

@section('title', 'Search Results for "' . e($query) . '"')

@section('content')
<div class="container mt-5">
    <h1>Search Results for "<em>{{ e($query) }}</em>"</h1>

    @if($results->isEmpty())
        <p>No movies found matching your search criteria.</p>
    @else
        <div class="row">
            @foreach($results as $item)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <a href="{{ route('movie.detail', ['id' => $item->movie_id]) }}">
                            <img src="{{ $item->movie_image ? asset('uploads/' . $item->movie_image) : asset('img/placeholder-movie.jpg') }}" class="card-img-top" alt="{{ $item->movie_name }}" style="height: 300px; object-fit: cover;">
                        </a>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('movie.detail', ['id' => $item->movie_id]) }}">{{ $item->movie_name }}</a>
                            </h5>
                            <p class="card-text">
                                <small class="text-muted">{{ $item->release_date }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection