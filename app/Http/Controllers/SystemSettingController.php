<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemSettingController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;
        $countries = School::getCountryCurrencyMap();
        $selectedCountry = $school->country ?: 'Nigeria';
        $currencyInfo = School::getCurrencyForCountry($selectedCountry);

        // Fetch settings from settings table if available
        $backupFreqSetting = Setting::where('school_id', $school->id)->where('key', 'backup_frequency')->first();
        $maintenanceModeSetting = Setting::where('school_id', $school->id)->where('key', 'maintenance_mode')->first();

        // Collect environment/system info
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'timezone' => config('app.timezone'),
            'locale' => config('app.locale'),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'mail_driver' => config('mail.default'),
            'db_connection' => config('database.default'),
        ];

        return view('system-settings.index', compact(
            'school',
            'countries',
            'selectedCountry',
            'currencyInfo',
            'systemInfo',
            'backupFreqSetting',
            'maintenanceModeSetting'
        ));
    }

    public function update(Request $request)
    {
        $school = Auth::user()->school;

        $validated = $request->validate([
            'country' => ['required', 'string', 'max:100'],
            'currency' => ['nullable', 'string', 'max:10'],
            'currency_symbol' => ['nullable', 'string', 'max:10'],
            'backup_freq' => ['nullable', 'string', 'in:daily,weekly,monthly'],
            'maintenance_mode' => ['nullable', 'boolean'],
        ]);

        // If currency and currency_symbol are empty or match the country's currency, derive them
        $countryDefaults = School::getCurrencyForCountry($validated['country']);
        $currency = ! empty($validated['currency']) ? strtoupper(trim($validated['currency'])) : $countryDefaults['code'];
        $currencySymbol = ! empty($validated['currency_symbol']) ? trim($validated['currency_symbol']) : $countryDefaults['symbol'];

        // Update school record
        $school->update([
            'country' => $validated['country'],
            'currency' => $currency,
            'currency_symbol' => $currencySymbol,
        ]);

        // Update settings table
        if (isset($validated['backup_freq'])) {
            Setting::updateOrCreate(
                ['school_id' => $school->id, 'key' => 'backup_frequency'],
                ['value' => $validated['backup_freq']]
            );
        }

        Setting::updateOrCreate(
            ['school_id' => $school->id, 'key' => 'maintenance_mode'],
            ['value' => $request->has('maintenance_mode') ? '1' : '0']
        );

        return redirect()->back()->with('success', 'System localization & currency settings updated successfully!');
    }
}
