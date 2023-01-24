<?php

namespace App\Models;

use App\Enums\SystemLogLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use danielme85\LaravelLogToDB\Models\LogToDbCreateObject;
use Illuminate\Support\Collection;

class SystemLog extends Model
{
    use LogToDbCreateObject;

    protected $table = 'logs';
    protected $connection = 'logs';

    protected $casts = [
        'datetime' => 'datetime',
        'level' => SystemLogLevel::class
    ];

    public $timestamps = false;

    public static function getCountImportant() {
        return self::where('level','>', SystemLogLevel::WARNING)->count();
    }

    public static function getCountForLevel(SystemLogLevel $level) {
        return self::where('level', $level)->count();
    }

    public static function levelCounts(array $levels) : Collection {
        return self::select('level', DB::raw('COUNT(*) as count'))
        ->whereIn('level', $levels)
        ->groupBy('level')
        ->pluck('count', 'level');
    }
}