<?php

namespace Modules\Genres\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Genres\Models\Genre;

class GenreController extends Controller
{
    /**
     * Hiển thị chi tiết genre và danh sách phim thuộc genre đó.
     * GET /genres/{id}
     */
    public function show($id)
    {
        try {
            $genre = Genre::findOrFail($id);
            
            // Lấy danh sách phim thuộc genre này
            $movies = $genre->movies()
                           ->withAvg('ratings', 'rating')
                           ->orderBy('release_date', 'desc')
                           ->paginate(20);
            
            return view('genres.show', compact('genre', 'movies'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('home')->with('error', 'Genre not found.');
        }
    }
}
