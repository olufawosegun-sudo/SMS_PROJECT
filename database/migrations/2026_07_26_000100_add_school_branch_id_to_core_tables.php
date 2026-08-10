<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Target tables to receive the nullable school_branch_id foreign key.
     */
    protected array $tables = [
        // User & People Management
        'users',
        'staffs',
        'students',
        'admission_applications',

        // Academic Structure & Facilities
        'classes',
        'class_arms',
        'school_rooms',
        'departments',

        // Facilities, Logistics & Inventory
        'hostels',
        'transport_routes',
        'transport_vehicles',
        'library_books',
        'inventory_items',
        'visitors',

        // Finance & Communication
        'invoices',
        'payments',
        'expenses',
        'fee_structures',
        'events',
        'announcements',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && ! Schema::hasColumn($tableName, 'school_branch_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // Check if school_id exists so we can place school_branch_id right after it
                    if (Schema::hasColumn($tableName, 'school_id')) {
                        $table->foreignId('school_branch_id')
                            ->nullable()
                            ->after('school_id')
                            ->constrained('school_branches')
                            ->onDelete('set null');
                    } else {
                        $table->foreignId('school_branch_id')
                            ->nullable()
                            ->constrained('school_branches')
                            ->onDelete('set null');
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
        foreach ($this->tables as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'school_branch_id')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropForeign(['school_branch_id']);
                    $table->dropColumn('school_branch_id');
                });
            }
        }
    }
};
