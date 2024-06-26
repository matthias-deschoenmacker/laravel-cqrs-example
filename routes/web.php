<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/users', [UserController::class, 'create']);
Route::get('/users/{email}', [UserController::class, 'getUserByEmail']);
Route::get('/users', [UserController::class, 'getUsers']);
