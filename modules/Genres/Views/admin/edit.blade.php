@extends('admin.layouts.app')

@section('title', 'Edit Genre: ' . $genre->genres_name)

@section('content')
    <h1>Edit Genre: <span class="text-primary">{{ $genre->genres_name }}</span></h1>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.genres.update', $genre) }}" method="POST">
                @method('PUT') {{-- Phương thức PUT cho update --}}
                @include('Genres::Admin._form', ['genre' => $genre]) {{-- Include form, truyền biến $genre --}}
            </form>
        </div>
    </div>
@endsection