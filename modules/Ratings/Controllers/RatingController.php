<?php

namespace Modules\Ratings\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log; 
use Modules\Movies\Models\Movie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Ratings\Models\Rating; // Import the Rating model

class RatingController extends Controller
{
    /**
     * Lưu hoặc cập nhật rating của user cho phim.
     */
    public function store(Request $request)
    {
        $request->validate([
            // ===>>> Sửa validation cho movie_id <<<===
            'movie_id' => ['required', 'integer', Rule::exists('movies', 'movie_id')], // Kiểm tra tồn tại trên cột movie_id
            // ===>>> Sửa validation cho rating (chính xác hơn) <<<===
            'rating' => 'required|numeric|min:0|max:10', // Cho phép số thực, min là 0
        ]);

        $user = Auth::user();
        $movieId = $request->input('movie_id');
        $ratingValue = $request->input('rating');

        try {
            Rating::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'movie_id' => $movieId,
                ],
                [
                    'rating' => $ratingValue,
                ]
            );

            // Tìm lại movie bằng PK đã khai báo (movie_id)
            $movie = Movie::find($movieId);
            $roundedAverage = 0; // Giá trị mặc định

            if ($movie) {
                 // Tính trung bình cột 'rating' từ quan hệ 'ratings' đã định nghĩa
                $newAverage = $movie->ratings()->avg('rating');
                $roundedAverage = round($newAverage ?? 0, 1);
            }

            return response()->json([
                'success' => true,
                'message' => 'Rating submitted successfully',
                'new_average_rating' => $roundedAverage // <<< Giữ nguyên trả về giá trị này
            ]);

        } catch (\Exception $e) {
            Log::error("Error saving rating: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to save rating.'], 500);
        }
    }

    /**
     * Xóa rating của user cho phim.
     */
    public function destroy(Request $request)
     {
         $request->validate([
             'movie_id' => ['required', 'integer', Rule::exists('movies', 'movie_id')], // <<< Sửa validation
         ]);

         $user = Auth::user();
         $movieId = $request->input('movie_id');

         try {
             $deleted = Rating::where('user_id', $user->id)
                              ->where('movie_id', $movieId)
                              ->delete();

             if ($deleted) {
                  // Tính lại điểm trung bình sau khi xóa
                  $movie = Movie::find($movieId);
                  $roundedAverage = 0;
                  if ($movie) {
                      $newAverage = $movie->ratings()->avg('rating');
                      $roundedAverage = round($newAverage ?? 0, 1);
                  }

                  return response()->json([
                     'success' => true,
                     'message' => 'Rating removed successfully',
                     'new_average_rating' => $roundedAverage // <<< Trả về điểm mới
                  ]);
             } else {
                 // Rating không tồn tại để xóa
                 return response()->json(['success' => false, 'message' => 'Rating not found.'], 404);
             }

         } catch (\Exception $e) {
             Log::error("Error removing rating: " . $e->getMessage());
             return response()->json(['success' => false, 'message' => 'Failed to remove rating.'], 500);
         }
     }
}