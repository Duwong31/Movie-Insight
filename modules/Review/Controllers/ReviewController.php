<?php
namespace Modules\Review\Controllers; 

use Modules\Movies\Models\Movie;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;



class ReviewController extends Controller
{
    public function store(Request $request, Movie $movie)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:400', // Minimum 400 characters
            'has_spoiler' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        /** @var \App\Models\User|null $user */ //
        $user = Auth::user();

        // 1. Create the review
        $review = $user->reviews()->create([
            'movie_id' => $movie->movie_id, 
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'has_spoiler' => $request->input('has_spoiler'),
        ]);


        return response()->json([
            'success' => 'Review submitted successfully!',
            'review' => $review->load('user') // Send back the review with user data
        ], 201);
    }

}
