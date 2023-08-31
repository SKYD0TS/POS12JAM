<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Item;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'category_id', 'id');
    }

    public static function getColumns()
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => '', 'className' => 'id hidden-column'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name', 'className' => 'name']
        ];
    }

    public static function getRelatedTables()
    {
        return [];
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

    public function getErrorMessages()
    {
        return [];
    }
}
