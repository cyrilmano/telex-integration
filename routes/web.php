<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\NumberController;
use App\Http\Controllers\KeywordMonitorController;

Route::get('/', [InfoController::class, 'index']);
Route::get('/api/info', [InfoController::class, 'getInfo']);

Route::get('/api/classify-number', [NumberController::class, 'classifyNumber']);

Route::get('/api/json-data', [KeywordMonitorController::class, 'getJsonData']);

Route::post('/api/telex/get-summary', [KeywordMonitorController::class, 'sendSummary'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

Route::post('/api/telex/get-message-from-channel', [KeywordMonitorController::class, 'getDataFromTelex'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
//Route::get('/api/telex/daily-monitor/summary', [KeywordMonitorController::class, 'sendSummary']);
