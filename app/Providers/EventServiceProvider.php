<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UserRegistered' => [
            'App\Listeners\UserRegisterdListener',
            'App\Listeners\WhatsAppNotificationListener@handleUserRegistered',
        ],
        'App\Events\CompanyRegistered' => [
            'App\Listeners\CompanyRegisterdListener',
            'App\Listeners\WhatsAppNotificationListener@handleCompanyRegistered',
        ],
        'App\Events\JobPosted' => [
            'App\Listeners\JobPostedListener',
            'App\Listeners\WhatsAppNotificationListener@handleJobPosted',
        ],
        'App\Events\JobApplied' => [
            'App\Listeners\JobAppliedJobSeekerListener',
            'App\Listeners\JobAppliedCompanyListener',
            'App\Listeners\WhatsAppNotificationListener@handleJobApplied',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //
    }

}
