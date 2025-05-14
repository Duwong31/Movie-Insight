{{-- Modules/Movies/Views/admin/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', $title ?? 'Add New Movie')

@section('content')
<div class="container">
    <h1>{{ $title ?? 'Add New Movie' }}</h1>

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

    <form action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-8">
                {{-- Movie Name --}}
                <div class="mb-3">
                    <label for="movie_name" class="form-label">Movie Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('movie_name') is-invalid @enderror" id="movie_name" name="movie_name" value="{{ old('movie_name') }}" required>
                    @error('movie_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label for="movie_desc" class="form-label">Description</label>
                    <textarea class="form-control @error('movie_desc') is-invalid @enderror" id="movie_desc" name="movie_desc" rows="5">{{ old('movie_desc') }}</textarea>
                    @error('movie_desc') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    {{-- Director --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="director" class="form-label">Director</label>
                            <input type="text" class="form-control @error('director') is-invalid @enderror" id="director" name="director" value="{{ old('director') }}">
                            @error('director') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    {{-- Release Year --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="release_date" class="form-label">Release Year</label>
                            <input type="number" class="form-control @error('release_date') is-invalid @enderror" id="release_date" name="release_date" value="{{ old('release_date') }}" min="1900" max="{{ date('Y') + 10 }}"> {{-- Cho phép năm tới 10 năm sau --}}
                            @error('release_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    {{-- Duration --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="duration" class="form-label">Duration (e.g., 2h 30m)</label>
                            <input type="text" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration') }}">
                            @error('duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    {{-- Trailer URL --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="trailer_url" class="form-label">Trailer URL (Youtube Embed Link)</label>
                            <input type="url" class="form-control @error('trailer_url') is-invalid @enderror" id="trailer_url" name="trailer_url" value="{{ old('trailer_url') }}" placeholder="https://www.youtube.com/embed/your_video_id">
                            @error('trailer_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                {{-- Genres Checkboxes (Dropdown Style) --}}
                <div class="mb-3">
                    <label class="form-label">Genres <span class="text-danger">*</span></label>
                    <div class="dropdown genres-dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle w-100 text-start @error('genres') is-invalid @enderror @error('genres.*') is-invalid @enderror" type="button" id="genresDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Select Genres
                        </button>
                        <ul class="dropdown-menu w-100 genres-checkbox-group" aria-labelledby="genresDropdownButton">
                            @if(isset($genres) && $genres->count() > 0)
                                @foreach ($genres as $genre)
                                <li>
                                    <div class="dropdown-item">
                                        <input class="form-check-input" type="checkbox" name="genres[]" id="genre_{{ $genre->genres_id }}" value="{{ $genre->genres_id }}"
                                               {{ (is_array(old('genres')) && in_array($genre->genres_id, old('genres'))) ? 'checked' : '' }}>
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
                    <input type="text" class="form-control @error('actors_input') is-invalid @enderror" id="actors_input" name="actors_input" value="{{ old('actors_input') }}" placeholder="e.g., Tom Hanks, Scarlett Johansson">
                    @error('actors_input') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

            </div> {{-- End col-md-8 --}}

            <div class="col-md-4">
                {{-- Status --}}
                <div class="mb-3">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="">-- Select Status --</option>
                        <option value="in_theaters" {{ old('status') == 'in_theaters' ? 'selected' : '' }}>In Theaters</option>
                        <option value="streaming" {{ old('status') == 'streaming' ? 'selected' : '' }}>Streaming</option>
                        <option value="coming soon" {{ old('status') == 'coming soon' ? 'selected' : '' }}>Coming Soon</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Movie Image --}}
                <div class="mb-3">
                    <label for="movie_image" class="form-label">Movie Image <span class="text-danger">*</span></label>
                    <input type="file" class="form-control @error('movie_image') is-invalid @enderror" id="movie_image" name="movie_image" required onchange="previewImage(event)">
                    <img id="image_preview" src="#" alt="Image Preview" style="max-width: 100%; max-height: 200px; margin-top: 10px; display: none;"/>
                    @error('movie_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div> {{-- End col-md-4 --}}
        </div> {{-- End row --}}

        <div class="mt-3"> {{-- Thêm khoảng cách cho nút submit --}}
            <button type="submit" class="btn btn-primary">Add Movie</button>
            <a href="{{ route('admin.movies.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>


@push('styles')
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
        cursor: default; /* Để không có hiệu ứng con trỏ tay khi hover item */
    }
    .genres-checkbox-group .form-check-input {
        margin-right: 0.5rem;
        margin-top: 0;
    }
    .genres-checkbox-group .form-check-label {
        cursor: pointer; /* Cho phép click vào label để chọn */
        flex-grow: 1; /* Đảm bảo label chiếm hết không gian còn lại */
        user-select: none; /* Ngăn chọn văn bản của label */
    }
    .dropdown-toggle.is-invalid { /* Style cho nút dropdown khi có lỗi */
        border-color: #dc3545;
    }
    .genres-checkbox-group.is-invalid { /* Style cho phần ul khi có lỗi (nếu cần) */
        /* border: 1px solid #dc3545; */ /* Có thể không cần nếu nút đã báo lỗi */
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
                    // Lấy text từ label tương ứng
                    const label = checkbox.closest('.dropdown-item').querySelector('.form-check-label');
                    if (label) {
                        selectedGenres.push(label.textContent.trim());
                    }
                }
            });

            if (genresDropdownButton) {
                if (selectedGenres.length > 0) {
                    let buttonText = selectedGenres.join(', ');
                    if (buttonText.length > 35) { // Giới hạn độ dài text hiển thị trên nút
                        buttonText = selectedGenres.length + ' genres selected';
                    }
                    genresDropdownButton.textContent = buttonText;
                } else {
                    genresDropdownButton.textContent = 'Select Genres';
                }
            }
        }

        // Cập nhật text nút khi tải trang (dựa trên old input)
        updateGenresButtonText();

        // Ngăn dropdown tự đóng khi click vào checkbox hoặc label
        document.querySelectorAll('.genres-checkbox-group .dropdown-item').forEach(function (item) {
            item.addEventListener('click', function (e) {
                e.stopPropagation();
                // Nếu click vào label, tự động check/uncheck checkbox tương ứng
                const checkbox = item.querySelector('input[type="checkbox"]');
                if (checkbox && e.target.tagName === 'LABEL') {
                    checkbox.checked = !checkbox.checked;
                    // Kích hoạt sự kiện change để cập nhật text nút
                    const changeEvent = new Event('change', { bubbles: true });
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

@endsection