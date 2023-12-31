<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Person extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'person_id', 'id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'person_id', 'id');
    }

    public static function getColumns()
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => '', 'className' => 'id', 'className' => 'id hidden-column', 'searchable' => 'false'],
            ['data' => 'first_name', 'name' => 'first_name', 'title' => 'Nama Awal', 'className' => 'first_name', 'orderable' => 'false'],
            ['data' => 'last_name', 'name' => 'last_name', 'title' => 'Nama Akhir', 'className' => 'last_name', 'orderable' => 'false'],
            ['data' => 'username', 'name' => 'username', 'title' => 'Nama', 'className' => 'username'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Nomor Telepon', 'className' => 'phone'],
            ['data' => 'address', 'name' => 'address', 'title' => 'Alamat', 'className' => 'address', 'orderable' => 'false'],
            ['data' => 'customer.customer_code', 'name' => 'customer.customer_code', 'title' => 'Pelanggan', 'className' => 'customer_customer_code'],
            ['data' => 'employee.role.name', 'name' => 'employee.role.name', 'title' => 'Pegawai', 'className' => 'employee_role_name'],
        ];
    }

    public static function getRelatedModelsName()
    {
        return ['customer', 'employee.role'];
    }

    public static function getFormColumns()
    {
        return [
            ['label' => 'Nama awal', 'name' => 'first_name', 'type' => 'text', 'input_type' => 'reg'],
            ['label' => 'Nama akhir', 'name' => 'last_name', 'type' => 'text', 'input_type' => 'reg'],
            ['label' => 'Username', 'name' => 'username', 'type' => 'text', 'input_type' => 'reg'],
            ['label' => 'Nomor Telepon', 'name' => 'phone', 'type' => 'tel', 'input_type' => 'reg'],
            ['label' => 'Alamat', 'name' => 'address', 'type' => 'text', 'input_type' => 'textarea'],
        ];
    }


    public static function getRules()
    {
        return [
            'first_name' => 'required|min:2',
            'last_name' => 'nullable',
            'username' => 'nullable',
            'phone' => 'numeric|min:7',
            'address' => 'nullable'
        ];
    }

    public static function getErrorMessages()
    {
        return [];
    }

    public static function getSelectSearchColumns()
    {
        return ['id', 'username', 'phone'];
    }
}
