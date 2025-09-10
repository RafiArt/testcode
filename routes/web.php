<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterItemsController;
use App\Http\Controllers\CategoriesController;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Master Items Routes
Route::get('/master-items', [MasterItemsController::class, 'index']);
Route::get('/master-items/search', [MasterItemsController::class, 'search']);
Route::get('/master-items/form/{method}/{id?}', [MasterItemsController::class, 'formView']);
Route::post('/master-items/form/{method}/{id?}', [MasterItemsController::class, 'formSubmit']);
Route::get('/master-items/view/{kode}', [MasterItemsController::class, 'singleView']);
Route::delete('/master-items/delete/{id}', [MasterItemsController::class, 'destroy']);
Route::get('/master-items/update-random-data', [MasterItemsController::class, 'updateRandomData']);

Route::get('/categories', [CategoriesController::class, 'index']);
Route::get('/categories/search', [CategoriesController::class, 'search']);
Route::get('/categories/form/{method}/{id?}', [CategoriesController::class, 'formView']);
Route::post('/categories/form/{method}/{id?}', [CategoriesController::class, 'formSubmit']);
Route::get('/categories/view/{id}', [CategoriesController::class, 'singleView']);
Route::delete('/categories/delete/{id}', [CategoriesController::class, 'delete']);
