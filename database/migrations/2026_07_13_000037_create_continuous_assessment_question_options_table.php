<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('continuous_assessment_question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('continuous_assessment_questions')->onDelete('cascade');
            $table->string('option_label'); // A, B, C, D
            $table->text('option_text');
            $table->string('image')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('continuous_assessment_question_options');
    }
};