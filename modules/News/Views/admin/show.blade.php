@extends('admin.layouts.app')

@section('title', 'View News Article')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">View News Article</h1>
        <div>
            <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>Edit Article
            </a>
            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Article Content</h6>
                </div>
                <div class="card-body">
                    <!-- Title -->
                    <h2 class="mb-3">{{ $news->title }}</h2>
                    
                    <!-- Meta Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                @if($news->published_at)
                                    Published: {{ $news->published_at->format('M d, Y \a\t g:i A') }}
                                @else
                                    Created: {{ $news->created_at->format('M d, Y \a\t g:i A') }}
                                @endif
                            </small>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>{{ number_format($news->views) }} views
                            </small>
                        </div>
                    </div>

                    <!-- Badges -->
                    <div class="mb-4">
                        @if($news->status === 'published')
                            <span class="badge badge-success">Published</span>
                        @else
                            <span class="badge badge-warning">Draft</span>
                        @endif
                        
                        @if($news->featured)
                            <span class="badge badge-info">Featured</span>
                        @endif
                        
                        @if($news->source)
                            <span class="badge badge-secondary">{{ $news->source }}</span>
                        @endif
                    </div>

                    <!-- Featured Image -->
                    @if($news->image_url)
                        <div class="mb-4 text-center">
                            <img src="{{ asset('storage/' . $news->image_url) }}" alt="{{ $news->title }}" 
                                 class="img-fluid rounded shadow-sm" style="max-height: 400px;">
                        </div>
                    @endif

                    <!-- Excerpt -->
                    @if($news->excerpt)
                        <div class="mb-4">
                            <h5 class="text-muted font-italic">{{ $news->excerpt }}</h5>
                        </div>
                    @endif

                    <!-- Content -->
                    <div class="article-content">
                        {!! nl2br(e($news->content)) !!}
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            @if($news->meta_description || $news->slug)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">SEO Information</h6>
                </div>
                <div class="card-body">
                    @if($news->slug)
                        <div class="mb-3">
                            <label class="font-weight-bold text-secondary">URL Slug:</label>
                            <div class="text-muted">{{ $news->slug }}</div>
                        </div>
                    @endif
                    
                    @if($news->meta_description)
                        <div class="mb-3">
                            <label class="font-weight-bold text-secondary">Meta Description:</label>
                            <div class="text-muted">{{ $news->meta_description }}</div>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <!-- Toggle Status -->
                        <form method="POST" action="{{ route('admin.news.toggle-status', $news) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $news->status === 'published' ? 'warning' : 'success' }} w-100">
                                <i class="fas fa-{{ $news->status === 'published' ? 'eye-slash' : 'check' }} me-2"></i>
                                {{ $news->status === 'published' ? 'Unpublish' : 'Publish' }}
                            </button>
                        </form>

                        <!-- Toggle Featured -->
                        <form method="POST" action="{{ route('admin.news.toggle-featured', $news) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $news->featured ? 'secondary' : 'info' }} w-100">
                                <i class="fas fa-star{{ $news->featured ? '' : '-o' }} me-2"></i>
                                {{ $news->featured ? 'Remove from Featured' : 'Make Featured' }}
                            </button>
                        </form>

                        <!-- Edit -->
                        <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-primary w-100">
                            <i class="fas fa-edit me-2"></i>Edit Article
                        </a>

                        <!-- Delete -->
                        <form method="POST" action="{{ route('admin.news.destroy', $news) }}" 
                              onsubmit="return confirm('Are you sure you want to delete this article? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash me-2"></i>Delete Article
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Article Statistics -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Article Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="h4 mb-0 text-info">{{ number_format($news->views) }}</div>
                                <small class="text-muted">Total Views</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-3">
                                <div class="h4 mb-0 text-success">{{ $news->status === 'published' ? 'Live' : 'Draft' }}</div>
                                <small class="text-muted">Status</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="h4 mb-0 text-primary">{{ $news->created_at->format('M d') }}</div>
                                <small class="text-muted">Created</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="h4 mb-0 text-warning">{{ $news->updated_at->format('M d') }}</div>
                                <small class="text-muted">Updated</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Article Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Article Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="font-weight-bold text-secondary">ID:</label>
                        <div class="text-muted">#{{ $news->id }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold text-secondary">Status:</label>
                        <div>
                            @if($news->status === 'published')
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-warning">Draft</span>
                            @endif
                        </div>
                    </div>

                    @if($news->published_at)
                        <div class="mb-3">
                            <label class="font-weight-bold text-secondary">Published:</label>
                            <div class="text-muted">
                                {{ $news->published_at->format('M d, Y \a\t g:i A') }}
                                <br><small>({{ $news->published_at->diffForHumans() }})</small>
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="font-weight-bold text-secondary">Created:</label>
                        <div class="text-muted">
                            {{ $news->created_at->format('M d, Y \a\t g:i A') }}
                            <br><small>({{ $news->created_at->diffForHumans() }})</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold text-secondary">Last Updated:</label>
                        <div class="text-muted">
                            {{ $news->updated_at->format('M d, Y \a\t g:i A') }}
                            <br><small>({{ $news->updated_at->diffForHumans() }})</small>
                        </div>
                    </div>

                    @if($news->source)
                        <div class="mb-3">
                            <label class="font-weight-bold text-secondary">Source:</label>
                            <div class="text-muted">{{ $news->source }}</div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="font-weight-bold text-secondary">Featured:</label>
                        <div>
                            @if($news->featured)
                                <span class="badge badge-info">Yes</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold text-secondary">Word Count:</label>
                        <div class="text-muted">{{ str_word_count(strip_tags($news->content)) }} words</div>
                    </div>

                    <div class="mb-3">
                        <label class="font-weight-bold text-secondary">Character Count:</label>
                        <div class="text-muted">{{ strlen(strip_tags($news->content)) }} characters</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.article-content {
    line-height: 1.6;
    font-size: 1.1rem;
}

.article-content p {
    margin-bottom: 1.2rem;
}
</style>
@endpush
@endsection
