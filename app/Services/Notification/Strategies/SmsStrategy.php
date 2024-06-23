<?php

namespace App\Services\Notification\Strategies;

use App\Models\User;
use App\Services\Notification\NotificationInterface;
use App\Services\Notification\Providers\NotificationProviderInterface;
use Illuminate\Support\Facades\Log;

class SmsStrategy implements NotificationInterface
{
    public function send(User $user, string $message): bool
    {
        //config/services.php->notificationService
        $result = false;
        foreach (Config::get('services.notificationService.smsProviders') as $provider) {
            if (! $provider instanceof NotificationProviderInterface) {
                Log::error('Sms provider is not instance of  NotificationProviderInterface: '.$provider);

                continue;
            }
            $provider = new $provider();
            $result = $provider->send($user->phone, $message);
            if ($result) {
                break;
            }
        }
        //check if all providers are called but non of them respond
        if ($result) {
            return true;
        }

        //Todo: report failure in circuit
        return false;
    }
}
