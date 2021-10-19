<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniSMS\Facades;

use Exception;
use JsonException;
use Mini\Facades\Facade;
use MiniSMS\Exceptions\SMSException;
use Mini\Facades\Log;
use Overtrue\EasySms\Contracts\MessageInterface;

/**
 * Class SMS
 * @package App\Facades
 * @see \Overtrue\EasySms\EasySms
 */
class SMS extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sms';
    }

    /**
     * @param string|array $to
     * @param MessageInterface|array $message
     * @param array $gateways
     * @throws SMSException|JsonException
     */
    public static function send($to, $message, array $gateways = []): void
    {
        try {
            app('sms')->send($to, $message, $gateways);
            if (config('sms.enable_send_log', false)) {
                Log::info([
                    'to' => $to,
                    'message' => $message,
                    'gateways' => $gateways
                ], [], 'sms.send');
            }
        } catch (Exception $exception) {
            if (config('sms.enable_error_log', false)) {
                Log::error($exception->getExceptions(), [], 'sms.error');
            }
//            throw $exception;
            throw new SMSException(json_encode($exception->getExceptions(), JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE), 90000);
        }
    }

    /**
     * 模板发送
     * @param string|array $to
     * @param string $template
     * @param array $data
     * @param array $gateways
     * @throws SMSException|JsonException
     */
    public static function sendWithTemplate($to, string $template, array $data = [], array $gateways = []): void
    {
        $templateContent = config('sms.templates.' . $template);
        if (!$templateContent) {
            throw new SMSException('短信模板[' . $template . ']不存在', 90001);
        }
        if (!empty($data)) {
            $searchKeys = array_map(static function ($val) {
                return '{' . $val . '}';
            }, array_keys($data));
            $templateContent['content'] = str_ireplace($searchKeys, array_values($data), $templateContent['content']);
        }
        $templateContent['data'] = $data;
        self::send($to, $templateContent, $gateways);
    }
}