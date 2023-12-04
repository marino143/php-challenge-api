<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API Version 1
Route::group(['prefix' => 'v1'], function () {
    // Authentication
    Route::namespace('Authentication')->middleware(['throttle:1024,1'])->group(function () {
        Route::post('authentication/login', [App\Http\Controllers\Authentication\LoginController::class, 'login']);
        Route::post('authentication/logout', [App\Http\Controllers\Authentication\LogoutController::class, 'logout']);
        Route::post('authentication/password-recovery', [App\Http\Controllers\Authentication\PasswordRecoveryController::class, 'passwordRecovery']);
        Route::post('authentication/password-recovery/change-password', [App\Http\Controllers\Authentication\PasswordRecoveryController::class, 'changePassword']);
    });

    // Administration
    Route::namespace('Administration')->middleware(['auth:sanctum', 'abilities:administrator', 'throttle:1024,1'])->group(function () {
        // Dedicated Routes
        Route::get('administration/statistic', [App\Http\Controllers\Administration\StatisticController::class, 'statistic']);

        // Generic Routes
        Route::get('administration/{model}/{id}', [App\Http\Controllers\Administration\ModelController::class, 'view']);
        Route::get('administration/{model}', [App\Http\Controllers\Administration\ModelController::class, 'list']);
        Route::post('administration/{model}', [App\Http\Controllers\Administration\ModelController::class, 'create']);
        Route::put('administration/{model}', [App\Http\Controllers\Administration\ModelController::class, 'update']);
        Route::delete('administration/{model}', [App\Http\Controllers\Administration\ModelController::class, 'delete']);
    });
});

