<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;

// Guest / Homepage
Route::get('/', [HomeController::class, 'homepage'])->name('homepage'); 

// Logged-in user's home (dashboard)
Route::get('/home', [AdminController::class, 'index'])->name('home');

// About page
Route::get('/about', function () {
    return view('home.about');
})->name('about');

// Services page using controller
Route::get('/services', [HomeController::class, 'services'])->name('services');

// Users list page
Route::get('/users-list', [HomeController::class, 'usersList'])->name('users.list');
