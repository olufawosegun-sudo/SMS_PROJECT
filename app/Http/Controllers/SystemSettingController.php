<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemSettingController extends Controller
{
    public function index()
    {
        $school = Auth::user()->school;

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

        return view('system-settings.index', compact('school', 'systemInfo'));
    }

    public function update(Request $request)
    {
        // Placeholder: system settings would update .env or a settings table
        return redirect()->back()->with('success', 'System settings updated!');
    }
}
