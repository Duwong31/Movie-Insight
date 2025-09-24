<?php

namespace Modules\News\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model 
{
    use HasFactory;
    
    protected $table = 'news'; 
    
    protected $fillable = [
        'title', 'slug', 'content', 'excerpt', 'meta_description', 
        'image_url', 'source', 'status', 'featured', 'published_at', 'views'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'views' => 'integer',
    ];

    // Automatically generate slug when creating news
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = static::generateUniqueSlug($news->title);
            }
        });

        static::updating(function ($news) {
            if ($news->isDirty('title') && empty($news->slug)) {
                $news->slug = static::generateUniqueSlug($news->title);
            }
        });
    }

    // Generate unique slug
    public static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Accessors
    public function getIsPublishedAttribute()
    {
        return $this->status === 'published' && $this->published_at <= now();
    }

    public function getExcerptOrContentAttribute()
    {
        return $this->excerpt ?: Str::limit(strip_tags($this->content), 150);
    }

    // Methods
    public function incrementViews()
    {
        $this->increment('views');
    }

    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => $this->published_at ?: now(),
        ]);
    }

    public function unpublish()
    {
        $this->update(['status' => 'draft']);
    }

    public function toggleFeatured()
    {
        $this->update(['featured' => !$this->featured]);
    }
}