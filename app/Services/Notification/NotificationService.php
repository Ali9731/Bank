<?php

namespace App\Services\Notification;

use App\Models\User;

class NotificationService
{
    private NotificationInterface $strategy;

    public function __construct(NotificationInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    //Todo: implement setStrategy method if you need to add service to Service Container

    public function send(User $user, string $message)
    {

        return $this->strategy->send($user, $message);

    }
}
