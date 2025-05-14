<?php

namespace Modules\Movies\Admin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Movies\Models\Movie;
use Illuminate\Support\Facades\File;
use Modules\Genres\Models\Genre;
use Modules\Actors\Models\Actor;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::where('movie_type', 'isMovie')->with('genres', 'actors')->orderByDesc('created_at')->paginate(10);
        return view('Movies::admin.index', compact('movies'))->with('title', 'Manage Movies');
    }

    public function create()
    {
        $genres = Genre::orderBy('genres_name')->get(); // Lấy tất cả genres để hiển thị checkbox
        return view('Movies::admin.create', compact('genres',))->with('title', 'Add New Movie');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'movie_name' => 'required|string|max:255|unique:movies,movie_name',
            'release_date' => 'nullable|integer|min:1900|max:' . (date('Y') + 5), 
            'movie_desc' => 'nullable|string',
            'director' => 'nullable|string|max:100',
            'duration' => 'nullable|string|max:50',
            'status' => 'required|in:in_theaters,streaming,coming soon',
            'trailer_url' => 'nullable|url|max:255',
            'movie_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
            'genres' => 'nullable|array', // 'genres' sẽ là một mảng các ID
            'genres.*' => 'integer|exists:genres,genres_id',
            'actors' => 'nullable|array', // <-- THÊM VALIDATION CHO ACTORS (DẠNG MẢNG ID)
            'actors_input' => 'nullable|string|max:1000',// Validate mỗi ID trong mảng genres
        ]);
        $data['movie_type'] = 'isMovie';

        if ($request->hasFile('movie_image')) {
            $file = $request->file('movie_image');
            $filename = uniqid('movie_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/movies'), $filename);
            $data['movie_image'] = 'movies/' . $filename;
        }
        $genreIds = $data['genres'] ?? [];
        unset($data['genres']);

        // Xử lý actors_input
        $actorIds = [];
        if (!empty($data['actors_input'])) {
            $actorNames = array_map('trim', explode(',', $data['actors_input']));
            $actorNames = array_filter($actorNames); // Loại bỏ các tên rỗng
        
            foreach ($actorNames as $actorName) {
                // Không cần kiểm tra empty($actorName) nữa vì array_filter đã làm
                $actor = Actor::findOrCreateByName($actorName);
                if ($actor && $actor->getKey()) { // Kiểm tra $actor tồn tại và có khóa chính (actor_id)
                    $actorIds[] = $actor->getKey(); // Dùng getKey() để lấy giá trị khóa chính
                }
            }
        }
        unset($data['actors_input']);

        $movie = Movie::create($data);

        if (!empty($genreIds)) {
            $movie->genres()->sync($genreIds); // sync() sẽ xử lý việc thêm/xóa trong bảng pivot
        }   
        if (!empty($actorIds)) {
            // Lọc thêm một lần nữa để chắc chắn không có giá trị null hoặc không phải số
            $validActorIds = array_filter(array_unique($actorIds), function($id) {
                return !is_null($id) && is_numeric($id) && $id > 0;
            });
            if (!empty($validActorIds)) {
                $movie->actors()->sync($validActorIds);
            }
        } else {
            $movie->actors()->detach();
        }
        return redirect()->route('admin.movies.index')->with('success', 'Movie added!');
    }

    public function edit($id)
    {
        $movie = Movie::with('genres')->findOrFail($id);
        $genres = Genre::orderBy('genres_name')->get();
        $movieGenreIds = $movie->genres->pluck('genres_id')->toArray();
        $actorsInputString = $movie->actors->pluck('actor_name')->implode(', ');
        return view('Movies::admin.edit', compact('movie', 'genres', 'movieGenreIds', 'actorsInputString'))->with('title', 'Edit Movie');
    }

    public function update(Request $request, $id)
    {

        $movie = Movie::findOrFail($id);
        $data = $request->validate([
            'movie_name' => 'required|string|max:255|unique:movies,movie_name,' . $movie->movie_id . ',movie_id',
            'release_date' => 'nullable|integer|min:1900|max:' . (date('Y') + 5),
            'movie_desc' => 'nullable|string',
            'director' => 'nullable|string|max:100',
            'duration' => 'nullable|string|max:50',
            'status' => 'required|in:in_theaters,streaming,coming soon',
            'trailer_url' => 'nullable|url|max:255',
            'movie_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Ảnh không bắt buộc khi update
            'genres' => 'nullable|array',
            'genres.*' => 'integer|exists:genres,genres_id',
            'actors_input' => 'nullable|string|max:1000',
        ]);
        $data['movie_type'] = 'isMovie';

        if ($request->hasFile('movie_image')) {
            $oldImagePathInDb = $movie->movie_image;

            // Xóa ảnh cũ nếu tồn tại
            if ($oldImagePathInDb) {
                $fullOldPath = public_path('uploads/' . $oldImagePathInDb);
                if (File::exists($fullOldPath)) {
                    File::delete($fullOldPath);
                }
            }

            $file = $request->file('movie_image');
            $filename = uniqid('movie_') . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/movies');
            $file->move($uploadPath, $filename);
            $data['movie_image'] = 'movies/' . $filename;
        }

        $genreIds = $data['genres'] ?? [];
        unset($data['genres']);
        // Xử lý actors_input
        $actorIds = [];
        if (isset($data['actors_input'])) { // Thay !empty bằng isset để xử lý trường hợp chuỗi rỗng ""
            $actorNamesString = $data['actors_input'];
            if (is_string($actorNamesString) && $actorNamesString !== '') { // Chỉ xử lý nếu là chuỗi không rỗng
                $actorNames = array_map('trim', explode(',', $actorNamesString));
                $actorNames = array_filter($actorNames, function($name) { // Lọc kỹ hơn
                    return !empty($name); // Chỉ giữ lại tên thực sự có ký tự
                });
        
                foreach ($actorNames as $actorName) {
                    // $actorName ở đây đã được trim và không rỗng
                    $actor = Actor::findOrCreateByName($actorName);
                    if ($actor && $actor->getKey()) {
                        $actorIds[] = $actor->getKey();
                    } else {
                        // Ghi log nếu findOrCreateByName không trả về actor hợp lệ
                        \Log::warning("Could not find or create actor for name: '{$actorName}' when updating movie ID: {$movie->movie_id}");
                    }
                }
            }
        }
        unset($data['actors_input']);

        $movie->update($data);

        $movie->genres()->sync($genreIds);
        $movie->actors()->sync(array_unique($actorIds));
        return redirect()->route('admin.movies.index')->with('success', 'Movie updated!');
    }

    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);
        $imagePathInDb = $movie->movie_image;

        if ($imagePathInDb) {
            $fullPath = public_path('uploads/' . $imagePathInDb);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }

        $movie->delete();
        return redirect()->route('admin.movies.index')->with('success', 'Movie deleted!');
    }
}
