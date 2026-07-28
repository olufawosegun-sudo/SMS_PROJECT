<?php

require 'c:/xampp/htdocs/SMS_Project/vendor/autoload.php';

$app = require_once 'c:/xampp/htdocs/SMS_Project/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

$superAdmin = User::updateOrCreate(
    ['email' => 'superadmin@sms.com'],
    [
        'uuid' => (string) Str::uuid(),
        'first_name' => 'West Africa',
        'last_name' => 'Super Admin',
        'phone' => '+2348000000000',
        'gender' => 'Male',
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
        'status' => 'active',
        'is_super_admin' => true,
    ]
);

echo "Super Admin Account Created / Updated Successfully!\n";
echo "Email: superadmin@sms.com\n";
echo "Password: password\n";
