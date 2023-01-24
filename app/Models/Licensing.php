<?php

namespace App\Models;

use App\Models\Campaign;
use App\Models\User4Defender;
use Illuminate\Database\Eloquent\Model;

class Licensing extends Model
{
    protected $table = 'licensing_users';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User4Defender::class, 'user_id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'user_id');
    }

}
