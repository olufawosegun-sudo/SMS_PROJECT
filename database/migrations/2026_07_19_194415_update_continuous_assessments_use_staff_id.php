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
        Schema::table('continuous_assessments', function (Blueprint $table) {
            // Drop old foreign key constraint
            $table->dropForeign(['teacher_id']);

            // Rename column to staff_id
            $table->renameColumn('teacher_id', 'staff_id');
        });

        Schema::table('continuous_assessments', function (Blueprint $table) {
            // Add new foreign key pointing to staffs table
            $table->foreign('staff_id')->references('id')->on('staffs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('continuous_assessments', function (Blueprint $table) {
            // Drop staff foreign key
            $table->dropForeign(['staff_id']);

            // Rename back to teacher_id
            $table->renameColumn('staff_id', 'teacher_id');
        });

        Schema::table('continuous_assessments', function (Blueprint $table) {
            // Add back old foreign key (only if teachers table exists)
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
        });
    }
};
