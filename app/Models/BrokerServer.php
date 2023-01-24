<?php

namespace App\Models;

use App\Enums\BrokerServerStatus;
use App\Enums\BrokerServerType;
use App\Enums\MetatraderType;
use App\Models\Account;
use App\Models\UserBrokerServer;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BrokerServer extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'broker_servers';

    protected $fillable = [
        'name','gmt_offset', 'group_title', 'pairs','srv_file', 'main_server_host', 'main_server_port', 'type'
    ];

    protected $casts = [
        'type' => MetatraderType::class,
        'status' => BrokerServerStatus::class,
        'api_or_manager' => BrokerServerType::class
    ];

    public function accounts() : HasMany
    {
        return $this->hasMany(Account::class,'broker_server_name', 'name');
    }

    public function user_server() : HasOne
    {
        return $this->hasOne(UserBrokerServer::class);
    }

    public function scopeManager($query) : Builder
    {
        return $query->where('api_or_manager', BrokerServerType::MANAGER);
    }

    public function scopeUnprocessed($query) : Builder
    {
        return $query->whereNull('human_readable');
    }

    public function scopeTypeOf($query, MetatraderType $type) : Builder
    {
        return $query->whereType($type);
    }

    public function scopeApi($query) : Builder
    {
        return $query->where('api_or_manager', BrokerServerType::API);
    }

    public function scopeEnabled($query) : Builder
    {
        return $query->where('enabled', 1);
    }

    public function scopeDefault($query) : Builder
    {
        return $query->where('is_default', 1);
    }

    public function scopeActive($query) : Builder
    {
        return $query->where('status', BrokerServerStatus::ACTIVE);
    }

    public static function deleteByName($name) {
        $brokerServer = self::where('name', $name)->first();

        if($brokerServer) {
            $brokerServer->delete();
        }
    }

}
