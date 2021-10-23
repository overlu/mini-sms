<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniSMS;

use Exception;

class VerifyCode
{
    private int $expired = 300;  // 5分钟

    public function __construct()
    {
        $this->expired = config('sms.verify_code_expired', $this->expired);
    }

    /**
     * 生成验证码
     * @param string $mobile
     * @param int $length
     * @return int
     * @throws Exception
     */
    public function make(string $mobile, int $length = 6): int
    {
        $verifyCode = random_int(10 ** ($length - 1), (10 ** $length) - 1);
        redis()->setex('verifyCode:' . $mobile, $this->expired, $verifyCode);
        return $verifyCode;
    }

    /**
     * 获取验证码
     * @param string $mobile
     * @return string
     */
    public function get(string $mobile): string
    {
        $code = redis()->get('verifyCode:' . $mobile);
        return $code ? (string)$code : '';
    }

    /**
     * 移除验证码
     * @param string $mobile
     * @return bool
     */
    public function delete(string $mobile): bool
    {
        return redis()->delete('verifyCode:' . $mobile) ? true : false;
    }

    /**
     * @param string $mobile
     * @param string $verifyCode
     * @return bool
     */
    public function verify(string $mobile, string $verifyCode): bool
    {
        return ($code = $this->get($mobile)) && $code === $verifyCode;
    }
}