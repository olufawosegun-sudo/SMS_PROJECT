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
        Schema::create('waec_remittances', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('session_id')->constrained('academic_sessions')->onDelete('cascade');

            $table->string('batch_reference')->unique();
            $table->string('waec_transaction_reference')->nullable();
            $table->integer('total_candidates_count')->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('payment_method')->default('bank_transfer'); // bank_transfer, waec_portal, card, draft
            $table->string('bank_name')->nullable();
            $table->date('payment_date');

            $table->string('proof_document')->nullable();
            $table->enum('status', ['pending', 'completed', 'verified'])->default('completed');
            $table->text('notes')->nullable();

            $table->foreignId('remitted_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('remitted_at')->useCurrent();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_id', 'session_id']);
            $table->index('batch_reference');
            $table->index('payment_date');
        });

        // Add waec_remittance_id to waec_candidates
        Schema::table('waec_candidates', function (Blueprint $table) {
            $table->foreignId('waec_remittance_id')->nullable()->after('cancellation_reason')->constrained('waec_remittances')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waec_candidates', function (Blueprint $table) {
            $table->dropForeign(['waec_remittance_id']);
            $table->dropColumn('waec_remittance_id');
        });

        Schema::dropIfExists('waec_remittances');
    }
};
