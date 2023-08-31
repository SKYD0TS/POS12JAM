<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'person_id', 'id');
    }

    public function getColumns()
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => '', 'className' => 'id', 'className' => 'id hidden-column'],
            ['data' => 'person.username', 'name' => 'person.username', 'title' => 'Nama', 'className' => 'person_name'],
            ['data' => 'person.phone', 'name' => 'person.phone', 'title' => 'Nomor Telepon', 'className' => 'person_phone'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email', 'className' => 'email'],
            ['data' => 'role.name', 'name' => 'role.name', 'title' => 'Role', 'className' => 'role_name'],
        ];
    }
    public function getRelatedTables()
    {
        return ['person', 'role'];
    }
}
