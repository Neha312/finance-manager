<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MasterLeaveStatus;

class MasterLeaveStatusController extends Controller
{
    /**
     * API of List leave status
     *
     * @param  \Illuminate\Http\Request  $request
     *@return $leaveStatus
     */
    public function list(Request $request)
    {
        $this->validate($request, [
            'page'          => 'nullable|integer',
            'perPage'       => 'nullable|integer',
            'search'        => 'nullable',
            'sort_field'    => 'nullable',
            'sort_order'    => 'nullable|in:asc,desc',
        ]);

        $query = MasterLeaveStatus::query();

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
        $leaveStatus = $query->get();

        $data = [
            'count' => $count,
            'data'  =>  $leaveStatus
        ];

        return ok('Leave status list', $data);
    }

    /**
     * API of Create leave status
     *
     *@param  \Illuminate\Http\Request  $request
     *@return $ $leaveStatus
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'code'           => 'required|string|max:5',
            'name'           => 'required|string|max:64'
        ]);

        $leaveStatus = MasterLeaveStatus::create($request->only('code', 'name'));

        return ok('Leave status created successfully!',  $leaveStatus);
    }

    /**
     * API of Update leave status
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'code'           => 'required|string|max:5',
            'name'           => 'required|string|max:64'
        ]);

        $leaveStatus = MasterLeaveStatus::findOrFail($id);
        $leaveStatus->update($request->only('code', 'name'));

        return ok('Leave status updated successfully!',  $leaveStatus);
    }

    /**
     * API of get perticuler leave status details
     *
     * @param  $id
     * @return  $leaveStatus
     */
    public function get($id)
    {
        $leaveStatus = MasterLeaveStatus::findOrFail($id);

        return ok('Leave status get successfully',  $leaveStatus);
    }

    /**
     * API of Delete leave status
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     */
    public function delete($id)
    {
        MasterLeaveStatus::findOrFail($id)->delete();

        return ok('Leave status deleted successfully');
    }
}
