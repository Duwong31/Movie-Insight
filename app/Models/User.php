<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // Thêm nếu dùng email verification
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Giữ lại nếu có thể dùng API sau này

class User extends Authenticatable // implements MustVerifyEmail (nếu dùng)
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Thêm các trường bạn cho phép điền khi tạo user mới (Register)
        'fullname', // Hoặc 'name' nếu bạn dùng cột 'name'
        'email',
        'password',
        'phone', // Nếu cho đăng ký cả phone
        // Không thêm 'status', 'isAdmin' ở đây trừ khi bạn muốn set lúc đăng ký
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

    // Nếu bạn cần định nghĩa relationship với Rating, Watchlist,... thì thêm ở đây
    // ví dụ:
    // public function ratings() {
    //     return $this->hasMany(Rating::class);
    // }
    // public function watchlistItems() {
    //     return $this->hasMany(WatchlistItem::class); // Giả sử có model WatchlistItem
    // }
}