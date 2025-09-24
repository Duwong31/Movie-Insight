<?php

namespace Modules\Review\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Review\Models\Review;
use Modules\Movies\Models\Movie;
use App\Models\User;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['user', 'movie', 'adminEditor']);

        // Filter by status if specified
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default to pending reviews
            $query->where('status', 0);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by movie
        if ($request->filled('movie_id')) {
            $query->where('movie_id', $request->movie_id);
        }

        // Search by title or content
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by admin edited
        if ($request->filled('admin_edited')) {
            $query->where('admin_edited', $request->admin_edited === '1');
        }

        $reviews = $query->latest()->paginate(15);
        
        // Get users and movies for filter dropdowns
        $users = User::select('id', 'fullname')->orderBy('fullname')->get();
        $movies = Movie::select('movie_id', 'movie_name')->orderBy('movie_name')->get();

        $title = 'Manage Reviews';
        if ($request->filled('status') && $request->status == 0) {
            $title = 'Pending Reviews';
        } elseif ($request->filled('status') && $request->status == 1) {
            $title = 'Approved Reviews';
        }

        return view('Review::admin.index', compact('reviews', 'users', 'movies', 'title'));
    }

    public function show($id)
    {
        $review = Review::with(['user', 'movie' => function($q) {
            $q->withAvg('ratings', 'rating');
        }])->findOrFail($id);
        return view('Review::admin.show', compact('review'));
    }       

    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->status = 1;
        $review->save();
        return redirect()->route('admin.reviews.index')->with('success', 'Review approved!');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted!');
    }

    /**
     * Show the form for creating a new review.
     */
    public function create()
    {
        $movies = Movie::select('movie_id', 'movie_name')->orderBy('movie_name')->get();
        $users = User::select('id', 'fullname')->orderBy('fullname')->get();
        
        return view('Review::admin.create', compact('movies', 'users'));
    }

    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'movie_id' => 'required|exists:movies,movie_id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:50',
            'rating_given' => 'nullable|integer|min:1|max:10',
            'has_spoiler' => 'boolean',
            'status' => 'required|in:0,1',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
        ]);

        $reviewData = $request->only([
            'user_id', 'movie_id', 'title', 'content', 
            'rating_given', 'has_spoiler', 'status'
        ]);

        $review = new Review($reviewData);

        // Set custom timestamps if provided
        if ($request->filled('created_at')) {
            $review->created_at = $request->created_at;
        }
        if ($request->filled('updated_at')) {
            $review->updated_at = $request->updated_at;
        }

        $review->save();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review created successfully!');
    }

    /**
     * Show the form for editing the specified review.
     */
    public function edit($id)
    {
        $review = Review::with(['user', 'movie', 'adminEditor'])->findOrFail($id);
        $movies = Movie::select('movie_id', 'movie_name')->orderBy('movie_name')->get();
        $users = User::select('id', 'fullname')->orderBy('fullname')->get();
        
        return view('Review::admin.edit', compact('review', 'movies', 'users'));
    }

    /**
     * Update the specified review in storage.
     */
    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'movie_id' => 'required|exists:movies,movie_id',
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:50',
            'rating_given' => 'nullable|integer|min:1|max:10',
            'has_spoiler' => 'boolean',
            'status' => 'required|in:0,1',
            'admin_edit_reason' => 'required|string|max:500',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
        ]);

        // Store original data for audit
        $originalData = $review->toArray();

        // Update review data
        $reviewData = $request->only([
            'user_id', 'movie_id', 'title', 'content', 
            'rating_given', 'has_spoiler', 'status'
        ]);

        $review->fill($reviewData);

        // Set admin edit metadata
        $review->admin_edited = true;
        $review->admin_edited_by = Auth::id();
        $review->admin_edit_reason = $request->admin_edit_reason;
        $review->admin_edit_timestamp = now();

        // Set custom timestamps if provided
        if ($request->filled('created_at')) {
            $review->created_at = $request->created_at;
        }
        if ($request->filled('updated_at')) {
            $review->updated_at = $request->updated_at;
        }

        $review->save();

        // Log the change (you could create a separate audit log table if needed)
        Log::info('Admin edited review', [
            'admin_id' => Auth::id(),
            'review_id' => $review->id,
            'reason' => $request->admin_edit_reason,
            'original_data' => $originalData,
            'new_data' => $review->fresh()->toArray(),
        ]);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review updated successfully!');
    }
}