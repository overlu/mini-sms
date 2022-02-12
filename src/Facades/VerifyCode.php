<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniSMS\Facades;

use Mini\Facades\Facade;

/**
 * Class VerifyCode
 * @method static int make(string $mobile, int $length = 0)
 * @method static string get(string $mobile)
 * @method static bool delete(string $mobile)
 * @method static bool verify(string $mobile, string $verifyCode, bool $removeCodeIfPass = true)
 * @package MiniSMS\Facades
 * @see \MiniSMS\VerifyCode
 */
class VerifyCode extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'verify_code';
    }
}