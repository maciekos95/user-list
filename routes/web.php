<?php

use Framework\Classes\Route;
use App\Controllers\HomeController;
use App\Controllers\UsersController;

Route::get('/', [HomeController::class, 'index']);
Route::post('/set-language', [HomeController::class, 'setLanguage']);
Route::get('/users/add', [UsersController::class, 'formAdd']);
Route::post('/users/add', [UsersController::class, 'add']);
Route::get('/users/edit/{id}', [UsersController::class, 'formEdit']);
Route::post('/users/edit/{id}', [UsersController::class, 'edit']);
Route::delete('/users/delete/{id}', [UsersController::class, 'delete']);
Route::addNotFoundHandler([HomeController::class, 'notFound']);