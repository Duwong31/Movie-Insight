@csrf {{-- Token CSRF cho form --}}

<div class="mb-3">
    <label for="genres_name" class="form-label">Genre Name <span class="text-danger">*</span></label>
    <input type="text" class="form-control @error('genres_name') is-invalid @enderror"
           id="genres_name" name="genres_name"
           value="{{ old('genres_name', isset($genre) ? $genre->genres_name : '') }}" required>
    @error('genres_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-success">
        <i class="fa fa-save"></i> {{ isset($genre) ? 'Update Genre' : 'Create Genre' }}
    </button>
    <a href="{{ route('admin.genres.index') }}" class="btn btn-secondary">
        <i class="fa fa-times"></i> Cancel
    </a>
</div>