<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Change teacher_subjects.teacher_id to staff_id
     * to work with the new unified staff table.
     */
    public function up(): void
    {
        Schema::table('teacher_subjects', function (Blueprint $table) {
            // Add new staff_id column first (nullable temporarily)
            $table->foreignId('staff_id')
                ->nullable()
                ->after('school_id')
                ->constrained('staff')
                ->cascadeOnDelete();
        });
        
        // Migrate data: Copy teacher's user_id to find corresponding staff_id
        DB::statement('
            UPDATE teacher_subjects ts
            INNER JOIN teachers t ON ts.teacher_id = t.id
            INNER JOIN staff s ON t.user_id = s.user_id
            SET ts.staff_id = s.id
        ');
        
        Schema::table('teacher_subjects', function (Blueprint $table) {
            // Now make staff_id required
            $table->foreignId('staff_id')->nullable(false)->change();
            
            // Drop the old foreign key and column
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_subjects', function (Blueprint $table) {
            // Drop the new foreign key and column
            $table->dropForeign(['staff_id']);
            $table->dropColumn('staff_id');
            
            // Restore the old teacher_id column
            $table->foreignId('teacher_id')
                ->after('school_id')
                ->constrained('teachers')
                ->cascadeOnDelete();
        });
    }
};
