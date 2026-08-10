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
        Schema::create('waec_candidates', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('session_id')->constrained('academic_sessions')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('arm_id')->nullable()->constrained('class_arms')->onDelete('set null');

            // Fee assignment
            $table->decimal('examination_fee', 15, 2)->default(0);
            $table->decimal('registration_fee', 15, 2)->default(0);
            $table->decimal('other_charges', 15, 2)->default(0);
            $table->decimal('total_fee', 15, 2)->default(0);

            // Payment tracking
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->decimal('balance', 15, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');

            // Candidate information
            $table->string('candidate_number')->nullable(); // Assigned after full payment
            $table->enum('status', ['registered', 'payment_pending', 'payment_complete', 'exam_ready', 'cancelled'])->default('registered');
            $table->date('registration_date');
            $table->text('notes')->nullable();

            // Audit fields
            $table->foreignId('registered_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('registered_at')->useCurrent();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['school_id', 'session_id']);
            $table->index(['student_id', 'session_id']);
            $table->index('payment_status');
            $table->index('status');
            $table->index('candidate_number');

            // Prevent duplicate registration per student per session
            $table->unique(['student_id', 'session_id'], 'unique_student_session');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waec_candidates');
    }
};
