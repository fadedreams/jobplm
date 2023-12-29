<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SRReq;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function createSeeker()
    {
        return view('users.seeker-register');
    }
    public function createEmployer()
    {
        return view('users.employer-register');
    }

    // public function storeSeeker(Request $request)
    public function storeSeeker(SRReq $request)
    {
        // Check if there is an authorization check here
        // $this->authorize('create', User::class);
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:1'],
        ]);
        User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'user_type' => User::JOB_SEEKER,
        ]);
        return back();
    }
    public function storeEmployer(Request $request)
    {
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:1'],
        ]);
        User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'user_type' => USER::JOB_EMPLOYER,
            'user_trial' => now()->addWeek(),
        ]);
        // Session::flash('success', 'Registration successful! Please log in.');
        return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
    }

    public function login(Request $request)
    {
        return view('users.login');
    }

    public function postLogin(Request $request)
    {
        request()->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:1'],
        ]);
        $cred = $request->only('email', 'password');
        if (Auth()->attempt($cred)) {
            return redirect()->intended('dashboard');
        }
        // Authentication failed, redirect back to login with error message
        return redirect()->route('login')->withInput($request->only('email'))
            ->withErrors(['email' => 'Invalid email or password']);
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->route('login');
    }
}
