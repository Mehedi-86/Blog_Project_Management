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

Route::post('/posts/{id}/view', [HomeController::class, 'increaseView'])->name('increaseView');

// Manage posts page
Route::get('/admin/manage-posts', [AdminController::class, 'managePosts'])->name('admin.manage.posts');

// Accept post
Route::get('/admin/manage-posts/accept/{id}', [AdminController::class, 'acceptPost'])->name('admin.accept.post');

// Reject post
Route::get('/admin/manage-posts/reject/{id}', [AdminController::class, 'rejectPost'])->name('admin.reject.post');

// Delete post
Route::delete('/admin/manage-posts/delete/{id}', [AdminController::class, 'deletePost'])->name('admin.delete.post');

Route::prefix('admin')->group(function () {

    // Show manage posts page
    Route::get('/manage-posts', [AdminController::class, 'managePosts'])->name('admin.manage.posts');

    // Accept post
    Route::get('/manage-posts/accept/{id}', [AdminController::class, 'acceptPost'])->name('admin.accept.post');

    // Reject post
    Route::get('/manage-posts/reject/{id}', [AdminController::class, 'rejectPost'])->name('admin.reject.post');

    // Delete post
    Route::get('/manage-posts/delete/{id}', [AdminController::class, 'deletePost'])->name('admin.delete.post');
});

Route::get('/manage-users', [AdminController::class, 'manageUsers'])->name('admin.manage.users');

Route::get('/ban-user/{id}', [AdminController::class, 'banUser'])->name('admin.ban.user');

Route::get('/unban-user/{id}', [AdminController::class, 'unbanUser'])->name('admin.unban.user');

Route::get('/delete-user/{id}', [AdminController::class, 'deleteUser'])->name('admin.delete.user');

Route::get('/posts/{id}/edit', [HomeController::class, 'editPost'])->name('editPost');

Route::post('/posts/{id}/update', [HomeController::class, 'updatePost'])->name('updatePost');

Route::get('/posts-list', [App\Http\Controllers\HomeController::class, 'listPosts'])->name('posts.list');

Route::get('/posts-liked', [HomeController::class, 'likedPosts'])->name('posts.liked');

Route::get('/posts-commented', [HomeController::class, 'commentedPosts'])->name('posts.commented');

// Posts By Me
Route::get('/my-posts', [HomeController::class, 'myPosts'])->name('posts.byMe');

// Posts Saved By Me
Route::get('/posts-saved', [HomeController::class, 'postsSavedByMe'])->name('posts.savedByMe');

// Followers of Me
Route::get('/followers-of-me', [HomeController::class, 'followersOfMe'])->name('followers.ofMe');

// Whom I Follow
Route::get('/whom-i-follow', [HomeController::class, 'whomIFollow'])->name('whom.iFollow');

Route::get('/post-details', [HomeController::class, 'postDetails'])->name('posts.details');

Route::get('/posts/active', [HomeController::class, 'activePosts'])->name('posts.active');

Route::get('/rejected-posts', [HomeController::class, 'rejectedPosts'])->name('posts.rejected');

Route::get('/reports/my-posts', [HomeController::class, 'reportsOnMyPosts'])->name('reports.my_posts');

Route::get('/reports-by-me', [HomeController::class, 'reportsByMe'])->name('reports.by_me');

// Show Active and Banned Users (read-only)
Route::get('/show-users', [HomeController::class, 'showUsers'])->name('users.show');

Route::get('/leaderboard', [HomeController::class, 'leaderboard'])->name('leaderboard');

Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

// Add this group of routes to your web.php file, preferably with other authenticated routes

Route::middleware('auth')->group(function () {
    Route::get('/manage-portfolio', [HomeController::class, 'managePortfolio'])->name('portfolio.manage');
    
    // Routes to handle the form submissions
    Route::post('/portfolio/work', [HomeController::class, 'addWorkExperience'])->name('portfolio.add.work');
    Route::post('/portfolio/education', [HomeController::class, 'addEducation'])->name('portfolio.add.education');
    Route::post('/portfolio/activity', [HomeController::class, 'addActivity'])->name('portfolio.add.activity');

    // Update
    Route::put('/portfolio/work/{id}', [HomeController::class, 'updateWorkExperience'])->name('portfolio.update.work');
    Route::put('/portfolio/education/{id}', [HomeController::class, 'updateEducation'])->name('portfolio.update.education');
    Route::put('/portfolio/activity/{id}', [HomeController::class, 'updateActivity'])->name('portfolio.update.activity');

    // Delete
    Route::delete('/portfolio/work/{id}', [HomeController::class, 'deleteWorkExperience'])->name('portfolio.delete.work');
    Route::delete('/portfolio/education/{id}', [HomeController::class, 'deleteEducation'])->name('portfolio.delete.education');
    Route::delete('/portfolio/activity/{id}', [HomeController::class, 'deleteActivity'])->name('portfolio.delete.activity');

    // --- NEW AI SEARCH ROUTES START HERE ---
    // This route displays the search page to the user
    Route::get('/ai-search', [HomeController::class, 'showAiSearchPage'])->name('ai.search.page');

    // This route is the API endpoint that our JavaScript will call
    Route::post('/ai-search-handler', [HomeController::class, 'handleAiSearch'])->name('ai.search.handler');
    // --- NEW AI SEARCH ROUTES END HERE ---


});

Route::get('/trending', [HomeController::class, 'showTrendingPosts'])->name('posts.trending');

Route::get('/post-interactions/{id}', [HomeController::class, 'showPostInteractions'])->name('post.interactions');

Route::get('/my-post-analytics', [HomeController::class, 'myPostAnalytics'])->name('posts.analytics');

Route::get('/mutual-follows', [HomeController::class, 'mutualFollows'])->name('followers.mutual');

Route::get('/activity-log', [HomeController::class, 'userActivityLog'])->name('activity.log'); // Make sure the old one has a route too!

Route::get('/activity-analysis', [HomeController::class, 'userActivityAnalysis'])->name('activity.analysis');

Route::get('/personalized-feed', [HomeController::class, 'personalizedFeed'])->name('personalized.feed');

Route::get('/post/{id}', [HomeController::class, 'showPost'])->name('post.details');
