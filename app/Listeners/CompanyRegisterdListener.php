<?php

namespace App\Listeners;

use Mail;
use App\Events\CompanyRegistered;
use App\Mail\CompanyRegisteredMailable;
// use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Contracts\Queue\ShouldQueue;

class CompanyRegisterdListener
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CompanyRegistered  $event
     * @return void
     */
    public function handle(CompanyRegistered $event)
    {
        // Mail::send(new CompanyRegisteredMailable($event->company)); // Disabled for local dev
    }

}
