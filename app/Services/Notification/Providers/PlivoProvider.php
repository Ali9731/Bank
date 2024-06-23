<?php

namespace App\Services\Notification\Providers;

use GuzzleHttp\Exception\ClientException;
use http\Client;
use Illuminate\Support\Facades\Log;

class PlivoProvider implements NotificationProviderInterface
{
    public function send(string $phone, string $text): bool
    {
        //Todo: check circuit
        try {
            $client = new Client();
            //send http request
        } catch (ClientException $exception) {
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
