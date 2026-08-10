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
        $tables = [
            'schools',
            'school_branches',
            'guardians',
            'admission_applications',
            'transport_drivers',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (! Schema::hasColumn($tableName, 'address_line_1')) {
                        $table->string('address_line_1')->nullable()->after('address');
                    }
                    if (! Schema::hasColumn($tableName, 'address_line_2')) {
                        $table->string('address_line_2')->nullable()->after('address_line_1');
                    }
                    if (! Schema::hasColumn($tableName, 'address_line_3')) {
                        $table->string('address_line_3')->nullable()->after('address_line_2');
                    }
                    if (! Schema::hasColumn($tableName, 'city')) {
                        $table->string('city')->nullable()->after('address_line_3');
                    }
                    if (! Schema::hasColumn($tableName, 'state')) {
                        $table->string('state')->nullable()->after('city');
                    }
                    if (! Schema::hasColumn($tableName, 'country')) {
                        $table->string('country')->nullable()->after('state');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'schools',
            'school_branches',
            'guardians',
            'admission_applications',
            'transport_drivers',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $colsToDrop = [];
                    foreach (['address_line_1', 'address_line_2', 'address_line_3'] as $col) {
                        if (Schema::hasColumn($tableName, $col)) {
                            $colsToDrop[] = $col;
                        }
                    }
                    if (! empty($colsToDrop)) {
                        $table->dropColumn($colsToDrop);
                    }
                });
            }
        }
    }
};
