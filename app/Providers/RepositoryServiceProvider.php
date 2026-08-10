<?php

namespace App\Providers;

use App\Models\Attendance;
// Models
use App\Models\Payment;
use App\Models\Result;
use App\Models\Student;
use App\Models\Teacher;
use App\Repositories\AttendanceRepository;
// Repositories
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\PaymentRepository;
use App\Repositories\ResultRepository;
use App\Repositories\StudentRepository;
use App\Repositories\TeacherRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind Repository Interface to Base Repository
        $this->app->bind(
            RepositoryInterface::class,
            BaseRepository::class
        );

        // Register Student Repository
        $this->app->singleton(StudentRepository::class, function ($app) {
            return new StudentRepository(new Student);
        });

        // Register Teacher Repository
        $this->app->singleton(TeacherRepository::class, function ($app) {
            return new TeacherRepository(new Teacher);
        });

        // Register Payment Repository
        $this->app->singleton(PaymentRepository::class, function ($app) {
            return new PaymentRepository(new Payment);
        });

        // Register Attendance Repository
        $this->app->singleton(AttendanceRepository::class, function ($app) {
            return new AttendanceRepository(new Attendance);
        });

        // Register Result Repository
        $this->app->singleton(ResultRepository::class, function ($app) {
            return new ResultRepository(new Result);
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
