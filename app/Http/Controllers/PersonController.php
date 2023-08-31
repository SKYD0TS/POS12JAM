<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Http\Requests\StorePersonRequest;
use App\Http\Requests\UpdatePersonRequest;
use App\Http\Controllers\ResourceController;

class PersonController extends Controller
{
    use ResourceController;
}
