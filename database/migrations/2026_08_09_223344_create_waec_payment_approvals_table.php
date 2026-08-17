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
        if (! Schema::hasTable('waec_payment_approvals')) {
            Schema::create('waec_payment_approvals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('payment_id')->constrained('waec_payments')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Who performed the action
                $table->string('action'); // submitted, approved, rejected, cancelled
                $table->string('previous_status')->nullable();
                $table->string('new_status');
                $table->text('comment')->nullable();
                $table->text('reason')->nullable(); // For rejections
                $table->string('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamps();

                // Indexes
                $table->index(['payment_id', 'created_at']);
                $table->index('user_id');
                $table->index('action');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waec_payment_approvals');
    }
};
