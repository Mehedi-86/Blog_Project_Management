<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
// Models are no longer needed here as we are using raw SQL for all operations.
// use App\Models\Post;
// use App\Models\User;
// use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function homepage()
    {
        // Copy the exact same query from your leaderboard() function
        $topPosts = DB::select("
            WITH RankedPosts AS (
                SELECT 
                    p.title,
                    p.views,
                    c.name AS category_name,
                    ROW_NUMBER() OVER(PARTITION BY c.id ORDER BY p.views DESC) as rn
                FROM posts p
                JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'active'
            )
            SELECT * FROM RankedPosts WHERE rn <= 3;
        ");
    
        // Pass the $topPosts variable to your homepage view
        return view('home.homepage', compact('topPosts'));
    }

    public function services()
{
    // Count total users
    $totalUsers = DB::select('SELECT COUNT(*) AS total FROM users')[0]->total;

    // Count total posts
    $totalPosts = DB::select('SELECT COUNT(*) AS total FROM posts')[0]->total;

    return view('home.services', compact('totalUsers', 'totalPosts'));
}


    public function usersList()
    {
        // This was already a raw SQL query.
        $users = DB::select('SELECT id, name, email, phone, usertype FROM users');
        return view('home.users_list', compact('users'));
    }

    // Step 1: Page with just the Add Post button
    public function showAddDataButton()
    {
        return view('home.addData');
    }

    // Step 2: Actual Add Post form
    public function showAddPostForm()
    {
        // CONVERTED: Was DB::table('categories')->get();
        $categories = DB::select('SELECT * FROM categories');
        return view('home.addPost', compact('categories'));
    }

    // Step 3: Store post in DB
    public function storePost(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|integer',
            'category_name' => 'nullable|string|max:255',
        ]);
    
        // Start the transaction
        DB::beginTransaction();
    
        try {
            $category_id = null;
            $now = now();
    
            if ($request->filled('category_name')) {
                // This block contains database reads and a potential write
                $categoryResult = DB::select('SELECT id FROM categories WHERE name = ? LIMIT 1', [$request->category_name]);
                $category = $categoryResult[0] ?? null;
    
                if ($category) {
                    $category_id = $category->id;
                } else {
                    DB::insert('INSERT INTO categories (name, created_at, updated_at) VALUES (?, ?, ?)', [
                        $request->category_name,
                        $now,
                        $now
                    ]);
                    $newCategoryResult = DB::select('SELECT id FROM categories WHERE name = ?', [$request->category_name]);
                    $category_id = $newCategoryResult[0]->id ?? null;
                }
            } 
            elseif ($request->filled('category_id')) {
                // This block contains a database read
                $existsResult = DB::select('SELECT 1 FROM categories WHERE id = ?', [$request->category_id]);
                if (!empty($existsResult)) {
                    $category_id = $request->category_id;
                }
            }
    
            // The final database write
            DB::insert("
                INSERT INTO posts (user_id, title, content, views, category_id, status, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ", [
                auth()->id(),
                $request->title,
                $request->content,
                0,
                $category_id,
                'active',
                $now,
                $now
            ]);
    
            // If all database operations were successful, commit them permanently.
            DB::commit();
    
            return redirect()->route('addPost')->with('success', 'Post added successfully!');
    
        } catch (\Exception $e) {
            // If any error occurred in the 'try' block, undo all database changes.
            DB::rollBack();
    
            // Optional: Log the actual error for debugging
            // Log::error('Post creation failed: ' . $e->getMessage());
    
            // Redirect back with an error message for the user
            return redirect()->back()->with('error', 'Could not add post. An error occurred.');
        }
    }
        

    public function showAllPostsForLike()
    {
        $user_id = auth()->id();

        // This was already a raw SQL query.
        $posts = DB::select("
            SELECT posts.*, users.name 
            FROM posts
            JOIN users ON posts.user_id = users.id
            ORDER BY posts.created_at DESC
        ");

        // CONVERTED: Fetch liked posts by current user.
        $likedResult = DB::select('SELECT post_id FROM likes WHERE user_id = ?', [$user_id]);
        // The result is an array of objects, e.g., [{'post_id': 1}, {'post_id': 5}].
        // We need to convert it to a simple array like [1, 5] to match the original pluck()->toArray().
        $liked = array_column($likedResult, 'post_id');

        // CONVERTED: Fetch saved posts by current user.
        $savedResult = DB::select('SELECT post_id FROM post_user_saves WHERE user_id = ?', [$user_id]);
        $saved = array_column($savedResult, 'post_id');

        return view('home.likePost', compact('posts', 'liked', 'saved'));
    }

    public function likePost($id)
    {
        $user_id = auth()->id();
        $now = now();

        // CONVERTED: Check if already liked.
        $existsResult = DB::select('SELECT 1 FROM likes WHERE post_id = ? AND user_id = ? LIMIT 1', [$id, $user_id]);
        
        if (empty($existsResult)) {
            // CONVERTED: Insert like.
            DB::insert('INSERT INTO likes (post_id, user_id, created_at, updated_at) VALUES (?, ?, ?, ?)', [
                $id,
                $user_id,
                $now,
                $now,
            ]);

            // --- New: Insert notification if post belongs to another user ---
            // CONVERTED: Get post owner ID.
            $postOwnerResult = DB::select('SELECT user_id FROM posts WHERE id = ? LIMIT 1', [$id]);
            $postOwnerId = $postOwnerResult[0]->user_id ?? null;

            if ($postOwnerId && $postOwnerId != $user_id) {
                // CONVERTED: Insert notification.
                DB::insert('INSERT INTO notifications (user_id, type, data, created_at, updated_at) VALUES (?, ?, ?, ?, ?)', [
                    $postOwnerId,
                    'like',
                    json_encode(['liked_by' => $user_id, 'post_id' => $id]),
                    $now,
                    $now,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Post liked!');
    }

    public function unlikePost($id)
    {
        $user_id = auth()->id();
        
        // CONVERTED: Was DB::table()->where()->delete().
        DB::delete('DELETE FROM likes WHERE post_id = ? AND user_id = ?', [$id, $user_id]);

        return redirect()->back()->with('success', 'Post unliked!');
    }

    public function showPostsForComment()
    {
        $user_id = auth()->id();

        // CONVERTED: Get all posts.
        $posts = DB::select("
            SELECT posts.*, users.name 
            FROM posts
            JOIN users ON posts.user_id = users.id
            ORDER BY posts.created_at DESC
        ");

        foreach ($posts as $post) {
            // CONVERTED: Fetch comments with username for each post.
            $post->comments = DB::select('
                SELECT c.*, u.name as username 
                FROM comments c 
                JOIN users u ON c.user_id = u.id 
                WHERE c.post_id = ?', 
                [$post->id]
            );

            // CONVERTED: Check if current user has commented.
            $userCommentedResult = DB::select('SELECT 1 FROM comments WHERE post_id = ? AND user_id = ? LIMIT 1', [$post->id, $user_id]);
            $post->user_commented = !empty($userCommentedResult);
        }

        return view('home.commentPost', compact('posts'));
    }

    public function clearComments($id)
    {
        $user_id = auth()->id();

        // CONVERTED: Delete comments by current user on a specific post.
        DB::delete('DELETE FROM comments WHERE post_id = ? AND user_id = ?', [$id, $user_id]);

        return redirect()->back()->with('success', 'Your comments have been cleared!');
    }

    public function savePost($id)
    {
        $user_id = auth()->id();

        // CONVERTED: Check if already saved.
        $existsResult = DB::select('SELECT 1 FROM post_user_saves WHERE post_id = ? AND user_id = ? LIMIT 1', [$id, $user_id]);

        if (empty($existsResult)) {
            // CONVERTED: Insert into saves table.
            DB::insert('INSERT INTO post_user_saves (post_id, user_id, created_at, updated_at) VALUES (?, ?, ?, ?)', [
                $id,
                $user_id,
                now(),
                now(),
            ]);
        }

        return redirect()->back()->with('success', 'Post saved!');
    }

    public function unsavePost($id)
    {
        $user_id = auth()->id();

        // CONVERTED: Delete from saves table.
        DB::delete('DELETE FROM post_user_saves WHERE post_id = ? AND user_id = ?', [$id, $user_id]);

        return redirect()->back()->with('success', 'Post unsaved!');
    }

    public function commentPost(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string|max:500']);

        $user_id = auth()->id();
        $now = now();

        // CONVERTED: Insert comment.
        DB::insert('INSERT INTO comments (post_id, user_id, content, created_at, updated_at) VALUES (?, ?, ?, ?, ?)', [
            $id,
            $user_id,
            $request->comment,
            $now,
            $now,
        ]);

        // CONVERTED: Get post owner ID for notification.
        $postOwnerResult = DB::select('SELECT user_id FROM posts WHERE id = ? LIMIT 1', [$id]);
        $postOwnerId = $postOwnerResult[0]->user_id ?? null;

        if ($postOwnerId && $postOwnerId != $user_id) {
            // CONVERTED: Insert notification.
            DB::insert('INSERT INTO notifications (user_id, type, data, created_at, updated_at) VALUES (?, ?, ?, ?, ?)', [
                $postOwnerId,
                'comment',
                json_encode(['commented_by' => $user_id, 'post_id' => $id]),
                $now,
                $now,
            ]);
        }

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    public function showNotifications()
    {
        // This was already using raw SQL queries. No changes needed.
        $user_id = auth()->id();
        $notifications = DB::select("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC", [$user_id]);

        foreach ($notifications as $notif) {
            $data = json_decode($notif->data, true);
            if ($notif->type === 'like' && isset($data['liked_by'], $data['post_id'])) {
                $user = DB::select("SELECT name FROM users WHERE id = ?", [$data['liked_by']]);
                $notif->liked_by_name = $user[0]->name ?? 'Unknown';
                $post = DB::select("SELECT title FROM posts WHERE id = ?", [$data['post_id']]);
                $notif->post_title = $post[0]->title ?? 'Unknown Post';
            }
            if ($notif->type === 'comment' && isset($data['commented_by'], $data['post_id'])) {
                $user = DB::select("SELECT name FROM users WHERE id = ?", [$data['commented_by']]);
                $notif->commented_by_name = $user[0]->name ?? 'Unknown';
                $post = DB::select("SELECT title FROM posts WHERE id = ?", [$data['post_id']]);
                $notif->post_title = $post[0]->title ?? 'Unknown Post';
            }
            $notif->data = $data;
        }
        return view('home.notifications', compact('notifications'));
    }

    public function deleteNotification($id)
    {
        $user_id = auth()->id();

        // CONVERTED: Delete a specific notification for the user.
        DB::delete('DELETE FROM notifications WHERE id = ? AND user_id = ?', [$id, $user_id]);

        return redirect()->back()->with('success', 'Notification deleted successfully!');
    }

    public function showFollowers()
    {
        $user = Auth::user();

        // CONVERTED: Get all users except logged-in user (was User::where).
        $allUsers = DB::select('SELECT * FROM users WHERE id != ?', [$user->id]);

        // CONVERTED: Get IDs of users the logged-in user is following (was Follow::where).
        $followingResult = DB::select('SELECT following_id FROM follows WHERE follower_id = ?', [$user->id]);
        // Convert array of objects to a simple array of IDs.
        $followingIds = array_column($followingResult, 'following_id');

        return view('home.follower', compact('allUsers', 'followingIds'));
    }

    public function followUser($id)
    {
        $user = Auth::user();

        // CONVERTED: Prevent duplicate follows (was Follow::where()->exists()).
        $existsResult = DB::select('SELECT 1 FROM follows WHERE follower_id = ? AND following_id = ? LIMIT 1', [$user->id, $id]);

        if (empty($existsResult)) {
            // CONVERTED: Create the follow relationship (was Follow::create).
            DB::insert('INSERT INTO follows (follower_id, following_id, created_at, updated_at) VALUES (?, ?, ?, ?)', [
                $user->id,
                $id,
                now(),
                now()
            ]);
        }

        return redirect()->back();
    }

    public function unfollowUser($id)
    {
        $user = Auth::user();
        
        // CONVERTED: Delete the follow relationship (was Follow::where()->delete()).
        DB::delete('DELETE FROM follows WHERE follower_id = ? AND following_id = ?', [$user->id, $id]);

        return redirect()->back();
    }

    public function reportPost($id)
    {
        $user_id = auth()->id();

        // CONVERTED: Check if already reported.
        $existsResult = DB::select('SELECT 1 FROM reports WHERE post_id = ? AND reported_by = ? LIMIT 1', [$id, $user_id]);

        if (empty($existsResult)) {
            // CONVERTED: Insert report.
            DB::insert('INSERT INTO reports (post_id, reported_by, reason, created_at, updated_at) VALUES (?, ?, ?, ?, ?)', [
                $id,
                $user_id,
                'Inappropriate content',
                now(),
                now(),
            ]);
        }
        return redirect()->back()->with('success', 'Post reported!');
    }

    public function undoReportPost($id)
    {
        $user_id = auth()->id();
        
        // CONVERTED: Delete report.
        DB::delete('DELETE FROM reports WHERE post_id = ? AND reported_by = ?', [$id, $user_id]);

        return redirect()->back()->with('success', 'Report removed!');
    }
    
    public function switchToAdminDashboard()
    {
        session()->forget('view_as_user');
        return redirect()->route('home');
    }

    public function increaseView($id)
    {
        // CONVERTED: Was Post::findOrFail()->save(). This is more efficient.
        // It directly increments the value in the database without fetching the model first.
        DB::update('UPDATE posts SET views = views + 1 WHERE id = ?', [$id]);

        return back()->with('success', 'View count updated!');
    }

    public function editPost($id)
{
    $post = DB::select("SELECT * FROM posts WHERE id = ?", [$id])[0] ?? null;

    if (!$post || $post->user_id != auth()->id()) {
        return redirect()->back()->with('error', 'Unauthorized action.');
    }

    return view('home.editPost', compact('post'));
}

public function updatePost(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
    ]);

    $post = DB::select("SELECT * FROM posts WHERE id = ?", [$id])[0] ?? null;

    if (!$post || $post->user_id != auth()->id()) {
        return redirect()->back()->with('error', 'Unauthorized action.');
    }

    DB::update("UPDATE posts SET title = ?, content = ?, updated_at = ? WHERE id = ?", [
        $request->title,
        $request->content,
        now(),
        $id
    ]);

    return redirect()->back()->with('success', 'Post updated successfully!');
}

public function listPosts(Request $request)
{
    $categoryId = $request->query('category_id'); // get selected category

    // Fetch categories for dropdown
    $categories = DB::table('categories')->get();

    // Fetch posts with optional category filter
    $postsQuery = DB::table('posts')
        ->join('users', 'posts.user_id', '=', 'users.id')
        ->leftJoin('categories', 'posts.category_id', '=', 'categories.id')
        ->select('posts.*', 'users.name', 'categories.name as category_name');

    if ($categoryId) {
        $postsQuery->where('posts.category_id', $categoryId);
    }

    $posts = $postsQuery->orderBy('posts.created_at', 'desc')->get();

    return view('home.posts_list', compact('posts', 'categories', 'categoryId'));
}


public function likedPosts()
{
    $user_id = auth()->id();

    // Get all posts liked by the current user with category names
    $posts = DB::select("
        SELECT p.id, p.title, p.views, p.status, c.name AS category_name
        FROM likes l
        JOIN posts p ON l.post_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE l.user_id = ?
        ORDER BY l.created_at DESC
    ", [$user_id]);

    return view('home.posts_liked', compact('posts'));
}

public function commentedPosts()
{
    $user_id = auth()->id();

    // Get posts the user has commented on with category name and latest comment
    $posts = DB::select("
        SELECT p.id, p.title, p.views, p.status, c.name AS category_name,
               cm.content AS user_comment
        FROM posts p
        JOIN comments cm ON cm.post_id = p.id AND cm.user_id = ?
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE cm.id = (
            SELECT MAX(id) 
            FROM comments 
            WHERE post_id = p.id AND user_id = ?
        )
        ORDER BY cm.created_at DESC
    ", [$user_id, $user_id]);

    return view('home.posts_commented', compact('posts'));
}

public function myPosts()
{
    $user_id = auth()->id();

    // Get all posts authored by logged-in user with category name
    $posts = DB::select("
        SELECT p.id, p.title, p.views, p.status, c.name AS category_name
        FROM posts p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.user_id = ?
        ORDER BY p.created_at DESC
    ", [$user_id]);

    return view('home.posts_by_me', compact('posts'));
}

public function postsSavedByMe()
{
    $user_id = auth()->id();

    // Fetch posts saved by the logged-in user with category name
    $posts = DB::select("
        SELECT p.id, p.title, p.views, p.status, c.name AS category_name
        FROM post_user_saves s
        JOIN posts p ON s.post_id = p.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE s.user_id = ?
        ORDER BY s.created_at DESC
    ", [$user_id]);

    return view('home.posts_saved', compact('posts'));
}

public function followersOfMe()
{
    $user_id = auth()->id();

    // Fetch all users who follow the logged-in user
    $followers = DB::select("
        SELECT u.id, u.name, u.email, u.phone, u.usertype, f.created_at AS followed_at
        FROM follows f
        JOIN users u ON f.follower_id = u.id
        WHERE f.following_id = ?
        ORDER BY f.created_at DESC
    ", [$user_id]);

    return view('home.followers_of_me', compact('followers'));
}

public function whomIFollow()
{
    $user_id = auth()->id();

    // Fetch all users the logged-in user is following
    $followings = DB::select("
        SELECT u.id, u.name, u.email, u.phone, u.usertype, f.created_at AS followed_at
        FROM follows f
        JOIN users u ON f.following_id = u.id
        WHERE f.follower_id = ?
        ORDER BY f.created_at DESC
    ", [$user_id]);

    return view('home.whom_i_follow', compact('followings'));
}

public function postDetails()
{
    // The complex query is gone, replaced by a simple select from our new VIEW.
    // The output to the Blade file is identical.
    $posts = DB::select("SELECT * FROM post_details_view ORDER BY created_at DESC");

    return view('home.post_details_table', compact('posts'));
}

public function activePosts()
{
    $posts = DB::select("
        SELECT 
            p.*, 
            c.name as category_name, 
            u.name as author_name,
            CASE 
                WHEN p.views >= 20 THEN '🔥 Hot'
                WHEN p.views >= 10 THEN '👍 Popular'
                ELSE '✨ New'
            END AS popularity_status
        FROM posts p
        JOIN categories c ON p.category_id = c.id
        JOIN users u ON p.user_id = u.id
        WHERE p.status = 'active'
        ORDER BY p.created_at DESC
    ");

    return view('home.active_posts', compact('posts'));
}

public function rejectedPosts()
{
    $posts = \DB::table('posts')
        ->join('categories', 'posts.category_id', '=', 'categories.id')
        ->join('users', 'posts.user_id', '=', 'users.id')
        ->select('posts.*', 'categories.name as category_name', 'users.name as author_name')
        ->where('posts.status', 'rejected')
        ->orderBy('posts.created_at', 'desc')
        ->get();

    return view('home.rejected_posts', compact('posts'));
}

public function reportsOnMyPosts()
{
    $userId = auth()->id(); // logged-in user (the post owner)

    $reports = DB::table('reports')
        ->join('posts', 'reports.post_id', '=', 'posts.id')
        ->join('users', 'reports.reported_by', '=', 'users.id')
        ->where('posts.user_id', $userId) // only reports on my posts
        ->select(
            'reports.id as report_id',
            'users.name as reporter_name',
            'users.email as reporter_email',
            'posts.id as post_id',
            'posts.title as post_title',
            'reports.reason',
            'reports.created_at as reported_at'
        )
        ->orderBy('reports.created_at', 'desc')
        ->get();

    return view('home.reports_on_my_posts', compact('reports'));
}

public function reportsByMe()
{
    $userId = auth()->id();

    $reports = \DB::table('reports')
        ->join('posts', 'reports.post_id', '=', 'posts.id')
        ->join('users as post_owner', 'posts.user_id', '=', 'post_owner.id')
        ->select(
            'reports.id as report_id',
            'post_owner.name as post_owner_name',
            'post_owner.email as post_owner_email',
            'posts.id as post_id',
            'posts.title as post_title',
            'reports.reason',
            'reports.created_at as reported_at'
        )
        ->where('reports.reported_by', $userId)
        ->orderBy('reports.created_at', 'desc')
        ->get();

    return view('home.reports_by_me', compact('reports'));
}

public function showUsers() {
    $activeUsers = DB::table('users')->where('is_banned', 0)->get();
    $bannedUsers = DB::table('users')->where('is_banned', 1)->get();
    return view('home.showUsers', compact('activeUsers', 'bannedUsers'));
}

public function leaderboard()
{
    $topPosts = DB::select("
        -- This is a CTE (Common Table Expression) to make the query readable
        WITH RankedPosts AS (
            SELECT 
                p.title,
                p.views,
                c.name AS category_name,
                -- The Window Function starts here!
                ROW_NUMBER() OVER(PARTITION BY c.id ORDER BY p.views DESC) as rn
            FROM posts p
            JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'active'
        )
        -- Now, select only the top 3 from our ranked list
        SELECT * FROM RankedPosts WHERE rn <= 3;
    ");

    return view('home.leaderboard', compact('topPosts'));
}

// Add this new method to your HomeController to display the page
public function managePortfolio()
{
    $userId = auth()->id();

    // Fetch all existing portfolio items for the logged-in user
    $workExperiences = DB::select("SELECT * FROM work_experiences WHERE user_id = ? ORDER BY year DESC", [$userId]);
    $educations = DB::select("SELECT * FROM educations WHERE user_id = ? ORDER BY graduation_year DESC", [$userId]);
    $activities = DB::select("SELECT * FROM extra_curricular_activities WHERE user_id = ? ORDER BY created_at DESC", [$userId]);

    return view('home.manage_portfolio', compact('workExperiences', 'educations', 'activities'));
}

// Add this method to handle adding a new work experience
public function addWorkExperience(Request $request)
{
    $request->validate([
        'workplace_name' => 'required|string|max:255',
        'designation' => 'required|string|max:255',
        'year' => 'required|string|max:255',
    ]);

    DB::insert(
        "INSERT INTO work_experiences (user_id, workplace_name, designation, year, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)",
        [auth()->id(), $request->workplace_name, $request->designation, $request->year, now(), now()]
    );

    return redirect()->route('portfolio.manage')->with('success', 'Work experience added successfully!');
}

// Add this method to handle adding new education
public function addEducation(Request $request)
{
    $request->validate([
        'school_name' => 'required|string|max:255',
        'degree' => 'required|string|max:255',
        'graduation_year' => 'required|string|max:255',
    ]);

    DB::insert(
        "INSERT INTO educations (user_id, school_name, degree, graduation_year, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?)",
        [auth()->id(), $request->school_name, $request->degree, $request->graduation_year, now(), now()]
    );

    return redirect()->route('portfolio.manage')->with('success', 'Education added successfully!');
}

// Add this method to handle adding a new activity
public function addActivity(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'time_duration' => 'required|string|max:255',
        'description' => 'nullable|string',
        'github_link' => 'nullable|url|max:255',
    ]);

    DB::insert(
        "INSERT INTO extra_curricular_activities (user_id, name, time_duration, description, github_link, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?)",
        [auth()->id(), $request->name, $request->time_duration, $request->description, $request->github_link, now(), now()]
    );

    return redirect()->route('portfolio.manage')->with('success', 'Activity added successfully!');
}

// Update Work Experience
public function updateWorkExperience(Request $request, $id)
{
    $request->validate([
        'workplace_name' => 'required|string|max:255',
        'designation' => 'required|string|max:255',
        'year' => 'required|string|max:255',
    ]);

    DB::update(
        "UPDATE work_experiences SET workplace_name = ?, designation = ?, year = ?, updated_at = ? WHERE id = ? AND user_id = ?",
        [$request->workplace_name, $request->designation, $request->year, now(), $id, auth()->id()]
    );

    return redirect()->route('portfolio.manage')->with('success', 'Work experience updated successfully!');
}

// Delete Work Experience
public function deleteWorkExperience($id)
{
    DB::delete("DELETE FROM work_experiences WHERE id = ? AND user_id = ?", [$id, auth()->id()]);
    return redirect()->route('portfolio.manage')->with('success', 'Work experience deleted successfully!');
}

// Update Education
public function updateEducation(Request $request, $id)
{
    $request->validate([
        'school_name' => 'required|string|max:255',
        'degree' => 'required|string|max:255',
        'graduation_year' => 'required|string|max:255',
    ]);

    DB::update(
        "UPDATE educations SET school_name = ?, degree = ?, graduation_year = ?, updated_at = ? WHERE id = ? AND user_id = ?",
        [$request->school_name, $request->degree, $request->graduation_year, now(), $id, auth()->id()]
    );

    return redirect()->route('portfolio.manage')->with('success', 'Education updated successfully!');
}

// Delete Education
public function deleteEducation($id)
{
    DB::delete("DELETE FROM educations WHERE id = ? AND user_id = ?", [$id, auth()->id()]);
    return redirect()->route('portfolio.manage')->with('success', 'Education deleted successfully!');
}

// Update Activity
public function updateActivity(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'time_duration' => 'required|string|max:255',
        'description' => 'nullable|string',
        'github_link' => 'nullable|url|max:255',
    ]);

    DB::update(
        "UPDATE extra_curricular_activities SET name = ?, time_duration = ?, description = ?, github_link = ?, updated_at = ? WHERE id = ? AND user_id = ?",
        [$request->name, $request->time_duration, $request->description, $request->github_link, now(), $id, auth()->id()]
    );

    return redirect()->route('portfolio.manage')->with('success', 'Activity updated successfully!');
}

// Delete Activity
public function deleteActivity($id)
{
    DB::delete("DELETE FROM extra_curricular_activities WHERE id = ? AND user_id = ?", [$id, auth()->id()]);
    return redirect()->route('portfolio.manage')->with('success', 'Activity deleted successfully!');
}

public function showTrendingPosts()
{
    $trendingPosts = DB::select("
        SELECT p.*, u.name as author_name, c.name as category_name
        FROM posts p
        JOIN users u ON p.user_id = u.id
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE p.is_trending = 1
        ORDER BY p.views DESC
    ");

    return view('home.trending_posts', compact('trendingPosts'));
}

public function showPostInteractions($id)
{
    // Security Check: Make sure the post exists and belongs to the logged-in user.
    $post = DB::select("SELECT id, title FROM posts WHERE id = ? AND user_id = ?", [$id, auth()->id()])[0] ?? null;

    // If the post doesn't exist or doesn't belong to the user, show a 403 Forbidden error.
    if (!$post) {
        abort(403, 'Unauthorized Action');
    }

    // Fetch all users who have liked this specific post
    $likers = DB::select("
        SELECT u.name 
        FROM likes l 
        JOIN users u ON l.user_id = u.id 
        WHERE l.post_id = ?
    ", [$id]);

    // Fetch all comments for this post, along with the commenter's name
    $commenters = DB::select("
        SELECT u.name, c.content, c.created_at 
        FROM comments c 
        JOIN users u ON c.user_id = u.id 
        WHERE c.post_id = ? 
        ORDER BY c.created_at DESC
    ", [$id]);

    // Pass all the data to the new view
    return view('home.post_interactions', compact('post', 'likers', 'commenters'));
}

}
