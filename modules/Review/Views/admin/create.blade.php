@extends('admin.layouts.app')

@section('title', 'Create New Review')

@section('content')
<div class="container-fluid">
    <div class="page-header mb-3">
        <h1>Create New Review</h1>
        <div class="page-header-actions">
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Reviews
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Review Details</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reviews.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="user_id" class="form-label">User <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->fullname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="movie_id" class="form-label">Movie <span class="text-danger">*</span></label>
                            <select name="movie_id" id="movie_id" class="form-select" required>
                                <option value="">Select Movie</option>
                                @foreach($movies as $movie)
                                    <option value="{{ $movie->movie_id }}" {{ old('movie_id') == $movie->movie_id ? 'selected' : '' }}>
                                        {{ $movie->movie_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Review Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control" 
                           value="{{ old('title') }}" required maxlength="255">
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Review Content <span class="text-danger">*</span></label>
                    <textarea name="content" id="content" class="form-control" rows="8" 
                              required minlength="50">{{ old('content') }}</textarea>
                    <div class="form-text">Minimum 50 characters required.</div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="rating_given" class="form-label">Rating (1-10)</label>
                            <input type="number" name="rating_given" id="rating_given" class="form-control" 
                                   value="{{ old('rating_given') }}" min="1" max="10">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Pending</option>
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Approved</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="has_spoiler" id="has_spoiler" 
                                       value="1" {{ old('has_spoiler') ? 'checked' : '' }}>
                                <label class="form-check-label" for="has_spoiler">
                                    Contains Spoilers
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="created_at" class="form-label">Custom Created Date (Optional)</label>
                            <input type="datetime-local" name="created_at" id="created_at" class="form-control" 
                                   value="{{ old('created_at') }}">
                            <div class="form-text">Leave empty to use current timestamp.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="updated_at" class="form-label">Custom Updated Date (Optional)</label>
                            <input type="datetime-local" name="updated_at" id="updated_at" class="form-control" 
                                   value="{{ old('updated_at') }}">
                            <div class="form-text">Leave empty to use current timestamp.</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-header-actions {
    flex-shrink: 0;
}
</style>
@endsection
