<?php

use App\Http\Controllers\API\AddressBookController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\API\Product\ProductController;
use App\Http\Controllers\Api\Role\RoleController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\CategoriesController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');

});
Route::middleware('userAuth')->group(function () {

    Route::post('/user/addAddress',[AddressBookController::class,'store']);
    Route::get('/user/getaddress',[AddressBookController::class,'index']);
    Route::put('user/updateAddress/{addressId}',[AddressBookController::class,'setDefaultAddress']);
    Route::delete('user/deleteAddress/{addressId}',[AddressBookController::class,'destroy']);
    Route::get('user/singleAddress/{addressId}',[AddressBookController::class,'show']);
    Route::put('user/editAddress/{addressId}',[AddressBookController::class,'update']);
    Route::get('user/getDefaultAddress',[AddressBookController::class,'getDefaultAddress']);

    Route::post('/order/neworder',[\App\Http\Controllers\api\OrderController::class,'store']);
    Route::get('/order/getallorder',[\App\Http\Controllers\api\OrderController::class,'userOrder']);

});
//->middleware();
Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/order/getorder/{id}',[\App\Http\Controllers\api\OrderController::class,'show']);
});
Route::group(['middleware'=>"adminAuth"], function () {

    Route::post('categories/add',[CategoriesController::class,'store']);
    Route::post('/item/addProduct',[ProductController::class,'addProduct']);
    Route::patch('/item/updateProduct/{id}',[ProductController::class,'updateProduct']);
    Route::get('order/allorder',[\App\Http\Controllers\api\OrderController::class,'index']);
//    Route::get('/order/getorders/{id}',[\App\Http\Controllers\api\OrderController::class,'show']);
    Route::patch('order/updateOrder/{id}',[\App\Http\Controllers\api\OrderController::class,'updateStatus']);



});

Route::get('/item/getProduct/{id}',[ProductController::class,'show']);
Route::get('/item/getproduct',[ProductController::class,'getProduct']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/admin/register',[AdminController::class,'register']);
Route::post('/admin/login',[AdminController::class,'loginPost']);
//route for role
Route::post('/admin/addrole',[RoleController::class,'createRole']);
Route::get('/admin/getrole',[RoleController::class,'getAllRoles']);
Route::delete('/admin/deleterole/{id}',[RoleController::class,'destroy']);
//category
Route::get('/admin/categories',[CategoriesController::class,'getCategories']);
Route::delete('/admin/categories/{id}',[CategoriesController::class,'destroy']);





