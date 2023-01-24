<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_profiles';

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'location',
        'bio',
        'twitter_username',
        'github_username',
        'user_profile_bg',
        'avatar',
        'avatar_status',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
