<?php

namespace App\Services\Notification;

use App\Models\User;

interface NotificationInterface
{
    public function send(User $user, string $message): bool;
}
