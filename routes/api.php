<?php

use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/transaction', [TransactionController::class, 'transaction']);
Route::get('/top-users', [TransactionController::class, 'topUsers']);
Route::resource('/users', UserController::class);
