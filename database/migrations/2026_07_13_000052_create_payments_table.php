<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('payment_reference')->unique();
            $table->string('payment_method'); // Cash, Bank Transfer, POS, Card, Online Payment
            $table->string('gateway')->nullable(); // Paystack, Flutterwave, etc.
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('NGN');
            $table->unsignedBigInteger('received_by')->nullable();
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
