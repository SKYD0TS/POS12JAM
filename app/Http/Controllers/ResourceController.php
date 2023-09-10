<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Item;
use App\Models\Person;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\Unit;
use Exception;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\Strings;

trait ResourceController
{
    private $model;
    private $header;

    private $modelClasses = [
        'products' => Product::class,
        'categories' => Category::class,
        'items' => Item::class,
        'units' => Unit::class,
        'roles' => Role::class,
        'employees' => Employee::class,
        'customers' => Customer::class,
        'people' => Person::class,
        'suppliers' => Supplier::class,
    ];

    private function getModelClass($modelName)
    {
        if (array_key_exists($modelName, $this->modelClasses)) {
            $this->model = new $this->modelClasses[$modelName];
        }

        // switch ($modelName) {
        //     case 'products':
        //         return new Product();
        //         break;
        //     case 'categories':
        //         return new Category();
        //         break;
        //     case 'items':
        //         return new Item();
        //         break;
        //     case 'units':
        //         return new Unit();
        //         break;
        //     case 'roles':
        //         return new Role();
        //         break;
        //     case 'employees':
        //         return new Employee();
        //         break;
        //     case 'customers':
        //         return new Customer();
        //         break;
        //     case 'people':
        //         return new Person();
        //         break;
        //     case 'suppliers':
        //         return new Supplier();
        //         break;
        //     default:
        //         // return new Product();
        //         return throw new Exception('not found');
        // }
    }

    public function __construct()
    {
        // dd(explode('/', request()->url()));
        $modelName = strtolower(request()->segment(2));
        $this->header = $modelName;
        $this->getModelClass($modelName);
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $modeldata['header'] = $this->header;
            return view('dashboard.index', [
                'modeldata' => $modeldata,
                "dname" => $this->model->dname,
                'formColumns' => $this->model::getFormColumns(),
                'formRules' => $this->model::getRules()
            ]);
        } catch (Exception $e) {
            return $e->getCode() . $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->model->getRules(), $this->model->getValidationMessages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            try {
                $this->model->create($request->all());
                return response()->json(['success' => 'Data berhasil ditambahkan']);
            } catch (Exception $e) {
                return 'exception: ' . $e->getMessage() . ' ' . $e->getCode();
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($model)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), $this->model->getRules(), $this->model->getValidationMessages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        try {
            $this->model->find($id)->update($request->all());
            return response()->json(['success' => 'Data berhasil di ubah']);
        } catch (Exception $e) {
            return 'exception: ' . $e->getMessage() . ' ' . $e->getCode();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->model::destroy($id);
            return response()->json(['success' => 'Data berhasil di hapus']);
        } catch (Exception $e) {
            return 'exception: ' . $e->getMessage() . ' ' . $e->getCode();
        }
    }
}


// <form class="form-floating" id="form_id" action="{{ $rows[0]['action'] }}"
//                     method="{{ $rows[0]['method'] }}">
//                     @if ($rows[0][0] === 'POST')
//                         @csrf
//                     @endif
//                     <h1>{{ $rows[0]['title'] }}</h1>
//                     @foreach (array_slice($rows, 1) as $col)
//                         @if ($col['input_type'] == 'reg')
//                             <div class="form-floating mb-4">
//                                 <input type={{ $col['type'] }} name={{ $col['name'] }} class="form-control "
//                                     id={{ $col['name'] }} placeholder=" " value={{ old($col['name']) }}>
//                                 <label for={{ $col['name'] }}>{{ $col['label'] }}</label>
//                                 <div class="invalid-feedback"></div>
//                             </div>
//                         @elseif($col['input_type'] == 'textarea')
//                             <div class="form-floating mb-4">
//                                 <textarea class="form-control" name={{ $col['name'] }} placeholder=" " id={{ $col['name'] }} style="height: 100px;">{{ old($col['name']) }}</textarea>
//                                 <label for={{ $col['name'] }}>{{ $col['label'] }}</label>
//                                 <div class="invalid-feedback"></div>
//                             </div>
//                         @elseif($col['input_type'] == 'select_dropdown')
//                             <div class="mb-4">
//                                 <label for={{ $col['label'] }} class="form-label">{{ $col['label'] }}</label>
//                                 <select class="form-select " name={{ $col['name'] }}>
//                                     <option value=''selected>-</option>
//                                     @foreach ($col['selections'] as $option)
//                                         @if (old($col['name']) == $option->id)
//                                             <option value={{ $option->id }} selected>{{ $option->name }}</option>
//                                         @else
//                                             <option value={{ $option->id }}>{{ $option->name }}</option>
//                                         @endif
//                                     @endforeach
//                                 </select>
//                                 <div class="invalid-feedback"></div>
//                             </div>
//                         @endif
//                     @endforeach
//                     <input type="submit" value={{ $rows[0][2] }} id="">
//                 </form>