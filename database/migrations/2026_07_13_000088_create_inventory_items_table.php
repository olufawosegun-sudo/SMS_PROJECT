<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('inventory_categories')->onDelete('cascade');
            $table->string('item_name');
            $table->string('serial_number')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2)->nullable();
            $table->string('condition')->nullable(); // Good, Damaged, Needs Repair
            $table->string('location')->nullable();
            $table->string('status')->default('available');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('inventory_items');
    }
};