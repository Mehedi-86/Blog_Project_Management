<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if (!$user) {
            // Not logged in, redirect to login
            return redirect()->route('login');
        }

        if ($user->usertype === 'user') {
            // Regular user, go to homepage (not /home)
            return redirect()->route('homepage'); 
        }

        // Admin user
        return view('admin.adminhome');
    }
}
