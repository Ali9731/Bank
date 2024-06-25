<?php

namespace App\Services\Sms\Strategies;

use App\Models\User;
use App\Services\Sms\SmsServiceInterface;
use Illuminate\Support\Facades\Log;
use Kavenegar\KavenegarApi;

class KavehNegar implements SmsServiceInterface
{
    public function __construct() {}

    public function send(User $user, string $message): bool
    {
        //Todo : add circuit breaker
        //Todo : add queue for messages
        //Todo : add a fallback plan to send with other providers when this service is unavailable
        try {
            (new KavenegarApi(env('KAVENEGAR_KEY')))->Send(
                env('KAVENEGAR_LINE_NUMBER'),
                $user->phone,
                $message
            );
        } catch (\Kavenegar\Exceptions\ApiException $e) {
            Log::error('send message api exception : '.$user->phone, [
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
            // report failure in circuit
        } catch (\Kavenegar\Exceptions\HttpException $e) {
            Log::error('send message http exception : '.$user->phone, [
                'status' => $e->getCode(),
                'message' => $e->getMessage(),
            ]);

            return false;
            // report failure in circuit
        }

        return true;
    }
}
