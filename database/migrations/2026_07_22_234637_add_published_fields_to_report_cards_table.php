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
        Schema::table('report_cards', function (Blueprint $table) {
            if (!Schema::hasColumn('report_cards', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('approved_by');
            }
            if (!Schema::hasColumn('report_cards', 'published_by')) {
                $table->foreignId('published_by')->nullable()->after('published_at')
                    ->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_cards', function (Blueprint $table) {
            if (Schema::hasColumn('report_cards', 'published_by')) {
                $table->dropForeign(['published_by']);
                $table->dropColumn('published_by');
            }
            if (Schema::hasColumn('report_cards', 'published_at')) {
                $table->dropColumn('published_at');
            }
        });
    }
};
