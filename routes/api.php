<?php

use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('/transaction', [TransactionController::class, 'transaction']);
Route::get('/top-users', [TransactionController::class, 'topUsers']);
Route::resource('/cards', CardController::class);
