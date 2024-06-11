<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProductModelController;
use Illuminate\Support\Facades\Route;

// home page
Route::get('/', [ItemController::class, 'index'])->name('home');

Route::resource('brands', BrandController::class);
Route::resource('models', ProductModelController::class);

Route::get('/get-models-by-brand/{brand}', [ItemController::class, 'getModelsByBrand']);

Route::resource('items', ItemController::class);
Route::get('/brands/{brand}/models', [BrandController::class, 'getModelsByBrand']); 
Route::get('/items', [ItemController::class, 'index'])->name('items.index');

// export items 
Route::get('/export', [ItemController::class, 'itemexport'])->name('items.exportt');
