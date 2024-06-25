<?php

namespace App\Services\Sms\Strategies;

use App\Models\User;
use App\Services\Sms\SmsServiceInterface;
use Ghasedak\Exceptions\ApiException;
use Ghasedak\Exceptions\HttpException;
use Ghasedak\GhasedakApi;
use Illuminate\Support\Facades\Log;

class Ghasedak implements SmsServiceInterface
{
    public function send(User $user, string $message): bool
    {
        //Todo : add circuit breaker
        //Todo : add queue for messages
        //Todo : add a fallback plan to send with other providers when this service is unavailable
        try {
            $api = new GhasedakApi(env('GHASEDAKAPI_KEY'));
            $api->SendSimple(
                $user->phone,
                $message,
                env('GHASEDAKAPI_LINE_NUMBER')
            );
        } catch (ApiException $e) {
            Log::error('send message api exception : '.$user->phone, [
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
        } catch (HttpException $e) {
            Log::error('send message http exception : '.$user->phone, [
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
            //Todo: report failure in circuit
        }

        return false;
    }
}
