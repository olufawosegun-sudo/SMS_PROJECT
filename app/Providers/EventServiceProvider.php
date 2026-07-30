<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

// Events
use App\Events\StudentRegistered;
use App\Events\StudentPromoted;
use App\Events\StudentStatusChanged;

// Listeners
use App\Listeners\SendStudentWelcomeEmail;
use App\Listeners\LogStudentPromotion;
use App\Listeners\NotifyGuardiansOfStatusChange;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Student Events
        StudentRegistered::class => [
            SendStudentWelcomeEmail::class,
        ],

        StudentPromoted::class => [
            LogStudentPromotion::class,
        ],

        StudentStatusChanged::class => [
            NotifyGuardiansOfStatusChange::class,
        ],

        // You can add more event-listener mappings here
        // Example:
        // TeacherRegistered::class => [
        //     SendTeacherWelcomeEmail::class,
        // ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
