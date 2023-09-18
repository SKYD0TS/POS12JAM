<?php

use App\Http\Controllers\APIController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnitController;
use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Http\Request;

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

Route::redirect('/', '/dashboard', 301);
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login-check');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/test', function () {
    $c = new Customer();
    $fc = $c->getFormColumns();
    $fr = $c->getRules();
    return view('test.test');
});

Route::post('/test-p', function (Request $r) {
    $modelsData = $r->input('model');
    $attributesData = $r->except('_token', 'model');
    $models = [];
    foreach ($modelsData as $index => $modelName) {
        $attributes = $attributesData[$modelName];

        // Create a new instance of the model dynamically
        $model = $modelName;

        // Populate the model with data from the form
        $models[$model] = ($attributes);

        // Save the model to the database
    }
    foreach ($models as $m) {
        dd($m);
    }
});

Route::middleware(['auth'])->group(function () {
    // Route::middleware(['role:CASHIER'])->group(function () {
    Route::resource('/dashboard/categories', CategoryController::class);
    // });
    Route::resource('/dashboard/products', ProductController::class);
    Route::resource('/dashboard/items', ItemController::class);
    Route::resource('/dashboard/units', UnitController::class);
    Route::resource('/dashboard/suppliers', SupplierController::class);

    Route::resource('/dashboard/people', PersonController::class);
    Route::resource('/dashboard/roles', RoleController::class);
    Route::resource('/dashboard/employees', EmployeeController::class);
    Route::resource('/dashboard/customers', CustomerController::class);
});
// Route::resource('/people', PersonController::class);
