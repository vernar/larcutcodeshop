<?php

namespace App\Listeners;

use App\Notifications\NewUserNotification;
use Illuminate\Auth\Events\Registered;

class SendEmailUserListener
{
    public function handle(Registered $event)
    {
        $event->user->notify(new NewUserNotification());
    }
}
