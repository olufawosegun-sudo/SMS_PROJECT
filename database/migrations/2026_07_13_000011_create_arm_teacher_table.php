<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('arm_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('arm_id')->constrained('class_arms')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('session_id')->nullable()->constrained('academic_sessions')->onDelete('set null');
            $table->foreignId('term_id')->nullable()->constrained('academic_terms')->onDelete('set null');
            $table->boolean('is_form_teacher')->default(false); // Main class teacher
            $table->date('assigned_date')->nullable();
            $table->boolean('is_current')->default(true);
            $table->timestamps();
            
            // Ensure a teacher can only be assigned to a class arm once per session/term
            $table->unique(['school_id', 'arm_id', 'teacher_id', 'session_id', 'term_id'], 'unique_arm_teacher_assignment');
        });
    }
    public function down(): void {
        Schema::dropIfExists('arm_teacher');
    }
};
