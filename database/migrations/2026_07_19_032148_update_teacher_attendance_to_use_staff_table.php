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
        // Check if staffs table exists (note: plural form)
        if (!Schema::hasTable('staffs')) {
            return;
        }

        // Check if the column needs to be renamed
        $hasTeacherId = Schema::hasColumn('teacher_attendance', 'teacher_id');
        $hasStaffId = Schema::hasColumn('teacher_attendance', 'staff_id');
        
        if ($hasTeacherId && !$hasStaffId) {
            // Clear any orphaned records
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('teacher_attendance')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            Schema::table('teacher_attendance', function (Blueprint $table) {
                // Drop the old foreign key constraint if it exists
                try {
                    $table->dropForeign(['teacher_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, that's ok
                }
                
                // Rename the column from teacher_id to staff_id
                $table->renameColumn('teacher_id', 'staff_id');
            });

            Schema::table('teacher_attendance', function (Blueprint $table) {
                // Add the new foreign key constraint pointing to staffs table (note: plural)
                $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('cascade');
            });
        } elseif ($hasStaffId) {
            // Column already renamed, just ensure foreign key exists
            try {
                Schema::table('teacher_attendance', function (Blueprint $table) {
                    $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                // Foreign key might already exist
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('teachers')) {
            return;
        }

        $hasStaffId = Schema::hasColumn('teacher_attendance', 'staff_id');
        
        if ($hasStaffId) {
            Schema::table('teacher_attendance', function (Blueprint $table) {
                // Drop the new foreign key constraint
                try {
                    $table->dropForeign(['staff_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist
                }
                
                // Rename back from staff_id to teacher_id
                $table->renameColumn('staff_id', 'teacher_id');
            });

            Schema::table('teacher_attendance', function (Blueprint $table) {
                // Restore the old foreign key constraint
                $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            });
        }
    }
};
