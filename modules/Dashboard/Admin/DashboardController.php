<?php

namespace Modules\Dashboard\Admin;

use Illuminate\Routing\Controller;
use Modules\Genres\Models\Genre;
use Modules\Movies\Models\Movie;
use App\Models\User;
use Modules\Review\Models\Review;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 2)->count();
        $totalReviews = Review::count();
        $totalMovies = Movie::where('movie_type', 'isMovie')->count();
        $totalTVShows = Movie::where('movie_type', 'isTVShow')->count();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalReviews',
            'totalMovies',
            'totalTVShows'
        ));
    }
}