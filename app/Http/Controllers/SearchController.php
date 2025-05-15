<?php

namespace App\Http\Controllers; // Hoặc namespace của bạn

use Illuminate\Http\Request;
use Modules\Movies\Models\Movie; // Giả sử bạn dùng Movie model này

class SearchController extends Controller
{
    public function webSearch(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            if ($request->ajax()) {
                return response()->json([]); // Trả về mảng rỗng nếu query rỗng cho AJAX
            }
            return view('search.results', ['results' => collect([]), 'query' => $query]); // Hoặc redirect về home
        }

        // Tìm kiếm phim dựa trên tên (chỉ isMovie)
        $movies = Movie::where('movie_type', 'isMovie') // Chỉ tìm phim
                       ->where('movie_name', 'LIKE', "%{$query}%")
                       ->select('movie_id', 'movie_name', 'release_date', 'movie_image') // Chọn các cột cần thiết
                       ->take(10) // Giới hạn số lượng kết quả cho gợi ý và trang kết quả
                       ->get();

        // Xử lý cho request AJAX (gợi ý)
        if ($request->ajax()) {
            if ($movies->isEmpty()) {
                return response()->json(['message' => 'No movies found matching your search.']);
            }

            $suggestions = $movies->map(function ($movie) {
                return [
                    'id' => $movie->movie_id,
                    'title' => $movie->movie_name,
                    'year' => $movie->release_date,
                    'image_url' => $movie->movie_image ? asset('uploads/' . $movie->movie_image) : asset('img/placeholder-movie.jpg'),
                    'url' => route('movie.detail', ['id' => $movie->movie_id]), // URL đến trang chi tiết phim
                    'type' => 'Movie', // Thêm type để JS có thể hiển thị
                ];
            });
            return response()->json($suggestions);
        }

        // Xử lý cho request thường (trang kết quả tìm kiếm)
        // Bạn có thể muốn tìm kiếm cả TV Shows, Celebs ở đây nếu trang kết quả chung
        // $allResults = $movies; // Mở rộng nếu cần
        return view('home.results', ['results' => $movies, 'query' => $query]);
    }
}