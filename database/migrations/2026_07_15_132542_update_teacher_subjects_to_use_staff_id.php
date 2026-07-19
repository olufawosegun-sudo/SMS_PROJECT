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
                ->constrained('staffs')
                ->cascadeOnDelete();
        });
        
        // Migrate data: Copy teacher's user_id to find corresponding staff_id
        DB::statement('
            UPDATE teacher_subjects ts
            INNER JOIN teachers t ON ts.teacher_id = t.id
            INNER JOIN staffs s ON t.user_id = s.user_id
            SET ts.staff_id = s.id
        ');
        
        Schema::table('teacher_subjects', function (Blueprint $table) {
            // Now make staff_id required
            $table->foreignId('staff_id')->nullable(false)->change();
            
            // Drop all foreign keys first to release unique index
            $table->dropForeign(['school_id']);
            $table->dropForeign(['teacher_id']);
            $table->dropForeign(['class_id']);
            $table->dropForeign(['subject_id']);
            $table->dropForeign(['session_id']);
            $table->dropForeign(['term_id']);
            
            // Drop the old unique key
            $table->dropUnique('teacher_subject_school_unique');
            
            // Drop the old column
            $table->dropColumn('teacher_id');

            // Create the new unique key using staff_id
            $table->unique(['school_id', 'staff_id', 'class_id', 'subject_id', 'session_id', 'term_id'], 'teacher_subject_school_unique');

            // Recreate other foreign keys
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('academic_sessions')->onDelete('set null');
            $table->foreign('term_id')->references('id')->on('academic_terms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teacher_subjects', function (Blueprint $table) {
            // Drop the new unique key first
            $table->dropUnique('teacher_subject_school_unique');

            // Drop foreign keys
            $table->dropForeign(['school_id']);
            $table->dropForeign(['staff_id']);
            $table->dropForeign(['class_id']);
            $table->dropForeign(['subject_id']);
            $table->dropForeign(['session_id']);
            $table->dropForeign(['term_id']);
            
            // Drop staff_id column
            $table->dropColumn('staff_id');
        });

        if (!Schema::hasColumn('teacher_subjects', 'teacher_id')) {
            Schema::table('teacher_subjects', function (Blueprint $table) {
                // Restore teacher_id column
                $table->foreignId('teacher_id')
                    ->after('school_id')
                    ->constrained('teachers')
                    ->cascadeOnDelete();
            });
        }

        Schema::table('teacher_subjects', function (Blueprint $table) {
            // Restore the old unique key
            $table->unique(['school_id', 'teacher_id', 'class_id', 'subject_id', 'session_id', 'term_id'], 'teacher_subject_school_unique');

            // Recreate other foreign keys
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('session_id')->references('id')->on('academic_sessions')->onDelete('set null');
            $table->foreign('term_id')->references('id')->on('academic_terms')->onDelete('set null');
        });
    }
};
