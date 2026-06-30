<?php

use App\Http\Controllers\HealthController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogOutController;
use App\Http\Controllers\TaskToggleController;
use App\Http\Controllers\ToDoTaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {

    Route::get('/health', [HealthController::class, 'health']);

    Route::post('/login', LoginController::class);

    Route::group(['middleware' => 'auth:sanctum'], function () {

        Route::resource('/todos', ToDoTaskController::class)->parameters(['todos' => 'todos']);
        Route::patch('/toggle/{task}', TaskToggleController::class);
        Route::post('/logout', LogOutController::class);
    });
});
