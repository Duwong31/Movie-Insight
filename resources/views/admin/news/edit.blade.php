@extends('admin.layouts.app')

@section('title', 'Edit News Article')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit News Article</h1>
        <div>
            <a href="{{ route('admin.news.show', $news) }}" class="btn btn-info me-2">
                <i class="fas fa-eye me-2"></i>View Article
            </a>
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Article Details</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.news.update', $news) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-8">
                        <!-- Title -->
                        <div class="form-group">
                            <label for="title" class="font-weight-bold">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $news->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug -->
                        <div class="form-group">
                            <label for="slug" class="font-weight-bold">Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                   id="slug" name="slug" value="{{ old('slug', $news->slug) }}">
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">URL-friendly version of the title.</small>
                        </div>

                        <!-- Content -->
                        <div class="form-group">
                            <label for="content" class="font-weight-bold">Content <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="15" required>{{ old('content', $news->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Excerpt -->
                        <div class="form-group">
                            <label for="excerpt" class="font-weight-bold">Excerpt</label>
                            <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                      id="excerpt" name="excerpt" rows="3" 
                                      placeholder="Short summary">{{ old('excerpt', $news->excerpt) }}</textarea>
                            @error('excerpt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Brief summary for article listings.</small>
                        </div>

                        <!-- Meta Description -->
                        <div class="form-group">
                            <label for="meta_description" class="font-weight-bold">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                      id="meta_description" name="meta_description" rows="2" 
                                      maxlength="160" placeholder="SEO meta description (max 160 characters)">{{ old('meta_description', $news->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <span id="meta-count">{{ strlen(old('meta_description', $news->meta_description ?? '')) }}</span>/160 characters. Used for search engine results.
                            </small>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-4">
                        <!-- Publishing Options -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Publishing</h6>
                            </div>
                            <div class="card-body">
                                <!-- Status -->
                                <div class="form-group">
                                    <label for="status" class="font-weight-bold">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="draft" {{ old('status', $news->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status', $news->status) === 'published' ? 'selected' : '' }}>Published</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Published At -->
                                <div class="form-group">
                                    <label for="published_at" class="font-weight-bold">Publication Date</label>
                                    <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" 
                                           id="published_at" name="published_at" 
                                           value="{{ old('published_at', $news->published_at ? $news->published_at->format('Y-m-d\TH:i') : '') }}">
                                    @error('published_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Featured -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="featured" name="featured" value="1" 
                                               {{ old('featured', $news->featured) ? 'checked' : '' }}>
                                        <label class="custom-control-label font-weight-bold" for="featured">Featured Article</label>
                                    </div>
                                    <small class="form-text text-muted">Featured articles appear in special sections.</small>
                                </div>

                                <!-- Article Stats -->
                                <div class="mt-4">
                                    <h6 class="font-weight-bold text-secondary">Article Stats</h6>
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <div class="border rounded p-2">
                                                <div class="h5 mb-0 text-info">{{ number_format($news->views) }}</div>
                                                <small class="text-muted">Views</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="border rounded p-2">
                                                <div class="h5 mb-0 text-success">{{ $news->created_at->diffForHumans() }}</div>
                                                <small class="text-muted">Created</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Current Image -->
                        @if($news->image_url)
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Current Image</h6>
                            </div>
                            <div class="card-body text-center">
                                <img src="{{ asset('storage/' . $news->image_url) }}" alt="Current Image" 
                                     class="img-fluid rounded mb-2" style="max-height: 200px;">
                                <br>
                                <small class="text-muted">Current featured image</small>
                            </div>
                        </div>
                        @endif

                        <!-- Image Upload -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    {{ $news->image_url ? 'Replace Image' : 'Featured Image' }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="image" class="font-weight-bold">Image File</label>
                                    <input type="file" class="form-control-file @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Supported formats: JPEG, PNG, JPG, GIF, WebP. Max size: 2MB.
                                        @if($news->image_url)
                                            Leave empty to keep current image.
                                        @endif
                                    </small>
                                </div>

                                <!-- Image Preview -->
                                <div id="imagePreview" class="mt-3" style="display: none;">
                                    <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                            </div>
                        </div>

                        <!-- Source -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Additional Info</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="source" class="font-weight-bold">Source</label>
                                    <input type="text" class="form-control @error('source') is-invalid @enderror" 
                                           id="source" name="source" value="{{ old('source', $news->source) }}" 
                                           placeholder="e.g., Reuters, BBC, etc.">
                                    @error('source')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Original source of the news (optional).</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row">
                    <div class="col-12">
                        <hr>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.news.show', $news) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <div>
                                <button type="submit" name="action" value="save_draft" class="btn btn-warning me-2">
                                    <i class="fas fa-save me-2"></i>Save as Draft
                                </button>
                                <button type="submit" name="action" value="publish" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i>Update & Publish
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slug = title.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
        .replace(/\s+/g, '-') // Replace spaces with hyphens
        .replace(/-+/g, '-') // Remove consecutive hyphens
        .trim('-'); // Remove leading/trailing hyphens
    
    document.getElementById('slug').value = slug;
});

// Meta description character counter
document.getElementById('meta_description').addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('meta-count').textContent = count;
    
    if (count > 160) {
        document.getElementById('meta-count').classList.add('text-danger');
    } else {
        document.getElementById('meta-count').classList.remove('text-danger');
    }
});

// Image preview
document.getElementById('image').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('imagePreview').style.display = 'none';
    }
});

// Handle form submission based on button clicked
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const saveButtons = document.querySelectorAll('button[name="action"]');
    
    saveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const action = this.value;
            const statusSelect = document.getElementById('status');
            
            if (action === 'save_draft') {
                statusSelect.value = 'draft';
            } else if (action === 'publish') {
                statusSelect.value = 'published';
            }
        });
    });
});

// Initialize counter on page load
document.addEventListener('DOMContentLoaded', function() {
    const metaDesc = document.getElementById('meta_description');
    const count = metaDesc.value.length;
    document.getElementById('meta-count').textContent = count;
    
    if (count > 160) {
        document.getElementById('meta-count').classList.add('text-danger');
    }
});
</script>
@endpush
@endsection
