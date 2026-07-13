<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('classroom_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('name'); // Room A1, Science Lab, ICT Lab, etc.
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->integer('capacity')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('classroom_rooms');
    }
};