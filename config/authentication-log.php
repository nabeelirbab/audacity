<?php

return [
    // The database table name
    // You can change this if the database keys get too long for your driver
    'table_name' => 'admin_authentication_log',

    // The database connection where the authentication_log table resides. Leave empty to use the default
    'db_connection' => null,
    'ignore_ips' => env('AUTH_LOG_IGNORE_IPS', '127.0.0.1'),

    'notifications' => [
        'new-device' => [
            // Send the NewDevice notification
            'enabled' => env('NEW_DEVICE_NOTIFICATION', true),

            // Use torann/geoip to attempt to get a location
            'location' => true,

            // The Notification class to send
            'template' => \App\Notifications\AuthenticationLogNewDeviceNotification::class,
        ],
        'failed-login' => [
            // Send the FailedLogin notification
            'enabled' => env('FAILED_LOGIN_NOTIFICATION', true),

            // Use torann/geoip to attempt to get a location
            'location' => true,

            // The Notification class to send
            'template' => \App\Notifications\AuthenticationLogFailedLoginNotification::class,
        ],
    ],

    // When the clean-up command is run, delete old logs greater than `purge` days
    // Don't schedule the clean-up command if you want to keep logs forever.
    'purge' => 90,
];