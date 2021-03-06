<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniSMS;

use Exception;
use Mini\Contracts\Container\BindingResolutionException;

class VerifyCode
{
    private int $expired = 300;  // 5分钟

    public function __construct()
    {
        $this->expired = config('sms.verify_code.verify_code_expired', $this->expired);
    }

    /**
     * 生成验证码
     * @param string $mobile
     * @param int $length
     * @return int
     * @throws Exception
     */
    public function make(string $mobile, int $length = 0): int
    {
        if ($length < 1) {
            $length = (int)config('sms.verify_code.verify_code_length', 6);
        }
        $verifyCode = random_int(10 ** ($length - 1), (10 ** $length) - 1);
        redis()->setex('verify_code:' . $mobile, $this->expired, $verifyCode);
        return $verifyCode;
    }

    /**
     * 获取验证码
     * @param string $mobile
     * @return string
     * @throws BindingResolutionException
     */
    public function get(string $mobile): string
    {
        $code = redis()->get('verify_code:' . $mobile);
        return $code ? (string)$code : '';
    }

    /**
     * 移除验证码
     * @param string $mobile
     * @return bool
     * @throws BindingResolutionException
     */
    public function delete(string $mobile): bool
    {
        return (bool)redis()->del('verify_code:' . $mobile);
    }

    /**
     * @param string $mobile
     * @param string $verifyCode
     * @param bool $removeCodeIfPass
     * @return bool
     * @throws BindingResolutionException
     */
    public function verify(string $mobile, string $verifyCode, bool $removeCodeIfPass = true): bool
    {
        if (empty($mobile) || empty($verifyCode)) {
            return false;
        }
        if (config('sms.verify_code.enable_dev_mode', false) && $verifyCode === config('sms.verify_code.dev_mode_verifycode', '666666')) {
            if (is_dev_env(true)) {
                return true;
            }
            if (in_array($mobile, config('sms.verify_code.dev_mode_mobiles'), true)) {
                return true;
            }
        }

        $pass = ($code = $this->get($mobile)) && $code === $verifyCode;
        if ($pass && $removeCodeIfPass) {
            $this->delete($mobile);
        }
        return $pass;
    }
}