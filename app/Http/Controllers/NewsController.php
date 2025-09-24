<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\News\Models\News;

class NewsController extends Controller
{
    /**
     * Hiển thị danh sách tin tức.
     * GET /news
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $news = News::query()
            ->when($search, function ($query, $search) {
                return $query->where('title', 'LIKE', "%{$search}%")
                           ->orWhere('content', 'LIKE', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('news.index', compact('news', 'search'));
    }

    /**
     * Hiển thị chi tiết tin tức.
     * GET /news/{id}
     */
    public function show($id)
    {
        try {
            $newsItem = News::findOrFail($id);
            
            // Lấy tin tức liên quan
            $relatedNews = News::where('id', '!=', $id)
                              ->latest()
                              ->take(5)
                              ->get();
            
            return view('news.show', compact('newsItem', 'relatedNews'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('news.index')->with('error', 'News not found.');
        }
    }
}
