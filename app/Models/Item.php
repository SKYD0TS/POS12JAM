<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(product::class, 'product_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }


    public static function getColumns()
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => '', 'className' => 'id', 'className' => 'id hidden-column'],
            ['data' => 'product.name', 'name' => 'product.name', 'title' => 'Nama Produk', 'className' => 'product_name'],
            ['data' => 'item_code', 'name' => 'item_code', 'title' => 'Kode Barang', 'className' => 'item_code'],
            ['data' => 'item_name', 'name' => 'item_name', 'title' => 'Nama Barang', 'className' => 'item_name'],
            ['data' => 'selling_price', 'name' => 'selling_price', 'title' => 'Harga Jual', 'className' => 'selling_price'],
            ['data' => 'capital_price', 'name' => 'capital_price', 'title' => 'Harga Beli', 'className' => 'capital_price'],
            ['data' => 'unit.name', 'name' => 'unit.name', 'title' => 'Unit', 'className' => 'unit_name'],
            ['data' => 'stock', 'name' => 'stock', 'title' => 'Stock', 'className' => 'stock'],
            ['data' => 'withdrawn', 'name' => 'withdrawn', 'title' => 'Dikembalikan', 'className' => 'withdrawn'],
            ['data' => 'category.name', 'name' => 'category.name', 'title' => 'Kategori', 'className' => 'category_name'],
            ['data' => 'employee.person.username', 'name' => 'employee.person.username', 'title' => 'Pegawai', 'className' => 'employee_name'],
            ['data' => 'product_id', 'name' => 'product_id', 'title' => '', 'className' => 'product_id hidden-column'],
            ['data' => 'unit_id', 'name' => 'unit_id', 'title' => '', 'className' => 'unit_id hidden-column'],
        ];
    }


    public static function getFormColumns()
    {
        $r = Product::getFormColumns();
        array_unshift(
            $r,
            ['title' => 'Produk yang sudah ada?', 'label' => 'Cari produk', 'name' => 'product_id', 'model' => 'product', 'searchColumns' => ['id', 'name'], 'inputs' => ['name'], 'input_type' => 'NewOrExist'],
        );
        $r[] = ['label' => 'Nama produk', 'name' => 'name', 'type' => 'text', 'input_type' => 'reg'];
        $r[] = ['label' => 'Nama barang', 'name' => 'item_name', 'type' => 'text', 'input_type' => 'reg'];
        $r[] = ['label' => 'Harga Jual', 'name' => 'selling_price', 'type' => 'text', 'input_type' => 'reg'];
        $r[] = ['label' => 'Unit', 'name' => 'unit_id', 'input_type' => 'select_dropdown', 'selections' => Unit::orderBy('id', 'desc')->get(), 'selection_data' => ['id', 'name']];
        return $r;
    }
    public static function getRelatedModelsName()
    {
        return ['category', 'unit', 'employee.person', 'product'];
    }

    public static function getRules()
    {
        return [
            'name' => 'required|min:2',
            'item_name' => 'required|min:2',
            'selling_price' => 'required|min:2',
            'capital_price' => 'required|min:2',
            'unit_id' => 'required|',

        ];
    }

    public function getValidationMessages()
    {
        return [];
    }
}
