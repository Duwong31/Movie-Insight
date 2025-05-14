<?php

namespace Modules\Review\Admin;

use Illuminate\Routing\Controller;
use Modules\Review\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::where('status', 0)
            ->with(['user', 'movie'])
            ->latest()
            ->paginate(15);
        return view('Review::admin.index', compact('reviews'))->with('title', 'Pending Reviews');
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
}