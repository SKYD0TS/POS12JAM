<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{
    use HasFactory;
    public $dname = '.person_name';

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }

    public function getColumns()
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => '', 'className' => 'id', 'className' => 'id hidden-column'],
            ['data' => 'customer_code', 'name' => 'customer_code', 'title' => 'Kode Customer', 'className' => 'customer_code'],
            ['data' => 'person.username', 'name' => 'person.username', 'title' => 'Nama', 'className' => 'person_name'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email', 'className' => 'email'],
            ['data' => 'person.phone', 'name' => 'person.phone', 'title' => 'Nomor Telepon', 'className' => 'person_phone'],
            ['data' => 'person.address', 'name' => 'person.address', 'title' => 'Alamat', 'className' => 'address'],
        ];
    }

    public function getRelatedTables()
    {
        return ['person'];
    }
}
