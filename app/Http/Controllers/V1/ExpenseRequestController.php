<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExpenseRequest;

class ExpenseRequestController extends Controller
{
    /**
     * API of List Expense
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $expence
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
            'date'          => 'nullable',
        ]);

        $query = ExpenseRequest::query();

        if ($request->search) {
            $query = $query->where('description', 'like', "%$request->search%");
        }

        if ($request->date) {
            $query = $query->whereDate('date', $request->date);
        }

        if ($request->sort_field || $request->sort_order) {
            $query = $query->orderBy($request->sort_field, $request->sort_order);
        }

        /* Pagination */
        $count = $query->count();
        if ($request->page && $request->perPage) {
            $page    = $request->page;
            $perPage = $request->perPage;
            $query   = $query->skip($perPage * ($page - 1))->take($perPage);
        }

        /* Get records */
        $expense = $query->get();

        $data = [
            'count' => $count,
            'data'  => $expense
        ];

        return ok('Expense list', $data);
    }

    /**
     * API of Create Expense
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $expence
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'financial_year_id'     => 'required|string|exists:financial_years,id',
            'date'                  => 'required|date',
            'amount'                => 'required|numeric',
            'attachment'            => 'nullable|mimes:jpg,bmp,png,pdf',
            'description'           => 'nullable|string|max:551',
            'is_active'             => 'nullable|boolean'
        ]);
        $fileName = str_replace(".", "", (string)microtime(true)) . '.' . $request->attachment->getClientOriginalExtension();
        $request->attachment->storeAs("public/attachment", $fileName);
        $expense = ExpenseRequest::create($request->only('financial_year_id', 'date', 'amount', 'description') + [
            'attachment'   => $fileName
        ]);
        return ok('Expense created successfully', $expense);
    }

    /**
     * API of Update Expense
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'financial_year_id'     => 'required|string|exists:financial_years,id',
            'date'                  => 'required|date',
            'amount'                => 'required|numeric',
            'attachment'            => 'nullable|mimes:jpg,bmp,png,pdf',
            'description'           => 'nullable|string|max:551',
            'is_active'             => 'nullable|boolean'
        ]);
        $expense  = ExpenseRequest::findOrFail($id);
        $fileName = str_replace(".", "", (string)microtime(true)) . '.' . $request->attachment->getClientOriginalExtension();
        $request->attachment->storeAs("public/attachment", $fileName);
        $expense->update($request->only('financial_year_id', 'date', 'amount', 'description') + [
            'attachment'   => $fileName
        ]);
        return ok('Expense updated successfully', $expense);
    }

    /**
     * API of get perticuler Expence details
     *
     * @param  $id
     * @return $expence
     */
    public function get($id)
    {
        $expense = ExpenseRequest::findOrFail($id);

        return ok('Expense get successfully', $expense);
    }

    /**
     * API of Delete Expense
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     */
    public function delete($id)
    {
        $expense = ExpenseRequest::findOrFail($id);
        $expense->delete();

        return ok('Expense deleted successfully');
    }

    /**
     * API of Approval
     *
     * @param  $id
     */
    public function approval($id, Request $request)
    {
        $this->validate($request, [
            'is_approved'       => 'required|boolean',
            'approval_status'   => 'required|in:P,U,PP',
            'approval_by'       => 'required|string|exists:users,id',
        ]);

        $expense = ExpenseRequest::findOrFail($id);
        $expense->update($request->only('is_approved', 'approval_status', 'approval_by'));

        return ok('Expense updated Successfully');
    }
}
