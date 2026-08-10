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
            // Add total_score column (in addition to existing 'total')
            $table->decimal('total_score', 5, 2)->nullable()->after('exam_score');

            // Add grade column (string for A1, B2, C3, etc.)
            $table->string('grade', 2)->nullable()->after('total_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropColumn(['total_score', 'grade']);
        });
    }
};
