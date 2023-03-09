<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmployeeLeaveAttachment;

class EmployeeLeaveAttachmentController extends Controller
{
    /**
     * API of List leave attachment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $attachment
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
        ]);

        $query = EmployeeLeaveAttachment::query();

        if ($request->search) {
            $query = $query->where('name', 'like', "%$request->search%");
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
        $attachment = $query->get();

        $data = [
            'count' => $count,
            'data'  => $attachment
        ];

        return ok('Employee leave attachment list', $data);
    }

    /**
     * API of Create leave attachment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $attachment
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'leave_appl_id'    => 'required|string|exists:emp_leave_appl,id',
            'name'             => 'nullable|string|max:128',
            'url'              => 'nullable|string|max:255',
        ]);

        $attachment = EmployeeLeaveAttachment::create($request->only('leave_appl_id', 'name', 'url'));
        return ok('Employee leave attachment created successfully', $attachment);
    }

    /**
     * API of Update leave attachment
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'leave_appl_id'    => 'required|string|exists:emp_leave_appl,id',
            'name'             => 'nullable|string|max:128',
            'url'              => 'nullable|url|max:255',
        ]);
        $attachment = EmployeeLeaveAttachment::findOrFail($id);
        $attachment->update($request->only('leave_appl_id', 'name', 'url'));
        return ok('Employee leave attachment updated successfully', $attachment);
    }

    /**
     * API of get perticuler leave attachment
     *
     * @param  $id
     * @return $attachment
     */
    public function get($id)
    {
        $attachment = EmployeeLeaveAttachment::findOrFail($id);

        return ok('Employee leave attachment get successfully', $attachment);
    }

    /**
     * API of Delete leave attachment
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     */
    public function delete($id)
    {
        $attachment = EmployeeLeaveAttachment::findOrFail($id);
        $attachment->delete();

        return ok('Employee leave  attachment deleted successfully');
    }
}
