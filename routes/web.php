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

// Step 1: Show page with just the button
Route::get('/add-data', [HomeController::class, 'showAddDataButton'])->name('addData');

// Step 2: Show actual form to add a post
Route::get('/add-post', [HomeController::class, 'showAddPostForm'])->name('addPost');

// Step 3: Handle form submission
Route::post('/add-post', [HomeController::class, 'storePost'])->name('addPost.store');

Route::get('/like-posts', [HomeController::class, 'showAllPostsForLike'])->name('likePostPage');

Route::post('/like/{id}', [HomeController::class, 'likePost'])->name('likePost');

Route::post('/unlike/{id}', [HomeController::class, 'unlikePost'])->name('unlikePost');

Route::delete('/clear-comments/{id}', [HomeController::class, 'clearComments'])->name('clearComments');

Route::post('/save-post/{id}', [HomeController::class, 'savePost'])->name('savePost');

Route::delete('/unsave-post/{id}', [HomeController::class, 'unsavePost'])->name('unsavePost');

Route::post('/comment-post/{id}', [HomeController::class, 'commentPost'])
    ->name('commentPost')
    ->middleware('auth');


    Route::get('/notifications', [HomeController::class, 'showNotifications'])
    ->name('showNotifications')
    ->middleware('auth');

    // Show posts for commenting
Route::get('/comment-posts', [HomeController::class, 'showPostsForComment'])
->name('commentPostPage')
->middleware('auth');

Route::delete('/notification/{id}', [HomeController::class, 'deleteNotification'])->name('deleteNotification')->middleware('auth');

Route::get('/followers', [HomeController::class, 'showFollowers'])
    ->name('followerPage')
    ->middleware('auth');

    Route::post('/follow/{id}', [HomeController::class, 'followUser'])->name('followUser')->middleware('auth');

Route::post('/unfollow/{id}', [HomeController::class, 'unfollowUser'])->name('unfollowUser')->middleware('auth');

Route::post('/report-post/{id}', [HomeController::class, 'reportPost'])->name('reportPost');

Route::delete('/undo-report/{id}', [HomeController::class, 'undoReportPost'])->name('undoReportPost');

Route::get('/switch-to-admin-dashboard', [HomeController::class, 'switchToAdminDashboard'])
    ->name('switch.admin.dashboard');

Route::get('/admin/home', [AdminController::class, 'index'])
    ->name('admin.home');