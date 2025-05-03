<?php

namespace Modules\Watchlists\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log; // Để ghi log lỗi (tùy chọn)
use Modules\Movies\Models\Movie;

class WatchlistController extends Controller
{
    /**
     * Hiển thị trang watchlist của người dùng đã đăng nhập.
     * GET /my-watchlist
     */
    public function index(Request $request)
    {
        $user = $request->user(); // Lấy người dùng đang đăng nhập

        // Lấy danh sách phim trong watchlist của người dùng
        // Sử dụng withAvg để tính rating trung bình từ bảng ratings
        // Giả định cột rating trong bảng ratings tên là 'rating'
        $movies = $user->watchlistMovies()
                       ->withAvg('ratings', 'rating') // 'ratings' là tên relationship, 'rating' là tên cột điểm
                       ->orderBy('watchlist.created_at', 'desc') // Sắp xếp theo thời gian thêm (tùy chọn)
                       ->get();

        // Trả về view với dữ liệu movies
        return view('watchlist.index', compact('movies'));
    }

    /**
     * Thêm một phim vào watchlist của người dùng.
     * POST /watchlist/add
     */
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|integer|exists:movies,movie_id', // Kiểm tra movie_id tồn tại trong bảng movies (giả sử PK là id)
                                                              // Nếu PK là movie_id thì đổi thành 'exists:movies,movie_id'
        ]);

        $user = $request->user();
        $movieId = $request->input('movie_id');

        // Kiểm tra xem phim đã có trong watchlist chưa
        $isAttached = $user->watchlistMovies()->where('movies.movie_id', $movieId)->exists();

        if ($isAttached) {
            return response()->json(['success' => false, 'message' => 'Movie already in watchlist'], 409); // 409 Conflict
        }

        try {
            // Thêm phim vào watchlist (attach chỉ thêm nếu chưa tồn tại)
            $user->watchlistMovies()->attach($movieId);
            return response()->json(['success' => true, 'message' => 'Movie added to watchlist']);
        } catch (\Exception $e) {
            Log::error("Error adding movie to watchlist: " . $e->getMessage()); // Ghi log lỗi
            return response()->json(['success' => false, 'message' => 'Failed to add movie to watchlist'], 500); // 500 Internal Server Error
        }
    }

    /**
     * Xóa một phim khỏi watchlist của người dùng.
     * DELETE /watchlist/remove (Hoặc POST nếu AJAX không hỗ trợ DELETE dễ dàng)
     * Lưu ý: Route của bạn đang là DELETE, nên AJAX call cần dùng method DELETE.
     */
    public function destroy(Request $request)
    {
         $request->validate([
            'movie_id' => 'required|integer|exists:movies,movie_id', // Hoặc exists:movies,movie_id
        ]);

        $user = $request->user();
        $movieId = $request->input('movie_id');

        try {
            // Xóa phim khỏi watchlist
            $detached = $user->watchlistMovies()->detach($movieId);

            if ($detached) {
                return response()->json(['success' => true, 'message' => 'Movie removed from watchlist']);
            } else {
                // Có thể phim không có trong watchlist để xóa
                return response()->json(['success' => false, 'message' => 'Movie not found in watchlist'], 404); // 404 Not Found
            }
        } catch (\Exception $e) {
             Log::error("Error removing movie from watchlist: " . $e->getMessage());
             return response()->json(['success' => false, 'message' => 'Failed to remove movie from watchlist'], 500);
        }
    }
}