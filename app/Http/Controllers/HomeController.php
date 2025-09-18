<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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

}
