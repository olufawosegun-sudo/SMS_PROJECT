<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cbt_exams', function (Blueprint $table) {
            // Change status to enum with approval workflow statuses
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'needs_revision', 'rejected', 'scheduled', 'active', 'completed'])->default('draft')->change();
            
            // Add approval workflow columns
            $table->unsignedBigInteger('created_by')->nullable()->after('status');
            $table->timestamp('submitted_at')->nullable()->after('created_by');
            $table->unsignedBigInteger('submitted_by')->nullable()->after('submitted_at');
            
            $table->timestamp('approved_at')->nullable()->after('submitted_by');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            
            $table->timestamp('rejected_at')->nullable()->after('approved_by');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at');
            $table->text('rejection_reason')->nullable()->after('rejected_by');
            
            $table->text('principal_comment')->nullable()->after('rejection_reason');
            $table->timestamp('returned_at')->nullable()->after('principal_comment');
            
            // Foreign keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('submitted_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('cbt_exams', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['submitted_by']);
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['rejected_by']);
            
            $table->dropColumn([
                'created_by', 'submitted_at', 'submitted_by',
                'approved_at', 'approved_by',
                'rejected_at', 'rejected_by', 'rejection_reason',
                'principal_comment', 'returned_at'
            ]);
            
            $table->enum('status', ['draft', 'scheduled', 'active', 'completed'])->default('draft')->change();
        });
    }
};
