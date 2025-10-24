<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Apply middleware in constructor
    public function __construct()
    {
        $this->middleware('auth'); // ensure user is logged in
    }

    // Admin dashboard
    public function index()
    {
        $user = Auth::user();

        if (!$user || $user->usertype !== 'admin') {
            return redirect()->route('homepage'); // redirect unauthorized users
        }

        return view('admin.adminhome'); // your admin dashboard blade
    }

    // Show manage posts page
    public function managePosts()
    {
        // CONVERTED: Was DB::table('posts')->join(...)
        $sql = "
            SELECT p.*, u.name, u.usertype 
            FROM posts p
            JOIN users u ON p.user_id = u.id
        ";
        $posts = DB::select($sql);

        return view('admin.managePost', compact('posts'));
    }

    // Accept post
    public function acceptPost($id)
    {
        // CONVERTED: Was DB::table('posts')->where(...)->update(...)
        DB::update("UPDATE posts SET status = ? WHERE id = ?", ['active', $id]);

        return redirect()->route('admin.manage.posts')->with([
            'message' => 'Post accepted successfully!',
            'type' => 'success'
        ]);
    }

    // Reject post
    public function rejectPost($id)
    {
        // CONVERTED: Was DB::table('posts')->where(...)->update(...)
        DB::update("UPDATE posts SET status = ? WHERE id = ?", ['rejected', $id]);

        return redirect()->route('admin.manage.posts')->with([
            'message' => 'Post rejected successfully!',
            'type' => 'warning'
        ]);
    }

    // Delete post
    public function deletePost($id)
    {
        // CONVERTED: Was DB::table('posts')->where(...)->delete()
        DB::delete("DELETE FROM posts WHERE id = ?", [$id]);

        return redirect()->route('admin.manage.posts')->with([
            'message' => 'Post deleted successfully!',
            'type' => 'danger'
        ]);
    }

    // Show manage users page (exclude admins)
    public function manageUsers()
    {
        // CONVERTED: Was DB::table('users')->where(...)
        $users = DB::select("SELECT * FROM users WHERE usertype != ?", ['admin']);

        return view('admin.manageUsers', compact('users'));
    }

    // Ban user
    public function banUser($id)
    {
        // CONVERTED: Was DB::table('users')->where(...)->update(...)
        DB::update("UPDATE users SET is_banned = ? WHERE id = ?", [1, $id]);

        return redirect()->back()->with([
            'message' => 'User banned successfully!',
            'type' => 'warning'
        ]);
    }

    // Unban user
    public function unbanUser($id)
    {
        // CONVERTED: Was DB::table('users')->where(...)->update(...)
        DB::update("UPDATE users SET is_banned = ? WHERE id = ?", [0, $id]);

        return redirect()->back()->with([
            'message' => 'User unbanned successfully!',
            'type' => 'success'
        ]);
    }

    // Delete user
    public function deleteUser($id)
    {
        // CONVERTED: Was DB::table('users')->where(...)->delete()
        DB::delete("DELETE FROM users WHERE id = ?", [$id]);

        return redirect()->back()->with([
            'message' => 'User deleted successfully!',
            'type' => 'danger'
        ]);
    }

   // In app/Http/Controllers/AdminController.php

public function dashboard()
{
    // --- 1. Learning DATE FUNCTIONS ---
    // Get the number of new posts created each day for the last 7 days.
    $postsLast7Days = DB::select("
        SELECT 
            DATE(created_at) as creation_date, 
            COUNT(id) as post_count
        FROM posts
        WHERE created_at >= CURDATE() - INTERVAL 7 DAY
        GROUP BY DATE(created_at)
        ORDER BY creation_date ASC
    ");

    // --- 2. Learning the HAVING Clause ---
    // Find "Power Users" - users who have created more than 3 posts.
    $powerUsers = DB::select("
        SELECT 
            u.name, 
            u.email,
            COUNT(p.id) as total_posts
        FROM users u
        JOIN posts p ON u.id = p.user_id
        GROUP BY u.id, u.name, u.email
        HAVING COUNT(p.id) > 3 -- Filter the GROUPS, not the rows
        ORDER BY total_posts DESC
    ");

    // --- 3. Learning UNION ---
    // Create a unified activity log of the 5 most recent new posts AND new users.
    $activityLog = DB::select("
        (SELECT id, 'New Post' as activity_type, title as details, created_at FROM posts ORDER BY created_at DESC LIMIT 5)
        UNION ALL
        (SELECT id, 'New User' as activity_type, name as details, created_at FROM users ORDER BY created_at DESC LIMIT 5)
        ORDER BY created_at DESC
        LIMIT 10
    ");

    return view('admin.dashboard', compact('postsLast7Days', 'powerUsers', 'activityLog'));
}
 
}
