<?php
return [
    'labels' => [
        'title' => 'Plans',
        'description' => 'Trading Plans',
    ],
    'fields' => [
        'title' => 'Title',
        'initial_balance' => 'Balance',
        'leverage' => 'Leverage',
        'max_daily_loss_perc' => 'Daily Loss',
        'max_loss_perc' => 'Max Loss',
        'profit_perc' => 'Profit',
        'min_trading_days' => 'Min',
        'max_trading_days' => 'Max',
        'price' => 'Price',
        'enabled' => 'Enabled?',
        'is_public' => 'Public?',
        'check_hedging' => 'Hedging?',
        'check_sl' => 'SL?',

    ],
    'options' => [
    ],
    'rules' => 'Trading Objective Rules/Targets',
    'min_trading_days'=> 'Min Trading Days',
    'max_trading_days'=> 'Max Trading Days',
    'trading_days' => 'Trading Days',
    'accounts' => 'Accounts',
    'broker' => 'Broker',
    'performances' => 'Performances',
    'help_check_hedging' => 'Check if trader is used hedging, it is prohobited',
    'help_check_sl' => 'Check if trader is used stoploss, it is required',
    'help_max_trading_days' => 'Set 0 to ignore this option'
];
