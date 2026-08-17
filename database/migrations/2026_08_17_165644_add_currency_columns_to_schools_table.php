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
            if (! Schema::hasColumn('schools', 'currency')) {
                $table->string('currency', 10)->nullable()->after('country');
            }
            if (! Schema::hasColumn('schools', 'currency_symbol')) {
                $table->string('currency_symbol', 10)->nullable()->after('currency');
            }
            if (! Schema::hasColumn('schools', 'admission_status')) {
                $table->string('admission_status', 20)->default('open')->after('motto');
            }
            if (! Schema::hasColumn('schools', 'primary_color')) {
                $table->string('primary_color', 20)->nullable()->after('admission_status');
            }
            if (! Schema::hasColumn('schools', 'secondary_color')) {
                $table->string('secondary_color', 20)->nullable()->after('primary_color');
            }
            if (! Schema::hasColumn('schools', 'portal_theme')) {
                $table->string('portal_theme', 50)->nullable()->after('secondary_color');
            }
            if (! Schema::hasColumn('schools', 'hero_banner')) {
                $table->string('hero_banner')->nullable()->after('portal_theme');
            }
            if (! Schema::hasColumn('schools', 'welcome_title')) {
                $table->string('welcome_title')->nullable()->after('hero_banner');
            }
            if (! Schema::hasColumn('schools', 'welcome_message')) {
                $table->text('welcome_message')->nullable()->after('welcome_title');
            }
            if (! Schema::hasColumn('schools', 'about_text')) {
                $table->text('about_text')->nullable()->after('welcome_message');
            }
            if (! Schema::hasColumn('schools', 'portal_hero_title')) {
                $table->string('portal_hero_title')->nullable()->after('about_text');
            }
            if (! Schema::hasColumn('schools', 'portal_hero_subtitle')) {
                $table->text('portal_hero_subtitle')->nullable()->after('portal_hero_title');
            }
            if (! Schema::hasColumn('schools', 'social_facebook')) {
                $table->string('social_facebook')->nullable()->after('portal_hero_subtitle');
            }
            if (! Schema::hasColumn('schools', 'social_instagram')) {
                $table->string('social_instagram')->nullable()->after('social_facebook');
            }
            if (! Schema::hasColumn('schools', 'social_twitter')) {
                $table->string('social_twitter')->nullable()->after('social_instagram');
            }
            if (! Schema::hasColumn('schools', 'social_linkedin')) {
                $table->string('social_linkedin')->nullable()->after('social_twitter');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $columns = [
                'currency', 'currency_symbol', 'admission_status', 'primary_color', 'secondary_color',
                'portal_theme', 'hero_banner', 'welcome_title', 'welcome_message', 'about_text',
                'portal_hero_title', 'portal_hero_subtitle', 'social_facebook', 'social_instagram',
                'social_twitter', 'social_linkedin',
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('schools', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
