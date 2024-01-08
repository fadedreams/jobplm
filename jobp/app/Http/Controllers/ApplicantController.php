<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{

    public function index()
    {
        // Check if the user is authenticated
        if (auth()->check()) {
            $cacheKey = 'user_' . auth()->user()->id . '_listings';

            // Attempt to retrieve data from cache
            $listings = Cache::get($cacheKey);

            // If data is not in cache, fetch it from the database and store in cache
            if ($listings === null) {
                $listings = Listing::latest()
                    ->withCount('users')
                    ->where('user_id', auth()->user()->id)
                    ->get();

                // Cache the data for 60 minutes (adjust the time as needed)
                Cache::put($cacheKey, $listings, 60);
            }
            return view('applicants.index', compact('listings'));
        } else {
            // Handle the case when the user is not authenticated
            // You might want to redirect them to the login page or handle it accordingly
            return redirect()->route('login');
        }
    }


    public function show(Listing $listing)
    {
        // $this->authorize('view', $listing);
        $listing = Listing::with('users')->where('slug', $listing->slug)->first();
        // dd($listing);

        return view('applicants.show', compact('listing'));
    }

    public function shortlist($listingId, $userId)
    {

        $listing = Listing::find($listingId);
        $user = User::find($userId);
        if ($listing) {
            $listing->users()->updateExistingPivot($userId, ['shortlisted' => true]);
            // Mail::to($user->email)->queue(new ShortlistMail($user->name, $listing->title));

            return back()->with('success', 'User is shortlisted successfully');
        }

        return back();
    }

    public function apply($listingId)
    {
        $user = auth()->user();
        $user->listings()->syncWithoutDetaching($listingId);
        return back()->with('success', 'Youe application was successfully submited');
    }
}
