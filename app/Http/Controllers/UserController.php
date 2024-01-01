<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SRReq;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Jobs\SendEmailVerificationNotificationJob;
use Illuminate\Support\Facades\Hash;

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
        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'user_type' => User::JOB_SEEKER,
        ]);
        Auth()->login($user);
        $user->sendEmailVerificationNotification();
        return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
        // return back();
    }

    public function storeEmployer(Request $request)
    {
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:1'],
        ]);
        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'user_type' => USER::JOB_EMPLOYER,
            'user_trial' => now()->addWeek(),
        ]);
        // Session::flash('success', 'Registration successful! Please log in.');
        Auth()->login($user);
        // $user->sendEmailVerificationNotification();
        //
        SendEmailVerificationNotificationJob::dispatch($user);
        // try {
        //     Mail::to(auth()->user())->queue(new PurchaseMail($plan, $billingEnds));
        // } catch (\Exception $e) {
        //     return response()->json($e);
        // }

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

    public function profile()
    {
        return view('profile.index');
    }


    public function seekerProfile()
    {
        return view('seeker.profile');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = auth()->user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Current password is incorrect');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Your password has been updated successfully');
    }

    public function uploadResume(Request $request)
    {
        $this->validate($request, [
            'resume' => 'required|mimes:pdf,doc,docx',
        ]);

        if ($request->hasFile('resume')) {
            $resume = $request->file('resume')->store('resume', 'public');
            User::find(auth()->user()->id)->update(['resume' => $resume]);

            return back()->with('success', 'Your resume has been updated successfully');
        }
    }


    public function update(Request $request)
    {
        if ($request->hasFile('profile_pic')) {
            $imagepath = $request->file('profile_pic')->store('profile', 'public');

            User::find(auth()->user()->id)->update(['profile_pic' => $imagepath]);
        }

        User::find(auth()->user()->id)->update($request->except('profile_pic'));

        return back()->with('success', 'Your profile has been updated');
    }

    public function jobApplied()
    {
        $users =  User::with('listings')->where('id', auth()->user()->id)->get();

        return view('seeker.job-applied', compact('users'));
    }
}
