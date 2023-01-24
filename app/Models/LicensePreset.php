<?php

namespace App\Models;

use App\Models\Product;
use App\Models\User;
use App\Models\UserBrokerServer;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class LicensePreset extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'licensing_presets';

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'licensing_preset_products', 'preset_id', 'product_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function brokers()
    {
        return $this->belongsToMany(UserBrokerServer::class, 'licensing_preset_brokers',
            'broker_name', 'broker_name');
    }

}
