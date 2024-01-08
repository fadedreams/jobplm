@extends('layouts.app')
@section('content')
<p>account is not verified</p>
<a href="{{route('resend.email')}}">resend</a>
@endsection
