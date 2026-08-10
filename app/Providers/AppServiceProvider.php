<?php

namespace App\Providers;

use App\Models\Student;
use App\Models\Teacher;
// Models
use App\Observers\StudentObserver;
use App\Policies\StudentPolicy;
// Policies
use Illuminate\Support\Facades\Gate;
// Observers
use Illuminate\Support\ServiceProvider;

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
