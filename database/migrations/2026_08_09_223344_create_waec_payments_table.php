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
        Schema::create('waec_payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained('waec_candidates')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('guardian_id')->nullable()->constrained('guardians')->onDelete('set null');

            // Payment details
            $table->string('payment_reference')->unique();
            $table->string('payment_method'); // bank_transfer, cash, card, online, etc.
            $table->string('gateway')->nullable(); // paystack, flutterwave, etc.
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('NGN');
            $table->date('payment_date');

            // Payment proof
            $table->string('proof_document')->nullable(); // upload path
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->text('payment_notes')->nullable();

            // Status workflow: pending → submitted → approved/rejected
            $table->enum('status', ['pending', 'submitted', 'under_review', 'approved', 'rejected', 'cancelled'])->default('pending');

            // Submission tracking
            $table->foreignId('submitted_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('submitted_at')->nullable();

            // Approval tracking
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();

            // Rejection tracking
            $table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // Receipt
            $table->string('receipt_number')->nullable()->unique();
            $table->timestamp('receipt_generated_at')->nullable();

            // Audit
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['school_id', 'status']);
            $table->index(['candidate_id', 'status']);
            $table->index(['student_id']);
            $table->index('payment_reference');
            $table->index('receipt_number');
            $table->index(['approved_by', 'approved_at']);
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waec_payments');
    }
};
