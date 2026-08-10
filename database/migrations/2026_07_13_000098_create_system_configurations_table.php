<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('config_key');
            $table->text('config_value')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['school_id', 'config_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_configurations');
    }
};
