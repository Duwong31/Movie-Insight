@extends('admin.layouts.app')

@section('title', 'Create New Genre')

@section('content')
    <h1>Create New Genre</h1>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.genres.store') }}" method="POST">
                @include('Genres::Admin._form') {{-- Include form dùng chung --}}
            </form>
        </div>
    </div>
@endsection