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

Route::get('/', [Controllers\User\UserMainPageController::class, 'createProducts'])->name('main');

Route::get('/dashboard', [Controllers\User\UserProfileController::class, 'create'])
    ->middleware(['auth'])->name('dashboard');

Route::POST('/dashboard',[Controllers\User\UserProfileController::class, 'update'])->name('updateProfileUser');

require __DIR__.'/auth.php';
