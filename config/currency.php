<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Currency Settings
    |--------------------------------------------------------------------------
    |
    | Here you can configure the default currency for the application.
    | Change these values to switch the entire site currency.
    |
    */

    // Main currency displayed throughout the website
    'default' => [
        'code' => 'EGP',
        'symbol' => 'ج.م',
        'name' => 'جنيه مصري',
        'decimals' => 0, // Number of decimal places to show
    ],

    // Payment gateway currency (keep as SAR for URWAY integration)
    'payment' => [
        'code' => 'SAR',
        'symbol' => 'ر.س',
        'name' => 'ريال سعودي',
        'decimals' => 2,
    ],
];
