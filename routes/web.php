<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\AsignarController;
use App\Http\Livewire\CashoutController;
use App\Http\Livewire\CategoriesController;
use App\Http\Livewire\ProductsController;
use App\Http\Livewire\ProviderController;
use App\Http\Livewire\PosController;
use App\Http\Livewire\ClientsController;
use App\Http\Livewire\CurrenciesController;
use App\Http\Livewire\RolesController;
use App\Http\Livewire\PermisosController;
use App\Http\Livewire\UsersController;
use App\Http\Livewire\ReportsController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('asignar', AsignarController::class);
Route::get('categories', CategoriesController::class);
Route::get('products', ProductsController::class);
Route::get('currencies', CurrenciesController::class);
Route::get('pos', PosController::class);
Route::get('clients', ClientsController::class);
Route::get('roles', RolesController::class);
Route::get('permisos', PermisosController::class);
Route::get('user', UsersController::class);
Route::get('cashout', CashoutController::class);
Route::get('reports', ReportsController::class);
Route::get('providers', ProviderController::class);
