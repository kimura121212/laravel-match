<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User; 
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        $userCount = $users->count(); 
        $from_user_id = Auth::id(); 

        return view('home', compact('users'))
        ->with('userCount', $userCount)
        ->with('from_user_id', $from_user_id);
        
    }
}
