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
        // Modify level enum to string in classes table and add category & order_index
        Schema::table('classes', function (Blueprint $table) {
            if (Schema::hasColumn('classes', 'level')) {
                $table->string('level', 100)->nullable()->change();
            } else {
                $table->string('level', 100)->nullable();
            }

            if (! Schema::hasColumn('classes', 'category')) {
                $table->string('category', 50)->nullable()->after('level')->comment('e.g. early_childhood, primary, junior_secondary, senior_secondary, vocational, other');
            }

            if (! Schema::hasColumn('classes', 'order_index')) {
                $table->integer('order_index')->default(0)->after('category');
            }
        });

        // Add educational system configuration columns to schools table
        Schema::table('schools', function (Blueprint $table) {
            if (! Schema::hasColumn('schools', 'educational_system')) {
                $table->string('educational_system', 50)->default('nigerian_waec')->after('motto');
            }

            if (! Schema::hasColumn('schools', 'active_stages')) {
                $table->json('active_stages')->nullable()->after('educational_system');
            }

            if (! Schema::hasColumn('schools', 'term_system')) {
                $table->string('term_system', 30)->default('3_terms')->after('active_stages');
            }

            if (! Schema::hasColumn('schools', 'pass_mark')) {
                $table->integer('pass_mark')->default(50)->after('term_system');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            if (Schema::hasColumn('classes', 'category')) {
                $table->dropColumn('category');
            }
            if (Schema::hasColumn('classes', 'order_index')) {
                $table->dropColumn('order_index');
            }
        });

        Schema::table('schools', function (Blueprint $table) {
            $cols = array_filter(['educational_system', 'active_stages', 'term_system', 'pass_mark'], fn ($col) => Schema::hasColumn('schools', $col));
            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
