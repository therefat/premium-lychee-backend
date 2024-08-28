<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\API\UserController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');

});
Route::post('item/addproduct',[\App\Http\Controllers\API\Product\ProductController::class,'addProduct']);
