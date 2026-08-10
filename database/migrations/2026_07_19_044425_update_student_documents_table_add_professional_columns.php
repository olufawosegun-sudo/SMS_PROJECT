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
        Schema::table('student_documents', function (Blueprint $table) {
            // Add school_id if it doesn't exist
            if (! Schema::hasColumn('student_documents', 'school_id')) {
                $table->foreignId('school_id')->after('id')->constrained('schools')->cascadeOnDelete();
            }

            // Add document_type if it doesn't exist
            if (! Schema::hasColumn('student_documents', 'document_type')) {
                $table->string('document_type')->after('student_id');
            }

            // Rename 'file' to 'file_path' if needed
            if (Schema::hasColumn('student_documents', 'file') && ! Schema::hasColumn('student_documents', 'file_path')) {
                $table->renameColumn('file', 'file_path');
            }

            // Add file_size if it doesn't exist
            if (! Schema::hasColumn('student_documents', 'file_size')) {
                $table->string('file_size')->nullable()->after('file_path');
            }

            // Add mime_type if it doesn't exist
            if (! Schema::hasColumn('student_documents', 'mime_type')) {
                $table->string('mime_type')->nullable()->after('file_size');
            }

            // Add uploaded_at if it doesn't exist
            if (! Schema::hasColumn('student_documents', 'uploaded_at')) {
                $table->timestamp('uploaded_at')->useCurrent()->after('uploaded_by');
            }

            // Add status if it doesn't exist
            if (! Schema::hasColumn('student_documents', 'status')) {
                $table->enum('status', ['active', 'archived', 'deleted'])->default('active')->after('uploaded_at');
            }

            // Add notes if it doesn't exist
            if (! Schema::hasColumn('student_documents', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }

            // Add expiry_date if it doesn't exist
            if (! Schema::hasColumn('student_documents', 'expiry_date')) {
                $table->date('expiry_date')->nullable()->after('notes');
            }
        });

        // Add indexes using portable index existence checks
        Schema::table('student_documents', function (Blueprint $table) {
            $existingIndexes = collect(Schema::getIndexes('student_documents'))->pluck('name');

            if (! $existingIndexes->contains('student_documents_school_id_student_id_index')) {
                $table->index(['school_id', 'student_id']);
            }

            if (! $existingIndexes->contains('student_documents_document_type_index')) {
                $table->index('document_type');
            }

            if (! $existingIndexes->contains('student_documents_status_index')) {
                $table->index('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_documents', function (Blueprint $table) {
            // Remove added columns
            $columns = ['school_id', 'document_type', 'file_size', 'mime_type', 'uploaded_at', 'status', 'notes', 'expiry_date'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('student_documents', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
