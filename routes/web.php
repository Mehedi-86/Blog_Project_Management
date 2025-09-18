<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;

// Guest / Homepage
Route::get('/', [HomeController::class, 'homepage'])->name('homepage'); 
// note: unique name 'homepage'

// Logged-in user's home (dashboard)
Route::get('/home', [AdminController::class, 'index'])->name('home');

// About page
Route::get('/about', function () {
    return view('home.about'); // resources/views/home/about.blade.php
})->name('about');

// Services page
Route::get('/services', function () {
    return view('home.services'); // resources/views/home/services.blade.php
})->name('services');
