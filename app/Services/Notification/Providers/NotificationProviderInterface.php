<?php

namespace App\Services\Notification\Providers;

interface NotificationProviderInterface
{
    public function send(string $phone, string $text): bool;
}
