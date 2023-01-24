<?php

namespace App\Models;

use App\Enums\AccountStatus;
use App\Enums\ApiServerStatus;
use App\Helpers\ApiServerHelper;
use App\Models\Account;
use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class ApiServer extends Model
{

    use HasDateTimeFormatter;

    protected $table = 'api_servers';

    protected $casts = ['api_server_status' => ApiServerStatus::class];

    public function accounts()
    {
        return $this->hasMany(Account::class, 'api_server_ip', 'ip')
            ->whereIn('account_status',
                [
                    AccountStatus::PENDING,
                    AccountStatus::ONLINE,
                    AccountStatus::VERIFYING,
                    AccountStatus::CNN_LOST
                ]);
    }

    public function scopeEnabled() {
        return $this->where('enabled', 1);
    }

    public function manager() {
        return $this->belongsTo(User::class);
    }

    public static function getOneWithFreeSpace($managerId) {
        $servers = ApiServer::enabled()->whereManagerId($managerId)->withCount('accounts')->get(['ip','max_accounts']);

        foreach($servers as $server) {
            /** @var ApiServer $server */
            if($server->max_accounts > $server->accounts_count)
                return $server;
        }

        return null;
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function(ApiServer $api) {
            ApiServerHelper::RefreshHorizonHosts();
        });

        static::deleted(function ($api) {
            ApiServerHelper::RefreshHorizonHosts();
        });
    }

}
