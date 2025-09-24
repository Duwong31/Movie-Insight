<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Actors\Models\Actor;

class CelebController extends Controller
{
    /**
     * Hiển thị danh sách celebrities.
     * GET /celebs
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $actors = Actor::query()
            ->when($search, function ($query, $search) {
                return $query->where('actor_name', 'LIKE', "%{$search}%");
            })
            ->orderBy('actor_name', 'asc')
            ->paginate(20);
        
        return view('celebs.index', compact('actors', 'search'));
    }

    /**
     * Hiển thị chi tiết celebrity.
     * GET /celebs/{id}
     */
    public function show($id)
    {
        try {
            $actor = Actor::with(['movies' => function ($query) {
                $query->withAvg('ratings', 'rating');
            }])->findOrFail($id);
            
            return view('celebs.show', compact('actor'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('celebs.list')->with('error', 'Celebrity not found.');
        }
    }
}
