<?php

use App\Enums\AccountStatus;
use App\Enums\AccountType;
use App\Enums\ApiServerStatus;
use App\Enums\BrokerServerHostStatus;
use App\Enums\BrokerServerStatus;
use App\Enums\BrokerServerType;
use App\Enums\ChallengeStatus;
use App\Enums\CopierDirection;
use App\Enums\CopierOrderType;
use App\Enums\CopierRiskType;
use App\Enums\CopierType;
use App\Enums\EnabledDisabledType;
use App\Enums\FilterCondition;
use App\Enums\HelpdeskTicketPriority;
use App\Enums\HelpdeskTicketStatus;
use App\Enums\MT4FileType;
use App\Enums\MT4Timeframe;
use App\Enums\MetatraderType;
use App\Enums\ObjectiveStatus;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\PerformanceObjectiveType;
use App\Enums\PerformanceStatus;
use App\Enums\SystemLogLevel;
use App\Enums\TemplateLoadStatusType;
use App\Enums\WeekDayType;
use App\Enums\YesNoType;

return [
    AccountType::class => [
        AccountType::DEMO->value => 'Demo',
        AccountType::REAL->value => 'Real',
    ],

    AccountStatus::class => [
        AccountStatus::ORFANT->value => 'Orfant',
        AccountStatus::NONE->value => 'Pinging',
        AccountStatus::ONLINE->value => 'Online',
        AccountStatus::OFFLINE->value => 'Offline',
        AccountStatus::PENDING->value => 'Connecting',
        AccountStatus::SUSPEND->value => 'Suspended',
        AccountStatus::REMOVING->value => 'Removing',
        AccountStatus::INVALID->value => 'Invalid',
        AccountStatus::SUSPEND_STOPPED->value => 'Suspended',
        AccountStatus::INVALID_STOPPED->value => 'Invalid',
        AccountStatus::VERIFYING->value => 'Verifying',
        AccountStatus::CNN_LOST->value => 'Reconnecting',
    ],

    ApiServerStatus::class => [
        ApiServerStatus::UP->value => 'Up',
        ApiServerStatus::DOWN->value => 'Down',
    ],

    TemplateLoadStatusType::class => [
        TemplateLoadStatusType::EMPTY->value => 'Empty',
        TemplateLoadStatusType::LOADED->value => 'Loaded',
        TemplateLoadStatusType::FAILED->value => 'Failed',
        TemplateLoadStatusType::WRONG_EA->value => 'WrongEA',

    ],

    BrokerServerStatus::class => [
        BrokerServerStatus::NONE->value => 'Pinging',
        BrokerServerStatus::ACTIVE->value => 'Active'
    ],

    BrokerServerHostStatus::class => [
        BrokerServerHostStatus::NONE->value => 'Pinging',
        BrokerServerHostStatus::ONLINE->value => 'Online',
        BrokerServerHostStatus::OFFLINE->value => 'Offline',
    ],

    BrokerServerType::class => [
        BrokerServerType::API->value => 'API',
        BrokerServerType::MANAGER->value => 'Manager',
    ],

    CopierDirection::class => [
        CopierDirection::NORMAL->value => 'Normal',
        CopierDirection::INVERSE->value => 'Inverse'
    ],

    CopierOrderType::class => [
        CopierOrderType::BOTH->value => 'Both',
        CopierOrderType::LONG_ONLY->value => 'Long Only',
        CopierOrderType::SHORT_ONLY->value => 'Short Only'
    ],

    CopierType::class => [
        CopierType::SENDER->value => 'Sender',
        CopierType::FOLLOWER->value => 'Follower',
        CopierType::STRATEGY->value => 'Strategy'
    ],

    CopierRiskType::class => [
        CopierRiskType::FIXED_LOT->value =>'Fixed Lot',
        CopierRiskType::MULTIPLIER->value =>'Multiplier',
        CopierRiskType::MONEY_RATIO->value =>'Money Ratio',
        CopierRiskType::RISK_PERCENT->value =>'Risk Percent',
        CopierRiskType::SCALING->value =>'Risk Scaling'
    ],

    OrderType::class => [
        OrderType::BUY->value => 'Buy',
        OrderType::SELL->value => 'Sell',
        OrderType::SELL_LIMIT->value => 'SellLimit',
        OrderType::BUY_LIMIT->value => 'BuyLimit',
        OrderType::BUY_STOP->value => 'BuyStop',
        OrderType::SELL_STOP->value => 'SellStop',
        OrderType::BALANCE->value => 'Balance',
        OrderType::CREDIT->value => 'Credit'
    ],

    OrderStatus::class => [
        OrderStatus::OPEN->value => 'Open',
        OrderStatus::CLOSED->value => 'Closed',
        OrderStatus::NOT_FILLED->value => 'NotFilled'
    ],

    FilterCondition::class => [
        FilterCondition::NONE->value => 'None',
        FilterCondition::EQUAL->value => 'Equal',
        FilterCondition::NOT_EQUAL->value => 'Not Equal',
        FilterCondition::CONTAIN->value => 'Contain',
        FilterCondition::NOT_CONTAIN->value => 'Not Contain'
    ],

    MetatraderType::class => [
        MetatraderType::V4->value => 'MT4',
        MetatraderType::V5->value => 'MT5',
    ],

    PerformanceObjectiveType::class => [
        PerformanceObjectiveType::MAX_LOSS->value => 'Max Loss',
        PerformanceObjectiveType::MAX_DAILY_LOSS->value => 'Max Daily Loss',
        PerformanceObjectiveType::PROFIT->value => 'Profit',
        PerformanceObjectiveType::TRADING_DAYS->value => 'Trading Days',
        PerformanceObjectiveType::HEDGING_DETECTED->value => 'Hedging Prohibited',
        PerformanceObjectiveType::SL_NOT_USED->value => 'SL is required',
    ],

    PerformanceStatus::class => [
        PerformanceStatus::CALCULATING->value => 'Calculating',
        PerformanceStatus::ACTIVE->value => 'Active',
        PerformanceStatus::ENDED_PASSED->value => 'Passed',
        PerformanceStatus::ENDED_FAILED->value => 'Failed'
    ],

    ObjectiveStatus::class => [
        ObjectiveStatus::PASSED->value => 'Passed',
        ObjectiveStatus::FAILED->value => 'Not Passing',
    ],

    ChallengeStatus::class => [
        ChallengeStatus::NEW->value => 'New',
        ChallengeStatus::CANCELLED->value => 'Cancelled',
        ChallengeStatus::CONFIRMED->value => 'Confirmed',
        ChallengeStatus::ACTIVE->value => 'Active',
        ChallengeStatus::ENDED->value => 'Ended',
        ChallengeStatus::ERROR->value => 'Error'
    ],

    MT4Timeframe::class => [
        MT4Timeframe::M1->value => 'M1',
        MT4Timeframe::M5->value => 'M5',
        MT4Timeframe::M15->value => 'M15',
        MT4Timeframe::M30->value => 'M30',
        MT4Timeframe::H1->value => 'H1',
        MT4Timeframe::H4->value => 'H4',
    ],

    MT4FileType::class => [
        MT4FileType::SRV->value => 'Srv',
        MT4FileType::TPL->value => 'Tpl',
        MT4FileType::LIB->value => 'Lib',
        MT4FileType::FILE->value => 'File',
        MT4FileType::EX4->value => 'Ex4',
        MT4FileType::EXE->value => 'Exe',
        MT4FileType::PAIRS->value => 'Pairs',
    ],

    WeekDayType::class => [
        WeekDayType::MONDAY->value => 'Monday',
        WeekDayType::TUESDAY->value => 'Tuesday',
        WeekDayType::WEDNESDAY->value => 'Wednesday',
        WeekDayType::THURSDAY->value => 'Thursday',
        WeekDayType::FRIDAY->value => 'Friday',
        WeekDayType::SATURDAY->value => 'Saturday',
        WeekDayType::SUNDAY->value => 'Sunday',
    ],

    YesNoType::class => [
        YesNoType::YES->value => 'Yes',
        YesNoType::NO->value => 'No',
    ],

    EnabledDisabledType::class => [
        EnabledDisabledType::ENABLED->value => 'Enabled',
        EnabledDisabledType::DISABLED->value => 'Disabled',
    ],

    SystemLogLevel::class => [
        SystemLogLevel::EMERGENCY->value => 'Emergency',
        SystemLogLevel::ERROR->value => 'Error',
        SystemLogLevel::INFO->value => 'Info',
        SystemLogLevel::WARNING->value => 'Warning',
        SystemLogLevel::CRITICAL->value => 'Critical',
    ],

    HelpdeskTicketStatus::class => [
        HelpdeskTicketStatus::OPEN->value => 'Open',
        HelpdeskTicketStatus::CLOSED->value => 'Closed'
    ],

    HelpdeskTicketPriority::class => [
        HelpdeskTicketPriority::NORMAL->value => 'Normal',
        HelpdeskTicketPriority::HIGH->value => 'High'
    ]

];