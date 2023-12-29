<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//import Auth
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    // public function index()
    // {
    //     if (Auth::check()) {
    //         return view('dashboard');
    //     }
    //     return back();
    // }
    public function index()
    {
        if (Auth::check()) {
            return view('dashboard');
        }
        return back();
    }
    public function verify()
    {
        return view('users.verify');
    }
}
