<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            // Add new columns for enhanced functionality (only if they don't exist)
            if (!Schema::hasColumn('news', 'excerpt')) {
                $table->text('excerpt')->nullable()->after('content');
            }
            if (!Schema::hasColumn('news', 'meta_description')) {
                $table->string('meta_description', 160)->nullable()->after('excerpt');
            }
            if (!Schema::hasColumn('news', 'status')) {
                $table->enum('status', ['draft', 'published'])->default('draft')->after('source');
            }
            if (!Schema::hasColumn('news', 'featured')) {
                $table->boolean('featured')->default(false)->after('status');
            }
            if (!Schema::hasColumn('news', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('featured');
            }
            if (!Schema::hasColumn('news', 'views')) {
                $table->integer('views')->default(0)->after('published_at');
            }
        });

        // Add indexes if they don't exist
        try {
            Schema::table('news', function (Blueprint $table) {
                $table->index('status');
                $table->index('featured');
                $table->index('published_at');
                $table->index('views');
            });
        } catch (Exception $e) {
            // Indexes might already exist, continue
        }

        // Add unique constraint for slug if it doesn't exist
        try {
            Schema::table('news', function (Blueprint $table) {
                $table->unique('slug');
            });
        } catch (Exception $e) {
            // Unique constraint might already exist, continue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['featured']);
            $table->dropIndex(['published_at']);
            $table->dropIndex(['views']);
            
            $table->dropColumn([
                'excerpt', 'meta_description', 'status', 
                'featured', 'published_at', 'views', 'updated_at'
            ]);
        });
    }
};
