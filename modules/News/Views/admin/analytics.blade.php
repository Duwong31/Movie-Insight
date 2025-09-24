@extends('admin.layouts.app')

@section('title', 'News Analytics')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">News Analytics</h1>
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to News
        </a>
    </div>

    <!-- Overview Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Articles</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($analytics['total_articles']) }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($analytics['published_articles']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($analytics['total_views']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Average Views</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($analytics['average_views']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Most Viewed Articles -->
        <div class="col-xl-6 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Most Viewed Articles</h6>
                </div>
                <div class="card-body">
                    @if($analytics['most_viewed']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Article</th>
                                        <th>Views</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analytics['most_viewed'] as $index => $article)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <a href="{{ route('admin.news.show', $article) }}" class="text-decoration-none">
                                                    {{ Str::limit($article->title, 40) }}
                                                </a>
                                                <br>
                                                <small class="text-muted">{{ $article->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ number_format($article->views) }}</span>
                                            </td>
                                            <td>
                                                @if($article->status === 'published')
                                                    <span class="badge badge-success">Published</span>
                                                @else
                                                    <span class="badge badge-warning">Draft</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No articles with views yet</h5>
                            <p class="text-muted">Articles will appear here once they start getting views.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Content Statistics -->
        <div class="col-xl-6 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Content Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-4">
                            <div class="border rounded p-3">
                                <div class="h4 mb-0 text-primary">{{ number_format($analytics['total_articles']) }}</div>
                                <small class="text-muted">Total Articles</small>
                            </div>
                        </div>
                        <div class="col-6 mb-4">
                            <div class="border rounded p-3">
                                <div class="h4 mb-0 text-success">{{ number_format($analytics['published_articles']) }}</div>
                                <small class="text-muted">Published</small>
                            </div>
                        </div>
                        <div class="col-6 mb-4">
                            <div class="border rounded p-3">
                                <div class="h4 mb-0 text-warning">{{ number_format($analytics['draft_articles']) }}</div>
                                <small class="text-muted">Drafts</small>
                            </div>
                        </div>
                        <div class="col-6 mb-4">
                            <div class="border rounded p-3">
                                <div class="h4 mb-0 text-info">{{ number_format($analytics['featured_articles']) }}</div>
                                <small class="text-muted">Featured</small>
                            </div>
                        </div>
                    </div>

                    <!-- Publication Rate -->
                    @if($analytics['total_articles'] > 0)
                        <div class="mt-4">
                            <h6 class="font-weight-bold text-secondary">Publication Rate</h6>
                            @php
                                $publicationRate = ($analytics['published_articles'] / $analytics['total_articles']) * 100;
                            @endphp
                            <div class="progress mb-2">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ $publicationRate }}%" 
                                     aria-valuenow="{{ $publicationRate }}" 
                                     aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted">{{ number_format($publicationRate, 1) }}% of articles are published</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Articles -->
        <div class="col-xl-6 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Articles</h6>
                </div>
                <div class="card-body">
                    @if($analytics['recent_articles']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Article</th>
                                        <th>Status</th>
                                        <th>Views</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($analytics['recent_articles'] as $article)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.news.show', $article) }}" class="text-decoration-none">
                                                    {{ Str::limit($article->title, 40) }}
                                                </a>
                                                @if($article->featured)
                                                    <span class="badge badge-info badge-sm ms-1">Featured</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($article->status === 'published')
                                                    <span class="badge badge-success">Published</span>
                                                @else
                                                    <span class="badge badge-warning">Draft</span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($article->views) }}</td>
                                            <td>
                                                <small>{{ $article->created_at->format('M d, Y') }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No articles yet</h5>
                            <p class="text-muted">Create your first article to see it here.</p>
                            <a href="{{ route('admin.news.create') }}" class="btn btn-primary">Add New Article</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Monthly Statistics -->
        <div class="col-xl-6 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Statistics</h6>
                </div>
                <div class="card-body">
                    @if($analytics['monthly_stats']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Articles</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $maxCount = $analytics['monthly_stats']->max('count');
                                    @endphp
                                    @foreach($analytics['monthly_stats'] as $stat)
                                        @php
                                            $percentage = $maxCount > 0 ? ($stat->count / $maxCount) * 100 : 0;
                                            $monthName = date('M Y', mktime(0, 0, 0, $stat->month, 1, $stat->year));
                                        @endphp
                                        <tr>
                                            <td>{{ $monthName }}</td>
                                            <td>{{ $stat->count }}</td>
                                            <td>
                                                <div class="progress" style="height: 15px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" 
                                                         style="width: {{ $percentage }}%" 
                                                         aria-valuenow="{{ $percentage }}" 
                                                         aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No monthly data yet</h5>
                            <p class="text-muted">Statistics will appear as you create more articles.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-plus me-2"></i>Create New Article
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.news.index') }}?status=draft" class="btn btn-warning btn-block">
                                <i class="fas fa-edit me-2"></i>View Drafts
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.news.index') }}?featured=1" class="btn btn-info btn-block">
                                <i class="fas fa-star me-2"></i>Featured Articles
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-list me-2"></i>All Articles
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
