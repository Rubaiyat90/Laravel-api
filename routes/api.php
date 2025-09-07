<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;


Route::get('/students', [StudentController::class, 'index']);
Route::post('/students', [StudentController::class, 'store']);
Route::get('/students/{id}/edit', [StudentController::class, 'show']);
Route::put('/students/{id}/edit', [StudentController::class, 'update']);
Route::delete('/students/{id}/delete', [StudentController::class, 'destroy']);
