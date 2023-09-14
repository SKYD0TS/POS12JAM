<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $guarded = ['id'];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public static function getColumns()
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => '', 'className' => 'id', 'className' => 'id hidden-column'],
            ['data' => 'person.first_name', 'name' => 'person.first_name', 'title' => 'Nama Awal', 'className' => 'first_name', 'orderable' => 'false'],
            ['data' => 'person.last_name', 'name' => 'person.last_name', 'title' => 'Nama Akhir', 'className' => 'last_name', 'orderable' => 'false'],
            ['data' => 'person.username', 'name' => 'person.username', 'title' => 'Nama', 'className' => 'username'],
            ['data' => 'person.phone', 'name' => 'person.phone', 'title' => 'Nomor Telepon', 'className' => 'phone'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email', 'className' => 'email'],
            ['data' => 'person.address', 'name' => 'person.address', 'title' => 'Alamat', 'className' => 'address', 'orderable' => 'false'],
            ['data' => 'role.name', 'name' => 'role.name', 'title' => 'Role', 'className' => 'role_name'],
            ['data' => 'role_id', 'name' => 'role_id', 'title' => 'Role', 'className' => 'hidden-column role_id', 'searchable' => 'false'],
        ];
    }
    public static function getRelatedModelsName()
    {
        return ['person', 'role'];
    }

    public static function getFormColumns()
    {
        $r = Person::getFormColumns();
        // array_unshift(
        //     $r,
        //     ['title' => 'Data yang sudah ada?', 'label' => 'Cari orang', 'name' => 'person', 'inputs' => ['first_name', 'last_name', 'person.phone'], 'input_type' => 'NewOrExist'],
        // );
        $r[] = ['label' => 'Role', 'name' => 'role_id', 'type' => 'text', 'input_type' => 'select_dropdown', 'selections' => Role::orderBy('id', 'asc')->get(), 'selection_data' => ['id', 'name']];
        $r[] = ['label' => 'Email', 'name' => 'email', 'type' => 'text', 'input_type' => 'reg'];
        $r[] = ['label' => 'Password', 'name' => 'password', 'type' => 'password', 'input_type' => 'reg'];
        $r[] = ['label' => 'Konfirmasi Password', 'name' => 'password_confirmation', 'type' => 'password', 'input_type' => 'reg'];
        // $r = array_push($r, array('label' => 'Email', 'name' => 'email', 'type' => 'email', 'input_type' => 'reg'));
        // $r[2] = ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'input_type' => 'reg'];
        return $r;
    }

    public static function getRules()
    {
        $r = Person::getRules();
        $r['password'] = 'required|confirmed|min:8';
        $r['password_confirmation'] = 'same:password';
        $r['role_id'] = 'required';

        $r['first_name'] = 'required|min:2';
        $r['last_name'] = 'nullable';
        $r['email'] = 'required|email|unique:employees';
        $r['username'] = 'nullable';
        $r['phone'] = 'required|numeric|min:7';
        $r['address'] = 'required';

        return $r;
    }

    public static function getValidationMessages()
    {
        return [];
    }

    public function isRole($role)
    {
        return $this->role->name == $role;
    }
}
