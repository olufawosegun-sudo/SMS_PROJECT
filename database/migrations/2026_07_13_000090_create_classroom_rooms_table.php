<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('school_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('set null');
            $table->foreignId('arm_id')->nullable()->constrained('class_arms')->onDelete('set null');
            $table->string('room_number'); // Room A1, Room 101, etc.
            $table->string('room_name')->nullable(); // Science Lab, ICT Lab, Library, etc.
            $table->enum('room_type', ['classroom', 'laboratory', 'ict_lab', 'library', 'staff_room', 'office', 'hall', 'other'])->default('classroom');
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->integer('capacity')->nullable();
            $table->text('equipment')->nullable(); // Projector, Whiteboard, Computers, etc.
            $table->enum('status', ['active', 'inactive', 'maintenance', 'reserved'])->default('active');
            $table->timestamps();
            
            // Index for faster queries
            $table->index(['school_id', 'room_type', 'status']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('school_rooms');
    }
};