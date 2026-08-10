<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('arm_id')->nullable()->constrained('class_arms')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('session_id')->constrained('academic_sessions')->onDelete('cascade');
            $table->foreignId('term_id')->constrained('academic_terms')->onDelete('cascade');
            $table->foreignId('classroom_room_id')->nullable()->constrained('school_rooms')->onDelete('set null');
            $table->enum('day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']); // Saturday for some schools
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

            // Prevent duplicate timetable entries
            $table->unique(['school_id', 'arm_id', 'day', 'start_time', 'session_id', 'term_id'], 'unique_timetable_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetables');
    }
};
