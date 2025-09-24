@extends('admin.layouts.app')

@section('title', 'News Management')

@push('styles')
<style>
.stats-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.action-buttons .btn {
    margin: 0 2px;
    border-radius: 6px;
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
}

.table-modern {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.table-modern thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.table-modern tbody tr {
    transition: all 0.3s ease;
}

.table-modern tbody tr:hover {
    background-color: #f8f9fc;
    transform: scale(1.02);
}

.badge {
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 10px;
    padding: 0.5rem 0.8rem;
}

.badge-success {
    background: linear-gradient(135deg, #1cc88a, #13855c);
}

.badge-warning {
    background: linear-gradient(135deg, #f6c23e, #dda20a);
}

.badge-info {
    background: linear-gradient(135deg, #36b9cc, #258391);
}

.badge-secondary {
    background: linear-gradient(135deg, #858796, #60616f);
}

.filter-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.btn-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    border-radius: 25px;
    font-weight: 600;
    padding: 0.7rem 1.5rem;
    transition: all 0.3s ease;
}

.btn-gradient-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 7px 14px rgba(50, 50, 93, .1), 0 3px 6px rgba(0, 0, 0, .08);
    color: white;
}

.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
}

.image-thumbnail {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.image-thumbnail:hover {
    transform: scale(1.1);
}

.table-actions {
    white-space: nowrap;
}

.dropdown-modern .dropdown-toggle {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 25px;
    color: white;
    font-weight: 600;
    padding: 0.7rem 1.5rem;
}

.dropdown-modern .dropdown-menu {
    border: none;
    border-radius: 10px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state i {
    color: #d1ecf1;
    margin-bottom: 1rem;
}

.article-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.3rem;
}

.article-slug {
    color: #7f8c8d;
    font-size: 0.8rem;
}

.status-published {
    background: linear-gradient(135deg, #1cc88a, #13855c);
    color: white;
}

.status-draft {
    background: linear-gradient(135deg, #f6c23e, #dda20a);
    color: white;
}

.featured-yes {
    background: linear-gradient(135deg, #36b9cc, #258391);
    color: white;
}

.featured-no {
    background: linear-gradient(135deg, #858796, #60616f);
    color: white;
}

/* Additional Modern Styles */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.modern-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    overflow: hidden;
}

.table-hover tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.05);
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

.thead-light th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: white !important;
}

.custom-control-input:checked~.custom-control-label::before {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
}

.btn-group-sm>.btn, .btn-sm {
    border-radius: 20px;
    font-weight: 600;
    padding: 0.4rem 1rem;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
}

.btn-success {
    background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    border: none;
}

.btn-warning {
    background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
    border: none;
}

.btn-danger {
    background: linear-gradient(135deg, #e74a3b 0%, #c0392b 100%);
    border: none;
    background-color: transparent !important;
}

.badge {
    border-radius: 12px;
    font-weight: 600;
    padding: 0.5rem 0.8rem;
}

.article-row {
    border-left: 4px solid transparent;
    transition: all 0.3s ease;
}

.article-row:hover {
    border-left-color: #667eea;
}

.article-image {
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.article-image:hover {
    transform: scale(1.05);
}

.dropdown-menu {
    border: none;
    border-radius: 12px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    overflow: hidden;
}

.dropdown-header {
    background: linear-gradient(135deg, #f8f9fc 0%, #eaecf4 100%);
    color: #5a5c69;
    font-weight: 700;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Modern Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-2">📰 News Management</h1>
                <p class="mb-0 opacity-75">Manage and organize your news articles</p>
            </div>
            <a href="{{ route('admin.news.create') }}" class="btn btn-light btn-lg">
                <i class="fas fa-plus me-2"></i>Add New Article
            </a>
        </div>
    </div>

    <!-- Modern Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-left-primary h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Articles</div>                                            <div class="h4 mb-0 font-weight-bold text-white">{{ number_format($statistics['total']) }}</div>
                            <small class="text-muted">All articles in system</small>
                        </div>
                        <div class="col-auto">
                            <div class="text-center">
                                <i class="fas fa-newspaper fa-2x text-primary mb-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-left-success h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Published</div>                                            <div class="h4 mb-0 font-weight-bold text-white">{{ number_format($statistics['published']) }}</div>
                            <small class="text-muted">Live articles</small>
                        </div>
                        <div class="col-auto">
                            <div class="text-center">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-left-warning h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Drafts</div>                                            <div class="h4 mb-0 font-weight-bold text-white">{{ number_format($statistics['draft']) }}</div>
                            <small class="text-muted">In progress</small>
                        </div>
                        <div class="col-auto">
                            <div class="text-center">
                                <i class="fas fa-edit fa-2x text-warning mb-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-left-info h-100 py-3">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Views</div>                                            <div class="h4 mb-0 font-weight-bold text-white">{{ number_format($statistics['total_views']) }}</div>
                            <small class="text-muted">Reader engagement</small>
                        </div>
                        <div class="col-auto">
                            <div class="text-center">
                                <i class="fas fa-eye fa-2x text-info mb-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters & Actions -->
    <div class="card modern-card mb-4">
        <div class="card-header bg-gradient-primary">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-filter mr-2"></i>Filters & Actions
            </h6>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.news.index') }}" class="advanced-filters">
                <div class="row">
                    <!-- Search Bar -->
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label for="search" class="form-label text-sm font-weight-bold text-white">Search Articles</label>
                            <div class="input-group">
                                <input type="text" 
                                       name="search" 
                                       id="search"
                                       class="form-control border-left-0 pl-0" 
                                       placeholder="Title, content, or author..."
                                       value="{{ request('search') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-2 mb-3">
                        <div class="form-group">
                            <label for="status" class="form-label text-sm font-weight-bold text-white">Status</label>
                            <select name="status" id="status" class="form-control custom-select">
                                <option value="">All Status</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                    </div>

                    <!-- Featured Filter -->
                    <div class="col-md-2 mb-3">
                        <div class="form-group">
                            <label for="featured" class="form-label text-sm font-weight-bold text-white">Featured</label>
                            <select name="featured" id="featured" class="form-control custom-select">
                                <option value="">All Articles</option>
                                <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured Only</option>
                                <option value="0" {{ request('featured') == '0' ? 'selected' : '' }}>Non-Featured</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sort Filter -->
                    <div class="col-md-2 mb-3">
                        <div class="form-group">
                            <label for="sort" class="form-label text-sm font-weight-bold text-white">Sort By</label>
                            <select name="sort" id="sort" class="form-control custom-select">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A-Z</option>
                                <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Most Views</option>
                            </select>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-md-2 mb-3">
                        <label class="form-label text-sm font-weight-bold text-white">&nbsp;</label>
                        <div class="d-flex flex-column">
                            <button type="submit" class="btn btn-primary btn-sm mb-2">
                                <i class="fas fa-search mr-1"></i>Filter
                            </button>
                            <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times mr-1"></i>Clear
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modern News Table -->
    <div class="card modern-card mb-4">
        <div class="card-header bg-gradient-primary d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-list mr-2"></i>Articles List ({{ number_format($news->total()) }} total)
            </h6>
            <!-- <div class="dropdown dropdown-modern">
                <button class="btn btn-light dropdown-toggle" type="button" id="bulkActions" 
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-tasks mr-2"></i>Bulk Actions
                </button>
                <div class="dropdown-menu" aria-labelledby="bulkActions">
                    <h6 class="dropdown-header"><i class="fas fa-check mr-1"></i>Publish Actions</h6>
                    <a class="dropdown-item" href="#" onclick="submitBulkForm('publish')">
                        <i class="fas fa-upload text-success mr-2"></i>Publish Selected
                    </a>
                    <a class="dropdown-item" href="#" onclick="submitBulkForm('unpublish')">
                        <i class="fas fa-download text-warning mr-2"></i>Unpublish Selected
                    </a>
                    <div class="dropdown-divider"></div>
                    <h6 class="dropdown-header"><i class="fas fa-star mr-1"></i>Feature Actions</h6>
                    <a class="dropdown-item" href="#" onclick="submitBulkForm('feature')">
                        <i class="fas fa-star text-info mr-2"></i>Feature Selected
                    </a>
                    <a class="dropdown-item" href="#" onclick="submitBulkForm('unfeature')">
                        <i class="far fa-star text-muted mr-2"></i>Unfeature Selected
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#" onclick="submitBulkForm('delete')">
                        <i class="fas fa-trash mr-2"></i>Delete Selected
                    </a>
                </div>
            </div> -->
        </div>
        <div class="card-body p-0">
            @if($news->count() > 0)
                <form id="bulkForm" method="POST" action="{{ route('admin.news.bulk-action') }}">
                    @csrf
                    <input type="hidden" name="action" id="bulkAction">
                    
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr class="border-0">
                                    <th class="border-0 py-3">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="selectAll">
                                            <label class="custom-control-label" for="selectAll"></label>
                                        </div>
                                    </th>
                                    <th class="border-0 py-3 font-weight-bold">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" 
                                           class="text-decoration-none text-white">
                                            <i class="fas fa-heading mr-2"></i>Article Details
                                            @if(request('sort') === 'title')
                                                <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-light ml-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="border-0 py-3 font-weight-bold text-center text-white">
                                        <i class="fas fa-image mr-2"></i>Image
                                    </th>
                                    <th class="border-0 py-3 font-weight-bold text-center">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" 
                                           class="text-decoration-none text-white">
                                            <i class="fas fa-toggle-on mr-2"></i>Status
                                            @if(request('sort') === 'status')
                                                <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-light ml-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="border-0 py-3 font-weight-bold text-center text-white">
                                        <i class="fas fa-star mr-2"></i>Featured
                                    </th>
                                    <th class="border-0 py-3 font-weight-bold text-center">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'views', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" 
                                           class="text-decoration-none text-white">
                                            <i class="fas fa-eye mr-2"></i>Views
                                            @if(request('sort') === 'views')
                                                <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-light ml-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="border-0 py-3 font-weight-bold text-center">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'published_at', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}" 
                                           class="text-decoration-none text-white">
                                            <i class="fas fa-calendar mr-2"></i>Published
                                            @if(request('sort') === 'published_at')
                                                <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }} text-light ml-1"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="border-0 py-3 font-weight-bold text-center text-white">
                                        <i class="fas fa-cogs mr-2"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($news as $article)
                                    <tr class="article-row border-0">
                                        <td class="align-middle py-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input news-checkbox" 
                                                       id="check{{ $article->id }}" name="news_ids[]" value="{{ $article->id }}">
                                                <label class="custom-control-label" for="check{{ $article->id }}"></label>
                                            </div>
                                        </td>
                                        <td class="align-middle py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="article-content">
                                                    <h6 class="article-title mb-1" style="color: white;">{{ Str::limit($article->title, 60) }}</h6>
                                                    <p class="article-slug mb-1" style="color: white;">{{ $article->slug }}</p>
                                                    @if($article->summary)
                                                        <small class="text-muted">{{ Str::limit(strip_tags($article->summary), 80) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle py-3 text-center">
                                            @if($article->image_url)
                                                <img src="{{ asset('storage/' . $article->image_url) }}" 
                                                     alt="Article Image" class="article-image" style="width: 50px; height: 50px; object-fit: cover; color: white;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center article-image" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="align-middle py-3 text-center">
                                            @if($article->status === 'published')
                                                <span class="badge status-published px-3 py-2">
                                                    <i class="fas fa-check mr-1"></i>Published
                                                </span>
                                            @elseif($article->status === 'draft')
                                                <span class="badge status-draft px-3 py-2">
                                                    <i class="fas fa-edit mr-1"></i>Draft
                                                </span>
                                            @else
                                                <span class="badge badge-secondary px-3 py-2">
                                                    <i class="fas fa-archive mr-1"></i>Archived
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle py-3 text-center">
                                            @if($article->featured)
                                                <span class="badge featured-yes px-3 py-2">
                                                    <i class="fas fa-star mr-1"></i>Featured
                                                </span>
                                            @else
                                                <span class="badge featured-no px-3 py-2">
                                                    <i class="far fa-star mr-1"></i>Regular
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle py-3 text-center">
                                            <span class="badge badge-info px-3 py-2">
                                                <i class="fas fa-eye mr-1"></i>{{ number_format($article->views) }}
                                            </span>
                                        </td>
                                        <td class="align-middle py-3 text-center">
                                            @if($article->published_at)
                                                <div class="text-sm font-weight-bold text-white">{{ $article->published_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $article->published_at->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">
                                                    <i class="fas fa-clock mr-1"></i>Not published
                                                </span>
                                            @endif
                                        </td>
                                        <td class="align-middle py-3 text-center table-actions">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.news.show', $article) }}" 
                                                   class="btn btn-sm btn-primary" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.news.edit', $article) }}" 
                                                   class="btn btn-sm btn-warning" title="Edit Article">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="deleteArticle({{ $article->id }})" title="Delete Article">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                    </tr>
                                                    </button>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                <!-- Modern Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4 p-4 bg-light">
                    <div class="text-muted">
                        <i class="fas fa-info-circle mr-2"></i>
                        Showing <strong>{{ $news->firstItem() }}</strong> to <strong>{{ $news->lastItem() }}</strong> 
                        of <strong>{{ number_format($news->total()) }}</strong> results
                    </div>
                    <div class="pagination-wrapper">
                        {{ $news->appends(request()->query())->links() }}
                    </div>
                </div>
            @else
                <div class="empty-state p-5">
                    <i class="fas fa-newspaper fa-5x mb-4"></i>
                    <h4 class="text-muted mb-3">No Articles Found</h4>
                    <p class="text-muted mb-4">
                        @if(request()->hasAny(['search', 'status', 'featured']))
                            No articles match your current filters. Try adjusting your search criteria.
                        @else
                            You haven't created any news articles yet. Create your first article to get started!
                        @endif
                    </p>
                    <div class="d-flex justify-content-center">
                        @if(request()->hasAny(['search', 'status', 'featured']))
                            <a href="{{ route('admin.news.index') }}" class="btn btn-outline-primary mr-3">
                                <i class="fas fa-times mr-2"></i>Clear Filters
                            </a>
                        @endif
                        <a href="{{ route('admin.news.create') }}" class="btn btn-gradient-primary">
                            <i class="fas fa-plus mr-2"></i>Create First Article
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Modern Select All functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.news-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateBulkActionsState();
});

// Update individual checkboxes
document.querySelectorAll('.news-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const allCheckboxes = document.querySelectorAll('.news-checkbox');
        const checkedBoxes = document.querySelectorAll('.news-checkbox:checked');
        
        document.getElementById('selectAll').checked = allCheckboxes.length === checkedBoxes.length;
        document.getElementById('selectAll').indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < allCheckboxes.length;
        
        updateBulkActionsState();
    });
});

// Update bulk actions dropdown state
function updateBulkActionsState() {
    const checkedBoxes = document.querySelectorAll('.news-checkbox:checked');
    const bulkButton = document.getElementById('bulkActions');
    
    if (checkedBoxes.length > 0) {
        bulkButton.classList.remove('btn-light');
        bulkButton.classList.add('btn-primary');
        bulkButton.innerHTML = `<i class="fas fa-tasks mr-2"></i>Bulk Actions (${checkedBoxes.length})`;
    } else {
        bulkButton.classList.remove('btn-primary');
        bulkButton.classList.add('btn-light');
        bulkButton.innerHTML = '<i class="fas fa-tasks mr-2"></i>Bulk Actions';
    }
}

// Submit bulk form with confirmation
function submitBulkForm(action) {
    const checkedBoxes = document.querySelectorAll('.news-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        alert('Please select at least one article to perform this action.');
        return;
    }
    
    let confirmMessage = '';
    switch(action) {
        case 'delete':
            confirmMessage = `Are you sure you want to delete ${checkedBoxes.length} selected article(s)? This action cannot be undone.`;
            break;
        case 'publish':
            confirmMessage = `Publish ${checkedBoxes.length} selected article(s)?`;
            break;
        case 'unpublish':
            confirmMessage = `Unpublish ${checkedBoxes.length} selected article(s)?`;
            break;
        case 'feature':
            confirmMessage = `Feature ${checkedBoxes.length} selected article(s)?`;
            break;
        case 'unfeature':
            confirmMessage = `Remove featured status from ${checkedBoxes.length} selected article(s)?`;
            break;
    }
    
    if (confirmMessage && !confirm(confirmMessage)) {
        return;
    }
    
    document.getElementById('bulkAction').value = action;
    document.getElementById('bulkForm').submit();
}

// Delete individual article
function deleteArticle(id) {
    if (confirm('Are you sure you want to delete this article? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/news/${id}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Enhanced search functionality
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit search form on status/featured/sort change
    const filterSelects = document.querySelectorAll('#status, #featured, #sort');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
    
    // Search input with debounce
    let searchTimeout;
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 3 || this.value.length === 0) {
                    this.closest('form').submit();
                }
            }, 500);
        });
    }
    
    // Initialize bulk actions state
    updateBulkActionsState();
});

// Toast notifications for actions
function showToast(message, type = 'success') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} toast-notification`;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border-radius: 8px;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    `;
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} mr-2"></i>
            ${message}
            <button type="button" class="ml-auto btn-close" onclick="this.parentElement.parentElement.remove()">&times;</button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}
</script>
@endpush
@endsection
