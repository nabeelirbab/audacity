<?php

namespace App\Models;

use App\Enums\YesNoType;
use App\Models\Challenge;
use App\Models\Performance;
use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerformancePlan extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'performance_plans';

    protected $casts = [
        'check_hedging' => YesNoType::class,
        'check_sl' => YesNoType::class
    ];

    public function manager() : BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function challenges() : BelongsToMany
    {
        return $this->belongsToMany(Challenge::class);
    }

    public function performances() : HasMany
    {
        return $this->hasMany(Performance::class);
    }

    public function scopeEnabled()
    {
        $this->whereEnabed(1);
    }

    public function scopePublic()
    {
        $this->where('is_public',1);
    }
}
