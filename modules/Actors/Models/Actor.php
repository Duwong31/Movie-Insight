<?php

namespace Modules\Actors\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Movies\Models\Movie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;
class Actor extends Model
{
    use HasFactory;

    protected $table = 'actors'; // Tên bảng diễn viên của bạn
    protected $primaryKey = 'actors_id'; // Khóa chính của bảng diễn viên

    protected $fillable = [
        'actor_name', 
    ];

    public $timestamps = false;

    public function movies(): BelongsToMany
    {
        return $this->belongsToMany(Movie::class, 'movie_actor', 'actor_id', 'movie_id');
    }

    /**
     * Find an actor by name or create a new one if not found.
     * Names are trimmed and compared case-insensitively.
     *
     * @param string $name
     * @return Actor
     */
    public static function findOrCreateByName(string $name): ?Actor
    {
        $trimmedName = trim($name);
        if (empty($trimmedName)) {
            return null;
        }

        // Tìm kiếm không phân biệt hoa thường
        $actor = self::whereRaw('LOWER(actor_name) = ?', [strtolower($trimmedName)])->first();

        if (!$actor) {
            try {
                $actor = self::create([
                    'actor_name' => $trimmedName,
                    // 'actors_image' => null, // Nếu cột này nullable
                ]);
                if (!$actor->getKey()) { // Kiểm tra lại ngay sau khi tạo
                    Log::error("Actor created for '{$trimmedName}' but has no ID.");
                    return null;
                }
            } catch (\Exception $e) {
                // Ghi log lỗi nếu tạo actor thất bại
                Log::error("Failed to create actor: {$trimmedName}. Error: " . $e->getMessage());
                return null; // Hoặc ném lại exception
            }
        }
        return $actor;
    }
}
