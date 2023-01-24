<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Account
 *
 * @OA\Schema (
 *     title="Account",
 *     description="Account model",
 *     @OA\Xml(
 *         name="Account"
 *     ),
 * @OA\Property (
 *     property="id",
 *     title="ID",
 *     description="ID",
 *     format="int64",
 *     example=1
 * ),
 * @OA\Property (
 *     property="account_number",
 *     title="Account Number",
 *     description="Account Number",
 *     example="12456"
 * ),
 * @OA\Property (
 *     property="title",
 *     title="Account Title",
 *     description="Account Title",
 *     example="Test Account"
 * ),
 * @OA\Property (
 *     property="broker_server_name",
 *     title="Broker Server Name",
 *     description="Broker Server Name",
 *     example="Alpari-Demo"
 * )
 * )
 * @property int $id
 * @property int $user_id
 * @property int $account_number
 * @property \App\Enums\CopierType $copier_type
 * @property string $broker_server_name
 * @property \App\Enums\AccountStatus $account_status
 * @property string|null $api_server_ip
 * @property int $manager_id
 * @property string|null $name
 * @property string|null $title
 * @property string|null $password
 * @property string|null $last_error
 * @property string|null $old_api_server_ip
 * @property string|null $api_version
 * @property \App\Enums\YesNoType $trade_allowed
 * @property \App\Enums\YesNoType|null $symbol_trade_allowed
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $creator_id
 * @property int|null $build
 * @property int|null $jfx_mode
 * @property \App\Enums\AccountType|null $is_live
 * @property int|null $processing
 * @property int|null $count_invalid_restarts
 * @property string|null $suffix
 * @property int|null $wait_restarting
 * @property int|null $collect_equity
 * @property-read \App\Models\ApiServer|null $api_server
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CopierSignal[] $asFollower
 * @property-read int|null $as_follower_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CopierSignal[] $asSender
 * @property-read int|null $as_sender_count
 * @property-read \App\Models\BrokerServer|null $broker_server
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $closedorders
 * @property-read int|null $closedorders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $followers
 * @property-read int|null $followers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $liveorders
 * @property-read int|null $liveorders_count
 * @property-read \App\Models\User $manager
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Performance|null $performance
 * @property-read \App\Models\PerformanceWithObjectives|null $performancesWithObjectives
 * @property-read \App\Models\AccountStat|null $stat
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Account follower()
 * @method static \Illuminate\Database\Eloquent\Builder|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account newQuery()
 * @method static \Illuminate\Database\Query\Builder|Account onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder|Account senders()
 * @method static \Illuminate\Database\Query\Builder|Account withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Account withoutTrashed()
 */
	class Account extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AccountExpertTemplate
 *
 * @property int $id
 * @property int $expert_id
 * @property int|null $account_id
 * @property int|null $tpl_file_id
 * @property string $options
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $enabled
 * @property string $symbol
 * @property int $timeframe
 * @property int|null $is_updated_or_new
 * @property string|null $automation_file_options
 * @property string|null $snapshot
 * @property \App\Enums\TemplateLoadStatusType|null $load_status
 * @property-read \App\Models\Account|null $account
 * @property-read \App\Models\Expert $expert
 * @property-read \App\Models\MT4File|null $template_file
 * @method static \Illuminate\Database\Eloquent\Builder|AccountExpertTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountExpertTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountExpertTemplate query()
 */
	class AccountExpertTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AccountRemoved
 *
 * @property int $id
 * @property int $account_number
 * @property string $password
 * @property string $broker_server_name
 * @property int $manager_id
 * @property int $creator_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $trade_allowed
 * @property int|null $symbol_trade_allowed
 * @property string|null $last_error
 * @property int|null $is_live
 * @property int|null $copier_type
 * @property string|null $api_server_ip
 * @property-read \App\Models\User $manager
 * @method static \Illuminate\Database\Eloquent\Builder|AccountRemoved newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountRemoved newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountRemoved query()
 */
	class AccountRemoved extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AccountStat
 *
 * @property int $account_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $nof_closed
 * @property int|null $nof_working
 * @property int|null $nof_won
 * @property int|null $nof_lost
 * @property float|null $win_ratio
 * @property float|null $total_profit
 * @property float|null $profit_factor
 * @property int|null $total_weeks
 * @property float|null $worst_trade_dol
 * @property float|null $best_trade_dol
 * @property float|null $worst_trade_pips
 * @property float|null $best_trade_pips
 * @property float|null $deposit
 * @property float|null $withdrawal
 * @property float|null $total_profit_pips
 * @property float|null $avg_win
 * @property float|null $avg_loss
 * @property float|null $avg_win_pips
 * @property float|null $avg_loss_pips
 * @property float|null $total_lots
 * @property float|null $total_commission
 * @property int|null $total_longs
 * @property int|null $total_shorts
 * @property int|null $longs_won
 * @property int|null $shorts_won
 * @property int|null $total_days
 * @property float|null $daily_perc
 * @property float|null $total_swap
 * @property float|null $balance
 * @property string|null $currency
 * @property float|null $profit
 * @property float|null $equity
 * @property float|null $credit
 * @property int|null $total_months
 * @property float|null $monthly_perc
 * @property float|null $gain_perc
 * @property float|null $highest_dol
 * @property float|null $drawdown_perc
 * @property string|null $highest_date
 * @property float|null $min_profit
 * @property float|null $max_profit
 * @property int|null $nof_pending
 * @property int|null $account_type
 * @property string|null $broker_company
 * @property string|null $broker_server
 * @property int|null $leverage
 * @property int|null $nof_processed
 * @property float|null $weekly_perc
 * @property float|null $loss_ratio
 * @property string|null $first_trade_day
 * @property float|null $avg_trades_per_day
 * @property float|null $avg_profit_per_day
 * @property float|null $current_pace
 * @property float|null $margin
 * @property string|null $last_trade_day
 * @property-read \App\Models\Account $account
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @method static \Illuminate\Database\Eloquent\Builder|AccountStat deposited()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountStat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountStat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountStat query()
 */
	class AccountStat extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Activation
 *
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property string $ip_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Activation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Activation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Activation query()
 */
	class Activation extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ApiServer
 *
 * @property int $id
 * @property int|null $manager_id
 * @property string $ip
 * @property string|null $title
 * @property \App\Enums\ApiServerStatus $api_server_status
 * @property int|null $max_accounts
 * @property int $enabled
 * @property int $mem
 * @property int $cpu
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $host_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $accounts
 * @property-read int|null $accounts_count
 * @property-read \App\Models\User|null $manager
 * @method static \Illuminate\Database\Eloquent\Builder|ApiServer enabled()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiServer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiServer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ApiServer query()
 */
	class ApiServer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BrokerGroup
 *
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerGroup query()
 */
	class BrokerGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BrokerManager
 *
 * @property int $id
 * @property string $ip
 * @property string $login
 * @property string $password
 * @property int|null $manager_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $enabled
 * @property int|null $port
 * @property int|null $broker_server_id
 * @property string|null $api_host
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerManager enabled()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerManager newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerManager newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerManager query()
 */
	class BrokerManager extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BrokerServer
 *
 * @property int $id
 * @property string $name
 * @property mixed $srv_file
 * @property string|null $suffix
 * @property int|null $is_updated_or_new
 * @property int|null $gmt_offset
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $srv_file_path
 * @property \App\Enums\BrokerServerType|null $api_or_manager
 * @property string|null $human_readable
 * @property \App\Enums\BrokerServerStatus|null $status
 * @property int|null $is_default
 * @property string|null $main_server_host
 * @property int|null $main_server_port
 * @property \App\Enums\MetatraderType|null $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $accounts
 * @property-read int|null $accounts_count
 * @property-read \App\Models\UserBrokerServer|null $user_server
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer active()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer api()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer default()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer enabled()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer manager()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer query()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer typeOf(\App\Enums\MetatraderType $type)
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer unprocessed()
 */
	class BrokerServer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BrokerServerHost
 *
 * @property int $id
 * @property string $host
 * @property int|null $port
 * @property int|null $ping
 * @property int $broker_server_id
 * @property int|null $is_main
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \App\Enums\BrokerServerHostStatus|null $status
 * @property-read \App\Models\BrokerServer $brokerServer
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServerHost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServerHost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServerHost query()
 */
	class BrokerServerHost extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BrokerServerWithAccounts
 *
 * @property int $id
 * @property string $name
 * @property mixed $srv_file
 * @property string|null $suffix
 * @property int|null $is_updated_or_new
 * @property int|null $gmt_offset
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $srv_file_path
 * @property \App\Enums\BrokerServerType|null $api_or_manager
 * @property string|null $human_readable
 * @property \App\Enums\BrokerServerStatus|null $status
 * @property int|null $is_default
 * @property string|null $main_server_host
 * @property int|null $main_server_port
 * @property \App\Enums\MetatraderType|null $type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $accounts
 * @property-read int|null $accounts_count
 * @property-read mixed $count_accounts
 * @property-read \App\Models\UserBrokerServer|null $user_server
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer active()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer api()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer default()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer enabled()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer manager()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServerWithAccounts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServerWithAccounts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServerWithAccounts query()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer typeOf(\App\Enums\MetatraderType $type)
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerServer unprocessed()
 */
	class BrokerServerWithAccounts extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BrokerSymbol
 *
 * @property string $name
 * @property float|null $spread
 * @property int|null $enabled
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BrokerSymbol enabled()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BrokerSymbol newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BrokerSymbol newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BrokerSymbol query()
 * @mixin \Eloquent
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
	class BrokerSymbol extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\BrokerUser
 *
 * @property int $user_id
 * @property string $group
 * @property string|null $reg_date
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BrokerUser query()
 */
	class BrokerUser extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Campaign
 *
 * @property int $id
 * @property int $manager_id
 * @property string $title
 * @property string|null $description
 * @property string|null $slug
 * @property string $expired_at
 * @property int|null $max_live_accounts
 * @property int|null $max_demo_accounts
 * @property int|null $single_pc
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $auto_confirm_new_accounts
 * @property-read \App\Models\User $manager
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign query()
 */
	class Campaign extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Challenge
 *
 * @property int $id
 * @property int $performance_plan_id
 * @property int $user_id
 * @property int $manager_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Enums\ChallengeStatus|null $status
 * @property float|null $price
 * @property int|null $performance_id
 * @property int|null $account_id
 * @property string|null $last_error
 * @property-read \App\Models\Account|null $account
 * @property-read \App\Models\User $manager
 * @property-read \App\Models\Performance|null $performance
 * @property-read \App\Models\PerformancePlan $plan
 * @property-read \App\Models\PerformanceStat|null $stat
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Challenge query()
 */
	class Challenge extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CopierSignal
 *
 * @OA\Schema (
 *     title="Signal",
 *     description="Signal model",
 *     @OA\Xml(
 *         name="Signal"
 *     ),
 * @OA\Property (
 *     property="id",
 *     title="ID",
 *     description="ID",
 *     format="int64",
 *     example=1
 * ),
 * @OA\Property (
 *     property="title",
 *     title="Title",
 *     description="Title",
 *     example="Signal1"
 * ),
 * @OA\Property (
 *     property="risk_type",
 *     title="Risk Type",
 *     description="Risk Type",
 *     format="string",
 *     enum={"MULTIPLIER", "FIXED_LOT", "MONEY_RATIO", "RISK_PERCENT", "SCALING"},
 *     example="MULTIPLIER"
 * )
 * )
 * @property int $id
 * @property int $manager_id
 * @property string|null $slug
 * @property string $title
 * @property int|null $risk_type
 * @property float|null $fixed_lot
 * @property float|null $max_risk
 * @property float|null $lots_multiplier
 * @property float|null $money_ratio_lots
 * @property float|null $money_ratio_dol
 * @property float|null $max_lots_per_trade
 * @property float|null $price_diff_accepted_pips
 * @property int|null $max_open_positions
 * @property float|null $min_balance
 * @property int|null $live_time
 * @property int|null $scaling_factor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $creator_id
 * @property int|null $allow_partial_close
 * @property int|null $lots_formula
 * @property string|null $pairs_matching
 * @property int|null $filter_symbol_condition
 * @property string|null $filter_symbol_values
 * @property int|null $filter_magic_condition
 * @property string|null $filter_magic_values
 * @property int|null $filter_comment_condition
 * @property string|null $filter_comment_values
 * @property int|null $is_public
 * @property string|null $comment
 * @property string|null $email_template_signal_new
 * @property string|null $email_template_signal_updated
 * @property string|null $email_template_signal_closed
 * @property int|null $email_enabled
 * @property int|null $copier_enabled
 * @property string|null $description
 * @property int|null $copy_existing
 * @property int|null $reverse_copy
 * @property string|null $telegram_chat_id
 * @property int|null $telegram_enabled
 * @property int|null $dont_copy_sl_tp
 * @property float|null $sender_sl_offset_pips
 * @property float|null $sender_tp_offset_pips
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\CopierSignalEmailSetting|null $email_settings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $followers
 * @property-read int|null $followers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CopierSignalFollower[] $followers_settings
 * @property-read int|null $followers_settings_count
 * @property-read \App\Models\User $manager
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\CopierSignalPageSetting|null $page_settings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $senders
 * @property-read int|null $senders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $subscribers
 * @property-read int|null $subscribers_count
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignal public()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignal query()
 */
	class CopierSignal extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CopierSignalEmailSetting
 *
 * @property int $signal_id
 * @property string|null $template_type_new
 * @property string|null $template_type_updated
 * @property string|null $template_type_closed
 * @property int|null $enabled
 * @property-read \App\Models\CopierSignal $signal
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalEmailSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalEmailSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalEmailSetting query()
 */
	class CopierSignalEmailSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CopierSignalFollower
 *
 * @property int $id
 * @property int|null $signal_id
 * @property int|null $account_id
 * @property int|null $copier_enabled
 * @property int|null $email_enabled
 * @property float|null $fixed_lot
 * @property float|null $lots_multiplier
 * @property float|null $max_lots_per_trade
 * @property float|null $max_risk
 * @property int|null $max_open_positions
 * @property \App\Enums\CopierRiskType|null $risk_type
 * @property float|null $money_ratio_lots
 * @property float|null $money_ratio_dol
 * @property float|null $min_balance
 * @property int|null $live_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float|null $scaling_factor
 * @property int|null $filter_symbol_condition
 * @property string|null $filter_symbol_values
 * @property int|null $filter_magic_condition
 * @property string|null $filter_magic_values
 * @property int|null $filter_comment_condition
 * @property string|null $filter_comment_values
 * @property int|null $reverse_copy
 * @property int|null $copy_existing
 * @property string|null $copier_enabled_at
 * @property string|null $signals_email
 * @property int|null $is_past_due
 * @property int|null $dont_copy_sl_tp
 * @property float|null $sender_sl_offset_pips
 * @property float|null $sender_tp_offset_pips
 * @property-read \App\Models\Account|null $account
 * @property-read \App\Models\User|null $creator
 * @property-read mixed $email
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\CopierSignal|null $signal
 * @property-read \App\Models\AccountStat|null $stat
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalFollower newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalFollower newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalFollower query()
 */
	class CopierSignalFollower extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CopierSignalPageSetting
 *
 * @property int $signal_id
 * @property int|null $hide_open_trades
 * @property int|null $hide_trade_history
 * @property int|null $hide_balance_info
 * @property int|null $hide_broker_info
 * @property int|null $hide_ticket
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalPageSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalPageSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalPageSetting query()
 */
	class CopierSignalPageSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CopierSignalSender
 *
 * @property int $signal_id
 * @property int $account_id
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\CopierSignal $signal
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalSender newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalSender newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalSender query()
 */
	class CopierSignalSender extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CopierSignalSubscription
 *
 * @property int $id
 * @property int $user_id
 * @property int $signal_id
 * @property string|null $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CopierSignal $signal
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalSubscription query()
 */
	class CopierSignalSubscription extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CopierSignalWithFollowers
 *
 * @property int $id
 * @property int $manager_id
 * @property string|null $slug
 * @property string $title
 * @property int|null $risk_type
 * @property float|null $fixed_lot
 * @property float|null $max_risk
 * @property float|null $lots_multiplier
 * @property float|null $money_ratio_lots
 * @property float|null $money_ratio_dol
 * @property float|null $max_lots_per_trade
 * @property float|null $price_diff_accepted_pips
 * @property int|null $max_open_positions
 * @property float|null $min_balance
 * @property int|null $live_time
 * @property int|null $scaling_factor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $creator_id
 * @property int|null $allow_partial_close
 * @property int|null $lots_formula
 * @property string|null $pairs_matching
 * @property int|null $filter_symbol_condition
 * @property string|null $filter_symbol_values
 * @property int|null $filter_magic_condition
 * @property string|null $filter_magic_values
 * @property int|null $filter_comment_condition
 * @property string|null $filter_comment_values
 * @property int|null $is_public
 * @property string|null $comment
 * @property string|null $email_template_signal_new
 * @property string|null $email_template_signal_updated
 * @property string|null $email_template_signal_closed
 * @property int|null $email_enabled
 * @property int|null $copier_enabled
 * @property string|null $description
 * @property int|null $copy_existing
 * @property int|null $reverse_copy
 * @property string|null $telegram_chat_id
 * @property int|null $telegram_enabled
 * @property int|null $dont_copy_sl_tp
 * @property float|null $sender_sl_offset_pips
 * @property float|null $sender_tp_offset_pips
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\CopierSignalEmailSetting|null $email_settings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $followers
 * @property-read int|null $followers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CopierSignalFollower[] $followers_settings
 * @property-read int|null $followers_settings_count
 * @property-read mixed $count_followers
 * @property-read \App\Models\User $manager
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\CopierSignalPageSetting|null $page_settings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $senders
 * @property-read int|null $senders_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $subscribers
 * @property-read int|null $subscribers_count
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalWithFollowers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalWithFollowers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignal public()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSignalWithFollowers query()
 */
	class CopierSignalWithFollowers extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CopierSubscription
 *
 * @property int $id
 * @property int $manager_id
 * @property string $title
 * @property \App\Enums\CopierRiskType|null $risk_type
 * @property float|null $fixed_lot
 * @property float|null $max_risk
 * @property float|null $lots_multiplier
 * @property float|null $money_ratio_lots
 * @property float|null $money_ratio_dol
 * @property float|null $max_lots_per_trade
 * @property float|null $price_diff_accepted_pips
 * @property int|null $max_open_positions
 * @property int|null $copier_delay
 * @property string|null $memc_servers
 * @property float|null $min_balance
 * @property int|null $live_time
 * @property int|null $scaling_factor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $creator_id
 * @property string|null $scope_key
 * @property int|null $allow_partial_close
 * @property int|null $lots_formula
 * @property string|null $pairs_matching
 * @property int|null $copier_subscription_group_id
 * @property int|null $filter_symbol_condition
 * @property string|null $filter_symbol_values
 * @property int|null $filter_magic_condition
 * @property string|null $filter_magic_values
 * @property int|null $filter_comment_condition
 * @property string|null $filter_comment_values
 * @property int|null $is_public
 * @property string|null $comment
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $destination
 * @property-read int|null $destination_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $sources
 * @property-read int|null $sources_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $subscribers
 * @property-read int|null $subscribers_count
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSubscription query()
 */
	class CopierSubscription extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CopierSubscriptionGroup
 *
 * @property int $id
 * @property string $title
 * @property int|null $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $manager_id
 * @property-read \App\Models\User|null $manager
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CopierSubscription[] $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSubscriptionGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSubscriptionGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSubscriptionGroup query()
 */
	class CopierSubscriptionGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CopierSubscriptionSourceAccount
 *
 * @property int $id
 * @property int $copier_subscription_id
 * @property int $account_id
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property-read \App\Models\Account|null $accounts
 * @property-read \App\Models\CopierSubscription|null $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSubscriptionSourceAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSubscriptionSourceAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CopierSubscriptionSourceAccount query()
 */
	class CopierSubscriptionSourceAccount extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EmailSubscription
 *
 * @property int $id
 * @property int $manager_id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $is_public
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $template_signal_new
 * @property string|null $template_signal_updated
 * @property string|null $template_signal_closed
 * @property-read \App\Models\User $manager
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $sources
 * @property-read int|null $sources_count
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscription public()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscription query()
 */
	class EmailSubscription extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EmailSubscriptionGroup
 *
 * @property int $id
 * @property string $title
 * @property int|null $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $manager_id
 * @property-read \App\Models\User|null $manager
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\EmailSubscription[] $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscriptionGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscriptionGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscriptionGroup query()
 */
	class EmailSubscriptionGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\EmailSubscriptionSourceAccount
 *
 * @property int $id
 * @property int $email_subscription_id
 * @property int $account_id
 * @property-read \App\Models\Account|null $accounts
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscriptionSourceAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscriptionSourceAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EmailSubscriptionSourceAccount query()
 */
	class EmailSubscriptionSourceAccount extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Expert
 *
 * @property int $id
 * @property string $name
 * @property int $ex4_file_id
 * @property int $manager_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $enabled
 * @property string $template_default
 * @property int|null $automation_file_id
 * @property-read \App\Models\MT4File|null $ex4_file
 * @method static \Illuminate\Database\Eloquent\Builder|Expert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Expert query()
 */
	class Expert extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ExpertSubscription
 *
 * @property int $id
 * @property int $manager_id
 * @property string $title
 * @property int|null $count_templates
 * @property int|null $enabled
 * @property int|null $expire_days
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Expert[] $experts
 * @property-read int|null $experts_count
 * @property-read \App\Models\User $manager
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertSubscription query()
 */
	class ExpertSubscription extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ExpertSubscriptionExpert
 *
 * @property int $id
 * @property int $expert_id
 * @property int $expert_subscription_id
 * @property-read \App\Models\Expert $expert
 * @property-read \App\Models\ExpertSubscription $subscription
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertSubscriptionExpert newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertSubscriptionExpert newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExpertSubscriptionExpert query()
 */
	class ExpertSubscriptionExpert extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\HelpdeskTicket
 *
 * @OA\Schema (
 *     title="HelpdeskTicket",
 *     description="HelpdeskTicket model",
 *     @OA\Xml(
 *         name="HelpdeskTicket"
 *     ),
 * @OA\Property (
 *     property="id",
 *     title="ID",
 *     description="ID",
 *     format="int64",
 *     example=1
 * )
 * )
 * @property int $id
 * @property int $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $description
 * @property string|null $subject
 * @property string|null $regarding_type
 * @property int|null $regarding_id
 * @property \App\Enums\HelpdeskTicketStatus|null $status
 * @property int|null $priority
 * @property int $manager_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $read_at
 * @property int|null $last_commentator_id
 * @property-read \App\Models\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\HelpdeskTicketComment[] $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\User|null $last_commentator
 * @property-read \App\Models\User $manager
 * @method static \Illuminate\Database\Eloquent\Builder|HelpdeskTicket closed()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpdeskTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpdeskTicket newQuery()
 * @method static \Illuminate\Database\Query\Builder|HelpdeskTicket onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpdeskTicket open()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpdeskTicket query()
 * @method static \Illuminate\Database\Query\Builder|HelpdeskTicket withTrashed()
 * @method static \Illuminate\Database\Query\Builder|HelpdeskTicket withoutTrashed()
 */
	class HelpdeskTicket extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\HelpdeskTicketComment
 *
 * @OA\Schema (
 *     title="HelpdeskTicketComment",
 *     description="HelpdeskTicketComment model",
 *     @OA\Xml(
 *         name="HelpdeskTicketComment"
 *     ),
 * @OA\Property (
 *     property="id",
 *     title="ID",
 *     description="ID",
 *     format="int64",
 *     example=1
 * )
 * )
 * @property int $id
 * @property int $ticket_id
 * @property int $author_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $body
 * @property-read \App\Models\User $author
 * @property-read \App\Models\HelpdeskTicket $ticket
 * @method static \Illuminate\Database\Eloquent\Builder|HelpdeskTicketComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpdeskTicketComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|HelpdeskTicketComment query()
 */
	class HelpdeskTicketComment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LicensePreset
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int $expiration_days
 * @property int|null $max_live_accounts
 * @property int|null $max_demo_accounts
 * @property int|null $single_pc
 * @property string|null $broker_name
 * @property int $manager_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $auto_confirm_new_accounts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserBrokerServer[] $brokers
 * @property-read int|null $brokers_count
 * @property-read \App\Models\User $manager
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|LicensePreset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LicensePreset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LicensePreset query()
 */
	class LicensePreset extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Licensing
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $affiliate_id
 * @property int|null $campaign_id
 * @property string|null $reference_source
 * @property-read \App\Models\Campaign $campaign
 * @property-read \App\Models\User4Defender $user
 * @method static \Illuminate\Database\Eloquent\Builder|Licensing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Licensing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Licensing query()
 */
	class Licensing extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\LicensingMemberBroker
 *
 * @property string|null $broker_name
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $member_id
 * @property-read \App\Models\Member|null $member
 * @method static \Illuminate\Database\Eloquent\Builder|LicensingMemberBroker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LicensingMemberBroker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LicensingMemberBroker query()
 */
	class LicensingMemberBroker extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MT4File
 *
 * @property int $id
 * @property string|null $path
 * @property mixed $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $type
 * @property int|null $is_updated_or_new
 * @property string $name
 * @property int $manager_id
 * @method static \Illuminate\Database\Eloquent\Builder|MT4File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MT4File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MT4File query()
 */
	class MT4File extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ManagerMailSetting
 *
 * @property int $manager_id
 * @property string|null $transport
 * @property string|null $host
 * @property int|null $port
 * @property string|null $encryption
 * @property string|null $username
 * @property string|null $password
 * @property string|null $from_email
 * @property string|null $from_name
 * @property string|null $main_template
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $manager
 * @method static \Illuminate\Database\Eloquent\Builder|ManagerMailSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ManagerMailSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ManagerMailSetting query()
 */
	class ManagerMailSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ManagerMailTemplate
 *
 * @property int $id
 * @property int|null $manager_id
 * @property string $mailable
 * @property string|null $subject
 * @property string $html_template
 * @property string|null $text_template
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $tag
 * @property-read array $variables
 * @property-read \App\Models\User|null $manager
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate forMailable(\Illuminate\Contracts\Mail\Mailable $mailable)
 * @method static \Illuminate\Database\Eloquent\Builder|ManagerMailTemplate forManagerMailable(\App\ManagerTemplateMailable $mailable)
 * @method static \Illuminate\Database\Eloquent\Builder|ManagerMailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ManagerMailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ManagerMailTemplate query()
 */
	class ManagerMailTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ManagerSetting
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $max_copiers
 * @property int|null $max_senders
 * @property int|null $max_followers
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $can_edit_brokers
 * @property int|null $create_default_subscription
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $accounts
 * @property-read int|null $accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CopierSubscription[] $copiers
 * @property-read int|null $copiers_count
 * @property-read mixed $copier_count
 * @property-read mixed $follower_count
 * @property-read mixed $sender_count
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|ManagerSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ManagerSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ManagerSetting query()
 */
	class ManagerSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Member
 *
 * @property int $id
 * @property int $user_id
 * @property string $license_key
 * @property int|null $expiration_days
 * @property int|null $max_live_accounts
 * @property int|null $max_demo_accounts
 * @property int|null $single_pc
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $expired_at
 * @property int|null $auto_confirm_new_accounts
 * @property string|null $activated_at
 * @property string|null $MAC
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $accounts
 * @property-read int|null $accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\LicensingMemberBroker[] $brokers
 * @property-read int|null $brokers_count
 * @property-read \App\Models\User|null $manager
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Tag[] $tags
 * @property-read int|null $tags_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member query()
 */
	class Member extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MemberProduct
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $member_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|MemberProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberProduct query()
 */
	class MemberProduct extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MemberProductAccount
 *
 * @property int $id
 * @property int $product_id
 * @property int $account_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $confirmed
 * @property int $member_id
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\Member $member
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\AccountStat|null $stat
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|MemberProductAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberProductAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MemberProductAccount query()
 */
	class MemberProductAccount extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property int $ticket
 * @property int $account_number
 * @property \App\Enums\OrderStatus $status
 * @property string|null $symbol
 * @property int|null $type
 * @property string|null $type_str
 * @property float|null $lots
 * @property float|null $price
 * @property float|null $stoploss
 * @property float|null $takeprofit
 * @property \Illuminate\Support\Carbon|null $time_close
 * @property float|null $price_close
 * @property float|null $pl
 * @property \Illuminate\Support\Carbon|null $time_open
 * @property string|null $time_last_action
 * @property int|null $magic
 * @property float $pips
 * @property float|null $swap
 * @property int $last_error_code
 * @property string|null $last_error
 * @property string $time_created
 * @property float|null $commission
 * @property string|null $comment
 * @property float|null $sl_pips
 * @property float|null $sl_dol
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $strategy_id
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\AccountStat|null $account_stat
 * @method static \Illuminate\Database\Eloquent\Builder|Order closed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order countable()
 * @method static \Illuminate\Database\Eloquent\Builder|Order countableClosed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order market()
 * @method static \Illuminate\Database\Eloquent\Builder|Order marketClosed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order marketOpen()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order open()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OrderEquity
 *
 * @property int $id
 * @property int $account_number
 * @property string $date_at
 * @property float $pl
 * @property float|null $pips
 * @method static \Illuminate\Database\Eloquent\Builder|OrderEquity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderEquity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderEquity query()
 */
	class OrderEquity extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OrderEvent
 *
 * @OA\Schema (
 *     title="OrderEvent",
 *     description="OrderEvent model",
 *     @OA\Xml(
 *         name="OrderEvent"
 *     ),
 * @OA\Property (
 *     property="id",
 *     title="ID",
 *     description="ID",
 *     format="int64",
 *     example=1
 * )
 * )
 * @property int $id
 * @property int $ticket
 * @property int $state
 * @property string $watcher_type
 * @property int $watcher_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $account_id
 * @property-read \App\Models\Account|null $account
 * @property-read \App\Models\Order|null $order
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $watcher
 * @method static \Illuminate\Database\Eloquent\Builder|OrderEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderEvent query()
 */
	class OrderEvent extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OrderWithCopied
 *
 * @property int $ticket
 * @property int $account_number
 * @property \App\Enums\OrderStatus $status
 * @property string|null $symbol
 * @property int|null $type
 * @property string|null $type_str
 * @property float|null $lots
 * @property float|null $price
 * @property float|null $stoploss
 * @property float|null $takeprofit
 * @property \Illuminate\Support\Carbon|null $time_close
 * @property float|null $price_close
 * @property float|null $pl
 * @property \Illuminate\Support\Carbon|null $time_open
 * @property string|null $time_last_action
 * @property int|null $magic
 * @property float $pips
 * @property float|null $swap
 * @property int $last_error_code
 * @property string|null $last_error
 * @property string $time_created
 * @property float|null $commission
 * @property string|null $comment
 * @property float|null $sl_pips
 * @property float|null $sl_dol
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $strategy_id
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\AccountStat|null $account_stat
 * @property-read mixed $count_copied_closed
 * @property-read mixed $count_copied_not_filled
 * @property-read mixed $count_copied_open
 * @method static \Illuminate\Database\Eloquent\Builder|Order closed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order countable()
 * @method static \Illuminate\Database\Eloquent\Builder|Order countableClosed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order market()
 * @method static \Illuminate\Database\Eloquent\Builder|Order marketClosed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order marketOpen()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderWithCopied newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderWithCopied newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order open()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderWithCopied query()
 */
	class OrderWithCopied extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Performance
 *
 * @property int $id
 * @property int $manager_id
 * @property int $user_id
 * @property int $performance_plan_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Enums\PerformanceStatus|null $status
 * @property string $slug
 * @property int $account_id
 * @property int $account_number
 * @property string|null $ended_at
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\AccountStat|null $account_stat
 * @property-read \App\Models\User $manager
 * @property-read \App\Models\PerformancePlan $plan
 * @property-read \App\Models\PerformanceStat|null $stat
 * @property-read \App\Models\PerformanceTarget|null $target
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Performance active()
 * @method static \Illuminate\Database\Eloquent\Builder|Performance ended()
 * @method static \Illuminate\Database\Eloquent\Builder|Performance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Performance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Performance query()
 */
	class Performance extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PerformanceInvoice
 *
 * @property-read \App\Models\Challenge|null $order
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceInvoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceInvoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceInvoice query()
 */
	class PerformanceInvoice extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PerformancePlan
 *
 * @property int $id
 * @property int $manager_id
 * @property string|null $title
 * @property int|null $enabled
 * @property float|null $max_daily_loss_perc
 * @property float|null $max_loss_perc
 * @property float|null $profit_perc
 * @property int|null $min_trading_days
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float|null $initial_balance
 * @property int|null $is_public
 * @property string|null $key
 * @property float|null $price
 * @property int|null $max_trading_days
 * @property int|null $leverage
 * @property \App\Enums\YesNoType|null $check_hedging
 * @property \App\Enums\YesNoType|null $check_sl
 * @property-read \App\Models\User $manager
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Performance[] $performances
 * @property-read int|null $performances_count
 * @method static \Illuminate\Database\Eloquent\Builder|PerformancePlan enabled()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformancePlan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformancePlan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformancePlan public()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformancePlan query()
 */
	class PerformancePlan extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PerformanceStat
 *
 * @property int $performance_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float|null $profit
 * @property float|null $max_daily_loss
 * @property float|null $max_loss
 * @property string|null $max_daily_loss_at
 * @property string|null $max_loss_at
 * @property int|null $days_traded
 * @property int|null $hedging_detected
 * @property string|null $hedging_detected_at
 * @property int|null $sl_not_used
 * @property string|null $sl_not_used_at
 * @property-read \App\Models\Account|null $account
 * @property-read \App\Models\Performance $performance
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceStat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceStat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceStat query()
 */
	class PerformanceStat extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PerformanceTarget
 *
 * @property int $performance_id
 * @property float|null $max_daily_loss
 * @property float|null $max_loss
 * @property float|null $profit
 * @property int|null $min_trading_days
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $max_trading_days
 * @property int|null $check_hedging
 * @property int|null $check_sl
 * @property-read \App\Models\Performance $performance
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceTarget newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceTarget newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceTarget query()
 */
	class PerformanceTarget extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PerformanceWithObjectives
 *
 * @property int $id
 * @property int $manager_id
 * @property int $user_id
 * @property int $performance_plan_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Enums\PerformanceStatus|null $status
 * @property string $slug
 * @property int $account_id
 * @property int $account_number
 * @property string|null $ended_at
 * @property \Illuminate\Support\Carbon|null $started_at
 * @property-read \App\Models\Account $account
 * @property-read \App\Models\AccountStat|null $account_stat
 * @property-read \App\Repositories\PerformanceObjectivesRepository $objectives
 * @property-read \App\Models\User $manager
 * @property-read \App\Models\PerformancePlan $plan
 * @property-read \App\Models\PerformanceStat|null $stat
 * @property-read \App\Models\PerformanceTarget|null $target
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Performance active()
 * @method static \Illuminate\Database\Eloquent\Builder|Performance ended()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceWithObjectives newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceWithObjectives newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PerformanceWithObjectives query()
 */
	class PerformanceWithObjectives extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Portfolio
 *
 * @property int $id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $manager_id
 * @property float|null $initial_deposit
 * @property string|null $deposited_at
 * @property int|null $is_public
 * @property float|null $last_balance
 * @property string|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $accounts
 * @property-read int|null $accounts_count
 * @property-read mixed $balance
 * @property-read mixed $count_orders
 * @property-read mixed $drawdown
 * @property-read mixed $profit
 * @property-read mixed $total_lots
 * @property-read \App\Models\User $manager
 * @property-read \App\Models\PortfolioStat|null $stat
 * @method static \Illuminate\Database\Eloquent\Builder|Portfolio newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Portfolio newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Portfolio query()
 */
	class Portfolio extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PortfolioStat
 *
 * @property int $portfolio_id
 * @property int|null $nof_closed
 * @property int|null $nof_winning
 * @property int|null $nof_lossing
 * @property float|null $win_ratio
 * @property float|null $net_pl
 * @property float|null $net_profit
 * @property float|null $gross_profit
 * @property float|null $gross_loss
 * @property float|null $profit_factor
 * @property int|null $weeks
 * @property float|null $worst_trade_dol
 * @property float|null $best_trade_dol
 * @property float|null $worst_trade_pips
 * @property float|null $best_trade_pips
 * @property int|null $nof_working
 * @property float|null $deposit
 * @property float|null $withdrawal
 * @property float|null $net_profit_pips
 * @property int|null $top_nof_closed
 * @property int|null $top_nof_winning
 * @property int|null $top_nof_lossing
 * @property float|null $top_win_ratio
 * @property float|null $top_net_profit
 * @property float|null $top_net_profit_pips
 * @property float|null $avg_win
 * @property float|null $avg_loss
 * @property float|null $avg_win_pips
 * @property float|null $avg_loss_pips
 * @property float|null $total_lots
 * @property float|null $total_commission
 * @property int|null $total_longs
 * @property int|null $total_shorts
 * @property int|null $longs_won
 * @property int|null $shorts_won
 * @property int|null $avg_trade_duration
 * @property int|null $total_days
 * @property float|null $avg_daily_return
 * @property float|null $interest
 * @property float|null $balance
 * @property string|null $currency
 * @property float|null $profit
 * @property float|null $equity
 * @property float|null $credit
 * @property int|null $mem
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $total_months
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $name
 * @property int|null $is_demo
 * @property float|null $monthly_perc
 * @property float|null $gain_perc
 * @property float|null $highest_dol
 * @property float|null $drawdown_perc
 * @property string|null $highest_date
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioStat newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioStat newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PortfolioStat query()
 */
	class PortfolioStat extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $key
 * @property string $title
 * @property string|null $description
 * @property int|null $manager_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $version
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductFile[] $files
 * @property-read int|null $files_count
 * @property-read \App\Models\User|null $manager
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ProductOption[] $opts
 * @property-read int|null $opts_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductFile
 *
 * @property int $id
 * @property string $name
 * @property string $path
 * @property int $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $type
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductFile query()
 */
	class ProductFile extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ProductOption
 *
 * @method static whereProductId($productId)
 * @property int $id
 * @property int|null $product_id
 * @property string $pkey
 * @property string $val
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $enabled
 * @property-read \App\Models\Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ProductOption query()
 * @mixin \Eloquent
 */
	class ProductOption extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Profile
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $location
 * @property string|null $bio
 * @property string|null $twitter_username
 * @property string|null $github_username
 * @property string|null $avatar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Profile query()
 */
	class Profile extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Social
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $provider
 * @property string|null $social_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Social newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Social newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Social query()
 */
	class Social extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Strategy
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $manager_id
 * @property string|null $file_name
 * @property int|null $account_id
 * @property-read \App\Models\Account|null $account
 * @property-read \App\Models\User $manager
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Order|null $ordersCount
 * @property-read \App\Models\AccountStat|null $stats
 * @method static \Illuminate\Database\Eloquent\Builder|Strategy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Strategy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Strategy query()
 */
	class Strategy extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\StrategyOrder
 *
 * @property int $ticket
 * @property int $account_number
 * @property int $status
 * @property string|null $symbol
 * @property int|null $type
 * @property string|null $type_str
 * @property float|null $lots
 * @property float|null $price
 * @property float|null $stoploss
 * @property float|null $takeprofit
 * @property string|null $time_close
 * @property float|null $price_close
 * @property float|null $pl
 * @property string|null $time_open
 * @property string|null $time_last_action
 * @property int|null $magic
 * @property float $pips
 * @property float|null $swap
 * @property int $last_error_code
 * @property string|null $last_error
 * @property string $time_created
 * @property float|null $commission
 * @property string|null $comment
 * @property float|null $sl_pips
 * @property float|null $sl_dol
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $strategy_id
 * @property-read \App\Models\Strategy|null $strategy
 * @method static \Illuminate\Database\Eloquent\Builder|StrategyOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StrategyOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StrategyOrder query()
 */
	class StrategyOrder extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\SystemLog
 *
 * @property int $id
 * @property string|null $message
 * @property string|null $channel
 * @property \App\Enums\SystemLogLevel $level
 * @property string $level_name
 * @property int $unix_time
 * @property \Illuminate\Support\Carbon|null $datetime
 * @property null|array $context
 * @property null|array $extra
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SystemLog query()
 */
	class SystemLog extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Tag
 *
 * @property int $id
 * @property string $title
 * @property string $color
 * @property int $manager_id
 * @property int|null $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $manager
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tag query()
 */
	class Tag extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Target
 *
 * @property int $id
 * @property float|null $max_daily_loss
 * @property float|null $max_loss
 * @property float|null $profit
 * @property int|null $min_trading_days
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $manager_id
 * @property-read \App\Models\User $manager
 * @method static \Illuminate\Database\Eloquent\Builder|Target newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Target newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Target query()
 */
	class Target extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TelegramMessageTemplate
 *
 * @property int $id
 * @property string $telegramable
 * @property int $manager_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $html_template
 * @property string|null $markdown_template
 * @property-read \App\Models\User $manager
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramMessageTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramMessageTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramMessageTemplate query()
 */
	class TelegramMessageTemplate extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TelegramSubscription
 *
 * @property int $id
 * @property string $title
 * @property string $bot_api_token
 * @property int $manager_id
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string $channel_id
 * @property string|null $template_signal_new
 * @property string|null $template_signal_updated
 * @property string|null $template_signal_closed_profit
 * @property string|null $template_signal_closed_lost
 * @property string|null $template_signal_closed_breakeven
 * @property string|null $template_signal_canceled
 * @property string|null $template_overview_week
 * @property string|null $template_overview_month
 * @property string|null $template_overview_quartal
 * @property string|null $template_overview_half_year
 * @property string|null $template_overview_year
 * @property string|null $tag
 * @property-read \App\Models\User $manager
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $sources
 * @property-read int|null $sources_count
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramSubscription query()
 */
	class TelegramSubscription extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\TelegramSubscriptionSourceAccount
 *
 * @property int $id
 * @property int $telegram_subscription_id
 * @property int $account_id
 * @property-read \App\Models\Account|null $accounts
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramSubscriptionSourceAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramSubscriptionSourceAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TelegramSubscriptionSourceAccount query()
 */
	class TelegramSubscriptionSourceAccount extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @OA\Schema (
 *     title="User",
 *     description="User model",
 *     @OA\Xml(
 *         name="User"
 *     ),
 * @OA\Property (
 *     property="id",
 *     title="ID",
 *     description="ID",
 *     format="int64",
 *     example=1
 * ),
 * @OA\Property (
 *     property="email",
 *     title="Email",
 *     description="Email",
 *     format="email",
 *     example="1@1.com"
 * ),
 * @OA\Property (
 *     property="name",
 *     title="User Name",
 *     description="name",
 *     example="Test User"
 * )
 * )
 * @property int $id
 * @property int|null $manager_id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string|null $avatar
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $creator_id
 * @property string|null $email
 * @property string|null $api_token
 * @property string|null $theme
 * @property array|null $trusted_hosts
 * @property int|null $activated
 * @property string|null $signup_ip
 * @property string|null $signup_confirmation_ip
 * @property string|null $signup_sm_ip
 * @property string|null $last_login_at
 * @property string|null $last_login_ip
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $accounts
 * @property-read int|null $accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog[] $authentications
 * @property-read int|null $authentications_count
 * @property-read User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\Dcat\Admin\Models\Domain[] $domains
 * @property-read int|null $domains_count
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $followers
 * @property-read int|null $followers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $follows
 * @property-read int|null $follows_count
 * @property-read mixed $is_admin
 * @property-read bool $is_online
 * @property-read \Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog|null $latestAuthentication
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderEvent[] $order_events
 * @property-read int|null $order_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Performance[] $performances
 * @property-read int|null $performances_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PerformanceWithObjectives[] $performancesWithObjectives
 * @property-read int|null $performances_with_objectives_count
 * @property-read \App\Models\Profile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection|\Dcat\Admin\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\UserSetting|null $setting
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CopierSignalSubscription[] $signal_subscriptions
 * @property-read int|null $signal_subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CopierSignal[] $signals
 * @property-read int|null $signals_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Social[] $social
 * @property-read int|null $social_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 */
	class User extends \Eloquent implements \Tymon\JWTAuth\Contracts\JWTSubject, \Illuminate\Contracts\Auth\CanResetPassword {}
}

namespace App\Models{
/**
 * App\Models\User4Defender
 *
 * @property int $id
 * @property int|null $manager_id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string|null $avatar
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $creator_id
 * @property string|null $email
 * @property string|null $api_token
 * @property string|null $theme
 * @property array|null $trusted_hosts
 * @property int|null $activated
 * @property string|null $signup_ip
 * @property string|null $signup_confirmation_ip
 * @property string|null $signup_sm_ip
 * @property string|null $last_login_at
 * @property string|null $last_login_ip
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $accounts
 * @property-read int|null $accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog[] $authentications
 * @property-read int|null $authentications_count
 * @property-read \App\Models\Campaign|null $campaign
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\Dcat\Admin\Models\Domain[] $domains
 * @property-read int|null $domains_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $followers
 * @property-read int|null $followers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $follows
 * @property-read int|null $follows_count
 * @property-read mixed $is_admin
 * @property-read bool $is_online
 * @property-read \Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog|null $latestAuthentication
 * @property-read \App\Models\Licensing|null $licensing
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderEvent[] $order_events
 * @property-read int|null $order_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Performance[] $performances
 * @property-read int|null $performances_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PerformanceWithObjectives[] $performancesWithObjectives
 * @property-read int|null $performances_with_objectives_count
 * @property-read \App\Models\Profile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection|\Dcat\Admin\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\UserSetting|null $setting
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CopierSignalSubscription[] $signal_subscriptions
 * @property-read int|null $signal_subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CopierSignal[] $signals
 * @property-read int|null $signals_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Social[] $social
 * @property-read int|null $social_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User4Defender newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User4Defender newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User4Defender query()
 */
	class User4Defender extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User4Experts
 *
 * @property int $id
 * @property int|null $manager_id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string|null $avatar
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $creator_id
 * @property string|null $email
 * @property string|null $api_token
 * @property string|null $theme
 * @property array|null $trusted_hosts
 * @property int|null $activated
 * @property string|null $signup_ip
 * @property string|null $signup_confirmation_ip
 * @property string|null $signup_sm_ip
 * @property string|null $last_login_at
 * @property string|null $last_login_ip
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $accounts
 * @property-read int|null $accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog[] $authentications
 * @property-read int|null $authentications_count
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\Dcat\Admin\Models\Domain[] $domains
 * @property-read int|null $domains_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ExpertSubscription[] $expertsubscriptions
 * @property-read int|null $expertsubscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $followers
 * @property-read int|null $followers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $follows
 * @property-read int|null $follows_count
 * @property-read mixed $is_admin
 * @property-read bool $is_online
 * @property-read \Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog|null $latestAuthentication
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderEvent[] $order_events
 * @property-read int|null $order_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Performance[] $performances
 * @property-read int|null $performances_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PerformanceWithObjectives[] $performancesWithObjectives
 * @property-read int|null $performances_with_objectives_count
 * @property-read \App\Models\Profile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection|\Dcat\Admin\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\UserSetting|null $setting
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CopierSignalSubscription[] $signal_subscriptions
 * @property-read int|null $signal_subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CopierSignal[] $signals
 * @property-read int|null $signals_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Social[] $social
 * @property-read int|null $social_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|User4Experts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User4Experts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User4Experts query()
 */
	class User4Experts extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserBrokerServer
 *
 * @property int $broker_server_id
 * @property int $user_id
 * @property int|null $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $id
 * @property int|null $is_default
 * @property string|null $default_group
 * @property-read \App\Models\BrokerServer $broker_server
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\BrokerServerHost[] $server_hosts
 * @property-read int|null $server_hosts_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserBrokerServer default()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBrokerServer enabled()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBrokerServer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBrokerServer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserBrokerServer query()
 */
	class UserBrokerServer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserEmailSubscription
 *
 * @property int $user_id
 * @property int $email_subscription_id
 * @property int|null $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $email
 * @property int $id
 * @property-read \App\Models\User $manager
 * @property-read \App\Models\EmailSubscription $subscription
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserEmailSubscription query()
 */
	class UserEmailSubscription extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserExpertSubscription
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $expert_subscription_id
 * @property string|null $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $manager
 * @property-read \App\Models\ExpertSubscription|null $subscription
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserExpertSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserExpertSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserExpertSubscription query()
 */
	class UserExpertSubscription extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserSetting
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $max_signals
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $max_accounts
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserSetting query()
 */
	class UserSetting extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\UserWithCount
 *
 * @property int $id
 * @property int|null $manager_id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string|null $avatar
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $creator_id
 * @property string|null $email
 * @property string|null $api_token
 * @property string|null $theme
 * @property array|null $trusted_hosts
 * @property int|null $activated
 * @property string|null $signup_ip
 * @property string|null $signup_confirmation_ip
 * @property string|null $signup_sm_ip
 * @property string|null $last_login_at
 * @property string|null $last_login_ip
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Account[] $accounts
 * @property-read int|null $accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog[] $authentications
 * @property-read int|null $authentications_count
 * @property-read \App\Models\User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\Dcat\Admin\Models\Domain[] $domains
 * @property-read int|null $domains_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $followers
 * @property-read int|null $followers_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $follows
 * @property-read int|null $follows_count
 * @property-read mixed $count_accounts
 * @property-read mixed $is_admin
 * @property-read bool $is_online
 * @property-read \Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog|null $latestAuthentication
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderEvent[] $order_events
 * @property-read int|null $order_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Performance[] $performances
 * @property-read int|null $performances_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PerformanceWithObjectives[] $performancesWithObjectives
 * @property-read int|null $performances_with_objectives_count
 * @property-read \App\Models\Profile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection|\Dcat\Admin\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\UserSetting|null $setting
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CopierSignalSubscription[] $signal_subscriptions
 * @property-read int|null $signal_subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CopierSignal[] $signals
 * @property-read int|null $signals_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Social[] $social
 * @property-read int|null $social_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithCount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithCount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserWithCount query()
 */
	class UserWithCount extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\VideoPost
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string|null $video_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $is_public
 * @property int $manager_id
 * @property int|null $type
 * @property-read \App\Models\User $manager
 * @method static \Illuminate\Database\Eloquent\Builder|VideoPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VideoPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VideoPost public()
 * @method static \Illuminate\Database\Eloquent\Builder|VideoPost query()
 */
	class VideoPost extends \Eloquent {}
}

