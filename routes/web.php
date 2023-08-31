<?php

use App\Http\Controllers\APIController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnitController;
use App\Models\Employee;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::resource('/dashboard/products', ProductController::class);
Route::resource('/dashboard/items', ItemController::class);
Route::resource('/dashboard/categories', CategoryController::class);
Route::resource('/dashboard/units', UnitController::class);
Route::resource('/dashboard/suppliers', SupplierController::class);

Route::resource('/dashboard/people', PersonController::class);
Route::resource('/dashboard/roles', RoleController::class);
Route::resource('/dashboard/employees', EmployeeController::class);
Route::resource('/dashboard/customers', CustomerController::class);
// Route::resource('/people', PersonController::class);
