<?php

return [

    'activation_enabled' => env('ACTIVATION_ENABLED', false),
    'max_attempts' => env('ACTIVATION_LIMIT_MAX_ATTEMPTS', 3),
    'time_period' => env('ACTIVATION_LIMIT_TIME_PERIOD', 24),

    'role' => env('REGISTRATION_ROLE', 'user'),

    'nullIpAddress' => env('NULL_IP_ADDRESS', '0.0.0.0'),
];
