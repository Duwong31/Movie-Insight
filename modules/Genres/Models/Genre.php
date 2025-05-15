<?php
namespace Modules\Genres\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// KHÔNG phải là App\Models hay bất cứ namespace nào khác.
use Modules\Movies\Models\Movie;
use Illuminate\Database\Eloquent\Model; // Ví dụ use Model

class Genre extends Model
{
    use HasFactory;

    protected $table = 'genres'; // Tên bảng
    protected $primaryKey = 'genres_id'; // Khóa chính

    protected $fillable = [
        'genres_name',
        // Các trường khác nếu có
    ];

    // Không cần $timestamps = false; nếu bảng genres có cột created_at, updated_at

    /**
     * The movies that belong to the genre.
     */
    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_genre', 'genre_id', 'movie_id')
                    ->withTimestamps();
    }
}