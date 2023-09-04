<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class APIController extends Controller
{
    use ResourceController;

    public function orderByRelatedColumn(Builder $query, $relatedModelname, $relatedColumnName, $orderByDirection = 'asc')
    {
        // Get the main table name from the query
        $mainTableName = $query->from;

        $relatedTableName = Str::plural($relatedModelname);
        $relatedModel = ucfirst($relatedModelname);
        $relatedModel = "App\Models\\$relatedModel";
        $relatedModel = new $relatedModel();

        // Construct the query
        $query->join($relatedTableName, $mainTableName . '.' . $relatedModel->getForeignKey(), '=', $relatedTableName . '.' . $query->getModel()->getKeyName())
            ->orderBy("{$relatedTableName}.{$relatedColumnName}", $orderByDirection);
        // ->select("{$mainTableName}.*");

        return $query;
    }

    public function apiHandler(Request $request)
    {
        try {
            $m = $this->model;

            $draw = $request->input('draw');

            if ($draw != null) {
                $relatedTables = $m::getRelatedModel();
                $start = $request->input('start');
                $length = $request->input('length');
                $page = $request->input('start') / $length + 1; // Calculate the current page
                $searchValue = $request->input('search.value');
                $orderColumn = $request->input('order.0.column');
                $orderDirection = $request->input('order.0.dir');
                $columns = $request->input('columns');

                $query = $m::query()->with($relatedTables);

                // if ($columns[$orderColumn]["name"] == "person.username") {
                //     // $m->table; // customers
                //     // $m->related; //person

                //     // $n = $m->orderByRelatedColumn('people', 'username', $orderDirection);

                //     // $n = $m::join('people', 'customers.person_id', '=', 'people.id')
                //     //     ->orderBy('people.username')
                //     //     ->select('customers.*'); // Select other columns from the customers table

                //     $m->orderByRelatedColumn($query, 'person', 'username', $orderDirection);
                // }


                // if (!empty($searchValue)) {
                //     // Get the column names from the table
                //     // $columnsearch = Schema::getColumnListing('your_table_name');

                //     $query->where(function ($query) use ($columns, $searchValue) {
                //         foreach ($columns as $column) {
                //             if ($column['searchable'] == 'true') {
                //                 $query->orWhere($column['name'], 'LIKE', '%' . $searchValue . '%');
                //             }
                //         }
                //     });
                // }

                // $query->orderBy($columns[$orderColumn]["name"], $orderDirection);

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

                if (strpos($columns[$orderColumn]["name"], '.') !== false) {
                    // Handle ordering for related columns
                    $relationColumn = explode('.', $columns[$orderColumn]["name"]);
                    $this->orderByRelatedColumn($query, 'person', 'username', $orderDirection);
                } else {
                    // Handle ordering for non-related columns
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

    public function selectSearch(Request $r)
    {
        return $r;
    }
}
