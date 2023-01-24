<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Activation extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_user_activations';

    protected $guarded = [
        'id',
    ];

    protected $hidden = [
        'user_id',
        'token',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
