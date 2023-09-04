<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Controllers\ResourceController;
use App\Models\Person;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;

class CustomerController extends Controller
{
    use ResourceController;
    public function store(Request $r)
    {
        $c = new Customer();
        $p = new Person();
        $validator = Validator::make($r->all(), $c->getRules(), $c->getErrorMessages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            try {
                // $p->create($r->all());
                // $c->create($r->all());
                return response()->json(['success' => 'Data berhasil ditambahkan']);
            } catch (Exception $e) {
                return 'exception: ' . $e->getMessage() . ' ' . $e->getCode();
            }
        }
    }
}
