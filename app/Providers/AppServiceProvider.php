<?php

namespace App\Providers;

use App\Repositories\Card\CardRepository;
use App\Repositories\Card\CardRepositoryInterface;
use App\Repositories\Transaction\TransactionRepository;
use App\Repositories\Transaction\TransactionRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Sms\SmsService;
use App\Services\Sms\SmsServiceInterface;
use App\Services\Sms\Strategies\KavehNegar;
use App\Services\Transaction\TransactionService;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Kavenegar', \Kavenegar\Laravel\Facade::class);
        //repositories
        $this->app->bind(SmsServiceInterface::class, KavehNegar::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
        $this->app->bind(CardRepositoryInterface::class, CardRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        //services
        $this->app->singleton(SmsService::class, function ($app) {
            return new SmsService($app->make(SmsServiceInterface::class));
        });
        $this->app->bind(TransactionService::class, function ($app) {
            return new TransactionService($app->make(TransactionRepositoryInterface::class), $app->make(CardRepositoryInterface::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
