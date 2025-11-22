<?php

use App\Http\Livewire\ClientCartsController;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\BackupController;
use App\Http\Livewire\BudgetsController;
use App\Http\Livewire\CartsController;
use App\Http\Livewire\CashoutController;
use App\Http\Livewire\CatalogController;
use App\Http\Livewire\CategoriesController;
use App\Http\Livewire\ProductsController;
use App\Http\Livewire\ProviderController;
use App\Http\Livewire\PosController;
use App\Http\Livewire\ClientsController;
use App\Http\Livewire\CurrenciesController;
use App\Http\Livewire\LogController;
use App\Http\Livewire\RolesController;
use App\Http\Livewire\PermisosController;
use App\Http\Livewire\PurchaseController;
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

Route::middleware('logger')->group(function () {
    Auth::routes();
});


Route::middleware(['auth', 'fetch.currency', 'logger'])->group(function () {
    Route::post('/ping', function () {
        if (Auth::check()) {
            $key = 'user-ping-' . Auth::id();
            Cache::put($key, now(), 60);
        }
        return response()->noContent();
    })->middleware('auth');

    Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
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
    Route::get('purchases', PurchaseController::class);
    Route::get('catalog', CatalogController::class)->name('catalog');
    Route::get('historial', ClientCartsController::class)->name('historial');
    Route::get('carts', CartsController::class);
    Route::get('budgets', BudgetsController::class);
    Route::get('backups', BackupController::class);
    Route::get('logs', LogController::class);
});
