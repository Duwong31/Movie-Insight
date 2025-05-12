<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // <<< Thêm dòng này
use App\Http\Controllers\HomeController;
use Modules\Movies\Controllers\MovieController;
use Modules\TVShows\Controllers\TVShowController;
use App\Http\Controllers\CelebController;
use App\Http\Controllers\NewsController;
use Modules\Watchlists\Controllers\WatchlistController;
use Modules\Ratings\Controllers\RatingController;
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
Route::get('/genres/{id}', [GenreController::class, 'show'])
      ->name('genres.show');
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

    Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show'); // Thay Controller nếu cần
    Route::get('/my-ratings', [UserRatingController::class, 'index'])->name('ratings.index'); // Thay Controller nếu cần
    Route::get('/account-settings', [AccountSettingController::class, 'edit'])->name('settings.account');

});

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); // Có thể comment hoặc xóa dòng này
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
