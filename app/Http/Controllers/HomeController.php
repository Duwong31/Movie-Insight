<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\News\Models\News;    
use Modules\Movies\Models\Movie;         
use Modules\Actors\Models\Actor;          
use Illuminate\Database\Eloquent\Relations\BelongsToMany;     
// Không cần Model Rating ở đây nếu dùng relationship

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ với dữ liệu cần thiết.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Lấy ID người dùng hiện tại (nếu đã đăng nhập)
        $userId = Auth::id(); // Trả về null nếu chưa đăng nhập

        // 1. Lấy dữ liệu News
        // Lấy 5 tin tức mới nhất chẳng hạn
        $news_array = News::latest()->take(5)->get();

        // 2. Lấy dữ liệu Movies
        $movies_query = Movie::where('movie_type', 'isMovie') // Lọc theo loại 'isMovie'
                           ->withAvg('ratings', 'rating'); // Tự động tính rating trung bình (tạo cột ratings_avg_rating)

        // Nếu người dùng đã đăng nhập, tải thêm rating của họ
        if ($userId) {
            // Giả sử bạn có relationship 'userRating' trong Model Movie để lấy rating của user hiện tại
            // Nếu chưa có, bạn cần định nghĩa nó trong App\Models\Movie:
            // public function userRating() {
            //    return $this->hasOne(Rating::class)->where('user_id', Auth::id());
            // }
             $movies_query->with(['userRating' => function ($query) use ($userId) {
                 $query->where('user_id', $userId); // Đảm bảo chỉ lấy rating của user này
             }]);
             
             // HOẶC nếu không muốn thêm relationship, dùng subquery (phức tạp hơn):
             /*
             $movies_query->withCount(['ratings as user_rating' => function ($query) use ($userId) {
                 $query->select('rating')->where('user_id', $userId);
             }]);
             // Lưu ý: Cách này trả về count, không phải giá trị rating, cần chỉnh lại select('rating')
             // Cách dùng relationship `userRating` ở trên thường dễ hơn.
             */
        }

        $movies_array = $movies_query->take(10)->get() // Lấy 10 phim
                                     ->map(function ($movie) {
                                         // Chuẩn hóa tên cột rating trung bình nếu cần
                                         $movie->average_rating = $movie->ratings_avg_rating ?? 0;
                                         // Lấy giá trị user rating từ relationship đã load (nếu có)
                                         // View sẽ truy cập $movie->userRating->rating ?? null
                                         // $movie->user_rating = $movie->userRating?->rating; // Gán trực tiếp nếu muốn
                                         return $movie;
                                     });


        // 3. Lấy dữ liệu TV Shows (Tương tự Movies)
        $tv_shows_query = Movie::where('movie_type', 'isTVShow') // Lọc theo loại 'isTVShow'
                               ->withAvg('ratings', 'rating'); // Tính rating trung bình

        if ($userId) {
             $tv_shows_query->with(['userRating' => function ($query) use ($userId) {
                 $query->where('user_id', $userId);
             }]);
        }

        $tv_shows_array = $tv_shows_query->take(10)->get() // Lấy 10 TV Shows
                                         ->map(function ($tvShow) {
                                             $tvShow->average_rating = $tvShow->ratings_avg_rating ?? 0;
                                             // View sẽ truy cập $tvShow->userRating->rating ?? null
                                             // $tvShow->user_rating = $tvShow->userRating?->rating;
                                             return $tvShow;
                                         });

        // 4. Lấy dữ liệu Actors
        $actors_array = Actor::take(10)->get(); // Lấy 10 diễn viên
        
        $userWatchlistMovieIds = [];
        if (Auth::check()) { // Kiểm tra xem người dùng đã đăng nhập chưa
            // Sử dụng relationship 'watchlistMovies' đã định nghĩa trong model User
            // Lấy danh sách các 'movie_id' từ bảng movies liên kết qua bảng watchlist
            // Đảm bảo cột khóa ngoại trong 'watchlist' và khóa chính trong 'movies' là 'movie_id'
            $userWatchlistMovieIds = Auth::user()->watchlistMovies()->pluck('movies.movie_id')->toArray();

            // Nếu khóa chính của bảng movies là 'id' và khóa ngoại trong watchlist là 'movie_id'
            // thì bạn cần pluck cột 'id' từ bảng movies:
            // $userWatchlistMovieIds = Auth::user()->watchlistMovies()->pluck('movies.id')->toArray();
            // =>>> KIỂM TRA LẠI CẤU TRÚC BẢNG VÀ RELATIONSHIP CỦA BẠN <<<
        }
        // 5. Trả về view với tất cả dữ liệu đã lấy
        return view('home.index', [
            'news_array' => $news_array,
            'movies_array' => $movies_array,
            'tv_shows_array' => $tv_shows_array,
            'actors_array' => $actors_array,
            'userWatchlistMovieIds' => $userWatchlistMovieIds,
            // Bạn có thể truyền thêm các biến khác nếu cần
        ]);
    }
}