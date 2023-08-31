<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;
use Exception;
use Yajra\DataTables\DataTables;

class APIController extends Controller
{
    use ResourceController;

    public function apiHandler(Request $request)
    {
        try {
            $m = $this->model;

            $draw = $request->input('draw');
            $columns = $m->getColumns();
            $relatedTables = $m->getRelatedTables();
            $formColumns = $m::getFormColumns();

            if ($draw != null) {
                $start = $request->input('start');
                $length = $request->input('length');
                $page = $request->input('start') / $length + 1; // Calculate the current page
                $searchValue = $request->input('search.value');
                $orderColumn = $request->input('order.0.column');
                $orderDirection = $request->input('order.0.dir');

                $query = $m::query();
                $query->with($relatedTables);

                if (!empty($searchValue)) {
                    $query->where(function ($query) use ($columns, $searchValue) {
                        foreach ($columns as $column) {
                            $query->orWhere($column["name"], 'LIKE', '%' . $searchValue . '%');
                        }
                    });
                }
                $query->orderBy($columns[$orderColumn]["name"], $orderDirection);
                $filteredRecords = $query->count();

                $data = $query->offset($start)->limit($length)->get();

                foreach ($data as $row) {
                    $row->actions = '
                    <button class="btn btn-info btn-edit" data-modal_mode="edit">Edit</button>
                    <button class="btn btn-danger btn-delete">Hapus</button>
                    ';
                }

                $totalRecords = $m::count();

                return response()->json([
                    'draw' => $draw,
                    'recordsTotal' => $totalRecords,
                    'recordsFiltered' => $filteredRecords,
                    'length' => $length,
                    'page' => $page,
                    'start' => $start,
                    'data' => $data,
                ]);
            }

            $columns[] = ['data' => 'actions', 'name' => 'actions', 'title' => 'Aksi', 'className' => 'actions', 'orderable' => false];

            //?Regular request
            return response()->json([
                "columns" => $columns,
                'formColumns' => $m::getFormColumns(),
            ]);
        } catch (Exception $e) {
            return $e->getMessage() . $e->getCode();
        }
    }
}
