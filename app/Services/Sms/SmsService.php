<?php

namespace App\Services\Sms;

use App\Models\User;

class SmsService
{
    private SmsServiceInterface $strategy;

    public function __construct(SmsServiceInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    //Todo: implement setStrategy method if you need to add service to Service Container

    public function send(User $user, string $message)
    {

        return $this->strategy->send($user, $message);

    }
}
