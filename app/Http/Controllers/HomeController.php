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
        return view('home.homepage');
    }

    public function services()
    {
        // This was already a raw SQL query.
        $totalUsers = DB::select('SELECT COUNT(*) AS total FROM users')[0]->total;
        return view('home.services', compact('totalUsers'));
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

        $category_id = null;
        $now = now();

        if ($request->filled('category_name')) {
            // CONVERTED: Check if category exists. DB::select returns an array.
            $categoryResult = DB::select('SELECT id FROM categories WHERE name = ? LIMIT 1', [$request->category_name]);
            $category = $categoryResult[0] ?? null;

            if ($category) {
                $category_id = $category->id;
            } else {
                // Create new category. DB::insert does not return the new ID.
                DB::insert('INSERT INTO categories (name, created_at, updated_at) VALUES (?, ?, ?)', [
                    $request->category_name,
                    $now,
                    $now
                ]);
                // We must fetch the ID of the new category.
                $newCategoryResult = DB::select('SELECT id FROM categories WHERE name = ?', [$request->category_name]);
                $category_id = $newCategoryResult[0]->id ?? null;
            }
        } 
        elseif ($request->filled('category_id')) {
            // CONVERTED: Verify category exists. !empty checks if the result array is not empty.
            $existsResult = DB::select('SELECT 1 FROM categories WHERE id = ?', [$request->category_id]);
            if (!empty($existsResult)) {
                $category_id = $request->category_id;
            }
        }

        // This was already a raw SQL query.
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

        return redirect()->route('addPost')->with('success', 'Post added successfully!');
    }

    public function showAllPostsForLike()
    {
        $user_id = auth()->id();

        // This was already a raw SQL query.
        $posts = DB::select("SELECT * FROM posts ORDER BY created_at DESC");

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
        $posts = DB::select('SELECT * FROM posts ORDER BY created_at DESC');

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
}
