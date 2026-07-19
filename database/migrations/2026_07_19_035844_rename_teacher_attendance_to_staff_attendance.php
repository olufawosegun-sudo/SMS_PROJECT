<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Rename teacher_attendance table to staff_attendance and add professional tracking columns
     */
    public function up(): void
    {
        // Check if teacher_attendance table exists
        if (Schema::hasTable('teacher_attendance')) {
            // Rename the table
            Schema::rename('teacher_attendance', 'staff_attendance');
        }

        // Add professional enhancement columns to staff_attendance
        if (Schema::hasTable('staff_attendance')) {
            Schema::table('staff_attendance', function (Blueprint $table) {
                // Add late tracking (nullable - only for late arrivals)
                if (!Schema::hasColumn('staff_attendance', 'late_minutes')) {
                    $table->integer('late_minutes')->nullable()->after('status')
                        ->comment('Number of minutes late (null if not late)');
                }

                // Add early departure tracking (nullable - only for early departures)
                if (!Schema::hasColumn('staff_attendance', 'early_departure_minutes')) {
                    $table->integer('early_departure_minutes')->nullable()->after('late_minutes')
                        ->comment('Number of minutes departed early (null if stayed full time)');
                }

                // Add recorded_by column to track who recorded the attendance
                if (!Schema::hasColumn('staff_attendance', 'recorded_by')) {
                    $table->foreignId('recorded_by')->nullable()->after('remark')
                        ->constrained('users')->nullOnDelete()
                        ->comment('User ID of who recorded this attendance');
                }

                // Add approval tracking (nullable - only for special cases like leave, absences)
                if (!Schema::hasColumn('staff_attendance', 'approved_by')) {
                    $table->foreignId('approved_by')->nullable()->after('recorded_by')
                        ->constrained('users')->nullOnDelete()
                        ->comment('User ID of who approved this attendance record (for leave/special cases)');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the added columns first
        if (Schema::hasTable('staff_attendance')) {
            Schema::table('staff_attendance', function (Blueprint $table) {
                if (Schema::hasColumn('staff_attendance', 'approved_by')) {
                    $table->dropForeign(['approved_by']);
                    $table->dropColumn('approved_by');
                }
                if (Schema::hasColumn('staff_attendance', 'recorded_by')) {
                    $table->dropForeign(['recorded_by']);
                    $table->dropColumn('recorded_by');
                }
                if (Schema::hasColumn('staff_attendance', 'early_departure_minutes')) {
                    $table->dropColumn('early_departure_minutes');
                }
                if (Schema::hasColumn('staff_attendance', 'late_minutes')) {
                    $table->dropColumn('late_minutes');
                }
            });

            // Rename the table back
            Schema::rename('staff_attendance', 'teacher_attendance');
        }
    }
};
