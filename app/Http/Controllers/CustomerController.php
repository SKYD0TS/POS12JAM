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
        //$l = 'l='.substr($txt,strpos($txt, " ")+1);
        //$f = 'f='.substr($txt, 0,strpos($txt, " "));
        $p = new Person();
        $input = $r->all();
        $userame = $r->last_name ? $r->first_name . ' ' . $r->last_name :  $r->first_name;
        $input["username"] = $r->username ?? $userame;

        $input["customer_code"] = Customer::generateCode();
        $validator = Validator::make($input, $this->model->getRules(), $this->model->getValidationMessages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            try {
                $p = $p->create($input);
                $p->customer()->create($input);
                return response()->json(['success' => 'Data berhasil ditambahkan']);
            } catch (Exception $e) {
                return 'exception: ' . $e->getMessage() . ' ' . $e->getCode();
            }
        }
    }

    public function update(Request $r, string $id)
    {
        $rule = $this->model->getRules();
        $rule['phone'] = str_replace('unique:people', '', $rule['phone']);
        $input = $r->all();

        $validator = Validator::make($r->all(), $rule, $this->model->getValidationMessages());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        try {
            $userame = $r->last_name ? $r->first_name . ' ' . $r->last_name :  $r->first_name;

            if ($this->model->find($id)->person->username == $input['username']) {
                $input["username"] = $userame;
            } else {
                $input["username"] = $r->username;
            }

            $this->model->find($id)->update($input);
            $c = $this->model->find($id);
            $c->person->update($input);
            return response()->json(['success' => 'Data berhasil di ubah']);
        } catch (Exception $e) {
            return 'exception: ' . $e->getMessage() . ' ' . $e->getCode();
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->model::destroy($id);
            $this->model->person::destroy($id);
            return response()->json(['success' => 'Data berhasil di hapus']);
        } catch (Exception $e) {
            return 'exception: ' . $e->getMessage() . ' ' . $e->getCode();
        }
    }
}
