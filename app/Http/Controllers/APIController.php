<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;
use App\Models\Employee;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class APIController extends Controller
{
    use ResourceController;


    public function apiHandler(Request $request)
    {
        try {
            $m = $this->model;

            $draw = $request->input('draw');

            if ($draw != null) {
                $relatedTables = $m::getRelatedModelsName();
                $start = $request->input('start');
                $length = $request->input('length');
                $page = $request->input('start') / $length + 1; // Calculate the current page
                $searchValue = $request->input('search.value');
                $orderColumn = $request->input('order.0.column');
                $orderDirection = $request->input('order.0.dir');
                $columns = $request->input('columns');

                $query = $m::query()->with($relatedTables);

                if (!empty($searchValue)) {
                    $query->where(function ($query) use ($columns, $searchValue) {
                        foreach ($columns as $column) {
                            if ($column['searchable'] == 'true') {
                                if (strpos($column['data'], '.') !== false) {
                                    // Handle related columns
                                    $relationColumn = explode('.', $column['data']);
                                    $query->orWhereHas($relationColumn[0], function ($subquery) use ($relationColumn, $searchValue) {
                                        $subquery->where($relationColumn[1], 'LIKE', '%' . $searchValue . '%');
                                    });
                                } else {
                                    // Handle non-related columns
                                    $query->orWhere($column['data'], 'LIKE', '%' . $searchValue . '%');
                                }
                            }
                        }
                    });
                }

                if (strpos($columns[$orderColumn]['name'], '.')) {
                    $query = $this->joinModelsTable($query, $m);
                    $query->orderBy(substr($columns[$orderColumn]["name"], strpos($columns[$orderColumn]['name'], '.')), $orderDirection);
                } else {
                    $query->orderBy($columns[$orderColumn]["name"], $orderDirection);
                }

                $filteredRecords = $query->count();

                $data = $query->offset($start)->limit($length)->get();
                $totalRecords = $m::count();

                return response()->json([
                    'a' => $relatedTables,
                    'p' => explode('.', $columns[$orderColumn]["name"]),
                    'draw' => $draw,
                    'recordsTotal' => $totalRecords,
                    'recordsFiltered' => $filteredRecords,
                    'length' => $length,
                    'page' => $page,
                    'start' => $start,
                    'data' => $data,
                ]);
            }

            $columns = $m->getColumns();
            $columns[] = ['data' => 'actions', 'name' => 'actions', 'title' => 'Aksi', 'className' => 'actions', 'searchable' => false, 'orderable' => false, 'defaultContent' => ''];

            //?Regular request
            return response()->json([
                "columns" => $columns,
                'formColumns' => $m::getFormColumns(),
            ]);
        } catch (Exception $e) {
            return $e->getMessage() . $e->getCode();
        }
    }

    public function selectSearch(Request $r, $model)
    {
        $model = ucfirst($model);
        $model = "\App\Models\\$model";
        $model = new $model();

        $query = $model::query();

        $searchColumns = $r->searchColumns;
        $searchValue = $r->search;

        if (!empty($searchValue)) {
            $query->where(function ($query) use ($searchColumns, $searchValue) {
                foreach ($searchColumns as $column) {
                    if (strpos($column, '.') !== false) {
                        dd('deaddaa');
                        // Handle related columns
                        $relationColumn = explode('.', $column);
                        $query->orWhereHas($relationColumn[0], function ($subquery) use ($relationColumn, $searchValue) {
                            $subquery->where($relationColumn[1], 'LIKE', '%' . $searchValue . '%');
                        });
                    } else {
                        // Handle non-related columns
                        $query->orWhere($column, 'LIKE', '%' . $searchValue . '%');
                    }
                }
            });
        }

        $length = 10;
        $query = $query->select($searchColumns)->limit($length)->get();

        return response()->json([
            'r' => $r->all(),
            'data' =>  $query
        ]);
    }

    public function ModelFromString(String $string)
    {
        $string = ucfirst($string);
        $string = "\App\Models\\$string";
        return new $string();
    }


    public function joinModelsTable($query, $mainModel)
    {
        $mainTable = $mainModel->getTable();
        $relatedModelsName = $mainModel::getRelatedModelsName();

        foreach ($relatedModelsName as $modelName) {
            $rTable = Str::plural($modelName);

            $query =  $query->join($rTable, $rTable .  '.id', '=', $mainTable . '.' . $modelName . '_id');
        }
        return $query;
    }
}
