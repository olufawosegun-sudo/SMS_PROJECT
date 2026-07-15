<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration drops the old teachers and principals tables since
     * they have been replaced by the unified staff table.
     */
    public function up(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();
        
        // Drop teachers table
        Schema::dropIfExists('teachers');
        
        // Drop principals table
        Schema::dropIfExists('principals');
        
        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     * 
     * Note: This migration is destructive. Rolling back will recreate empty tables
     * but will NOT restore the old data. The data has been migrated to the staff table.
     */
    public function down(): void
    {
        // Recreate teachers table (empty - for rollback only)
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->string('staff_no')->nullable();
            $table->string('qualification')->nullable();
            $table->string('specialization')->nullable();
            $table->date('employment_date')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('office_location')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->enum('contract_type', ['permanent', 'contract', 'temporary'])->default('permanent');
            $table->decimal('salary', 12, 2)->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended', 'resigned'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        // Recreate principals table (empty - for rollback only)
        Schema::create('principals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained('departments')->onDelete('set null');
            $table->string('staff_no')->nullable();
            $table->string('qualification')->nullable();
            $table->string('specialization')->nullable();
            $table->date('employment_date')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('office_location')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->enum('contract_type', ['permanent', 'contract', 'temporary'])->default('permanent');
            $table->decimal('salary', 12, 2)->nullable();
            $table->enum('principal_type', ['Principal', 'Vice Principal', 'Assistant Principal'])->default('Principal');
            $table->enum('status', ['active', 'inactive', 'suspended', 'resigned'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
