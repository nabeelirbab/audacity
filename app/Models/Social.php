<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_social_logins';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
