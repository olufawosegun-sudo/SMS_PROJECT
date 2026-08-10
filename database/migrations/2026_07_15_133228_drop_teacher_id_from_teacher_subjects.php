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
        if (Schema::hasColumn('teacher_subjects', 'teacher_id')) {
            Schema::table('teacher_subjects', function (Blueprint $table) {
                // Just drop the column (foreign key already removed)
                $table->dropColumn('teacher_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('teacher_subjects', 'teacher_id')) {
            Schema::table('teacher_subjects', function (Blueprint $table) {
                // Restore teacher_id column
                $table->foreignId('teacher_id')
                    ->after('staff_id')
                    ->constrained('teachers')
                    ->cascadeOnDelete();
            });
        }
    }
};
