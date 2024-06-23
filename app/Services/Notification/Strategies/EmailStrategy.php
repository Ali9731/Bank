<?php

namespace App\Services\Notification\Strategies;

use App\Mail\MyCustomEmail;
use App\Models\User;
use App\Services\Notification\NotificationInterface;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Mail;

class EmailStrategy implements NotificationInterface
{
    public function __construct() {}

    public function send(User $user, string $message): bool
    {
        //laravel mail uses failover to send with another provider if primary provider not respond
        //configs in config/mail.php
        //Todo: check the circuit here
        $result = Mail::to($user)->send((new MyCustomEmail())->setText($message));

        if ($result instanceof SentMessage) {
            return true;
        }

        //Todo: report failure in circuit
        return false;
    }
}
