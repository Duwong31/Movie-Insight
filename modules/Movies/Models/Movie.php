<?php

namespace Modules\Movies\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // <<< Thêm dòng này
use Illuminate\Database\Eloquent\Relations\HasOne;  // <<< Giữ lại nếu đã có userRating
use Illuminate\Support\Facades\Auth;             // <<< Giữ lại nếu đã có userRating
use App\Models\Rating;  

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
        // Giả định:
        // - Model Rating là App\Models\Rating
        // - Bảng ratings có cột khóa ngoại là 'movie_id' trỏ đến 'id' của bảng movies.
        // Nếu khóa ngoại của bạn tên khác, hãy truyền nó làm tham số thứ hai.
        // Ví dụ: return $this->hasMany(Rating::class, 'foreign_key_khac');
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
        // Chỉ trả về rating của user hiện tại
        return $this->hasOne(Rating::class, 'movie_id')
                    ->where('user_id', Auth::id());
    }

    // ... các phương thức khác nếu có ...
}