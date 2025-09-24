@extends('admin.layouts.app')

@section('title', 'Edit Celebrity - ' . $actor->actor_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Edit Celebrity</h1>
    <div>
        <a href="{{ route('admin.actors.show', $actor) }}" class="btn btn-info">
            <i class="fas fa-eye"></i> View Details
        </a>
        <a href="{{ route('admin.actors.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-edit"></i> Edit Celebrity Information
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.actors.update', $actor) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="actor_name" class="form-label">
                                    Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="actor_name" id="actor_name" 
                                       class="form-control @error('actor_name') is-invalid @enderror" 
                                       value="{{ old('actor_name', $actor->actor_name) }}" required>
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
                                       value="{{ old('birth_date', $actor->birth_date) }}">
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
                               value="{{ old('nationality', $actor->nationality) }}" 
                               placeholder="e.g., American, British, etc.">
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
                        <div class="form-text">
                            Leave empty to keep current image. Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="biography" class="form-label">Biography</label>
                        <textarea name="biography" id="biography" rows="5" 
                                  class="form-control @error('biography') is-invalid @enderror" 
                                  placeholder="Enter celebrity biography...">{{ old('biography', $actor->biography) }}</textarea>
                        @error('biography')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.actors.show', $actor) }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Celebrity
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
                    <i class="fas fa-image"></i> Current Image
                </h5>
            </div>
            <div class="card-body text-center">
                <img id="imagePreview" 
                     src="{{ $actor->actors_image ? asset('storage/' . $actor->actors_image) : asset('img/placeholder-actor.jpg') }}" 
                     alt="Current Image" 
                     class="img-fluid rounded"
                     style="max-height: 300px; object-fit: cover;">
                
                @if($actor->actors_image)
                    <div class="mt-3">
                        <small class="text-muted">Current image will be replaced if you upload a new one</small>
                    </div>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle"></i> Celebrity Stats
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="fas fa-film text-primary me-2"></i>
                        <strong>{{ $actor->movies->count() }}</strong> Movies/Shows
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-calendar text-info me-2"></i>
                        @if($actor->birth_date)
                            <strong>{{ \Carbon\Carbon::parse($actor->birth_date)->age }}</strong> years old
                        @else
                            Age not specified
                        @endif
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-flag text-success me-2"></i>
                        <strong>{{ $actor->nationality ?? 'Not specified' }}</strong>
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-clock text-warning me-2"></i>
                        Added {{ $actor->created_at ? $actor->created_at->diffForHumans() : 'Unknown' }}
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
