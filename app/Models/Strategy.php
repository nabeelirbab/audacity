<?php

namespace App\Models;

use App\Models\Account;
use App\Models\AccountStat;

use App\Models\Order;
use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Strategy extends Model
{

    use HasDateTimeFormatter;

    protected $table = 'strategies';

    public function manager() : BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function account() : BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function stats() : HasOneThrough
    {
        return $this->hasOneThrough(AccountStat::class, Account::class, 'id', 'account_number', 'account_id', 'account_number');
    }

    public function orders() : HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function ordersCount() : HasOne
    {
        return $this->hasOne(Order::class)
        ->selectRaw('strategy_id, count(ticket) as aggregate')
        ->groupBy('strategy_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (Strategy $strategy) {
            $strategy->account()->delete();
        });
    }
}
