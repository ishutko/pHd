<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalculationController;

Route::get('/calculation', [CalculationController::class, 'showForm'])->name('calculation.form');
Route::post('/calculation', [CalculationController::class, 'calculate'])->name('calculate');
Route::get('/calculation/random', [CalculationController::class, 'generateRandom'])->name('generate-random');
Route::get('/calculation/example-data', [CalculationController::class, 'getExampleData'])->name('get-example-data');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/about', [PageController::class, 'contact'])->name('contact');
