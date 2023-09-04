<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{
    use HasFactory;
    public $dname = '.person_name';
    protected $guarded = ['id'];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }

    public function getColumns()
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => '', 'className' => 'id', 'className' => 'id hidden-column', 'searchable' => 'false'],
            ['data' => 'customer_code', 'name' => 'customer_code', 'title' => 'Kode Customer', 'className' => 'customer_code'],
            ['data' => 'person.username', 'name' => 'person.username', 'title' => 'Nama', 'className' => 'person_name'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email', 'className' => 'email'],
            ['data' => 'person.phone', 'name' => 'person.phone', 'title' => 'Nomor Telepon', 'className' => 'person_phone'],
            ['data' => 'person.address', 'name' => 'person.address', 'title' => 'Alamat', 'className' => 'address', 'orderable' => 'false'],
        ];
    }

    public static function getRelatedModel()
    {
        return ['person'];
    }

    public static function getFormColumns()
    {
        $r = Person::getFormColumns();
        array_unshift(
            $r,
            ['title' => 'Data yang sudah ada?', 'label' => 'Cari orang', 'name' => 'NewOrExist', 'inputs' => ['first_name', 'last_name', 'person.phone',], 'input_type' => 'NewOrExist'],
        );
        $r = array_replace($r, array(3 => ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'input_type' => 'reg']));
        // $r[2] = ['label' => 'Email', 'name' => 'email', 'type' => 'email', 'input_type' => 'reg'];
        return $r;
    }


    public static function getRules()
    {
        return [
            'first_name' => 'required|min:2',
            'last_name' => 'nullable',
            'email' => 'nullable|email',
            'username' => 'nullable',
            'phone' => 'required|numeric|min:7',
        ];
    }

    public function getErrorMessages()
    {
        return [];
    }
}
