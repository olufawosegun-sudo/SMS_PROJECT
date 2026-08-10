<?php

use App\Models\ReportCard;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/debug/reports', function () {
    $reportCards = ReportCard::with(['student.user', 'school'])->get();

    echo '<h1>Report Cards Debug</h1>';
    echo '<p>Total Report Cards: '.$reportCards->count().'</p>';

    foreach ($reportCards as $rc) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
        echo "<strong>ID:</strong> {$rc->id}<br>";
        echo "<strong>Student:</strong> {$rc->student->user->first_name} {$rc->student->user->last_name}<br>";
        echo "<strong>Status:</strong> {$rc->status}<br>";
        echo "<strong>School ID:</strong> {$rc->school_id}<br>";
        echo "<strong>School Name:</strong> {$rc->school->name}<br>";
        echo "<strong>Class:</strong> {$rc->schoolClass->name}<br>";
        echo '</div>';
    }

    echo '<hr>';
    echo '<h2>Users with Principal Role</h2>';
    $principals = User::whereHas('role', function ($q) {
        $q->where('name', 'Principal');
    })->get();

    foreach ($principals as $p) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
        echo "<strong>Name:</strong> {$p->first_name} {$p->last_name}<br>";
        echo "<strong>Email:</strong> {$p->email}<br>";
        echo "<strong>School ID:</strong> {$p->school_id}<br>";
        echo '<strong>School Name:</strong> '.($p->school ? $p->school->name : 'N/A').'<br>';
        echo '</div>';
    }
});
