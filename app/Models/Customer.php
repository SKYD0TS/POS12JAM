<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'id');
    }

    public static function getColumns()
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => '', 'className' => 'id', 'className' => 'id hidden-column', 'searchable' => 'false'],
            ['data' => 'customer_code', 'name' => 'customer_code', 'title' => 'Kode Customer', 'className' => 'customer_code'],
            ['data' => 'person.first_name', 'name' => 'person.first_name', 'title' => 'Nama Awal', 'className' => 'first_name', 'orderable' => 'false'],
            ['data' => 'person.last_name', 'name' => 'person.last_name', 'title' => 'Nama Akhir', 'className' => 'last_name', 'orderable' => 'false'],
            ['data' => 'person.username', 'name' => 'person.username', 'title' => 'Nama', 'className' => 'username column-for-delete-name'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email', 'className' => 'email', 'orderable' => 'false'],
            ['data' => 'person.phone', 'name' => 'person.phone', 'title' => 'Nomor Telepon', 'className' => 'phone', 'orderable' => 'false'],
            ['data' => 'person.address', 'name' => 'person.address', 'title' => 'Alamat', 'className' => 'address', 'orderable' => 'false'],
        ];
    }

    public static function getRelatedModelsName()
    {
        return ['person'];
    }

    public static function getFormColumns()
    {
        $r = Person::getFormColumns();
        // array_unshift(
        //     $r,
        //     ['title' => 'Data yang sudah ada?', 'label' => 'Cari orang', 'name' => 'person', 'inputs' => ['first_name', 'last_name', 'person.phone'], 'input_type' => 'NewOrExist'],
        // );
        array_splice($r, 3, 0, array(['label' => 'Email', 'name' => 'email', 'type' => 'email', 'input_type' => 'reg']));
        return $r;
    }

    public static function getRules()
    {
        $r = Person::getRules();
        return [
            ...$r,
            'first_name' => 'required|min:2',
            'last_name' => 'nullable',
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric|min:7|unique:people',
            'address' => 'nullable',
        ];
    }

    public static function getValidationMessages()
    {
        return [];
    }

    public static function getSelectSearchColumns()
    {
        return ['id', 'customer_code', 'person.username'];
    }

    public static function generateCode()
    {
        return dechex(rand(256, 4095)) . '-' . dechex(strtotime(date('H:i:s')));
    }
}
