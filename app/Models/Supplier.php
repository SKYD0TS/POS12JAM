<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    public function getColumns()
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => '', 'className' => 'id hidden-column'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name']
        ];
    }

    public function getRelatedTables()
    {
        return [];
    }
}
