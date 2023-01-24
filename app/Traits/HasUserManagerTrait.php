<?php

namespace App\Traits;

use App\Models\User;
use Dcat\Admin\Admin;

trait HasUserManagerTrait
{


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->user_id = Admin::user()->id;
            $query->manager_id = Admin::user()->manager_id;
        });

    }


}
