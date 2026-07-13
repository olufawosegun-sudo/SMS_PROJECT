<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('result_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('result_id')->constrained('results')->onDelete('cascade');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->timestamp('approved_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('result_approvals');
    }
};