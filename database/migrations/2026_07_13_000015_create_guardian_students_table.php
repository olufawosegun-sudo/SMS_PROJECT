<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('guardian_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guardian_id')->constrained('guardians')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('relationship')->nullable();
            $table->boolean('is_primary')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('guardian_students');
    }
};