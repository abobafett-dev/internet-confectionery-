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

Route::POST('/dashboard',[Controllers\User\UserProfileController::class, 'update'])
    ->middleware(['auth'])->name('updateProfileUser');

Route::GET('/catalog', [Controllers\User\UserCatalogController::class, 'create'])
    ->name('catalog');

Route::GET('/cart', [Controllers\User\UserCartController::class, 'create'])
    ->name('cart');

Route::GET('/order/{order}', [Controllers\User\UserOrderPageController::class, 'create'])
    ->middleware(['auth'])->name('order');

require __DIR__.'/auth.php';
