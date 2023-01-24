<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Models\Account;
use App\Models\AccountStat;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Order extends Model
{
    use HasDateTimeFormatter;

    const TABLE = 'account_orders';
    protected $table = self::TABLE;

    protected $primaryKey = 'ticket';

    protected $casts = [
        'time_close' => 'datetime',
        'time_open' => 'datetime',
        'status' => OrderStatus::class,
        'type' => OrderType::class,
    ];

    protected $fillable = [
        'ticket','account_number', 'status', 'type', 'type_str', 'pl', 'pips', 'stoploss', 'takeprofit',
        'swap', 'commission', 'symbol', 'lots', 'price_close', 'time_close', 'price', 'time_open',
        'magic', 'last_error_code', 'last_error', 'comment'
    ];

    public function account_stat() : HasOneThrough
    {
        return $this->hasOneThrough(AccountStat::class, Account::class, 'account_number', 'account_number', 'account_number', 'account_number');
    }

    public function account() : BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_number', 'account_number');
    }

    public function scopeMarketClosed($query) : Builder
    {
        return $query->market()->closed();
    }

    public function scopeMarketOpen($query) : Builder
    {
        return $query->market()->open();
    }

    public function scopeMarket($query) : Builder
    {
        return $query->whereIn('type', OrderType::marketTypes());
    }

    public function scopeCountableClosed($query) : Builder
    {
        return $query->countable()->closed();
    }

    public function scopeCountable($query) : Builder
    {
        return $query->whereIn('type', OrderType::countableTypes());
    }

    public function scopeClosed($query) : Builder
    {
        return $query->whereStatus(OrderStatus::CLOSED)->whereNotNull('time_close');
    }

    public function scopeOpen($query) : Builder
    {
        return $query->whereStatus(OrderStatus::OPEN)->whereNotNull('time_open');
    }

    public static function getCountErrors($userId) : int
    {
        return self::where('ticket', '<', 0)
            ->whereHas('account', function ($q) use($userId) {
                $q->whereManagerId($userId);
            })
            //->where('last_error_code', '<', 6000)
            ->count();
    }
}
