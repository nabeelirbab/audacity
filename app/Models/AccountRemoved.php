<?php

namespace App\Models;

use App\Enums\AccountStatus;
use App\Enums\AccountType;
use App\Enums\CopierType;
use App\Enums\YesNoType;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AccountRemoved extends Model
{
    protected $table = 'accounts_removed';

    protected $fillable = [
        'account_number',
        'password',
        'broker_server_name',
        'manager_id',
        'creator_id',
        'trade_allowed',
        'symbol_trade_allowed',
        'last_error',
        'is_live',
        'copier_type',
        'api_server_ip'
    ];

    protected $casts = [
        'account_status' => AccountStatus::class,
        'is_live' => AccountType::class,
        'trade_allowed' => YesNoType::class,
        'symbol_trade_allowed' => YesNoType::class,
        'copier_type' => CopierType::class
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

}
