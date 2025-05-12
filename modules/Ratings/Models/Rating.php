<?php

namespace Modules\Ratings\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Movies\Models\Movie;
use App\Models\User; // Adjust the namespace to the correct location of the User class

class Rating extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'rating_id';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'movie_id',
        'rating',
    ];

    /**
     * Lấy thông tin user đã tạo rating này.
     */
    public function user(): BelongsTo
    {
        // Foreign key là 'user_id', khóa chính của User thường là 'id'
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Lấy thông tin movie được rating.
     */
    public function movie(): BelongsTo
    {
        // Foreign key là 'movie_id', khóa chính của Movie là 'movie_id'
        return $this->belongsTo(Movie::class, 'movie_id', 'movie_id');
    }
}
