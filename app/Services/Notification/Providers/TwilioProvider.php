<?php

namespace App\Services\Notification\Providers;

use Illuminate\Support\Facades\Log;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class TwilioProvider implements NotificationProviderInterface
{
    public function send(string $phone, string $text): bool
    {
        //Todo: check circuit
        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
        try {
            $result = $twilio->messages
                ->create($phone,
                    [
                        'body' => $text,
                    ]
                );
        } catch (TwilioException $exception) {
            //Todo: report failure to circuit
            Log::error('Sms provider failed : '.self::class, [
                'status' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ]);

            return false;
        }

        //Todo: report success to circuit
        return true;
    }
}
