{{-- Modules/Movies/Views/admin/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', $title ?? 'Edit Movie')

@section('content')
<div class="container">
    <h1>{{ $title ?? 'Edit Movie' }}: {{ $movie->movie_name }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.movies.update', $movie->movie_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Quan trọng cho update --}}

        <div class="row">
            <div class="col-md-8">
                {{-- Movie Name --}}
                <div class="mb-3">
                    <label for="movie_name" class="form-label">Movie Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('movie_name') is-invalid @enderror" id="movie_name" name="movie_name" value="{{ old('movie_name', $movie->movie_name) }}" required>
                    @error('movie_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="movie_desc" class="form-label">Description</label>
                    <textarea class="form-control @error('movie_desc') is-invalid @enderror" id="movie_desc" name="movie_desc" rows="5">{{ old('movie_desc', $movie->movie_desc) }}</textarea>
                    @error('movie_desc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    {{-- Director --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="director" class="form-label">Director</label>
                            <input type="text" class="form-control @error('director') is-invalid @enderror" id="director" name="director" value="{{ old('director', $movie->director) }}">
                            @error('director') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    {{-- Release Year --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="release_date" class="form-label">Release Year</label>
                            <input type="number" class="form-control @error('release_date') is-invalid @enderror" id="release_date" name="release_date" value="{{ old('release_date', $movie->release_date) }}" min="1900" max="{{ date('Y') + 10 }}">
                            @error('release_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Duration --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="duration" class="form-label">Duration (e.g., 2h 30m)</label>
                            <input type="text" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration', $movie->duration) }}">
                            @error('duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    {{-- Trailer URL --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="trailer_url" class="form-label">Trailer URL (Youtube Embed Link)</label>
                            <input type="url" class="form-control @error('trailer_url') is-invalid @enderror" id="trailer_url" name="trailer_url" value="{{ old('trailer_url', $movie->trailer_url) }}" placeholder="https://www.youtube.com/embed/your_video_id">
                            @error('trailer_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Genres Checkboxes (Dropdown Style) --}}
                <div class="mb-3">
                    <label class="form-label">Genres <span class="text-danger">*</span></label>
                    <div class="dropdown genres-dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start @error('genres') is-invalid @enderror @error('genres.*') is-invalid @enderror" type="button" id="genresDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Select Genres {{-- Sẽ được cập nhật bởi JS --}}
                        </button>
                        <ul class="dropdown-menu w-100 genres-checkbox-group" aria-labelledby="genresDropdownButton">
                            @if(isset($genres) && $genres->count() > 0)
                                @foreach ($genres as $genre)
                                <li>
                                    <div class="dropdown-item">
                                        <input class="form-check-input" type="checkbox" name="genres[]" id="genre_{{ $genre->genres_id }}" value="{{ $genre->genres_id }}"
                                               {{-- Ưu tiên old input, sau đó mới đến giá trị hiện tại của phim --}}
                                               {{ (is_array(old('genres')) && in_array($genre->genres_id, old('genres'))) || (!old('genres') && isset($movieGenreIds) && in_array($genre->genres_id, $movieGenreIds)) ? 'checked' : '' }}
                                               >
                                        <label class="form-check-label" for="genre_{{ $genre->genres_id }}">{{ $genre->genres_name }}</label>
                                    </div>
                                </li>
                                @endforeach
                            @else
                                <li><span class="dropdown-item-text">No genres available.</span></li>
                            @endif
                        </ul>
                    </div>
                    @error('genres') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    @error('genres.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>


                {{-- Actors Input Text --}}
                <div class="mb-3">
                    <label for="actors_input" class="form-label">Actors (comma-separated names)</label>
                    <input type="text" class="form-control @error('actors_input') is-invalid @enderror" id="actors_input" name="actors_input" value="{{ old('actors_input', $actorsInputString ?? '') }}" placeholder="e.g., Tom Hanks, Scarlett Johansson">
                    @error('actors_input') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

            </div> {{-- End col-md-8 --}}

            <div class="col-md-4">
                {{-- Status --}}
                <div class="mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="">-- Select Status --</option>
                        <option value="in_theaters" {{ old('status', $movie->status) == 'in_theaters' ? 'selected' : '' }}>In Theaters</option>
                        <option value="streaming" {{ old('status', $movie->status) == 'streaming' ? 'selected' : '' }}>Streaming</option>
                        <option value="coming soon" {{ old('status', $movie->status) == 'coming soon' ? 'selected' : '' }}>Coming Soon</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Movie Image --}}
                <div class="mb-3">
                    <label for="movie_image" class="form-label">Movie Image (Leave blank to keep current)</label>
                    <input type="file" class="form-control @error('movie_image') is-invalid @enderror" id="movie_image" name="movie_image" onchange="previewImage(event)">
                     @if ($movie->movie_image)
                        <div class="mt-2">
                            <img id="image_preview" src="{{ asset('uploads/' . $movie->movie_image) }}" alt="Current Image" style="max-width: 100%; max-height: 200px; display: block;"/>
                            <small class="form-text text-muted">Current image.</small>
                        </div>
                    @else
                        <img id="image_preview" src="#" alt="Image Preview" style="max-width: 100%; max-height: 200px; margin-top: 10px; display: none;"/>
                    @endif
                    @error('movie_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div> {{-- End col-md-4 --}}
        </div> {{-- End row --}}

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Update Movie</button>
            <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('styles')
{{-- Copy style từ create.blade.php --}}
<style>
    .genres-dropdown .dropdown-menu {
        min-width: 100%;
    }
    .genres-checkbox-group {
        max-height: 250px;
        overflow-y: auto;
        padding: 0.5rem 0;
    }
    .genres-checkbox-group .dropdown-item {
        padding: .25rem 1rem;
        display: flex;
        align-items: center;
        cursor: default;
    }
    .genres-checkbox-group .form-check-input {
        margin-right: 0.5rem;
        margin-top: 0;
    }
    .genres-checkbox-group .form-check-label {
        cursor: pointer;
        flex-grow: 1;
        user-select: none;
    }
    .dropdown-toggle.is-invalid {
        border-color: #dc3545;
    }
</style>
@endpush

@push('scripts')
<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('image_preview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const genresDropdownButton = document.getElementById('genresDropdownButton');
        const genreCheckboxes = document.querySelectorAll('.genres-checkbox-group input[type="checkbox"]');

        function updateGenresButtonText() {
            const selectedGenres = [];
            genreCheckboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    const label = checkbox.closest('.dropdown-item').querySelector('.form-check-label');
                    if (label) {
                        selectedGenres.push(label.textContent.trim());
                    }
                }
            });

            if (genresDropdownButton) {
                if (selectedGenres.length > 0) {
                    let buttonText = selectedGenres.join(', ');
                    if (buttonText.length > 35) { // Giới hạn độ dài text hiển thị
                        buttonText = selectedGenres.length + ' genres selected';
                    }
                    genresDropdownButton.textContent = buttonText;
                } else {
                    genresDropdownButton.textContent = 'Select Genres';
                }
            }
        }

        // Cập nhật text nút khi tải trang
        updateGenresButtonText();

        // Ngăn dropdown đóng khi click vào checkbox hoặc label
        document.querySelectorAll('.genres-checkbox-group .dropdown-item').forEach(function (item) {
            item.addEventListener('click', function (e) {
                e.stopPropagation();
                const checkbox = item.querySelector('input[type="checkbox"]');
                if (checkbox && e.target.tagName === 'LABEL') {
                    checkbox.checked = !checkbox.checked;
                    const changeEvent = new Event('change', { bubbles: true }); // Kích hoạt sự kiện change
                    checkbox.dispatchEvent(changeEvent);
                }
            });
        });

        // Cập nhật text nút khi trạng thái checkbox thay đổi
         genreCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', updateGenresButtonText);
        });
    });
</script>
@endpush
