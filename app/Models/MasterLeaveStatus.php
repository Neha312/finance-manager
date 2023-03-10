<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterLeaveStatus extends Model
{
    protected $table        = 'mst_leave_status';
    public $timestamps      = false;
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
        'name'
    ];
}
