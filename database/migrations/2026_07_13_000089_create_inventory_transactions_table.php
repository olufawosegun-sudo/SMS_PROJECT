<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->string('issued_to')->nullable();
            $table->string('issued_by')->nullable();
            $table->integer('quantity');
            $table->string('transaction_type'); // Issued, Returned, Damaged, Lost
            $table->date('transaction_date');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('inventory_transactions');
    }
};