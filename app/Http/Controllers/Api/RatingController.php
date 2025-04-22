<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// Import các Model cần thiết (ví dụ: Movie, Rating)
// use App\Models\Rating;
// use App\Models\Movie;

class RatingController extends Controller
{
    /**
     * Lưu hoặc cập nhật rating của user cho phim.
     */
    public function store(Request $request)
    {
        // --- Xác thực dữ liệu đầu vào ---
        $validated = $request->validate([
            'movie_id' => 'required|integer|exists:movies,movie_id', // Kiểm tra movie_id tồn tại trong bảng movies
            'rating' => 'required|integer|min:1|max:10', // Rating từ 1 đến 10
        ]);

        $user = Auth::user(); // Lấy user đã xác thực qua Sanctum

        // --- Logic lưu/cập nhật rating ---
        // Ví dụ sử dụng updateOrCreate:
        /*
        Rating::updateOrCreate(
            [
                'user_id' => $user->id,
                'movie_id' => $validated['movie_id'],
            ],
            [
                'rating' => $validated['rating'],
            ]
        );
        */

        // --- (Tùy chọn) Tính toán lại điểm trung bình ---
        // $movie = Movie::find($validated['movie_id']);
        // $newAverageRating = $movie->calculateAverageRating(); // Giả sử có hàm này trong Model

        // --- Trả về response JSON ---
        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully.',
            // 'new_average_rating' => number_format($newAverageRating, 1), // Trả về điểm mới nếu có
        ]);
    }

    /**
     * Xóa rating của user cho phim.
     */
    public function destroy(Request $request)
    {
         // --- Xác thực dữ liệu đầu vào ---
         $validated = $request->validate([
            'movie_id' => 'required|integer|exists:movies,movie_id',
         ]);

         $user = Auth::user();

         // --- Logic xóa rating ---
         /*
         Rating::where('user_id', $user->id)
               ->where('movie_id', $validated['movie_id'])
               ->delete();
         */

         // --- (Tùy chọn) Tính toán lại điểm trung bình ---
         // $movie = Movie::find($validated['movie_id']);
         // $newAverageRating = $movie->calculateAverageRating();

         // --- Trả về response JSON ---
         return response()->json([
            'success' => true,
            'message' => 'Rating removed successfully.',
            // 'new_average_rating' => number_format($newAverageRating, 1),
         ]);
    }
}