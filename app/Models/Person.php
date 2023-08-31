<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;

class Person extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->hasOne(Customer::class, 'person_id', 'id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'person_id', 'id');
    }

    public function getColumns()
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => '', 'className' => 'id', 'className' => 'id hidden-column'],
            ['data' => 'username', 'name' => 'username', 'title' => 'Nama', 'className' => 'username'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Nomor Telepon', 'className' => 'phone'],
            ['data' => 'address', 'name' => 'address', 'title' => 'Alamat', 'className' => 'address'],
            ['data' => 'customer.customer_code', 'name' => 'customer.customer_code', 'title' => 'Pelanggan', 'className' => 'customer_customer_code'],
            ['data' => 'employee.role.name', 'name' => 'employee.role.name', 'title' => 'Pegawai', 'className' => 'employee_role_name'],
        ];
    }

    public function getRelatedTables()
    {
        return ['customer', 'employee.role'];
    }
}
