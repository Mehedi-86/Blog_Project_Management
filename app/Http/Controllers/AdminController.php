<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AdminController extends Controller
{
    // Apply middleware in constructor if desired
    public function __construct()
    {
        $this->middleware('auth');      // ensure user is logged in
        // only admins can access index
    }

    public function index()
{
    $user = Auth::user();

    if (!$user || $user->usertype !== 'admin') {
        return redirect()->route('homepage'); // redirect unauthorized users
    }

    return view('admin.adminhome'); // your admin dashboard blade
}



    // Show manage posts page
    public function managePosts() {
        $posts = DB::table('posts')
                    ->join('users', 'posts.user_id', '=', 'users.id')
                    ->select('posts.*', 'users.name', 'users.usertype')
                    ->get();

        return view('admin.managePost', compact('posts'));
    }

    // Accept post
    public function acceptPost($id) {
        DB::table('posts')->where('id', $id)->update(['status' => 'active']);
        return redirect()->route('admin.manage.posts')->with([
            'message' => 'Post accepted successfully!',
            'type' => 'success'
        ]);
    }

    // Reject post
    public function rejectPost($id) {
        DB::table('posts')->where('id', $id)->update(['status' => 'rejected']);
        return redirect()->route('admin.manage.posts')->with([
            'message' => 'Post rejected successfully!',
            'type' => 'warning'
        ]);
    }

    // Delete post
    public function deletePost($id) {
        DB::table('posts')->where('id', $id)->delete();
        return redirect()->route('admin.manage.posts')->with([
            'message' => 'Post deleted successfully!',
            'type' => 'danger'
        ]);
    }

    public function manageUsers()
{
    // Only fetch users who are NOT admin
    $users = User::where('usertype', '!=', 'admin')->get();
    return view('admin.manageUsers', compact('users'));
}

    public function banUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_banned = 1;
        $user->save();

        return redirect()->back()->with(['message' => 'User banned successfully!', 'type' => 'warning']);
    }

    public function unbanUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_banned = 0;
        $user->save();

        return redirect()->back()->with(['message' => 'User unbanned successfully!', 'type' => 'success']);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with(['message' => 'User deleted successfully!', 'type' => 'danger']);
    }

}


