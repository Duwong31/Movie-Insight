<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\User;  
use App\Http\Controllers\HomeController;
use Modules\Movies\Controllers\MovieController;
use Modules\TVShows\Controllers\TVShowController;
use App\Http\Controllers\CelebController;
use App\Http\Controllers\NewsController;
use Modules\Watchlists\Controllers\WatchlistController;
use Modules\Ratings\Controllers\RatingController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\SearchController;
use Modules\Dashboard\Admin\DashboardController;
use Modules\User\Admin\UserController;
use Modules\Review\Controllers\ReviewController;
use Modules\Review\Admin\ReviewController as AdminReviewController;
use Modules\Movies\Admin\MovieController as AdminMovieController;
use Modules\TVShows\Admin\TVShowController as AdminTVShowController;
use Modules\Genres\Admin\GenreController as AdminGenreController;
use Modules\Genres\Controllers\GenreController;
// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Các route công khai khác (Dùng route() helper trong view thay vì ?module=...)
Route::get('/movies', [MovieController::class, 'index'])->name('movies.list');
Route::get('/movies/{id}', [MovieController::class, 'show'])->name('movie.detail');
Route::post('/movies/{movie}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/movies/reviews/{id}', [MovieController::class, 'allReviews'])->name('movie.reviews');
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

Auth::routes();

Route::prefix('admin')->group(function() {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.index');
    //users
    Route::prefix('module/users')
    ->as('admin.users.')
    ->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{user}', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
    //reviews
    Route::prefix('module/reviews')
    ->as('admin.reviews.')
    ->group(function () {
        Route::get('/', [AdminReviewController::class, 'index'])->name('index');
        Route::get('/{review}', [AdminReviewController::class, 'show'])->name('show');
        Route::post('/{review}/approve', [AdminReviewController::class, 'approve'])->name('approve');
        Route::delete('/{review}', [AdminReviewController::class, 'destroy'])->name('destroy');
    });

    //movies
    Route::prefix('module/movies')
        ->as('admin.movies.') // Tạo prefix cho tên route: admin.movies.index, admin.movies.create,...
        ->group(function () {
            Route::get('/', [AdminMovieController::class, 'index'])->name('index');
            Route::get('/create', [AdminMovieController::class, 'create'])->name('create');
            Route::post('/', [AdminMovieController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [AdminMovieController::class, 'edit'])->name('edit'); // {id} hoặc {movie} nếu dùng route model binding
            Route::put('/{id}', [AdminMovieController::class, 'update'])->name('update'); // {id} hoặc {movie}
            Route::delete('/{id}', [AdminMovieController::class, 'destroy'])->name('destroy'); // {id} hoặc {movie}
            // Bạn có thể dùng Route::resource('/', AdminMovieController::class); nếu các phương thức theo chuẩn resource.
    });

    //TVSHOWS
    Route::prefix('module/tvshows')
        ->as('admin.tvshows.')
        ->group(function () {
            Route::get('/', [AdminTVShowController::class, 'index'])->name('index');
            Route::get('/create', [AdminTVShowController::class, 'create'])->name('create');
            Route::post('/', [AdminTVShowController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [AdminTVShowController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AdminTVShowController::class, 'update'])->name('update');
            Route::delete('/{id}', [AdminTVShowController::class, 'destroy'])->name('destroy');
    });

    //Genres
    Route::prefix('module/genres')
        ->as('admin.genres.')
        ->group(function () {
            Route::get('/', [AdminGenreController::class, 'index'])->name('index');
            Route::get('/create', [AdminGenreController::class, 'create'])->name('create');
            Route::post('/', [AdminGenreController::class, 'store'])->name('store');
            Route::get('/edit/{genre}', [AdminGenreController::class, 'edit'])->name('edit'); // {genre} sử dụng route model binding
            Route::put('/{genre}', [AdminGenreController::class, 'update'])->name('update');
            Route::delete('/{genre}', [AdminGenreController::class, 'destroy'])->name('destroy');
        });
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
