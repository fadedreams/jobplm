@extends('layouts.app')

@section('content')

<div class="container mt-5 mb-3">
    <h3>Find your next employer</h3>

    <div class="row">
        @foreach(\App\Models\User::where('user_type','employer')->take(6)->orderBy('id','DESC')->get() as $employer)
        <div class="col-md-4">
            <div class="card p-3 mb-2">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-row align-items-center">
                        <div class="icon">
                            @if($employer->profile_pic)
                            <a href="{{route('company',[$employer->id])}}"><img src="{{Storage::url($employer->profile_pic)}}" width="50"></a>
                            @else
                            <a href="{{route('company',[$employer->id])}}"><img src="icons8-amazon-60.png"></a>
                            @endif
                        </div>
                        <div class="ms-2 c-details">
                            <h6 class="mb-0">{{$employer->name}}</h6> <span></span>
                        </div>
                    </div>
                    <div class="badge"> <span>Design</span> </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<div class="container mt-5">
    <section class="centered-content">
        <p class="lead"><a href="/login"> <button class="btn btn-dark" style="margin-left: -150px;">Sign in</button></a> or Register
            to manage your profile, start applying jobs.</p>
    </section>
    <div class="d-flex justify-content-between mt-5">
        <h4>Recommended Jobs</h4>

        <div class="dropdown">
            <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Salary
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{route('listing.index',['sort' => 'salary_high_to_low'])}}">High to low</a></li>
                <li><a class="dropdown-item" href="{{route('listing.index',['sort' => 'salary_low_to_high'])}}">Low to high</a></li>

            </ul>

            <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Date
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{route('listing.index',['date' => 'latest'])}}">Latest</a></li>
                <li><a class="dropdown-item" href="{{route('listing.index',['date' => 'oldest'])}}">Oldest</a></li>
            </ul>

            <button class="btn btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Job type
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{route('listing.index',['job_type' => 'Fulltime'])}}">Fulltime</a></li>
                <li><a class="dropdown-item" href="{{route('listing.index',['job_type' => 'Parttime'])}}">Parttime</a></li>
                <li><a class="dropdown-item" href="{{route('listing.index',['job_type' => 'Casual'])}}">Casual</a></li>
                <li><a class="dropdown-item" href="{{route('listing.index',['job_type' => 'Contract'])}}">Contract</a></li>
            </ul>
        </div>
    </div>
    <div class="row mt-2 g-1">
        @foreach($jobs as $job)
        <div class="col-md-3">
            <div class="card p-2 {{$job->job_type}}">
                <div class="text-right"> <small class="badge text-bg-info">{{$job->job_type}}</small> </div>
                <div class="text-center mt-2 p-3"> <img class="rounded-circle" width="50" src="{{Storage::url($job->profile->profile_pic)}}" width="100" /> <br>
                    <span class="d-bl>ock font-weight-bold">{{$job->title}}</span>
                    <hr> <span>{{$job->profile->name}}</span>
                    <div class="d-flex flex-row align-items-center justify-content-center">
                        <small class="ml-1">{{$job->address}}</small>
                    </div>
                    <div class="d-flex justify-content-between mt-3"> <span>${{number_format($job->salary,2)}}</span>
                        <a href="{{route('job.show',[$job->slug])}}"><button class="btn btn-dark">Apply Now</button> </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="container">
    <div class="mt-5">
        <h3>Recent openning</h3>
    </div>
    @foreach(\App\Models\Listing::take(3)->orderBy('id','DESC')->get() as $listing)
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex flex-column flex-lg-row">

                <div class="row flex-fill">
                    <div class="col-sm-5">
                        <h4 class="h5">{{$listing->title}}</h4>
                        <span class="badge bg-secondary">{{$listing->job_type}}</span> <span class="badge bg-success">${{number_format($listing->salary,2)}}</span>
                    </div>

                    <div class="col-sm-7 text-lg-end">
                        <a href="{{route('job.show',$listing->slug)}}" class="btn btn-dark stretched-link">View</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

</div>

<style>
    /* .Fulltime {
        background-color: green;
        color: #fff;
    }

    .Parttime {
        background-color: blue;
        color: #fff;
    }

    .Casual {
        background-color: red;
        color: #fff;
    }

    .Contract {
        background-color: purple;
        color: #fff;
    } */

    .centered-content {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100px;
        background-color: #f5f5f5;
    }
</style>
@endsection
