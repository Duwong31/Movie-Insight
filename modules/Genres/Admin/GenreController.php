<?php

namespace Modules\Genres\Admin; // Namespace đúng

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Genres\Models\Genre; // Model Genre của bạn
use Illuminate\Support\Facades\Log; // Để log nếu cần

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $genres = Genre::query()
            ->when($search, function ($query, $search) {
                return $query->where('genres_name', 'like', '%' . $search . '%');
            })
            ->orderBy('genres_name', 'asc') // Sắp xếp theo tên
            ->paginate(10); // Phân trang, 10 items mỗi trang

        return view('Genres::admin.index', compact('genres', 'search'));
        // Lưu ý cách gọi view: 'ModuleName::FolderPath.fileName'
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Genres::admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'genres_name' => 'required|string|max:255|unique:genres,genres_name',
        ]);

        try {
            Genre::create($request->only('genres_name'));
            return redirect()->route('admin.genres.index')
                             ->with('success', 'Genre created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating genre: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Failed to create genre. Please try again.')
                             ->withInput();
        }
    }

    /**
     * Display the specified resource.
     * (Thường không cần trang show riêng cho admin genres, có thể bỏ qua)
     */
    // public function show(Genre $genre)
    // {
    //     // return view('Genres::Admin.show', compact('genre'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Genre $genre) // Sử dụng Route Model Binding
    {
        return view('Genres::admin.edit', compact('genre'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Genre $genre) // Sử dụng Route Model Binding
    {
        $request->validate([
            'genres_name' => 'required|string|max:255|unique:genres,genres_name,' . $genre->genres_id . ',genres_id',
            // genres_id là tên cột khóa chính của bảng genres
        ]);

        try {
            $genre->update($request->only('genres_name'));
            return redirect()->route('admin.genres.index')
                             ->with('success', 'Genre updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating genre: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Failed to update genre. Please try again.')
                             ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre) // Sử dụng Route Model Binding
    {
        try {
            // Kiểm tra xem genre có đang được sử dụng bởi phim nào không (nếu cần)
            if ($genre->movies()->exists()) {
                return redirect()->route('admin.genres.index')
                                 ->with('error', 'Cannot delete genre. It is currently associated with one or more movies.');
            }
            $genre->delete();
            return redirect()->route('admin.genres.index')
                             ->with('success', 'Genre deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting genre: ' . $e->getMessage());
            return redirect()->route('admin.genres.index')
                             ->with('error', 'Failed to delete genre. Please try again.');
        }
    }
}