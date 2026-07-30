<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Models
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Payment;
use App\Models\Attendance;
use App\Models\Result;

// Repositories
use App\Repositories\StudentRepository;
use App\Repositories\TeacherRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\AttendanceRepository;
use App\Repositories\ResultRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind Repository Interface to Base Repository
        $this->app->bind(
            \App\Repositories\Contracts\RepositoryInterface::class,
            \App\Repositories\BaseRepository::class
        );

        // Register Student Repository
        $this->app->singleton(StudentRepository::class, function ($app) {
            return new StudentRepository(new Student());
        });

        // Register Teacher Repository
        $this->app->singleton(TeacherRepository::class, function ($app) {
            return new TeacherRepository(new Teacher());
        });

        // Register Payment Repository
        $this->app->singleton(PaymentRepository::class, function ($app) {
            return new PaymentRepository(new Payment());
        });

        // Register Attendance Repository
        $this->app->singleton(AttendanceRepository::class, function ($app) {
            return new AttendanceRepository(new Attendance());
        });

        // Register Result Repository
        $this->app->singleton(ResultRepository::class, function ($app) {
            return new ResultRepository(new Result());
        });

        // Add more repository bindings as you create them
        // Example:
        // $this->app->singleton(GuardianRepository::class, function ($app) {
        //     return new GuardianRepository(new Guardian());
        // });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
