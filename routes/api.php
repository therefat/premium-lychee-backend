<?php

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
Route::group(['middleware'=>"adminAuth"], function () {
    Route::post('categories/add',[CategoriesController::class,'store']);

});
Route::post('/item/addProduct',[ProductController::class,'addProduct']);
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

