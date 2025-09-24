<?php

namespace Modules\Review\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Modules\Movies\Models\Movie;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'movie_id',
        'title',
        'content',
        'rating_given',
        'has_spoiler',
        'status',
        'admin_edited',
        'admin_edited_by',
        'admin_edit_reason',
        'admin_edit_timestamp',
    ];

    protected $casts = [
        'has_spoiler' => 'boolean',
        'admin_edited' => 'boolean',
        'admin_edit_timestamp' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }

    public function adminEditor()
    {
        return $this->belongsTo(User::class, 'admin_edited_by');
    }
}