<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InfoController;

Route::get('/', [InfoController::class, 'index']);
Route::get('/api/info', [InfoController::class, 'getInfo']);
