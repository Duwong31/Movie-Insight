<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // Thêm nếu dùng email verification
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Movies\Models\Movie;
use Modules\Ratings\Models\Rating;


class User extends Authenticatable // implements MustVerifyEmail (nếu dùng)
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname', 
        'email',
        'password',
        'phone', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'isAdmin' => 'boolean', // Cast isAdmin thành true/false
    ];

    public function ratings(): HasMany
    {
        // Giả sử bạn có Model Rating và bảng ratings có user_id
        return $this->hasMany(Rating::class);
    }

    public function watchlistMovies(): BelongsToMany // <<< Thêm phương thức này
    {
        return $this->belongsToMany(Movie::class, 'watchlist', 'user_id', 'movie_id')
                    ->withTimestamps(); // Thêm cái này nếu bảng watchlist có cột created_at, updated_at
    }
}