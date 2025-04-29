<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // <<< Thêm dòng này
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TvShowController;
use App\Http\Controllers\CelebController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\SearchController;

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Các route công khai khác (Dùng route() helper trong view thay vì ?module=...)
Route::get('/movies', [MovieController::class, 'index'])->name('movies.list');
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movie.detail'); // Dùng route này cho link chi tiết
Route::get('/tv-shows', [TvShowController::class, 'index'])->name('tvshows.list');
// Route chi tiết TV Show có thể dùng chung 'movie.detail' nếu cấu trúc giống nhau
// Route::get('/tv-shows/{id}', [TvShowController::class, 'show'])->name('tvshow.detail');
Route::get('/celebs', [CelebController::class, 'index'])->name('celebs.list');
Route::get('/celebs/{id}', [CelebController::class, 'show'])->name('celeb.detail'); // Dùng route này
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/search', [SearchController::class, 'webSearch'])->name('search');

// Route xác thực (Login, Register, Forgot Password, Logout...)
Auth::routes(); // Tự động tạo các route /login, /register, /logout,...

// Route cần đăng nhập
Route::middleware(['auth'])->group(function () { // <<< Dùng 'auth'

    // Rating
    Route::post('/rate/movie', [RatingController::class, 'store'])->name('api.rate.movie');
    Route::post('/remove-rating', [RatingController::class, 'destroy'])->name('api.remove.rating'); // Đổi thành DELETE nếu muốn chuẩn RESTful hơn

    // Watchlist
    Route::post('/watchlist/add', [WatchlistController::class, 'store'])->name('api.watchlist.add');
    Route::delete('/watchlist/remove', [WatchlistController::class, 'destroy'])->name('api.watchlist.remove');
    Route::get('/my-watchlist', [WatchlistController::class, 'index'])->name('watchlist.index'); // Route xem watchlist

    // Profile (Ví dụ)
    // Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
    // Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');

});

// Laravel UI tạo route /home mặc định, bạn có thể bỏ nếu trang chủ là '/'
// Hoặc sửa redirect trong Auth Controllers
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); // Có thể comment hoặc xóa dòng này
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
