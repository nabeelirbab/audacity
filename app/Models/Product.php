<?php

namespace App\Models;

use App\Models\ProductFile;
use App\Models\ProductOption;
use App\Models\User;
use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'licensing_products';

    protected $fillable = ['key', 'title', 'description', 'manager_id'];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function opts()
    {
        return $this->hasMany(ProductOption::class);
    }

    public function files()
    {
        return $this->hasMany(ProductFile::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
        });
    }

}
