<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('actors', function (Blueprint $table) {
            $table->date('birth_date')->nullable()->after('actors_image');
            $table->string('nationality', 100)->nullable()->after('birth_date');
            $table->text('biography')->nullable()->after('nationality');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actors', function (Blueprint $table) {
            $table->dropColumn(['birth_date', 'nationality', 'biography', 'created_at', 'updated_at']);
        });
    }
};
