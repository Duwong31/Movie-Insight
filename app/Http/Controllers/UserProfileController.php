<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    /**
     * Hiển thị trang profile của người dùng đã đăng nhập.
     * GET /profile
     */
    public function show(Request $request)
    {
        $user = $request->user(); // Lấy người dùng đang đăng nhập
        
        // Lấy thống kê của người dùng
        $ratingsCount = $user->ratings()->count();
        $watchlistCount = $user->watchlistMovies()->count();
        
        // Lấy một số ratings gần đây
        $recentRatings = $user->ratings()
                             ->with('movie')
                             ->orderBy('created_at', 'desc')
                             ->limit(5)
                             ->get();
        
        // Lấy một số phim trong watchlist gần đây
        $recentWatchlist = $user->watchlistMovies()
                               ->orderBy('watchlist.created_at', 'desc')
                               ->limit(5)
                               ->get()
                               ->map(function ($movie) {
                                   // Cast pivot created_at to Carbon instance
                                   if ($movie->pivot->created_at && is_string($movie->pivot->created_at)) {
                                       $movie->pivot->created_at = \Carbon\Carbon::parse($movie->pivot->created_at);
                                   }
                                   return $movie;
                               });
        
        return view('users.profile.show', compact(
            'user', 
            'ratingsCount', 
            'watchlistCount', 
            'recentRatings', 
            'recentWatchlist'
        ));
    }

    /**
     * Update user's profile image.
     * PUT /profile/update-image
     */
    public function updateImage(Request $request)
    {
        $user = $request->user();
        
        $request->validate([
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_profile_image' => 'nullable|boolean',
        ]);

        // Handle profile image
        if ($request->boolean('remove_profile_image')) {
            // Remove existing profile image
            if ($user->profile_image) {
                $imagePath = storage_path('profile/' . $user->profile_image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $user->update(['profile_image' => null]);
            }
        } elseif ($request->hasFile('profile_image')) {
            // Remove old image if exists
            if ($user->profile_image) {
                $oldImagePath = storage_path('profile/' . $user->profile_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            // Store new image
            $image = $request->file('profile_image');
            $imageName = 'profile_' . $user->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = storage_path('profile');
            $image->move($destinationPath, $imageName);
            $user->update(['profile_image' => $imageName]);
        }

        return redirect()->route('profile.show')->with('success', 'Profile image updated successfully!');
    }
}
