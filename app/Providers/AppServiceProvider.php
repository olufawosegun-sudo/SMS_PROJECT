<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

// Models
use App\Models\Student;
use App\Models\Teacher;

// Policies
use App\Policies\StudentPolicy;

// Observers
use App\Observers\StudentObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Application-wide service registrations can go here
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Model Observers
        Student::observe(StudentObserver::class);

        // Register Policies
        Gate::policy(Student::class, StudentPolicy::class);
        
        // You can add more observers and policies here as needed
        // Example:
        // Teacher::observe(TeacherObserver::class);
        // Gate::policy(Teacher::class, TeacherPolicy::class);
    }
}
