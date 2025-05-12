<?php

namespace Modules\TVShows\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Movies\Models\Movie;

class TVShowController extends Controller
{
    public function index(Request $request)
    {   
        
        $query = Movie::query();
        // 1. Lọc theo Trạng thái (In Theaters, Coming Soon,...)
        $status = $request->input('status', 'in_theaters'); 
        if ($status && $status !== 'all') { 
            $query->where('status', $status);
        }

        // Chỉ lấy TV Shows
        $query->where('movie_type', 'isTVShow');

        // Có thể thêm filter giống MovieController nếu muốn
        if ($request->filled('genre_id')) {
            $genreId = $request->input('genre_id');
            $query->whereHas('genres', function ($q) use ($genreId) {
                $q->where('genres.id', $genreId);
            });
        }
        if ($request->filled('rating_min')) {
            $query->where('tomatometer_score', '>=', $request->input('rating_min'));
        }
        if ($request->has('certified_fresh')) {
            $query->where('tomatometer_score', '>=', 75);
        }
        $sortBy = $request->input('sort_by', 'popularity.desc');
        switch ($sortBy) {
            case 'popularity.desc':
            case 'release_date.desc':
                $query->orderBy('release_date', 'desc');
                break;
            case 'release_date.asc':
                $query->orderBy('release_date', 'asc');
                break;
            case 'rating.desc':
                $query->orderBy('tomatometer_score', 'desc');
                break;
        }
        $tvshows = $query->with(['genres'])
                        ->paginate(12)
                        ->appends($request->query());

        $userWatchlistMovieIds = [];
        if (Auth::check()) {
            $userWatchlistMovieIds = Auth::user()->watchlistMovies()->pluck('movies.movie_id')->toArray();
        }
        $pageTitle = 'TV Shows';
        return view('tvshows.index', compact('tvshows', 'pageTitle', 'userWatchlistMovieIds', 'status'));
    }
}
