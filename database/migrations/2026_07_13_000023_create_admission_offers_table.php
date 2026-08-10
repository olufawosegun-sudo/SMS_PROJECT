<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('admission_applications')->onDelete('cascade');
            $table->foreignId('offered_class_id')->constrained('classes')->onDelete('cascade');
            $table->unsignedBigInteger('offered_by')->nullable();
            $table->string('status')->default('pending'); // pending, accepted, declined
            $table->date('offer_date')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admission_offers');
    }
};
