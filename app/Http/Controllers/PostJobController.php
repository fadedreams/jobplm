<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\IsEmployer;
use App\Http\Middleware\isPremiumUser;
use App\Http\Requests\JobEditFormRequest;
use App\Models\Listing;
use App\Post\JobPost;
use Illuminate\Support\Str;
use App\Http\Requests\JPR;

class PostJobController extends Controller
{

    protected $job;
    public function __construct(JobPost $job)
    {
        $this->job = $job;
        $this->middleware('auth');
        // $this->middleware(IsPremiumUser::class)->only(['create', 'store']);
        $this->middleware(IsEmployer::class);
    }

    public function index()
    {
        // $jobs = Listing::where('user_id', auth()->user()->id)->get();
        //
        // return view('job.index', compact('jobs'));
    }

    public function create()
    {
        return view('job.create');
    }

    public function store(JPR $request)
    {
        $this->job->store($request);
        return back();
        // return redirect()->route('job.index')->with('success', 'Your job post has been posted');
    }

    // public function store(JPR $request)
    // {
    //     $image_path = $request->file('feature_image')->store('public/images');
    //
    //     $post = new Listing;
    //     $post->user_id = auth()->user()->id;
    //     $post->feature_image = $image_path;
    //     $post->title = $request->title;
    //     $post->description = $request->description;
    //     $post->job_type = $request->description;  // <-- This might be a typo, should it be $request->job_type?
    //     $post->address = $request->address;
    //     $post->application_close_date = $request->date;
    //     $post->salary = $request->salary;
    //     $post->slug = Str::slug($request->title) . '.' . Str::uuid();
    //
    //     $post->save();
    //
    //     return back();
    //     // return redirect()->route('job.index')->with('success', 'Your job post has been posted');
    // }


    // public function store(JobPostFormRequest $request)
    // {
    //     $this->job->store($request);
    //
    //     return redirect()->route('job.index')->with('success', 'Your job post has been posted');
    // }

    public function edit(Listing $listing)
    {
        return view('job.edit', compact('listing'));
    }

    public function update($id, JobEditFormRequest $request)
    {
        $this->job->updatePost($id, $request);

        return back()->with('success', 'Your job post has been successfully updated');
    }

    public function destroy($id)
    {
        Listing::find($id)->delete();

        return back()->with('success', 'Your job post has been successfully deleted');
    }
}
