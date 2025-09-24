<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\News\Models\News;
use Modules\News\Admin\NewsController;

class TestNewsAdmin extends Command
{
    protected $signature = 'test:news-admin';
    protected $description = 'Test admin news functionality';

    public function handle()
    {
        $this->info('Testing News Admin functionality...');
        
        // Test model
        $newsCount = News::count();
        $this->info("Total news articles: {$newsCount}");
        
        $publishedCount = News::where('status', 'published')->count();
        $this->info("Published articles: {$publishedCount}");
        
        $draftCount = News::where('status', 'draft')->count();
        $this->info("Draft articles: {$draftCount}");
        
        // Test slug generation
        $slug = News::generateUniqueSlug('Test Article Title');
        $this->info("Sample slug: {$slug}");
        
        // Test first news article
        $firstNews = News::first();
        if ($firstNews) {
            $this->info("First article: {$firstNews->title}");
            $this->info("Slug: {$firstNews->slug}");
            $this->info("Status: {$firstNews->status}");
        }
        
        $this->info('✅ News Admin functionality test completed!');
        
        return 0;
    }
}
