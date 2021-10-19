<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

use MiniSMS\Exceptions\SMSException;
use MiniSMS\Facades\SMS;
use Overtrue\EasySms\Contracts\MessageInterface;

if (!function_exists('sms')) {
    /**
     * @param string|array $to
     * @param MessageInterface|array $message
     * @param array $gateways
     * @throws SMSException|JsonException
     */
    function sms($to, $message, array $gateways = []): void
    {
        SMS::send($to, $message, $gateways);
    }
}

if (!function_exists('smsWithTemplate')) {
    /**
     * 模板发送
     * @param string|array $to
     * @param string $template
     * @param array $data
     * @param array $gateways
     * @throws JsonException
     * @throws SMSException
     */
    function smsWithTemplate($to, string $template, array $data = [], array $gateways = []): void
    {
        SMS::sendWithTemplate($to, $template, $data, $gateways);
    }
}