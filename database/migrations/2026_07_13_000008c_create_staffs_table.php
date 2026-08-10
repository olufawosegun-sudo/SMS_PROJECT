<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This table stores ALL staff members:
     * - Principal
     * - Vice Principal
     * - Teachers
     * - Accountants
     * - Librarians
     * - Hostel Masters
     * - Drivers
     * - Nurses
     * - Security
     * - etc.
     */
    public function up(): void
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();

            // Multi-tenant support
            $table->foreignId('school_id')
                ->constrained('schools')
                ->cascadeOnDelete();

            // Link to User account (for authentication)
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Department (optional - some staff belong to departments)
            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();

            // Staff Identification
            $table->string('staff_no')->unique(); // e.g., STF00001, TCH00001, PRI00001
            $table->string('staff_type'); // Principal, Teacher, Accountant, Librarian, etc.

            // Educational Background
            $table->string('qualification')->nullable(); // e.g., PhD, M.Ed, B.Sc
            $table->string('specialization')->nullable(); // e.g., Mathematics, Finance

            // Employment Details
            $table->date('employment_date')->nullable();
            $table->date('confirmation_date')->nullable(); // When probation ended
            $table->integer('years_of_experience')->nullable();
            $table->string('previous_employer')->nullable();

            // Contract Information
            $table->enum('employment_type', ['full-time', 'part-time', 'contract', 'temporary'])
                ->default('full-time');
            $table->enum('contract_type', ['permanent', 'contract', 'probation'])
                ->default('permanent');
            $table->date('contract_start_date')->nullable();
            $table->date('contract_end_date')->nullable();

            // Financial
            $table->decimal('salary', 10, 2)->nullable();
            $table->enum('payment_frequency', ['monthly', 'bi-weekly', 'weekly'])
                ->default('monthly');
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();

            // Work Details
            $table->string('office_location')->nullable(); // e.g., "Admin Block, Room 101"
            $table->string('job_description')->nullable();

            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();

            // Documents
            $table->string('appointment_letter')->nullable(); // File path
            $table->string('resume_cv')->nullable(); // File path
            $table->string('certificates')->nullable(); // JSON array of file paths

            // Status
            $table->enum('status', ['active', 'inactive', 'on_leave', 'suspended', 'resigned', 'retired', 'terminated'])
                ->default('active');
            $table->date('resignation_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->text('exit_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for better query performance
            $table->index(['school_id', 'status']);
            $table->index(['school_id', 'staff_type']);
            $table->index('staff_no');
            $table->index('employment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staffs');
    }
};
