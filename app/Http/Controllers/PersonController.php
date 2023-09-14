<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Http\Controllers\ResourceController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;

class PersonController extends Controller
{
    use ResourceController;
    public function store(Request $r)
    {
        //$l = 'l='.substr($txt,strpos($txt, " ")+1);
        //$f = 'f='.substr($txt, 0,strpos($txt, " "));
        $validationMessage = method_exists($this->model, 'getValidationMessages') ? $this->model->getValidationMessages() : [];

        $p = new Person();
        $input = $r->all();
        $userame = $r->last_name ? $r->first_name . ' ' . $r->last_name :  $r->first_name;
        $input["username"] = $r->username ?? $userame;

        $validator = Validator::make($input, $this->model->getRules(), $validationMessage);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            try {
                $p->create($input);
                return response()->json(['success' => 'Data berhasil ditambahkan']);
            } catch (Exception $e) {
                return 'exception: ' . $e->getMessage() . ' ' . $e->getCode();
            }
        }
    }

    public function update(Request $r, string $id)
    {
        $validationMessage = method_exists($this->model, 'getValidationMessages') ? $this->model->getValidationMessages() : [];

        $rule = $this->model->getRules();
        $rule['phone'] = str_replace('unique:people', '', $rule['phone']);
        $input = $r->all();

        $validator = Validator::make($r->all(), $rule, $validationMessage);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        try {
            $userame = $r->last_name ? $r->first_name . ' ' . $r->last_name :  $r->first_name;

            if ($this->model->find($id)->username == $input['username']) {
                $input["username"] = $userame;
            } else {
                $input["username"] = $r->username;
            }

            $this->model->find($id)->update($input);
            return response()->json(['success' => 'Data berhasil di ubah']);
        } catch (Exception $e) {
            return 'exception: ' . $e->getMessage() . ' ' . $e->getCode();
        }
    }

    public function destroy(string $id)
    {
        try {
            if (isset($this->model->find($id)->employee->id) || isset($this->model->find($id)->customer->id)) {
                return response()->json(['success' => 'Data tidak boleh dihapus']);
            } else {
                $this->model::destroy($id);
                return response()->json(['success' => 'Data berhasil di hapus']);
            }
        } catch (Exception $e) {
            return 'exception: ' . $e->getMessage() . ' ' . $e->getCode();
        }
    }
}
