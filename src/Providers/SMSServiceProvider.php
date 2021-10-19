<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniSMS\Providers;

use Mini\Contracts\Container\BindingResolutionException;
use Mini\Support\ServiceProvider;
use Overtrue\EasySms\EasySms;

class SMSServiceProvider extends ServiceProvider
{
    /**
     * @throws BindingResolutionException
     */
    public function register(): void
    {
        $this->app->singleton('sms', function () {
            return new EasySms(config('sms'));
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../Config/sms.php' => config_path('sms.php')
        ], 'config');
    }
}