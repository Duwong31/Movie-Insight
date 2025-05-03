<?php
namespace Modules\Movies\Controllers; // Namespace module

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Movies\Models\Movie; // Model Movie trong module
use App\Models\Rating;           // Model Rating (giả sử vẫn ở App\Models)
// Không cần use Genre hay Actor ở đây nữa nếu chỉ dùng qua relationship

class MovieController extends Controller
{
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