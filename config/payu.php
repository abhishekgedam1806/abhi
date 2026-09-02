<?php

return [
    'default' => env('PAYU_DEFAULT_GATEWAY', 'biz'),

    'gateways' => [
        'money' => [
            'mode' => env('PAYU_MONEY_MODE', 'test'),
            'key'  => env('PAYU_MONEY_KEY', 'YOURKEY'),
            'salt' => env('PAYU_MONEY_SALT', 'YOUR-SALT-KEY'),
        ],
        'biz' => [
            'mode' => env('PAYU_BIZ_MODE', 'test'),
            'key'  => env('PAYU_BIZ_KEY', 'YOURKEY'),
            'salt' => env('PAYU_BIZ_SALT', 'YOUR-SALT-KEY'),
        ],
    ],
];
