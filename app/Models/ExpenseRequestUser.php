<?php

namespace App\Models;

use App\Http\Traits\UuidTrait;
use App\Models\BaseModel;


class ExpenseRequestUser extends BaseModel
{
    use UuidTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'expence_request_id',
        'user_id'
    ];
}
