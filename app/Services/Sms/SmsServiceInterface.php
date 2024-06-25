<?php

namespace App\Services\Sms;

use App\Models\User;

interface SmsServiceInterface
{
    public function send(User $user, string $message): bool;
}
