<?php

namespace App\Models;

use App\Http\Traits\UuidTrait;
use App\Models\BaseModel;

class EmployeeLeaveAttachment extends BaseModel
{
    use UuidTrait;
    protected $table  = 'emp_leave_appl_attachments';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable     = [
        'id',
        'leave_appl_id',
        'name',
        'url',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
}
