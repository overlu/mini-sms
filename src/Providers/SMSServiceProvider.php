<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniSMS\Providers;

use Mini\Contracts\Container\BindingResolutionException;
use Mini\Facades\Validator;
use Mini\Support\ServiceProvider;
use MiniSMS\VerifyCode;
use Overtrue\EasySms\EasySms;

class SMSServiceProvider extends ServiceProvider
{
    /**
     * @throws BindingResolutionException
     */
    public function register(): void
    {
        // Merge configs
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/sms.php',
            'sms'
        );

        $this->app->singleton('sms', function () {
            return new EasySms(config('sms'));
        });
        $this->app->singleton('verify_code', function () {
            return new VerifyCode();
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/sms.php' => config_path('sms.php')
        ], 'config');

        Validator::addValidator('verify_code', new \MiniSMS\Rules\VerifyCode());
    }
}