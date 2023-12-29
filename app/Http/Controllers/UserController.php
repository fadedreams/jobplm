<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function createSeeker()
    {
        return view('users.seeker-register');
    }

    public function storeSeeker(Request $request)
    {
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => request('password'),
            'user_type' => 'seeker',
        ]);
        return back();
    }
}
