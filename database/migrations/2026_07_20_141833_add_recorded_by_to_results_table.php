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
        Schema::table('results', function (Blueprint $table) {
            $table->unsignedBigInteger('recorded_by')->nullable()->after('published_at');
            $table->timestamp('recorded_at')->nullable()->after('recorded_by');
            
            // Foreign key to users table (teacher who recorded the result)
            $table->foreign('recorded_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropForeign(['recorded_by']);
            $table->dropColumn(['recorded_by', 'recorded_at']);
        });
    }
};
