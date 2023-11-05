@extends('layouts.app')
@section('title', 'Register | Megadesk Chat')
@section('content')
<!-- Registration form -->
<form class="login-form m-auto mt-3" action="{{ route('register') }}" method="post">
    @csrf
    <div class="card mb-0">
        <div class="card-body">
            <div class="text-center mb-3">
                <div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
                    <img src="{{asset('assets/images/logo_icon.svg')}}" class="h-48px" alt="">
                </div>
                <h5 class="mb-0">Create account</h5>
                <span class="d-block text-muted">All fields are required</span>
            </div>
            <div class="mb-3">
                <label class="form-label">Name</label>
                <div class="form-control-feedback form-control-feedback-start">
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="John Doe">
                    <div class="form-control-feedback-icon">
                        <i class="ph-user-circle text-muted"></i>
                    </div>
                </div>
                @if ($errors->has('name'))
                <div class="form-text text-danger"><i class="ph-x-circle me-1"></i>{{ $errors->first('name') }}</div>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Your email</label>
                <div class="form-control-feedback form-control-feedback-start">
                    <input type="text" name="email" value="{{ old('email') }}" class="form-control" placeholder="john@doe.com">
                    <div class="form-control-feedback-icon">
                        <i class="ph-at text-muted"></i>
                    </div>
                </div>
                @if ($errors->has('email'))
                <div class="form-text text-danger"><i class="ph-x-circle me-1"></i>{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="form-control-feedback form-control-feedback-start">
                    <input type="password" name="password" class="form-control" placeholder="•••••••••••">
                    <div class="form-control-feedback-icon">
                        <i class="ph-lock text-muted"></i>
                    </div>
                </div>
                @if ($errors->has('password'))
                <div class="form-text text-danger"><i class="ph-x-circle me-1"></i>{{ $errors->first('password') }}</div>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <div class="form-control-feedback form-control-feedback-start">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="•••••••••••">
                    <div class="form-control-feedback-icon">
                        <i class="ph-lock text-muted"></i>
                    </div>
                </div>              
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </div>
    </div>
</form>
<!-- /registration form -->
@endsection