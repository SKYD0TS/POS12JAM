<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\ResourceController;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    use ResourceController;

}
