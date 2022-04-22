<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BotController;
use App\Http\Controllers\Api\HalloweenController;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('cheesecurd', [BotController::class, 'start']);

Route::post('first_halloween', [HalloweenController::class, 'first']);

