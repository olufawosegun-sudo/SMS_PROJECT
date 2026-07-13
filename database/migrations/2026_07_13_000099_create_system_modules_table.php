<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('system_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('module_name'); // Library, Hostel, Transport, CBT, Payroll, Inventory, Parent Portal
            $table->boolean('is_enabled')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['school_id', 'module_name']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('system_modules');
    }
};