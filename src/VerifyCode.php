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
        return redis()->delete('verify_code:' . $mobile) ? true : false;
    }

    /**
     * @param string $mobile
     * @param string $verifyCode
     * @return bool
     * @throws Exception
     */
    public function verify(string $mobile, string $verifyCode): bool
    {
        if (empty($mobile) || empty($verifyCode)) {
            return false;
        }
        if (is_dev_env(true) && config('sms.verify_code.enable_dev_mode', false) && $verifyCode === config('sms.verify_code.dev_mode_verifycode', '666666')) {
            return true;
        }
        return ($code = $this->get($mobile)) && $code === $verifyCode;
    }
}