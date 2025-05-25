<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CancionApiController;

Route::apiResource('canciones', CancionApiController::class);
