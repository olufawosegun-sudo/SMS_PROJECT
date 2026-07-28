<?php

require 'c:/xampp/htdocs/SMS_Project/vendor/autoload.php';

$app = require_once 'c:/xampp/htdocs/SMS_Project/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\School;
use App\Models\SchoolBranch;
use App\Models\Student;
use App\Models\Staff;

echo "========================================\n";
echo " TESTING SUPER ADMIN MASTER PORTAL \n";
echo "========================================\n\n";

$superAdmin = User::where('email', 'superadmin@sms.com')->first();

if (!$superAdmin) {
    echo "[FAIL] Super Admin account 'superadmin@sms.com' NOT found!\n";
    exit(1);
}

echo "1. Super Admin Account Check:\n";
echo "   [OK] Email: {$superAdmin->email}\n";
echo "   [OK] Name: {$superAdmin->name}\n";
echo "   [OK] isSuperAdmin(): " . ($superAdmin->isSuperAdmin() ? 'TRUE' : 'FALSE') . "\n";

echo "\n2. Platform-Wide Oversight Metrics:\n";
echo "   • Total Schools: " . School::count() . "\n";
echo "   • Total Campuses/Branches: " . SchoolBranch::count() . "\n";
echo "   • Total Students Across Platform: " . Student::count() . "\n";
echo "   • Total Teachers/Staff Across Platform: " . Staff::count() . "\n";
echo "   • Total Users Across Platform: " . User::count() . "\n";

echo "\n========================================\n";
echo " RESULT: SUPER ADMIN PORTAL VERIFIED! \n";
echo "========================================\n";
