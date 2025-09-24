@extends('admin.layouts.app')

@section('title', 'News Management')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">News Management</h1>
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Article
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Articles</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $statistics['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Published</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $statistics['published'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Drafts</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $statistics['draft'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-edit fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Views</div>
                            <div class="h5 mb-0 font-weight-bold">{{ number_format($statistics['total_views']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters & Search</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.news.index') }}">
                <div class="row align-items-end">
                    <div class="col-md-3">
                        <label for="search">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search articles...">
                    </div>
                    <div class="col-md-2">
                        <label for="status">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="featured">Featured</label>
                        <select class="form-select" id="featured" name="featured">
                            <option value="">All</option>
                            <option value="1" {{ request('featured') === '1' ? 'selected' : '' }}>Featured</option>
                            <option value="0" {{ request('featured') === '0' ? 'selected' : '' }}>Not Featured</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="date_from">From Date</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to">To Date</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- News Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Articles List</h6>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="bulkActions" 
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Bulk Actions
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="bulkActions">
                    <a class="dropdown-item" href="#" onclick="submitBulkForm('publish')">Publish Selected</a>
                    <a class="dropdown-item" href="#" onclick="submitBulkForm('unpublish')">Unpublish Selected</a>
                    <a class="dropdown-item" href="#" onclick="submitBulkForm('feature')">Feature Selected</a>
                    <a class="dropdown-item" href="#" onclick="submitBulkForm('unfeature')">Unfeature Selected</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#" onclick="submitBulkForm('delete')">Delete Selected</a>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($news->count() > 0)
                <form id="bulkForm" method="POST" action="{{ route('admin.news.bulk-action') }}">
                    @csrf
                    <input type="hidden" name="action" id="bulkAction">
                    
                    <div class="table-responsive">
                        <table class="table table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th class="p-3"><input type="checkbox" class="form-check-input" id="selectAll"></th>
                                    <th class="p-3">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}">
                                            Title 
                                            @if(request('sort') === 'title')
                                                <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="p-3">Image</th>
                                    <th class="p-3">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'status', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}">
                                            Status
                                            @if(request('sort') === 'status')
                                                <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="p-3">Featured</th>
                                    <th class="p-3">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'views', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}">
                                            Views
                                            @if(request('sort') === 'views')
                                                <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="p-3">
                                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'published_at', 'order' => request('order') === 'asc' ? 'desc' : 'asc']) }}">
                                            Published
                                            @if(request('sort') === 'published_at')
                                                <i class="fas fa-sort-{{ request('order') === 'asc' ? 'up' : 'down' }}"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="p-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($news as $article)
                                    <tr>
                                        <td class="p-3">
                                            <input type="checkbox" name="news_ids[]" value="{{ $article->id }}" class="form-check-input news-checkbox">
                                        </td>
                                        <td class="p-3">
                                            <div class="fw-bold">{{ Str::limit($article->title, 50) }}</div>
                                            <small class="text-muted">{{ $article->slug }}</small>
                                        </td>
                                        <td class="p-3">
                                            @if($article->image_url)
                                                <img src="{{ asset('storage/' . $article->image_url) }}" 
                                                     alt="Article Image" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center rounded" 
                                                     style="width: 50px; height: 50px; background-color: var(--bg-dark-hover);">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="p-3">
                                            @if($article->status === 'published')
                                                <span class="badge status-published">Published</span>
                                            @else
                                                <span class="badge status-draft">Draft</span>
                                            @endif
                                        </td>
                                        <td class="p-3">
                                            @if($article->featured)
                                                <span class="badge featured-yes">Featured</span>
                                            @else
                                                <span class="badge featured-no">Regular</span>
                                            @endif
                                        </td>
                                        <td class="p-3">{{ number_format($article->views) }}</td>
                                        <td class="p-3">
                                            @if($article->published_at)
                                                {{ $article->published_at->format('M d, Y') }}
                                                <br><small class="text-muted">{{ $article->published_at->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">Not published</span>
                                            @endif
                                        </td>
                                        <td class="p-3">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.news.show', $article) }}" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.news.edit', $article) }}" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                                <form method="POST" action="{{ route('admin.news.destroy', $article) }}" 
                                                      style="display: inline;" 
                                                      onsubmit="return confirm('Are you sure you want to delete this article?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                <!-- Pagination -->
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing {{ $news->firstItem() }} to {{ $news->lastItem() }} of {{ $news->total() }} results
                    </div>
                    {{ $news->links() }}
                </div>
            @else
                <div class="card-body text-center py-5">
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No articles found</h5>
                    <p class="text-muted">Create your first news article to get started.</p>
                    <a href="{{ route('admin.news.create') }}" class="btn btn-primary mt-3">Add New Article</a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('selectAll')?.addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.news-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

function submitBulkForm(action) {
    const checkedBoxes = document.querySelectorAll('.news-checkbox:checked');
    if (checkedBoxes.length === 0) {
        alert('Please select at least one article.');
        return;
    }
    
    if (action === 'delete' && !confirm('Are you sure you want to delete the selected articles?')) {
        return;
    }
    
    document.getElementById('bulkAction').value = action;
    document.getElementById('bulkForm').submit();
}
</script>
@endpush
@endsection
