<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('library_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('book_category_id')->constrained('book_categories')->onDelete('cascade');
            $table->string('isbn')->nullable();
            $table->string('accession_number')->unique();
            $table->string('title');
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->integer('publication_year')->nullable();
            $table->string('edition')->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('available')->default(1);
            $table->string('shelf_location')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('library_books');
    }
};