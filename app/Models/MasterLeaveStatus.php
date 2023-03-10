<?php

namespace App\Models;

use App\Models\BaseModel;

class MasterLeaveStatus extends BaseModel
{
    protected $table        = 'mst_leave_status';
    protected $primaryKey   = 'code';
    protected $keyType      = 'string';
    public $incrementing    = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable     = [
        'code',
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
