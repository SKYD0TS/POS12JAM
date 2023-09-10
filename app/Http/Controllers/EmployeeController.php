<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Controllers\ResourceController;
use App\Models\Person;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    use ResourceController;


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->model->getRules(), $this->model->getValidationMessages());
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            try {
                $input = $request->all();
                $request->username ?? $input["username"] = $request->first_name . ' ' . $request->last_name;
                $input["password"] = bcrypt($request->password);
                $person = Person::create($input);
                $person->employee()->create($input);
                // create($request->all());
                return response()->json(['success' => 'Data berhasil ditambahkan']);
            } catch (Exception $e) {
                return 'exception: ' . $e->getMessage() . ' ' . $e->getCode();
            }
        }
    }

    public function update(Request $r, string $id)
    {
        try {
            $row = $this->model->find($id);
            $input = $r->all();
            $role = new Role();
            $adminCount = $role->find(1)->employees->count();

            //?check if current data is admin && has no more admin && input is changing admin
            if ($row->role->name == 'ADMIN' && $adminCount == 1 && $input['role_id'] != $row->role_id) {
                return response()->json(['errors' => ['role_id' => 'Admin terakhir tidak boleh ubah']]);
            }

            $rule = $this->model->getRules();
            $rule['email'] = str_replace('unique:employees', '', $rule['email']);
            //?can be replaced with regex?

            $passUnchanged = false;
            if ($input['password'] == null) {
                $passUnchanged = true;
                $rule['password'] = '';
            }

            $validator = Validator::make($r->all(), $rule, $this->model->getValidationMessages());
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()]);
            }
            $userame = $r->last_name ? $r->first_name . ' ' . $r->last_name :  $r->first_name;

            if ($r->username == null) {
                $input["username"] = $userame;
            } else {
                $input["username"] = $r->username;
            }

            if ($passUnchanged) {
                $input["password"] = $row->password;
            } else {
                $input["password"] = bcrypt($r->password);
            }
            $row->update($input);
            $e = $row;
            $e->person->update($input);
            return response()->json(['success' => 'Data berhasil di ubah']);
        } catch (Exception $e) {
            return 'exception: ' . $e->getMessage() . ' ' . $e->getCode();
        }
    }

    public function destroy(string $id)
    {
        try {
            if ($this->model->find($id) == auth()->user() && $this->model->find($id)->role->id == 1) {
                return response()->json(['success' => 'Pegawai tidak boleh di hapus']);
            }
            $p = new Person();
            $row = $this->model::find($id);
            $p->destroy($this->model::find($id)->person_id);
            return response()->json(['success' => 'Data berhasil di hapus']);
        } catch (Exception $e) {
            return 'exception: ' . $e->getMessage() . ' ' . $e->getCode();
        }
    }
}
