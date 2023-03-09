<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     *Use Boot Method For Generating uuid & get user id for created by & updated by
     *
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });
        static::updating(function ($model) {
            $model->updated_by = auth()->user()->id;
        });
    }
}
