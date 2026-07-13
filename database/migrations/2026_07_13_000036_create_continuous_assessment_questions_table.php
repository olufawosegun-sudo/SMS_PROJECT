<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('continuous_assessment_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('continuous_assessments')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->text('question');
            $table->string('question_type'); // Multiple Choice, True/False, Short Answer, Essay
            $table->decimal('marks', 5, 2);
            $table->string('difficulty')->nullable(); // Easy, Medium, Hard
            $table->string('image')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void {
        Schema::dropIfExists('continuous_assessment_questions');
    }
};