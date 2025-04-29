<?php

namespace Modules\News\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model 
{
    use HasFactory;
    protected $table = 'news'; 
    protected $fillable = ['title','content', 'image_url', 'source'];
}