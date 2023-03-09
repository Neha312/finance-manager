<?php

namespace App\Models;

use App\Http\Traits\UuidTrait;
use App\Models\BaseModel;

class EmployeeLeaveApplication extends BaseModel
{
    use UuidTrait;
    protected $table  = 'emp_leave_appl';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable     = [
        'id',
        'financial_year_id',
        'user_id',
        'start_date',
        'end_date',
        'description',
        'num_days',
        'is_planned',
        'pending_with',
        'leave_status',
        'approval_by',
        'created_at',
        'updated_at',
        'deleted_at',
        'created_by',
        'updated_by',
        'deleted_by'
    ];
    /* Accessors */
    public function getApprovalStatusNameAttribute()
    {
        switch ($this->leave_status) {
            case 'IR':
                return 'in_reporting_to';
            case 'IM':
                return 'in_managed_by';
            case 'IH':
                return 'in_hr';
            case 'A':
                return 'Approved';
            case 'R':
                return 'Rejected';
            default:
                return $this->leave_status;
        }
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
