<?php

namespace Modules\Actors\Admin;

use App\Http\Controllers\Controller;
use Modules\Actors\Models\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ActorController extends Controller
{
    /**
     * Display a listing of actors with filters and search
     */
    public function index(Request $request)
    {
        $query = Actor::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('actor_name', 'LIKE', "%{$search}%");
        }

        // Get actors with movie count
        $query->withCount('movies');
        
        $actors = $query->orderBy('actor_name', 'asc')->paginate(15);

        $statistics = [
            'total' => Actor::count(),
            'with_movies' => Actor::has('movies')->count(),
            'without_movies' => Actor::doesntHave('movies')->count(),
        ];

        return view('Actors::admin.index', compact('actors', 'statistics'));
    }

    /**
     * Show the form for creating a new actor
     */
    public function create()
    {
        return view('Actors::admin.create');
    }

    /**
     * Store a newly created actor
     */
    public function store(Request $request)
    {
        $request->validate([
            'actor_name' => 'required|string|max:255|unique:actors,actor_name',
            'actors_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'biography' => 'nullable|string',
        ]);

        $data = $request->only(['actor_name', 'birth_date', 'nationality', 'biography']);

        // Handle image upload
        if ($request->hasFile('actors_image')) {
            $data['actors_image'] = $this->uploadImage($request->file('actors_image'));
        }

        $actor = Actor::create($data);

        return redirect()
            ->route('admin.actors.show', $actor)
            ->with('success', 'Actor created successfully!');
    }

    /**
     * Display the specified actor
     */
    public function show(Actor $actor)
    {
        $actor->load(['movies' => function ($query) {
            $query->withAvg('ratings', 'rating');
        }]);
        
        return view('Actors::admin.show', compact('actor'));
    }

    /**
     * Show the form for editing the specified actor
     */
    public function edit(Actor $actor)
    {
        return view('Actors::admin.edit', compact('actor'));
    }

    /**
     * Update the specified actor
     */
    public function update(Request $request, Actor $actor)
    {
        $request->validate([
            'actor_name' => 'required|string|max:255|unique:actors,actor_name,' . $actor->actors_id . ',actors_id',
            'actors_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'biography' => 'nullable|string',
        ]);

        $data = $request->only(['actor_name', 'birth_date', 'nationality', 'biography']);

        // Handle image upload
        if ($request->hasFile('actors_image')) {
            // Delete old image if exists
            if ($actor->actors_image) {
                Storage::disk('public')->delete($actor->actors_image);
            }
            $data['actors_image'] = $this->uploadImage($request->file('actors_image'));
        }

        $actor->update($data);

        return redirect()
            ->route('admin.actors.show', $actor)
            ->with('success', 'Actor updated successfully!');
    }

    /**
     * Remove the specified actor
     */
    public function destroy(Actor $actor)
    {
        // Check if actor has movies
        if ($actor->movies()->count() > 0) {
            return redirect()
                ->route('admin.actors.index')
                ->with('error', 'Cannot delete actor that has movies. Please remove movie associations first.');
        }

        // Delete associated image
        if ($actor->actors_image) {
            Storage::disk('public')->delete($actor->actors_image);
        }

        $actor->delete();

        return redirect()
            ->route('admin.actors.index')
            ->with('success', 'Actor deleted successfully!');
    }

    /**
     * Bulk actions for multiple actors
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete',
            'actor_ids' => 'required|array',
            'actor_ids.*' => 'exists:actors,actors_id'
        ]);

        $actorIds = $request->actor_ids;
        $action = $request->action;
        $count = count($actorIds);

        switch ($action) {
            case 'delete':
                // Check if any actors have movies
                $actorsWithMovies = Actor::whereIn('actors_id', $actorIds)
                    ->has('movies')
                    ->count();
                
                if ($actorsWithMovies > 0) {
                    return redirect()
                        ->route('admin.actors.index')
                        ->with('error', 'Some actors have movies and cannot be deleted. Please remove movie associations first.');
                }

                // Delete images and actors
                $actors = Actor::whereIn('actors_id', $actorIds)->get();
                foreach ($actors as $actor) {
                    if ($actor->actors_image) {
                        Storage::disk('public')->delete($actor->actors_image);
                    }
                }
                
                Actor::whereIn('actors_id', $actorIds)->delete();
                $message = "{$count} actors deleted successfully!";
                break;
        }

        return redirect()
            ->route('admin.actors.index')
            ->with('success', $message);
    }

    /**
     * Upload image and return path
     */
    private function uploadImage($image)
    {
        $filename = 'actors/' . time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        return $image->storeAs('actors', basename($filename), 'public');
    }
}
