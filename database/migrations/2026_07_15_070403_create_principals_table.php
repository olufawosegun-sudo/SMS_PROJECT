<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('principals', function (Blueprint $table) {
            $table->id();
            
            // Multi-tenant support
            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();
            
            // Link to User account
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            
            // Department (optional - some principals oversee specific departments)
            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();
            
            // Staff Information
            $table->string('staff_no')->unique(); // e.g., PRI00001
            $table->string('qualification')->nullable(); // e.g., PhD in Education, M.Ed
            $table->string('specialization')->nullable(); // e.g., Mathematics, Science, Administration
            $table->date('employment_date')->nullable();
            $table->integer('years_of_experience')->nullable(); // Total years of experience
            $table->string('previous_school')->nullable(); // Previous school worked at
            
            // Office & Contact
            $table->string('office_location')->nullable(); // e.g., "Admin Block, Room 101"
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            
            // Employment Details
            $table->enum('contract_type', ['permanent', 'contract', 'temporary'])
                ->default('permanent');
            $table->string('appointment_letter')->nullable(); // File path to appointment letter
            $table->decimal('salary', 10, 2)->nullable();
            $table->date('contract_start_date')->nullable();
            $table->date('contract_end_date')->nullable();
            
            // Principal Type
            $table->enum('principal_type', ['Principal', 'Vice Principal', 'Assistant Principal'])
                ->default('Principal');
            
            // Status
            $table->enum('status', ['active', 'inactive', 'on_leave', 'suspended', 'retired'])
                ->default('active');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['school_id', 'status']);
            $table->index('staff_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('principals');
    }
};
