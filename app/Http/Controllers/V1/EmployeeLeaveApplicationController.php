<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmployeeLeaveApplication;

class EmployeeLeaveApplicationController extends Controller
{
    /**
     * API of List Employee Leave
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $leave
     */
    public function list(Request $request)
    {
        //validation
        $this->validate($request, [
            'page'          => 'nullable|integer',
            'perPage'       => 'nullable|integer',
            'search'        => 'nullable',
            'sort_field'    => 'nullable',
            'sort_order'    => 'nullable|in:asc,desc',
            'start_date'    => 'nullable',
        ]);

        $query = EmployeeLeaveApplication::query();

        if ($request->search) {
            $query = $query->where('description', 'like', "%$request->search%");
        }

        if ($request->start_date) {
            $query = $query->whereDate('start_date', '>', $request->start_date);
        }

        if ($request->sort_field || $request->sort_order) {
            $query = $query->orderBy($request->sort_field, $request->sort_order);
        }

        /* Pagination */
        $count = $query->count();
        if ($request->page && $request->perPage) {
            $page = $request->page;
            $perPage = $request->perPage;
            $query = $query->skip($perPage * ($page - 1))->take($perPage);
        }

        /* Get records */
        $leave = $query->get();

        $data = [
            'count' => $count,
            'data'  => $leave
        ];

        return ok('Employee leave list', $data);
    }

    /**
     * API of Create Employee Leave
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $leave
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'financial_year_id'     => 'required|string|exists:financial_years,id',
            'user_id'               => 'required|string|exists:users,id',
            'start_date'            => 'required|date_format:Y-m-d',
            'end_date'              => 'required_if:start_date,after_or_equal:start_date|date_format:Y-m-d',
            'description'           => 'nullable|string|max:551',
            'num_days'              => 'required|integer',
            'is_planned'            => 'required|boolean'
        ]);

        $leave = EmployeeLeaveApplication::create($request->only('financial_year_id', 'user_id', 'start_date', 'end_date', 'num_days', 'description', 'is_planned'));
        return ok('Employee leave created successfully', $leave);
    }

    /**
     * API of Update Employee Leave
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'financial_year_id'     => 'required|string|exists:financial_years,id',
            'user_id'               => 'required|string|exists:users,id',
            'start_date'            => 'required|date',
            'end_date'              => 'required_if:start_date,after_or_equal:start_date',
            'description'           => 'nullable|string|max:551',
            'num_days'              => 'required|integer',
            'is_planned'            => 'required|boolean'
        ]);
        $leave = EmployeeLeaveApplication::findOrFail($id);
        $leave->update($request->only('financial_year_id', 'user_id', 'start_date', 'end_date', 'num_days', 'description', 'is_planned'));
        return ok('Employee leave updated successfully', $leave);
    }

    /**
     * API of get perticuler Employee Leave
     *
     * @param  $id
     * @return $leave
     */
    public function get($id)
    {
        $leave = EmployeeLeaveApplication::with(['user'])->findOrFail($id);

        return ok('Employee leave get successfully', $leave);
    }

    /**
     * API of Delete Employee Leave
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     */
    public function delete($id)
    {
        $leave = EmployeeLeaveApplication::findOrFail($id);
        $leave->delete();

        return ok('Employee leave deleted successfully');
    }

    /**
     * API of Approval
     *
     * @param  $id
     */
    public function approval($id, Request $request)
    {
        $this->validate($request, [
            'is_planned'        => 'required|boolean',
            'leave_status'      => 'required|in:IR,IM,IH,A,R',
            'approval_by'       => 'required|string|exists:users,id',
            'pending_with'      => 'required|string|exists:users,id',
        ]);

        $leave = EmployeeLeaveApplication::findOrFail($id);
        $leave->update($request->only('is_planned', 'leave_status', 'approval_by', 'pending_with'));

        return ok('Employee leave updated Successfully');
    }
}
