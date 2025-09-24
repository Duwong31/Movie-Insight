<?php

namespace Modules\News\Admin;

use App\Http\Controllers\Controller;
use Modules\News\Models\News;
use Modules\News\Admin\Requests\CreateNewsRequest;
use Modules\News\Admin\Requests\UpdateNewsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function __construct()
    {
        // Middleware will be applied via route groups
    }

    /**
     * Display a listing of news with filters and search
     */
    public function index(Request $request)
    {
        $query = News::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%")
                  ->orWhere('excerpt', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Featured filter
        if ($request->filled('featured')) {
            $query->where('featured', $request->featured === '1');
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('published_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('published_at', '<=', $request->date_to);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        if (in_array($sortBy, ['title', 'status', 'featured', 'published_at', 'views', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $news = $query->paginate(20)->withQueryString();
        
        // Statistics for dashboard
        $statistics = [
            'total' => News::count(),
            'published' => News::where('status', 'published')->count(),
            'draft' => News::where('status', 'draft')->count(),
            'featured' => News::where('featured', true)->count(),
            'total_views' => News::sum('views'),
        ];

        return view('admin.news.index', compact('news', 'statistics'));
    }

    /**
     * Show the form for creating a new news article
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Store a newly created news article
     */
    public function store(CreateNewsRequest $request)
    {
        $data = $request->validated();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image_url'] = $this->uploadImage($request->file('image'));
        }

        // Auto-generate excerpt if not provided
        if (empty($data['excerpt'])) {
            $data['excerpt'] = Str::limit(strip_tags($data['content']), 150);
        }

        // Set published_at if publishing immediately
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $news = News::create($data);

        return redirect()
            ->route('admin.news.show', $news)
            ->with('success', 'News article created successfully!');
    }

    /**
     * Display the specified news article
     */
    public function show(News $news)
    {
        return view('admin.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified news article
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified news article
     */
    public function update(UpdateNewsRequest $request, News $news)
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($news->image_url) {
                Storage::disk('public')->delete($news->image_url);
            }
            $data['image_url'] = $this->uploadImage($request->file('image'));
        }

        // Auto-generate excerpt if not provided
        if (empty($data['excerpt'])) {
            $data['excerpt'] = Str::limit(strip_tags($data['content']), 150);
        }

        // Set published_at if status changed to published
        if ($data['status'] === 'published' && $news->status !== 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        $news->update($data);

        return redirect()
            ->route('admin.news.show', $news)
            ->with('success', 'News article updated successfully!');
    }

    /**
     * Remove the specified news article
     */
    public function destroy(News $news)
    {
        // Delete associated image
        if ($news->image_url) {
            Storage::disk('public')->delete($news->image_url);
        }

        $news->delete();

        return redirect()
            ->route('admin.news.index')
            ->with('success', 'News article deleted successfully!');
    }

    /**
     * Bulk actions for multiple news articles
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,unpublish,feature,unfeature,delete',
            'news_ids' => 'required|array',
            'news_ids.*' => 'exists:news,id'
        ]);

        $newsIds = $request->news_ids;
        $action = $request->action;
        $count = count($newsIds);

        switch ($action) {
            case 'publish':
                News::whereIn('id', $newsIds)->update([
                    'status' => 'published',
                    'published_at' => now()
                ]);
                $message = "{$count} articles published successfully!";
                break;

            case 'unpublish':
                News::whereIn('id', $newsIds)->update(['status' => 'draft']);
                $message = "{$count} articles unpublished successfully!";
                break;

            case 'feature':
                News::whereIn('id', $newsIds)->update(['featured' => true]);
                $message = "{$count} articles featured successfully!";
                break;

            case 'unfeature':
                News::whereIn('id', $newsIds)->update(['featured' => false]);
                $message = "{$count} articles unfeatured successfully!";
                break;

            case 'delete':
                $newsToDelete = News::whereIn('id', $newsIds)->get();
                foreach ($newsToDelete as $news) {
                    if ($news->image_url) {
                        Storage::disk('public')->delete($news->image_url);
                    }
                }
                News::whereIn('id', $newsIds)->delete();
                $message = "{$count} articles deleted successfully!";
                break;
        }

        return redirect()
            ->route('admin.news.index')
            ->with('success', $message);
    }

    /**
     * Toggle publication status
     */
    public function toggleStatus(News $news)
    {
        if ($news->status === 'published') {
            $news->unpublish();
            $message = 'Article unpublished successfully!';
        } else {
            $news->publish();
            $message = 'Article published successfully!';
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(News $news)
    {
        $news->toggleFeatured();
        
        $message = $news->featured 
            ? 'Article featured successfully!' 
            : 'Article unfeatured successfully!';

        return redirect()
            ->back()
            ->with('success', $message);
    }

    /**
     * Show analytics for news
     */
    public function analytics()
    {
        $analytics = [
            'total_articles' => News::count(),
            'published_articles' => News::where('status', 'published')->count(),
            'draft_articles' => News::where('status', 'draft')->count(),
            'featured_articles' => News::where('featured', true)->count(),
            'total_views' => News::sum('views'),
            'average_views' => round(News::avg('views'), 2),
            'most_viewed' => News::orderBy('views', 'desc')->take(10)->get(),
            'recent_articles' => News::latest()->take(10)->get(),
            'monthly_stats' => News::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->take(12)
                ->get(),
        ];

        return view('admin.news.analytics', compact('analytics'));
    }

    /**
     * Upload image and return path
     */
    private function uploadImage($image)
    {
        $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs('news', $filename, 'public');
        return $path;
    }
}
