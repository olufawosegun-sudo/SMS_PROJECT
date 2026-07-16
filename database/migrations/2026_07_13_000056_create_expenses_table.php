<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('expense_category_id')->constrained('expense_categories')->onDelete('cascade');
            $table->string('title');
            $table->string('currency', 3)->default('NGN');
            $table->decimal('amount', 15, 2);
            $table->string('paid_to')->nullable();
            $table->date('expense_date');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('expenses');
    }
};