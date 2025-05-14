<?php
namespace Modules\Movies\Controllers; 

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Movies\Models\Movie; // Model Movie trong module
use Modules\Ratings\Models\Rating;           // Model Rating (giả sử vẫn ở App\Models)
use Modules\Genres\Models\Genre;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::query();

        // 1. Lọc theo Trạng thái (In Theaters, Coming Soon,...)
        $status = $request->input('status', 'in_theaters'); 
        if ($status && $status !== 'all') { 
            $query->where('status', $status);
        }

        // 2. Lọc theo Thể loại (Genre)
        if ($request->filled('genre_id')) {
            $genreIds = $request->input('genre_id');
            if (!is_array($genreIds)) $genreIds = [$genreIds];
            // Chỉ filter nếu có ít nhất 1 genre được chọn
            if (count(array_filter($genreIds))) {
                $query->whereHas('genres', function ($q) use ($genreIds) {
                    $q->whereIn('genres.genres_id', $genreIds);
            });
            }
        }

        // 3. Lọc theo Rating (ví dụ: MI score >= X)
        if ($request->filled('rating_min')) {
            $query->where('tomatometer_score', '>=', $request->input('rating_min'));
        }
        // Bạn có thể thêm các bộ lọc khác như "Certified Fresh", "Verified Hot"
        // Ví dụ: nếu "Certified Fresh" là một boolean flag hoặc một ngưỡng score cụ thể
        if ($request->has('certified_fresh')) {
            // Giả sử certified_fresh là một flag boolean trong bảng movies
            // $query->where('is_certified_fresh', true);
            // Hoặc dựa trên score
            $query->where('tomatometer_score', '>=', 75); // Ví dụ ngưỡng cho Certified Fresh
        }

        // 4. Sắp xếp (Sort)
        $sortBy = $request->input('sort_by',''); // Mặc định
        switch ($sortBy) {
            case 'popularity.desc':
                $query->orderBy('release_date', 'desc'); 
                break;
            case 'release_date.desc':
                $query->orderBy('release_date', 'desc');
                break;
            case 'release_date.asc':
                $query->orderBy('release_date', 'asc');
                break;
            case 'rating.desc':
                $query->orderBy('tomatometer_score', 'desc');
                break;
            default:
                break;
            // Thêm các trường hợp sắp xếp khác
        }

        // Lấy danh sách phim đã lọc và phân trang
        $movies = $query->with(['genres'])
                       ->withAvg('ratings as ratings_avg_rating', 'rating')
                       ->paginate(12)
                       ->appends($request->query());

        // Lấy danh sách các genres để hiển thị trong dropdown filter
        $genres = Genre::orderBy('genres_name')->get();

        // Lấy danh sách các phim đang trong watchlist của người dùng (nếu đã đăng nhập)
        $userWatchlistMovieIds = [];
        if (Auth::check()) {
            $userWatchlistMovieIds = Auth::user()->watchlistMovies()->pluck('movies.movie_id')->toArray();
        }

        // Tiêu đề trang động dựa trên filter (ví dụ)
        $pageTitle = "Movies";
        if ($status === 'in_theaters') $pageTitle = "Best Movies in Theaters";
        // elseif ($status === 'at_home') $pageTitle = "Movies Available at Home";
        elseif ($status === 'coming_soon') $pageTitle = "Upcoming Movies";

        return view('movies.index', compact(
            'movies',
            'genres',
            'status', 
            'sortBy', 
            'pageTitle',
            'userWatchlistMovieIds' // Truyền ID phim trong watchlist
        ));
    }
    /**
     * Display the specified movie resource.
     *
     * @param  int  $id Movie ID (tương ứng movie_id)
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $userId = Auth::id();

        try {
            $movie = Movie::with([
                'genres',
                'actors',
                'reviews.user'
            ])
            ->withAvg('ratings', 'rating')
            ->findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('home')->with('error', 'Movie not found.');
        }

        // Lấy rating của user hiện tại cho phim này (nếu user đã đăng nhập)
        $userRating = null;
        if ($userId) {
            $userRating = \Modules\Ratings\Models\Rating::where('movie_id', $movie->movie_id)
                ->where('user_id', $userId)
                ->value('rating');
        }

        // Lấy tất cả reviews và ratings cho phim này
        $reviews = $movie->reviews; // đã eager load user
        $ratings = \Modules\Ratings\Models\Rating::where('movie_id', $movie->movie_id)
            ->pluck('rating', 'user_id');

        return view('movies.show', [
            'movie' => $movie,
            'userRating' => $userRating,
            'reviews' => $reviews,
            'ratings' => $ratings,
        ]);
    }
}