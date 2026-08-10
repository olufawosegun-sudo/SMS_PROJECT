<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('full_name');
            $table->string('relationship'); // Mother, Father, Uncle, Aunt, Guardian, etc.
            $table->string('primary_phone');
            $table->string('secondary_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('address_line_3')->nullable();
            $table->string('occupation')->nullable();
            $table->string('workplace')->nullable();
            $table->boolean('is_primary')->default(false); // Primary emergency contact
            $table->integer('priority')->default(1); // 1 = first to call, 2 = second, etc.
            $table->timestamps();

            // Index for faster queries
            $table->index(['school_id', 'student_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_emergency_contacts');
    }
};
