<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('tasks.index')
        : redirect()->route('login');
})->name('home');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name("login");
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name("register");

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth')->group(function () {
    Route::prefix('task')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
        Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
        Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    });
    Route::post("/logout", [AuthController::class, "logout"])->name("logout");
});
