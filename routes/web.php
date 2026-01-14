<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name("home");

Route::post('/login', [AuthController::class, "login"]);
Route::post('/register', [AuthController::class, "register"]);
Route::post('/logout', [AuthController::class, "logout"]);

Route::middleware("auth")->group(function () {});
