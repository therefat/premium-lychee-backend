<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\API\Product\ProductController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\CategoriesController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');

});
Route::post('/item/addProduct',[ProductController::class,'addProduct']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::post('/admin/register',[AdminController::class,'register']);
Route::post('/admin/login',[AdminController::class,'loginPost']);
Route::post('categories/add',[CategoriesController::class,'store']);
