<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;


Route::get('/', [HomeController::class,'homepage']);



route::get('/home', [AdminController::class,'index'])->name('home');