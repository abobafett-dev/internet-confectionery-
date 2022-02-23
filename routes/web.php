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

Route::POST('/product/deleteProductInCartAjax', [Controllers\User\UserProductController::class, 'deleteProductInCartAjax'])
    ->name('deleteProductInCartAjax');

Route::POST('/product/addProductFromConstructor', [Controllers\User\UserProductController::class, 'addProductFromConstructor'])
    ->name('addProductFromConstructor');

Route::GET('/catalog', [Controllers\User\UserCatalogController::class, 'create'])
    ->name('catalog');

Route::GET('/cart', [Controllers\User\UserCartController::class, 'create'])
    ->name('cart');

Route::POST('/cart/intervals', [Controllers\User\UserCartController::class, 'createIntervalsAjax'])
    ->name('cartIntervals');

Route::POST('/cart/addOrderToUser', [Controllers\User\UserCartController::class, 'addOrderToUser'])
    ->name('addOrderToUser');

Route::GET('/order/{order}', [Controllers\User\UserOrderPageController::class, 'create'])
    ->middleware(['auth'])->name('order');

Route::GET('admin/orders/{date}', [Controllers\Admin\AdminOrdersPageController::class, 'create'])
    ->middleware(['auth'])->name('adminOrders');

Route::GET('admin/products', [Controllers\Admin\AdminProductsPageController::class, 'create'])
    ->middleware(['auth'])->name('adminProducts');

Route::GET('admin/products/add', [Controllers\Admin\AdminCreateProductPageController::class, 'create'])
    ->middleware(['auth'])->name('adminProductsAdd');

require __DIR__.'/auth.php';
