<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use Illuminate\Support\Facades\URL;

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
if (config('app.env') === 'production') {
    Route::group(['scheme' => 'https'], function () {
        Route::GET('/', [Controllers\User\UserMainPageController::class, 'createProducts'])
            ->name('main');

        Route::GET('/userAgreement', [Controllers\User\UserAgreementController::class, 'create'])
            ->name('userAgreement');

        Route::GET('/dashboard', [Controllers\User\UserProfileController::class, 'create'])
            ->middleware(['auth'])->name('dashboard');

        Route::POST('/dashboard/update', [Controllers\User\UserProfileController::class, 'update'])
            ->middleware(['auth'])->name('updateProfileUser');

        Route::POST('/dashboard/delete', [Controllers\User\UserProfileController::class, 'delete'])
            ->middleware(['auth'])->name('deleteProfileUser');

//Route::GET('/dashboard', [Controllers\User\UserProfileController::class, 'create'])
//    ->middleware(['auth'])->name('dashboard');
//
//Route::POST('/dashboard/update',[Controllers\User\UserProfileController::class, 'update'])
//    ->middleware(['auth'])->name('updateProfileUser');
//
//Route::POST('/dashboard/delete',[Controllers\User\UserProfileController::class, 'delete'])
//    ->middleware(['auth'])->name('deleteProfileUser');

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

        Route::GET('admin/orders', [Controllers\Admin\AdminOrdersPageController::class, 'create'])
            ->middleware(['auth'])->name('adminOrders');

        Route::POST('admin/orders/createAjax', [Controllers\Admin\AdminOrdersPageController::class, 'createAjax'])
            ->middleware(['auth'])->name('createAjax');

        Route::POST('admin/orders/changeStatusAjax', [Controllers\Admin\AdminOrdersPageController::class, 'changeStatusAjax'])
            ->middleware(['auth'])->name('changeStatusAjax');

        Route::GET('admin/products', [Controllers\Admin\AdminProductsPageController::class, 'create'])
            ->middleware(['auth'])->name('adminProducts');

        Route::POST('admin/products/changeActiveAjax', [Controllers\Admin\AdminProductsPageController::class, 'changeActiveAjax'])
            ->middleware(['auth'])->name('changeActiveAjax');

        Route::GET('admin/products/add', [Controllers\Admin\AdminCreateProductPageController::class, 'create'])
            ->middleware(['auth'])->name('adminProductsAdd');


        Route::POST('admin/products/add/addProduct', [Controllers\Admin\AdminCreateProductPageController::class, 'addProduct'])
            ->middleware(['auth'])->name('adminProductsAddProduct');

        Route::POST('admin/products/add/addProductType', [Controllers\Admin\AdminCreateProductPageController::class, 'addProductType'])
            ->middleware(['auth'])->name('adminProductsAddProductType');

        Route::POST('admin/products/add/addComponent', [Controllers\Admin\AdminCreateProductPageController::class, 'addComponent'])
            ->middleware(['auth'])->name('adminProductsAddComponent');

        Route::POST('admin/products/add/addComponentType', [Controllers\Admin\AdminCreateProductPageController::class, 'addComponentType'])
            ->middleware(['auth'])->name('adminProductsAddComponentType');

        Route::POST('admin/products/add/addIngredient', [Controllers\Admin\AdminCreateProductPageController::class, 'addIngredient'])
            ->middleware(['auth'])->name('adminProductsAddIngredient');

        Route::GET('admin/products/update/{product}', [Controllers\Admin\AdminUpdateProductPageController::class, 'create'])
            ->middleware(['auth'])->name('adminProductsUpdate');
    });
} else {
    Route::GET('/', [Controllers\User\UserMainPageController::class, 'createProducts'])
        ->name('main');

    Route::GET('/userAgreement', [Controllers\User\UserAgreementController::class, 'create'])
        ->name('userAgreement');

    Route::GET('/dashboard', [Controllers\User\UserProfileController::class, 'create'])
        ->middleware(['auth'])->name('dashboard');

    Route::POST('/dashboard/update', [Controllers\User\UserProfileController::class, 'update'])
        ->middleware(['auth'])->name('updateProfileUser');

    Route::POST('/dashboard/delete', [Controllers\User\UserProfileController::class, 'delete'])
        ->middleware(['auth'])->name('deleteProfileUser');

//Route::GET('/dashboard', [Controllers\User\UserProfileController::class, 'create'])
//    ->middleware(['auth'])->name('dashboard');
//
//Route::POST('/dashboard/update',[Controllers\User\UserProfileController::class, 'update'])
//    ->middleware(['auth'])->name('updateProfileUser');
//
//Route::POST('/dashboard/delete',[Controllers\User\UserProfileController::class, 'delete'])
//    ->middleware(['auth'])->name('deleteProfileUser');

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

    Route::GET('admin/orders', [Controllers\Admin\AdminOrdersPageController::class, 'create'])
        ->middleware(['auth'])->name('adminOrders');

    Route::POST('admin/orders/createAjax', [Controllers\Admin\AdminOrdersPageController::class, 'createAjax'])
        ->middleware(['auth'])->name('createAjax');

    Route::POST('admin/orders/changeStatusAjax', [Controllers\Admin\AdminOrdersPageController::class, 'changeStatusAjax'])
        ->middleware(['auth'])->name('changeStatusAjax');

    Route::GET('admin/products', [Controllers\Admin\AdminProductsPageController::class, 'create'])
        ->middleware(['auth'])->name('adminProducts');

    Route::POST('admin/products/changeActiveAjax', [Controllers\Admin\AdminProductsPageController::class, 'changeActiveAjax'])
        ->middleware(['auth'])->name('changeActiveAjax');

    Route::GET('admin/products/add', [Controllers\Admin\AdminCreateProductPageController::class, 'create'])
        ->middleware(['auth'])->name('adminProductsAdd');


    Route::POST('admin/products/add/addProduct', [Controllers\Admin\AdminCreateProductPageController::class, 'addProduct'])
        ->middleware(['auth'])->name('adminProductsAddProduct');

    Route::POST('admin/products/add/addProductType', [Controllers\Admin\AdminCreateProductPageController::class, 'addProductType'])
        ->middleware(['auth'])->name('adminProductsAddProductType');

    Route::POST('admin/products/add/addComponent', [Controllers\Admin\AdminCreateProductPageController::class, 'addComponent'])
        ->middleware(['auth'])->name('adminProductsAddComponent');

    Route::POST('admin/products/add/addComponentType', [Controllers\Admin\AdminCreateProductPageController::class, 'addComponentType'])
        ->middleware(['auth'])->name('adminProductsAddComponentType');

    Route::POST('admin/products/add/addIngredient', [Controllers\Admin\AdminCreateProductPageController::class, 'addIngredient'])
        ->middleware(['auth'])->name('adminProductsAddIngredient');

    Route::GET('admin/products/update/{product}', [Controllers\Admin\AdminUpdateProductPageController::class, 'create'])
        ->middleware(['auth'])->name('adminProductsUpdate');
}

require __DIR__ . '/auth.php';
