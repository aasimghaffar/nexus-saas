<?php

use App\Http\Controllers\Api\V1Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['auth:sanctum', 'ability:read'])->group(function () {
    Route::get('/projects', [V1Controller::class, 'projects']);
    Route::get('/tasks', [V1Controller::class, 'tasks']);
    Route::post('/tasks', [V1Controller::class, 'storeTask']);
    Route::get('/tickets', [V1Controller::class, 'tickets']);
});
