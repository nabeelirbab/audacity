<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="OrderEvent",
 *     description="OrderEvent model",
 *     @OA\Xml(
 *         name="OrderEvent"
 *     ),
 * @OA\Property(
 *     property="id",
 *     title="ID",
 *     description="ID",
 *     format="int64",
 *     example=1
 * )
 * )
*/
class OrderEvent extends Model
{
    protected $table = 'order_events';
    public $timestamps = false;

    protected $fillable = [
        'ticket', 'state', 'watcher_type', 'watcher_id', 'created_at', 'account_id'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'ticket');
    }

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function watcher()
    {
        return $this->morphTo();
    }
}
