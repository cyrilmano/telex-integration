<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\NumberController;

Route::get('/', [InfoController::class, 'index']);
Route::get('/api/info', [InfoController::class, 'getInfo']);

Route::get('/api/classify-number', [NumberController::class, 'classifyNumber']);
