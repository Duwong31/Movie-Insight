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
        $userId = Auth::id(); // Lấy ID người dùng đang đăng nhập (nếu có)

        try {
            // Lấy phim bằng ID, đồng thời tải sẵn relationships 'genres', 'actors'
            // và tính trung bình cột 'rating' từ relationship 'ratings'
            $movie = Movie::with([
                           'genres', // Tải relationship genres (yêu cầu định nghĩa trong Model)
                           'actors', // Tải relationship actors (yêu cầu định nghĩa trong Model)
                       ])
                       ->withAvg('ratings', 'rating') // Tính điểm trung bình (kiểm tra tên cột 'rating')
                       ->findOrFail($id); // Tìm theo khóa chính hoặc báo lỗi 404 nếu không thấy

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Xử lý trường hợp không tìm thấy phim
            // Bạn có thể chuyển hướng hoặc hiển thị thông báo lỗi tùy ý
            return redirect()->route('home')->with('error', 'Movie not found.'); // Ví dụ: chuyển về trang chủ
            // Hoặc: abort(404);
        }

        // Lấy rating của user hiện tại cho phim này (nếu user đã đăng nhập)
        $userRating = null;
        if ($userId) {
            $userRating = Rating::where('movie_id', $movie->movie_id) // Dùng $movie->movie_id rõ ràng hơn
                               ->where('user_id', $userId)
                               ->value('rating'); // Lấy giá trị cột 'rating' (kiểm tra tên cột)
        }

        $viewPath = 'movies.show'; 

        // Trả về view với dữ liệu cần thiết
        return view($viewPath, [
            'movie' => $movie,         
            'userRating' => $userRating,  
        ]);
    }
}