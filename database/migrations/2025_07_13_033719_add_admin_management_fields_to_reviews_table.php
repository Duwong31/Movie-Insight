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
        Schema::table('reviews', function (Blueprint $table) {
            $table->boolean('admin_edited')->default(false)->after('status');
            $table->unsignedBigInteger('admin_edited_by')->nullable()->after('admin_edited');
            $table->text('admin_edit_reason')->nullable()->after('admin_edited_by');
            $table->timestamp('admin_edit_timestamp')->nullable()->after('admin_edit_reason');
            
            $table->foreign('admin_edited_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['admin_edited_by']);
            $table->dropColumn(['admin_edited', 'admin_edited_by', 'admin_edit_reason', 'admin_edit_timestamp']);
        });
    }
};
