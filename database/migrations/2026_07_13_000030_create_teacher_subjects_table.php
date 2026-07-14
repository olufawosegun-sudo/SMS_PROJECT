<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('teacher_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('session_id')->nullable()->constrained('academic_sessions')->onDelete('set null');
            $table->foreignId('term_id')->nullable()->constrained('academic_terms')->onDelete('set null');
            $table->timestamps();

            // Prevent duplicate assignments per school
            $table->unique(['school_id', 'teacher_id', 'class_id', 'subject_id', 'session_id', 'term_id'], 'teacher_subject_school_unique');
        });
    }

    public function down(): void {
        Schema::dropIfExists('teacher_subjects');
    }
};
