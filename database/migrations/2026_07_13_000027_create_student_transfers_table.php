<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('student_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('transfer_type'); // Incoming, Outgoing
            $table->string('school_name')->nullable();
            $table->text('reason')->nullable();
            $table->date('transfer_date')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('student_transfers');
    }
};