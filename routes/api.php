<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\API\UserController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});
