<?php

return [
    'bots'                         => [
        'mybot' => [
            'username'            => 'UserInfoPh',
            'token'               => env('TELEGRAM_BOT_TOKEN', '5845834260:AAG1QNdmXS58r13MkYANvSoRHRRXwdLySl4'),
            'certificate_path'    => env('TELEGRAM_CERTIFICATE_PATH', ''),
            'webhook_url'         => env('TELEGRAM_WEBHOOK_URL', ''),
            'commands'            => [],
        ],

    ],
];
