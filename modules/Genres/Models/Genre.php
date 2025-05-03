<?php
namespace Modules\Genres\Models;

// KHÔNG phải là App\Models hay bất cứ namespace nào khác.
// Phải có class Genre được định nghĩa bên dưới:
use Illuminate\Database\Eloquent\Model; // Ví dụ use Model

class Genre extends Model
{
    // Nội dung class Genre của bạn
    protected $primaryKey = 'genres_id'; // Ví dụ
    // ...
}