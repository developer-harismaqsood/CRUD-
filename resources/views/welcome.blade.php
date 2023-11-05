@extends('layouts.app')
@section('title', 'Home | Company CRM')
@section('content')
<div class="mb-0 text-center" style="margin-top:17em;">
    <h1>Welcome To Company CRM</h1>
    <div class="card-body">
        @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
        @endif
        @auth()
        {{ __('You are logged in!') }}
        @endauth
    </div>
</div>
<div class="navbar navbar-sm navbar-footer border-top" style="position: absolute;bottom: 0px;width: 100%;">
    <div class="container">
        <span class="footer-text">&copy; 2023 <a href="http://www.mdcdev.xyz">Company CRM</a></span>
    </div>
</div>
@endsection