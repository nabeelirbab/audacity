<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Challenge;
use App\Models\Performance;
use App\Models\PerformanceWithObjectives;
use App\Models\Profile;
use App\Models\Social;
use App\Traits\CanFollowHasFolllowers;
use Creativeorange\Gravatar\Facades\Gravatar;
use Dcat\Admin\Admin;
use Dcat\Admin\Models\Administrator;
use Exception;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
//use IvanoMatteo\LaravelDeviceTracking\Traits\UseDevices;
use Laravel\Sanctum\HasApiTokens;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @OA\Schema(
 *     title="User",
 *     description="User model",
 *     @OA\Xml(
 *         name="User"
 *     ),
 * @OA\Property(
 *     property="id",
 *     title="ID",
 *     description="ID",
 *     format="int64",
 *     example=1
 * ),
 * @OA\Property(
 *     property="email",
 *     title="Email",
 *     description="Email",
 *     format="email",
 *     example="1@1.com"
 * ),
 * @OA\Property(
 *     property="name",
 *     title="User Name",
 *     description="name",
 *     example="Test User"
 * )
 * )
 */
class User extends Administrator implements JWTSubject, CanResetPasswordContract
{
    use Notifiable;
    use HasRelationships;
    use CanResetPassword;
    use AuthenticationLoggable;
    use CanFollowHasFolllowers;
    use HasApiTokens;
  //  use UseDevices;

    protected $connection = 'mysql';

    protected $casts = [
        'meta' => 'array',
        'trusted_hosts' => 'array'
    ];

    public function getAvatar()
    {
        try {
            if(Gravatar::exists($this->email) )
                return Gravatar::get($this->email);
        } catch(Exception $ex) {
            Log::warning('gravatar failed to load avatar. ', [
                'email' => $this->email,
                'ex' => $ex
            ]);
        }

        return parent::getAvatar();
    }

    protected function getDefaultRole() : string
    {
        return config('registration.role', 'user');
    }

    protected $fillable = [
        'name', 'username', 'email', 'password', 'creator_id', 'manager_id', 'avatar', 'api_token', 'signup_ip_address', 'activated'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];


    public function order_events() : MorphMany
    {
        return $this->morphMany(OrderEvent::class, 'watcher');
    }

    public function routeNotificationForUserManagerNotifier($notifiable) : int
    {
        return $this->manager_id;
    }

    public function routeNotificationForManagerMailer($notifiable) : string
    {
        return $this->email;
    }

    public function getIsOnlineAttribute(): bool
    {
        $users = Cache::get('online-users');

        if (!$users) return false;

        $onlineUsers = collect($users);

        $onlineUser = $onlineUsers->firstWhere('id', $this->id);

        return $onlineUser && ($onlineUser['last_activity_at'] >= now()->subMinutes(config('funded.active_minites')));
    }

    public function getIsAdminAttribute()
    {
        return $this->isAdministrator();
    }

    public function creator() : BelongsTo
    {
        return $this->belongsTo(self::class, 'creator_id');
    }

    public function accounts() : HasMany
    {
        return $this->hasMany(Account::class, 'user_id', 'id');
    }

    public function getJWTIdentifier() : string
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function social() : HasMany
    {
        return $this->hasMany(Social::class);
    }

    public function profile() : HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function challenges() : HasMany
    {
        return $this->hasMany(Challenge::class, 'user_id', 'id');
    }

    public function performances() : HasMany
    {
        return $this->hasMany(Performance::class, 'user_id', 'id');
    }

    public function performancesWithObjectives() : HasMany
    {
        return $this->hasMany(PerformanceWithObjectives::class, 'user_id', 'id');
    }

    public static function GetManagerId() : int
    {
        if (Admin::user()->isRole('assistant')) {
            return Admin::user()->manager_id;
        }

        return Admin::user()->id;
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function (User $user) {

            $roleModel = config('admin.database.roles_model');
            $role = $roleModel::whereSlug($user->getDefaultRole())->first();

            if ($role) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }

            $settings = new UserSetting;

            $settings->max_accounts = config('funded.max_accounts_def');

            $user->setting()->save($settings);
        });

        static::deleted(function(User $user) {
            $user->accounts()->delete();
        });
    }

    public function signals() : BelongsToMany
    {
        return $this->belongsToMany(
            CopierSignal::class,
            CopierSignalSubscription::class,
            'user_id',
            'signal_id'
        );
    }

    public function signal_subscriptions() : HasMany
    {
        return $this->hasMany(CopierSignalSubscription::class, 'user_id');
    }

    public function setting() : HasOne
    {
        return $this->hasOne(UserSetting::class, 'user_id', 'id');
    }

}
