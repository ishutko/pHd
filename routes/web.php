<?php

use App\Http\Controllers\PredictionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PredictionController::class, 'index']);
Route::post('/prediction', [PredictionController::class, 'calculate']);
