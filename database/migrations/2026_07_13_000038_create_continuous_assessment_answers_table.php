<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('continuous_assessment_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('continuous_assessment_questions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->unsignedBigInteger('selected_option_id')->nullable(); // Can link to options later
            $table->text('answer_text')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->boolean('is_correct')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('continuous_assessment_answers');
    }
};