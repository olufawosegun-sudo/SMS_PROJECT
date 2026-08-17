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
        Schema::table('schools', function (Blueprint $table) {
            if (! Schema::hasColumn('schools', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('currency_symbol');
            }
            if (! Schema::hasColumn('schools', 'account_number')) {
                $table->string('account_number')->nullable()->after('bank_name');
            }
            if (! Schema::hasColumn('schools', 'account_name')) {
                $table->string('account_name')->nullable()->after('account_number');
            }
            if (! Schema::hasColumn('schools', 'bank_branch')) {
                $table->string('bank_branch')->nullable()->after('account_name');
            }
            if (! Schema::hasColumn('schools', 'bank_sort_code')) {
                $table->string('bank_sort_code')->nullable()->after('bank_branch');
            }
            if (! Schema::hasColumn('schools', 'momo_number')) {
                $table->string('momo_number')->nullable()->after('bank_sort_code');
            }
            if (! Schema::hasColumn('schools', 'momo_network')) {
                $table->string('momo_network')->nullable()->after('momo_number');
            }
            if (! Schema::hasColumn('schools', 'payment_instructions')) {
                $table->text('payment_instructions')->nullable()->after('momo_network');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $cols = array_filter([
                'bank_name',
                'account_number',
                'account_name',
                'bank_branch',
                'bank_sort_code',
                'momo_number',
                'momo_network',
                'payment_instructions',
            ], fn ($col) => Schema::hasColumn('schools', $col));

            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
