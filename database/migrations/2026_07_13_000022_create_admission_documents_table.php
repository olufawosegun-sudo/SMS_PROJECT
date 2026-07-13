<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('admission_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('admission_applications')->onDelete('cascade');
            $table->string('document_name');
            $table->string('file');
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('admission_documents');
    }
};