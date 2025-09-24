<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First add the slug column if it doesn't exist
        if (!Schema::hasColumn('news', 'slug')) {
            Schema::table('news', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('title');
            });
        }

        // Populate existing news with slugs
        $news = DB::table('news')->whereNull('slug')->orWhere('slug', '')->get();
        
        foreach ($news as $article) {
            $baseSlug = Str::slug($article->title);
            $slug = $baseSlug;
            $counter = 1;
            
            // Ensure unique slug
            while (DB::table('news')->where('slug', $slug)->where('id', '!=', $article->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            DB::table('news')->where('id', $article->id)->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We don't want to remove slugs in rollback
        // Schema::table('news', function (Blueprint $table) {
        //     $table->dropColumn('slug');
        // });
    }
};
