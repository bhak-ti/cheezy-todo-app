<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TodosrecController;

Route::resource('todos', TodosrecController::class);

Route::get('/', [TodosrecController::class, 'index']);