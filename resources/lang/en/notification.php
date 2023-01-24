<?php
return [
    'labels' => [
        'title' => 'Notifications',
        'description' => 'System Notifications',
    ],
    'fields' => [
        'created_at' => 'Date',
        'data' => 'Details',
        'status' => 'Status',
    ],
    'options' => [
    ],
    'notifications' => 'Notifications',
    'UserRegisteredNotification' => 'New User Registered',
    'UserActivatedNotification' => 'User Activated',
    'ChallengeCreatedNotification' => 'New Order',
    'ChallengeConfirmedNotification' => 'Order Confirmed',
    'ChallengeCancelledNotification' => 'Order Cancelled',
    'ChallengeAccountGeneratedNotification' => 'Challenge Account Generated',
    'TradingObjectiveFailedNotification' => 'Objective Failed',
    'PerformancePassedNotification' => 'Challenge Passed',
    'AccountGeneratedNotification' => 'Account Generated',
    'AccountStatusInvalidNotification' => 'Invalid Account',
    'manager' => [
        'order_account_generated' => 'Account Number :account_number',
        'order_created' => 'Order #:order_id for ":plan" successfully created.',
        'order_cancelled' => 'Order #:order_id for ":plan" was cancelled.',
        'user_registered' => 'User ID :user_id, Email :email',
        'user_activated' => 'User ID :user_id, Email :email',
        'failed_objective' => 'Objective ":failed_objective". Account ":account". Plan ":plan"',
        'performance_passed' => 'Account ":account" passed challenge on ":plan"'
    ],
    'user' => [
        'account_invalid' => 'Account #:account_number, Broker ":broker_server"',
        'order_created' => 'Order #:order_id for ":plan" successfully created, please wait for confirmation.',
        'order_cancelled' => 'Order #:order_id for ":plan" cancelled.',
        'order_confirmed' => 'Order #:order_id for ":plan" confirmed, please wait for Metatrader Account Activation',
        'order_account_generated' => 'Find your Metatrader4 Account details under "Accounts" -> "Details"',
        'failed_objective' => 'Objective ":failed_objective". Account ":account". Plan ":plan"',
        'performance_passed' => 'Account ":account" passed challenge on ":plan"'
    ],
    'AccountStatusOrfant' => 'Orfant Account',
    'CopierHasErrors' => 'Copier Errors',
    'new_notifications' => 'New Notifications',
    'read_all' => 'See All Notifications'
];
