<?php

return [
    'cmi' => [
        'merchant_id' => env('CMI_MERCHANT_ID'),
        'terminal_id' => env('CMI_TERMINAL_ID'),
        'secret' => env('CMI_SECRET'),
        'base_url' => env('CMI_BASE_URL', 'https://test.cmi.co.ma'),
    ],
];
