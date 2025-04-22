<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController; // Import HomeController nếu dùng Controller
use App\Http\Controllers\MovieController;
use App\Http\Controllers\TvShowController;
use App\Http\Controllers\CelebController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\SearchController; // Controller cho tìm kiếm

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home'); // Gán tên 'home'

// Ví dụ các route khác (bạn sẽ cần tạo Controller tương ứng)
Route::get('/movies', [MovieController::class, 'index'])->name('movies.list');
Route::get('/tv-shows', [TvShowController::class, 'index'])->name('tvshows.list');
Route::get('/celebs', [CelebController::class, 'index'])->name('celebs.list');
Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/search', [SearchController::class, 'webSearch'])->name('search'); // Route cho form search

// Route cần đăng nhập (sử dụng middleware auth)
Route::middleware('auth:sanctum')->group(function () {

    // *** ĐỊNH NGHĨA ROUTE RATING PHIM ***
    Route::post('/rate/movie', [RatingController::class, 'store'])
          ->name('api.rate.movie'); // <-- Đặt tên route ở đây

    // *** ĐỊNH NGHĨA ROUTE XÓA RATING ***
    // (Cần cả route này vì JS cũng dùng route('api.remove.rating'))
    // Có thể dùng POST hoặc DELETE
    Route::post('/remove-rating', [RatingController::class, 'destroy'])
           ->name('api.remove.rating');

    // *** ĐỊNH NGHĨA ROUTE WATCHLIST ***
    // (Cần cả các route này vì JS cũng dùng)
    Route::post('/watchlist/add', [WatchlistController::class, 'store'])
           ->name('api.watchlist.add');

    // Sử dụng DELETE sẽ hợp lý hơn cho việc xóa
    Route::delete('/watchlist/remove', [WatchlistController::class, 'destroy'])
            ->name('api.watchlist.remove');

    // Thêm các API endpoint cần xác thực khác ở đây...

});


// Route xác thực mặc định của Laravel (nếu bạn cài đặt auth)
// require __DIR__.'/auth.php';

// Route API cho search suggestions (trong routes/api.php)
// Route::get('/search', [ApiSearchController::class, 'suggestions'])->name('api.search');