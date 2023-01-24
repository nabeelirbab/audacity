<?php

namespace App\Models;

use App\Enums\AccountStatus;
use App\Enums\AccountType;
use App\Enums\CopierType;
use App\Enums\MetatraderType;
use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\YesNoType;
use App\Helpers\MT4TerminalApi;
use App\Helpers\MT5TerminalApi;
use App\Jobs\ProcessPendingAccount;
use App\Models\AccountStat;
use App\Models\ApiServer;
use App\Models\BrokerServer;
use App\Models\Order;
use App\Models\Performance;
use App\Models\PerformanceWithObjectives;
use App\Models\User;
use App\Models\UserBrokerServer;
use App\Notifications\AccountStatusInvalidNotification;
use App\Notifications\AccountStatusOrfant;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * @OA\Schema(
 *     title="Account",
 *     description="Account model",
 *     @OA\Xml(
 *         name="Account"
 *     ),
 * @OA\Property(
 *     property="id",
 *     title="ID",
 *     description="ID",
 *     format="int64",
 *     example=1
 * ),
 * @OA\Property(
 *     property="account_number",
 *     title="Account Number",
 *     description="Account Number",
 *     example="12456"
 * ),
 * @OA\Property(
 *     property="title",
 *     title="Account Title",
 *     description="Account Title",
 *     example="Test Account"
 * ),
 * @OA\Property(
 *     property="broker_server_name",
 *     title="Broker Server Name",
 *     description="Broker Server Name",
 *     example="Alpari-Demo"
 * )
 * )
*/
class Account extends Model
{
    use HasDateTimeFormatter;
    use SoftDeletes;

    protected $table = 'accounts';
    protected $casts = [
        'account_status' => AccountStatus::class,
        'is_live' => AccountType::class,
        'trade_allowed' => YesNoType::class,
        'symbol_trade_allowed' => YesNoType::class,
        'copier_type' => CopierType::class
    ];

    protected $fillable = [
        'account_number', 'broker_server_name', 'password', 'api_server_ip', 'creator_id',
        'user_id', 'manager_id', 'account_status', 'title', 'copier_type', 'collect_equity'
    ];

    //protected $hidden = ['password'];

    public function stat() : HasOne
    {
        return $this->hasOne(AccountStat::class, 'account_number', 'account_number');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function manager() : BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function api_server() : BelongsTo
    {
        return $this->belongsTo(ApiServer::class, 'api_server_ip', 'ip');
    }

    public function broker_server(): BelongsTo
    {
        return $this->belongsTo(BrokerServer::class, 'broker_server_name', 'name');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'account_number', 'account_number');
    }

    public function performance() : HasOne
    {
        return $this->hasOne(Performance::class);
    }

    public function performancesWithObjectives() : HasOne
    {
        return $this->hasOne(PerformanceWithObjectives::class);
    }

    public function liveorders() : HasMany
    {
        return $this->hasMany(Order::class, 'account_number', 'account_number')
            ->whereIn('status',[OrderStatus::OPEN]);
    }

    public function closedorders() : HasMany
    {
        return $this->hasMany(Order::class, 'account_number', 'account_number')
            ->where('status',OrderStatus::CLOSED);
    }

    public function ordersClosedMarket() : HasMany
    {
        return $this->closedorders()
            ->whereIn('type',[OrderType::BUY, OrderType::SELL]);
    }

    public function scopeSenders($query) {
        return $query->where('copier_type', CopierType::SENDER);
    }

    public function tags() : MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables');
    }

    public function asSender() : BelongsToMany
    {
        return $this->belongsToMany(
            CopierSignal::class,
            CopierSignalSender::class,
            'account_id',
            'signal_id'
        );
    }

    public function asFollower() : BelongsToMany
    {
        return $this->belongsToMany(
            CopierSignal::class,
            CopierSignalFollower::class,
            'account_id',
            'signal_id'
        )
        ->withPivot(['copier_enabled']);
    }

    public function scopeFollower($query) {
        return $query->where('copier_type', CopierType::FOLLOWER);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function(Account $account) {
            $stat = new AccountStat();
            $account->stat()->save($stat);
        });

        static::creating(function ($query) {
            $query->jfx_mode = config('funded.jfx_mode');

            $query->account_status = AccountStatus::PENDING;
            if(empty($query->title))
                $query->title = $query->account_number;
        });

        static::softDeleted(function ($account) {
            $account->processing = 0;
            $account->save();
        });

        static::forceDeleted(function ($account) {
            Order::where('account_number', $account->account_number)->delete();
        });
    }

    public function getConnectingQueueName() : string
    {
        if(config('funded.api_single_thread')) {
            return 'accounts';
        }

        return $this->api_server_ip;
    }

    public function getRemovingQueueName() : string
    {
        return 'removing';
    }

    public static function monthlyGainChartData($accountNumber, $from, $to) {

        $orders = Order
            ::marketClosed()
            ->where('account_number',$accountNumber)
            ->when(!is_null($from), function($query) use($from) {
                $query->where('time_close', '>=', $from);
            })
            ->when(!is_null($to), function($query) use($to) {
                $query->where('time_close', '<=', $to);
            })
            ->groupBy('month')
            ->orderBy('month')
            ->selectRaw("date_format(time_close, '%Y-%m-01') as `month`, sum(pl) as profit, ".
            " ( select sum(pl) from account_orders where account_number=$accountNumber and time_close < `month` ) as balance, ".
            " ( SELECT SUM(pl) FROM account_orders WHERE account_number=$accountNumber AND `type`=6 and date_format(time_close, '%Y-%m-01') = `month` ) AS deposit")
            ->get(['month','profit','balance','deposit']);

        $data = collect($orders)->mapWithKeys(function($order) {

            /** @var mixed $order */
            if(is_null($order->balance))
                $p = $order->deposit;
            else
                $p = $order->balance;

            $month = Str::replaceLast('-01','', $order->month);

            if($p)
                return [$month => number_format($order->profit/$p*100, 2 )];

            return [$month => 0];
        });

        return $data;
    }

    public static function instrumentsChartData($accountNumber, $from = null, $to = null) {

        return Order
            ::marketClosed()
            ->where('account_number', $accountNumber)
            ->when(!is_null($from), function($query) use($from) {
                $query->where('time_close', '>=', $from);
            })
            ->when(!is_null($to), function($query) use($to) {
                $query->where('time_close', '<=', $to);
            })
            ->groupBy('symbol')
            ->selectRaw('count(ticket) as cnt, symbol')
            ->pluck('cnt', 'symbol');
    }

    public static function dailyWinLossChartData($accountNumber) : Collection {
        $orders = Order
            ::marketClosed()
            ->where('account_number', $accountNumber)
            ->orderBy('day')
            ->groupBy('day')
            ->selectRaw('WEEKDAY(`time_close`) AS `day`, '.
            "(SELECT COUNT(ticket) FROM account_orders WHERE WEEKDAY(`time_close`) = `day` AND account_number=$accountNumber AND (`type` = 0 OR `type` = 1) AND `status`=3 AND time_close IS NOT NULL AND pl > 0 ) AS winners,".
            "(SELECT COUNT(ticket) FROM account_orders WHERE WEEKDAY(`time_close`) = `day` AND account_number=$accountNumber AND (`type` = 0 OR `type` = 1) AND `status`=3 AND time_close IS NOT NULL AND pl <= 0 ) AS losers")
            ->get(['day','winners','losers']);

        $data = $orders->mapWithKeys(function($order) {
            return [$order->day => ['winners' => $order->winners, 'losers' => $order->losers]];
        });

        return $data;
    }

    public static function hourlyWinLossChartData($accountNumber) : Collection {
        $orders = Order
            ::marketClosed()
            ->where('account_number', $accountNumber)
            ->groupBy('hour')
            ->selectRaw('HOUR(`time_close`) AS `hour`, '.
            "(SELECT COUNT(ticket) FROM account_orders WHERE HOUR(`time_close`) = `hour` AND account_number=$accountNumber AND (`type` = 0 OR `type` = 1) AND `status`=3 AND time_close IS NOT NULL AND pl > 0 ) AS winners,".
            "(SELECT COUNT(ticket) FROM account_orders WHERE HOUR(`time_close`) = `hour` AND account_number=$accountNumber AND (`type` = 0 OR `type` = 1) AND `status`=3 AND time_close IS NOT NULL AND pl <= 0 ) AS losers")
            ->get(['hour','winners','losers']);

        $data = $orders->mapWithKeys(function($order) {
            return [$order->hour => ['winners' => $order->winners, 'losers' => $order->losers]];
        });

        for($i = 0; $i < 24; $i++) {
            if(!isset($data[$i])) {
                $data[$i] = ['winners' => 0, 'losers' => 0];
            }
        }

        return $data->sortKeys();
    }

    public static function growthChartData($accountNumber, $from = null, $to = null) {
        $orders = Order
            ::closed()
            ->whereIn('type',[OrderType::BUY, OrderType::SELL, OrderType::BALANCE])
            ->where('account_number', $accountNumber)
            ->when(!is_null($from), function($query) use($from) {
                $query->where('time_close', '>=', $from);
            })
            ->when(!is_null($to), function($query) use($to) {
                $query->where('time_close', '<=', $to);
            })
            ->orderBy('time_close', 'ASC')
            ->get(['time_close','pl','type']);

        $dataPL = array();
        $sum = 0;
        $deposit = 0;
        foreach ($orders as $order) {

            if($order->type == OrderType::BALANCE) {
                $deposit += $order->pl;
                continue;
            }

            if($deposit != 0) {
                $sum += $order->pl;
                $time = strtotime($order->time_close) * 1000;
                $dataPL[] = [(int)$time,number_format($sum/$deposit*100, 2, '.', '')];
            }
        }

        return $dataPL;
    }

    public static function profitChartData($accountNumber, $from = null, $to = null) {
        $items = Order
            ::marketClosed()
            ->where('account_number', $accountNumber)
            ->when(!is_null($from), function($query) use($from) {
                $query->where('time_close', '>=', $from);
            })
            ->when(!is_null($to), function($query) use($to) {
                $query->where('time_close', '<=', $to);
            })
            ->orderBy('time_close', 'ASC')
            ->get(['time_close','pl']);

        $dataPL = array();
        $sum = 0;
        foreach ($items as $item) {
            $sum += $item->pl;
            $time = strtotime($item->time_close) * 1000;
            $dataPL[] = [(int)$time, number_format($sum, 2, '.', '')];
        }

        return $dataPL;
    }

    public static function balanceChartData($accountNumber, $from = null, $to = null) {
        $items = Order
            ::whereIn('type',[OrderType::BUY, OrderType::SELL, OrderType::BALANCE])
            ->where('status', OrderStatus::CLOSED)
            ->where('account_number', $accountNumber)
            ->when(!is_null($from), function($query) use($from) {
                $query->where('time_close', '>=', $from);
            })
            ->when(!is_null($to), function($query) use($to) {
                $query->where('time_close', '<=', $to);
            })
            ->orderBy('time_close', 'ASC')
            ->get(['time_close','pl']);

        $dataPL = array();
        $sum = 0;
        foreach ($items as $item) {
            $sum += $item->pl;
            $time = strtotime($item->time_close) * 1000;
            $dataPL[] = [(int)$time, number_format($sum, 2, '.', '')];
        }

        return $dataPL;
    }

    public function restart() {
        switch($this->account_status) {
            case AccountStatus::REMOVING:
                return;

            case AccountStatus::PENDING:
                $this->processing = false;
                $this->save();
                return;

            case AccountStatus::ONLINE:
            case AccountStatus::CNN_LOST:
            case AccountStatus::VERIFYING:
                $this->account_status = AccountStatus::PENDING;
                $this->processing = true;
                $this->save();

                ProcessPendingAccount::dispatch($this->id)->onQueue($this->getConnectingQueueName());
                return;

            case AccountStatus::INVALID:
            case AccountStatus::SUSPEND:
                $this->wait_restarting = true;
                $this->save();
                return;

            case AccountStatus::NONE:
            case AccountStatus::INVALID_STOPPED:
            case AccountStatus::SUSPEND_STOPPED:
            case AccountStatus::OFFLINE:
            case AccountStatus::ORFANT:
                $this->account_status = AccountStatus::PENDING;
                $this->processing = false;
                $this->save();
                return;
        }
    }

    public static function hasOrfant($managerId) {
        return self::whereManagerId($managerId)->where('account_status', AccountStatus::ORFANT)->exists();
    }

    public function markOrfant() {
        $this->account_status = AccountStatus::ORFANT;
        $this->processing = false;
        $this->api_server_ip = null;

        $this->save();

        $manager = $this->manager()->first();
        $manager->notify(new AccountStatusOrfant($this));
    }

    public function markInvalid($lastError) {
        $this->account_status = AccountStatus::INVALID;
        $this->last_error = $lastError;
        $this->save();

        if(config('funded.notify_account_invalid')) {
            $manager = $this->manager()->first();
            $manager->notify(new AccountStatusInvalidNotification($this, $manager->id));

            $user = $this->user()->first();
            $user->notify(new AccountStatusInvalidNotification($this, $user->manager_id));
        }

        // if(config('admin.send_account_invalid_email')) {
        //     try {
        //         ManagerMailer::handle(
        //             $account->user->email,
        //             new AccountInvalidMail(
        //                 $account->account_number,
        //                 $account->broker_server_name,
        //                 $account->manager_id)
        //         );
        //     } catch (\Exception $e) {
        //         Log::exception($e);
        //     }
        // }

    }

    public function move($ip) {
        $this->old_api_server_ip = $this->api_server_ip;
        $this->api_server_ip = $ip;
        $this->processing = true;
        $this->account_status = AccountStatus::PENDING;
        $this->save();

        ProcessPendingAccount::dispatch($this->id)->onQueue($this->getConnectingQueueName());
    }

    public function followers() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'account_followers')
                    ->withTimestamps();
    }

    public function follow($userId) : Account
    {
        $this->followers()->attach($userId);
        return $this;
    }

    public function unfollow($userId) : Account
    {
        $this->followers()->detach($userId);
        return $this;
    }

    public static function CreateDemo( int $managerId, int $userId, UserBrokerServer $broker,
        float $balance, int $leverage,
        string $email = null, string $name = null) : Account
    {

        if(is_null($email)) {
            $user = User::find($userId);

            $email = $user->email;
            $name = $user->name;
        }

        $type = $broker->broker_server->type;
        $login = '';
        $password = '';

        if($type == MetatraderType::V4) {
            $json = MT4TerminalApi::CreateAccount($broker->broker_server->main_server_host,
                $broker->broker_server->main_server_port, $name, $email, $balance, $leverage,
                $broker->default_group, $broker->broker_server->name,
                'Country', 'City', 'State', 'Zip', 'Address', 'Phone');
            $mt4Account = json_decode($json);

            if(is_null($mt4Account) || !isset($mt4Account->user)) {
                Log::critical('failed to create account', [ 'json' => $json, 'mt4Account' => $mt4Account]);
                throw new \Exception('Failed to create mt4 account via terminal api');
            }

            $login = $mt4Account->user;
            $password = $mt4Account->password;
        }

        if($type == MetatraderType::V5) {
            $json = MT5TerminalApi::CreateAccount( $name, $email, $balance, $leverage,
                $broker->default_group, $broker->broker_server->name,
                'Country', 'City', 'State', 'Zip', 'Address', 'Phone');
            $mt5Account = json_decode($json);

            if(is_null($mt5Account) || !isset($mt5Account->login)) {
                throw new \Exception('Failed to create mt5 account via terminal api');
            }

            $login = $mt5Account->login;
            $password = $mt5Account->password;
        }

        $account = new Account();
        $account->manager_id = $managerId;
        $account->user_id = $userId;
        $account->creator_id = $managerId;
        $account->broker_server_name = $broker->broker_server->name;
        $account->account_number = $login;
        $account->password = $password;
        $account->copier_type = CopierType::SENDER;

        $account->save();

        return $account;
    }

}
