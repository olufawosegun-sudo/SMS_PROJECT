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
        Schema::table('cbt_exams', function (Blueprint $table) {
            $table->foreignId('assessment_id')->nullable()->after('subject_id')
                ->constrained('continuous_assessments')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cbt_exams', function (Blueprint $table) {
            $table->dropForeign(['cbt_exams_assessment_id_foreign']);
            $table->dropColumn('assessment_id');
        });
    }
};
