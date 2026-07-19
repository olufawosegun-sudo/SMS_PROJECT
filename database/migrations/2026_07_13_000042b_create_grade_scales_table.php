<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('grade_scales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grading_system_id')->constrained('grading_systems')->onDelete('cascade');
            $table->decimal('min_score', 5, 2);
            $table->decimal('max_score', 5, 2);
            $table->string('grade');
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('grade_scales');
    }
};