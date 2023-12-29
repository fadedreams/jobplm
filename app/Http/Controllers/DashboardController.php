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
    public function resend(Request $request)
    {
        $user = Auth::user();
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('success', 'Email already verified.');
        }
        $user->sendEmailVerificationNotification();
        return back()->with('success', 'Email verification link sent on your email id.');
    }
}
