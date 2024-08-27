<?php

use App\Http\Controllers\EstimationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EstimationController::class, 'index'])->name('form');
Route::post('/calculate', [EstimationController::class, 'calculate'])->name('calculate');
