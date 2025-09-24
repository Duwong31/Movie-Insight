@extends('admin.layouts.app')

@section('title', 'Add New Celebrity')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Add New Celebrity</h1>
    <a href="{{ route('admin.actors.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Celebrities
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-plus"></i> Celebrity Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.actors.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="actor_name" class="form-label">
                                    Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="actor_name" id="actor_name" 
                                       class="form-control @error('actor_name') is-invalid @enderror" 
                                       value="{{ old('actor_name') }}" required>
                                @error('actor_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="birth_date" class="form-label">Birth Date</label>
                                <input type="date" name="birth_date" id="birth_date" 
                                       class="form-control @error('birth_date') is-invalid @enderror" 
                                       value="{{ old('birth_date') }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nationality" class="form-label">Nationality</label>
                        <input type="text" name="nationality" id="nationality" 
                               class="form-control @error('nationality') is-invalid @enderror" 
                               value="{{ old('nationality') }}" placeholder="e.g., American, British, etc.">
                        @error('nationality')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="actors_image" class="form-label">Profile Image</label>
                        <input type="file" name="actors_image" id="actors_image" 
                               class="form-control @error('actors_image') is-invalid @enderror" 
                               accept="image/*" onchange="previewImage(this)">
                        @error('actors_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB</div>
                    </div>

                    <div class="mb-3">
                        <label for="biography" class="form-label">Biography</label>
                        <textarea name="biography" id="biography" rows="5" 
                                  class="form-control @error('biography') is-invalid @enderror" 
                                  placeholder="Enter celebrity biography...">{{ old('biography') }}</textarea>
                        @error('biography')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.actors.index') }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Celebrity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-image"></i> Image Preview
                </h5>
            </div>
            <div class="card-body text-center">
                <img id="imagePreview" src="{{ asset('img/placeholder-actor.jpg') }}" 
                     alt="Image Preview" 
                     class="img-fluid rounded"
                     style="max-height: 300px; object-fit: cover;">
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle"></i> Tips
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Use high-quality images for better presentation
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Portrait orientation works best
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Fill in all available information
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Biography helps users learn more
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
