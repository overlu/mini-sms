<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

use Overtrue\EasySms\Strategies\OrderStrategy;
use Overtrue\EasySms\Strategies\RandomStrategy;

/**
 * @see https://github.com/overtrue/easy-sms
 */
return [
    /**
     * HTTP 请求的超时时间（秒）
     */
    'timeout' => 5.0,

    /**
     * 默认发送配置
     */
    'default' => [
        // 网关调用策略，默认：顺序调用
        'strategy' => OrderStrategy::class,
        // 随机调用
//        'strategy' => RandomStrategy::class,

        // 默认可用的发送网关
        'gateways' => [
            'aliyun',
        ],
    ],
    /**
     * 可用的网关配置
     */
    'gateways' => [
        'aliyun' => [
            'access_key_id' => env('ALIYUN_ACCESS_ID'),
            'access_key_secret' => env('ALIYUN_ACCESS_SECRET'),
            'sign_name' => '...',
        ],
        //...
    ],
    'verify_code_length' => 6,
    /**
     * 常用模块
     */
    'templates' => [
        /*'verify_code' => [
            'content' => '您的验证码{code}，该验证码5分钟内有效，请勿泄漏于他人！',
            'template' => 'SMS_001',
        ]*/
    ],
    /**
     * 记录发送错误日志
     */
    'enable_error_log' => true,
    /**
     * 记录发送记录日志
     */
    'enable_send_log' => false
];