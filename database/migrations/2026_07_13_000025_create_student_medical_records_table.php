<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('student_medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('blood_group')->nullable();
            $table->string('genotype')->nullable();
            $table->text('allergies')->nullable();
            $table->text('medical_condition')->nullable();
            $table->string('doctor_name')->nullable();
            $table->string('hospital')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('student_medical_records');
    }
};