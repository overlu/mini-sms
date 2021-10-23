<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniSMS\Rules;

use Mini\Facades\Request;
use Mini\Validator\Rule;

class VerifyCode extends Rule
{

    /** @var string */
    protected string $message = "The :attribute is wrong";

    /** @var array */
    protected array $fillableParams = ['verifycode'];

    /**
     * Check the $value is valid
     *
     * @param mixed $value
     * @return bool
     */
    public function check($value): bool
    {
        $verifycode = (string)$this->parameter('verifycode', Request::input('verifycode', Request::input('verify_code', '')));
        return \MiniSMS\Facades\VerifyCode::verify((string)$value, $verifycode);
    }
}
