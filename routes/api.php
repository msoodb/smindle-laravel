<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

// Define the POST route for creating orders
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders', [OrderController::class, 'fetch']);
