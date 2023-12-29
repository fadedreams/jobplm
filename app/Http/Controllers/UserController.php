<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SRReq;
use App\Models\User;

class UserController extends Controller
{
    public function createSeeker()
    {
        return view('users.seeker-register');
    }

    // public function storeSeeker(Request $request)
    public function storeSeeker(SRReq $request)
    {
        // Check if there is an authorization check here
        // $this->authorize('create', User::class);
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:1', 'confirmed'],
        ]);
        User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'user_type' => 'seeker',
        ]);
        return back();
    }
}
