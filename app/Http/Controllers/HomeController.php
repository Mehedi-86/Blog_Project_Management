<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function homepage()
    {
        return view('home.homepage');
    }

    public function services()
{
    $totalUsers = DB::select('SELECT COUNT(*) AS total FROM users')[0]->total;
    return view('home.services', compact('totalUsers'));
}


    public function usersList()
{
    // Raw SQL query
    $users = DB::select('SELECT id, name, email, phone, usertype FROM users'); // changed role -> usertype

    return view('home.users_list', compact('users'));
}

// Step 1: Page with just the Add Post button
public function showAddDataButton()
{
    return view('home.addData'); // Blade with only button
}

// Step 2: Actual Add Post form
public function showAddPostForm()
{
    $categories = DB::table('categories')->get();
    return view('home.addPost', compact('categories'));
}

// Step 3: Store post in DB
public function storePost(Request $request)
{
    // Validate input
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'category_id' => 'nullable|integer',
        'category_name' => 'nullable|string|max:255', // new category input
    ]);

    $category_id = null;

    // If user typed a new category
    if ($request->filled('category_name')) {
        // Check if this category already exists
        $category = DB::table('categories')->where('name', $request->category_name)->first();
        if ($category) {
            $category_id = $category->id;
        } else {
            // Create new category and get its ID
            $category_id = DB::table('categories')->insertGetId([
                'name' => $request->category_name,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    } 
    // If user selected an existing category from dropdown
    elseif ($request->filled('category_id')) {
        // Verify category exists
        $exists = DB::table('categories')->where('id', $request->category_id)->exists();
        if ($exists) {
            $category_id = $request->category_id;
        } else {
            $category_id = null; // fallback if invalid
        }
    }

    // Insert post
    DB::insert("
        INSERT INTO posts (user_id, title, content, views, category_id, status, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
    ", [
        auth()->id(),
        $request->title,
        $request->content,
        0,
        $category_id,
        'active'
    ]);

    return redirect()->route('addPost')->with('success', 'Post added successfully!');
}


public function showAllPostsForLike()
{
    $user_id = auth()->id();

    // Fetch all posts
    $posts = DB::select("SELECT * FROM posts ORDER BY created_at DESC");

    // Fetch liked posts by current user
    $liked = DB::table('likes')
        ->where('user_id', $user_id)
        ->pluck('post_id')
        ->toArray();

    // Fetch saved posts by current user
    $saved = DB::table('post_user_saves')
        ->where('user_id', $user_id)
        ->pluck('post_id')
        ->toArray();

    return view('home.likePost', compact('posts', 'liked', 'saved'));
}


public function likePost($id)
{
    $user_id = auth()->id();

    // Check if already liked
    $exists = DB::table('likes')
        ->where('post_id', $id)
        ->where('user_id', $user_id)
        ->exists();

    if(!$exists) {
        // Insert like (existing functionality)
        DB::table('likes')->insert([
            'post_id' => $id,
            'user_id' => $user_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // --- New: Insert notification if post belongs to another user ---
        $postOwnerId = DB::table('posts')->where('id', $id)->value('user_id');
        if ($postOwnerId && $postOwnerId != $user_id) { // don't notify self
            DB::table('notifications')->insert([
                'user_id' => $postOwnerId,
                'type' => 'like',
                'data' => json_encode([
                    'liked_by' => $user_id,
                    'post_id' => $id
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    return redirect()->back()->with('success', 'Post liked!');
}


public function unlikePost($id)
{
    $user_id = auth()->id();

    DB::table('likes')
        ->where('post_id', $id)
        ->where('user_id', $user_id)
        ->delete();

    return redirect()->back()->with('success', 'Post unliked!');
}

// Show posts for commenting
public function showPostsForComment()
{
    $user_id = auth()->id();

    $posts = DB::table('posts')->orderBy('created_at', 'desc')->get();

    foreach($posts as $post) {
        // Fetch comments with username
        $post->comments = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->where('comments.post_id', $post->id)
            ->select('comments.*', 'users.name as username')
            ->get();

        // Check if current user has commented on this post
        $post->user_commented = DB::table('comments')
            ->where('post_id', $post->id)
            ->where('user_id', $user_id)
            ->exists();
    }

    return view('home.commentPost', compact('posts'));
}


public function clearComments($id)
{
    $user_id = auth()->id();

    // Delete only comments by current user on that post
    DB::table('comments')->where('post_id', $id)->where('user_id', $user_id)->delete();

    return redirect()->back()->with('success', 'Your comments have been cleared!');
}

public function savePost($id)
{
    $user_id = auth()->id();

    // Check if already saved
    $exists = DB::table('post_user_saves')
        ->where('post_id', $id)
        ->where('user_id', $user_id)
        ->exists();

    if (!$exists) {
        DB::table('post_user_saves')->insert([
            'post_id' => $id,
            'user_id' => $user_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return redirect()->back()->with('success', 'Post saved!');
}

public function unsavePost($id)
{
    $user_id = auth()->id();

    DB::table('post_user_saves')
        ->where('post_id', $id)
        ->where('user_id', $user_id)
        ->delete();

    return redirect()->back()->with('success', 'Post unsaved!');
}


public function commentPost(Request $request, $id)
{
    $request->validate(['comment' => 'required|string|max:500']);

    $user_id = auth()->id();

    DB::table('comments')->insert([
        'post_id' => $id,
        'user_id' => $user_id,
        'content' => $request->comment,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Insert notification
    $postOwnerId = DB::table('posts')->where('id', $id)->value('user_id');
    if ($postOwnerId != $user_id) { // don't notify self
        DB::table('notifications')->insert([
            'user_id' => $postOwnerId,
            'type' => 'comment',
            'data' => json_encode(['commented_by' => $user_id, 'post_id' => $id]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return redirect()->back()->with('success', 'Comment added successfully!');
}

public function showNotifications()
{
    $user_id = auth()->id();

    // Fetch all notifications for this user
    $notifications = DB::select("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC", [$user_id]);

    // Loop through each notification and fetch related names/titles
    foreach ($notifications as $notif) {
        $data = json_decode($notif->data, true);

        if ($notif->type === 'like' && isset($data['liked_by'], $data['post_id'])) {
            // Get username of the user who liked
            $user = DB::select("SELECT name FROM users WHERE id = ?", [$data['liked_by']]);
            $notif->liked_by_name = $user[0]->name ?? 'Unknown';

            // Get post title
            $post = DB::select("SELECT title FROM posts WHERE id = ?", [$data['post_id']]);
            $notif->post_title = $post[0]->title ?? 'Unknown Post';
        }

        if ($notif->type === 'comment' && isset($data['commented_by'], $data['post_id'])) {
            // Get username of the user who commented
            $user = DB::select("SELECT name FROM users WHERE id = ?", [$data['commented_by']]);
            $notif->commented_by_name = $user[0]->name ?? 'Unknown';

            // Get post title
            $post = DB::select("SELECT title FROM posts WHERE id = ?", [$data['post_id']]);
            $notif->post_title = $post[0]->title ?? 'Unknown Post';
        }

        // Keep original data for other purposes
        $notif->data = $data;
    }

    return view('home.notifications', compact('notifications'));
}

public function deleteNotification($id)
{
    $user_id = auth()->id();

    DB::table('notifications')
        ->where('id', $id)
        ->where('user_id', $user_id)
        ->delete();

    return redirect()->back()->with('success', 'Notification deleted successfully!');
}

public function showFollowers()
{
    $user = Auth::user();

    // Get all users except logged-in user
    $allUsers = User::where('id', '!=', $user->id)->get();

    // Get IDs of users the logged-in user is following
    $followingIds = Follow::where('follower_id', $user->id)
                          ->pluck('following_id')
                          ->toArray();

    return view('home.follower', compact('allUsers', 'followingIds'));
}

public function followUser($id)
{
    $user = Auth::user();

    // Prevent duplicate follows
    if (!Follow::where('follower_id', $user->id)->where('following_id', $id)->exists()) {
        Follow::create([
            'follower_id' => $user->id,
            'following_id' => $id,
        ]);
    }

    return redirect()->back();
}

public function unfollowUser($id)
{
    $user = Auth::user();

    Follow::where('follower_id', $user->id)
          ->where('following_id', $id)
          ->delete();

    return redirect()->back();
}

public function reportPost($id)
{
    $user_id = auth()->id();

    // Check if already reported
    $exists = DB::table('reports')
        ->where('post_id', $id)
        ->where('reported_by', $user_id)
        ->exists();

    if (!$exists) {
        DB::table('reports')->insert([
            'post_id' => $id,
            'reported_by' => $user_id,
            'reason' => 'Inappropriate content', // optional, you can add a reason input later
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return redirect()->back()->with('success', 'Post reported!');
}

public function undoReportPost($id)
{
    $user_id = auth()->id();

    DB::table('reports')
        ->where('post_id', $id)
        ->where('reported_by', $user_id)
        ->delete();

    return redirect()->back()->with('success', 'Report removed!');
}

}
