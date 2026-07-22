<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('report_cards', function (Blueprint $table) {
            // Change status enum to include 'approved'
            $table->enum('status', ['draft', 'approved', 'published'])->default('draft')->change();
            
            // Add columns for tracking approval workflow
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            $table->timestamp('published_at')->nullable()->after('approved_by');
            $table->unsignedBigInteger('published_by')->nullable()->after('published_at');
            
            // Foreign keys
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('published_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('report_cards', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['published_by']);
            $table->dropColumn(['approved_at', 'approved_by', 'published_at', 'published_by']);
            $table->enum('status', ['draft', 'published'])->default('draft')->change();
        });
    }
};
