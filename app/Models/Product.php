<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany(Item::class, 'product_id', 'id');
    }

    public static function getColumns()
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => '', 'className' => 'id', 'className' => 'id hidden-column'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Nama', 'className' => 'name'],
            ['data' => 'items', 'name' => 'items.item_name', 'title' => 'Barang', 'className' => 'item_item_name', 'column_type' => 'list'],
        ];
    }

    public static function getFormColumns()
    {
        return [
            ['label' => 'Name', 'name' => 'name', 'type' => 'text', 'input_type' => 'reg'],
        ];
    }

    public static function getRules()
    {
        return [
            'name' => 'required|min:2',
        ];
    }

    public static function getRelatedModelsName()
    {
        return ['items'];
    }
}
