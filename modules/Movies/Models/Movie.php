<?php

namespace Modules\Movies\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // <<< Thêm dòng này
use Illuminate\Database\Eloquent\Relations\HasOne;  // <<< Giữ lại nếu đã có userRating
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // <<< Thêm dòng này
use Illuminate\Support\Facades\Auth;             // <<< Giữ lại nếu đã có userRating
use App\Models\Rating;  
use Modules\Genres\Models\Genre;
use Modules\Actors\Models\Actor;
use App\Models\User; // <<< Add this line to import the User model


class Movie extends Model
{
    use HasFactory;

    /**
     * Tên của khóa chính trong bảng.
     * Chỉ định rõ ràng vì nó không phải là 'id' mặc định.
     *
     * @var string
     */
    protected $primaryKey = 'movie_id';

    /**
     * Định nghĩa mối quan hệ One-to-Many: Một Movie có nhiều Rating.
     * Tên phương thức 'ratings' (số nhiều) là quy ước cho hasMany.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'movie_id');
    }

    /**
     * Lấy rating của người dùng đang đăng nhập cho movie/tv show này.
     * (Giữ lại phương thức này nếu bạn đã thêm nó ở bước trước)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userRating(): HasOne
    {
        return $this->hasOne(Rating::class, 'movie_id')
                    ->where('user_id', Auth::id());
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'movie_genre', 'movie_id', 'genre_id');
    }

    public function actors(): BelongsToMany
    {
        return $this->belongsToMany(Actor::class, 'movie_actor', 'movie_id', 'actor_id');
    }
    
    public function watchers(): BelongsToMany // <<< Thêm phương thức này
    {
        return $this->belongsToMany(User::class, 'watchlist', 'movie_id', 'user_id')
                    ->withTimestamps();
    }
}