<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::GET('/', [Controllers\User\UserMainPageController::class, 'createProducts'])
    ->name('main');

Route::GET('/dashboard', [Controllers\User\UserProfileController::class, 'create'])
    ->middleware(['auth'])->name('dashboard');

Route::POST('/dashboard/update',[Controllers\User\UserProfileController::class, 'update'])
    ->middleware(['auth'])->name('updateProfileUser');

Route::POST('/dashboard/delete',[Controllers\User\UserProfileController::class, 'delete'])
    ->middleware(['auth'])->name('deleteProfileUser');

Route::POST('/product/addProductInCart/{product}', [Controllers\User\UserProductController::class, 'addProductInCart'])
    ->name('addProductInCart');

Route::POST('/product/deleteProductInCart', [Controllers\User\UserProductController::class, 'deleteProductInCart'])
    ->name('deleteProductInCart');

Route::POST('/product/addProductFromConstructor', [Controllers\User\UserProductController::class, 'addProductFromConstructor'])
    ->name('addProductFromConstructor');

Route::GET('/catalog', [Controllers\User\UserCatalogController::class, 'create'])
    ->name('catalog');

Route::GET('/cart', [Controllers\User\UserCartController::class, 'create'])
    ->name('cart');

Route::POST('/cart/intervals', [Controllers\User\UserCartController::class, 'createIntervalsAjax'])
    ->name('cartIntervals');

Route::GET('/order/{order}', [Controllers\User\UserOrderPageController::class, 'create'])
    ->middleware(['auth'])->name('order');

require __DIR__.'/auth.php';
