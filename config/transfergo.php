<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    'messageServices' => [
        'aws' => [

        ],
        'twilio' => [
            'enabled'     => true,
            'default'     => true,
            'sid'         => 'ACf919c45291cbb8758fd7719a52ce128c',
            'token'       => 'ee35dea65ca123a077a806d9329f4967',
            'from'        => '+18184235214',
            'priority'    => 0,
            'clientClass' => 'App\\MessageServiceProviders\\TwilioMessageService'
        ],
        'pushy' => [
            'enabled'     => true,
            'default'     => false,
            'appId'       => '604f194bbe50e00f1b8f5427',
            'token'       => '02b4bbbdffa51b5c87b68b8dd6712c703f6e2928917238350ecd4d909cd5ca99',
            'priority'    => 1,
            'clientClass' => 'App\\MessageServiceProviders\\PushyMessageService'
        ],
    ]

];
