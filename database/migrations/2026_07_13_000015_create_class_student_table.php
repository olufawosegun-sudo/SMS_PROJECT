<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('arm_id')->nullable()->constrained('class_arms')->onDelete('set null');
            $table->foreignId('session_id')->nullable()->constrained('academic_sessions')->onDelete('set null');
            $table->foreignId('term_id')->nullable()->constrained('academic_terms')->onDelete('set null');
            $table->date('assigned_date')->nullable();
            $table->boolean('is_current')->default(true);
            $table->timestamps();

            // Ensure a student can only be in one class per session/term
            $table->unique(['school_id', 'student_id', 'class_id', 'session_id', 'term_id'], 'unique_class_student_assignment');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_student');
    }
};
