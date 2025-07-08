<?php

namespace App\Http\Controllers; // ĐẢM BẢO DÒNG NÀY ĐÚNG

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Movies\Models\Movie; // Điều chỉnh namespace nếu cần

class UserRatingController extends Controller
{
    // ... nội dung controller của bạn ...
    public function index()
    {
        $user = Auth::user();

        $ratings = $user->ratings()
                        ->with('movie')
                        ->orderBy('rating', 'desc')
                        ->paginate(10);

        return view('users.ratings.index', compact('ratings', 'user'));
    }
}