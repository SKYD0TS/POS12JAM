<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function employees()
    {
        return $this->hasMany(Employee::class, 'role_id', 'id');
    }

    public function getColumns()
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => '', 'className' => 'id hidden-column'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'employees', 'name' => 'employees.person.username', 'title' => 'Name', 'column_type' => 'list'],
        ];
    }

    public function getRelatedTables()
    {
        return ['employees.person'];
    }
}
