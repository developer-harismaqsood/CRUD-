@extends('./layouts.app')

@section('title', 'Login | Megadesk Chat')
@section('content')
<!-- Login form -->
<form class="login-form mt-4 mb-4 m-auto" action="{{ route('login') }}" method="post">
	@csrf
	<div class="card mb-0">
		<div class="card-body">
			<div class="text-center mb-3">
				<div class="d-inline-flex align-items-center justify-content-center mb-4 mt-2">
					<img src="{{asset('assets/images/logo_icon.svg')}}" class="h-48px" alt="">
				</div>
				<h5 class="mb-0">Login to your account</h5>
				<span class="d-block text-muted">Enter your credentials below</span>
			</div>

			<div class="mb-3">
				<label class="form-label">Email</label>
				<div class="form-control-feedback form-control-feedback-start">
					<input type="text" name="email" class="form-control" placeholder="john@doe.com">
					<div class="form-control-feedback-icon">
						<i class="ph-user-circle text-muted"></i>
					</div>
				</div>
				@if ($errors->has('email'))
				<div class="form-text text-danger"><i class="ph-x-circle me-1"></i>{{ $errors->first('email') }}</div>
				@endif
			</div>

			<div>
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

			<a href="my-account/password-forget" class="mt-0">Forgotten Password?</a>

			<div class="mb-3">
				<button type="submit" class="btn btn-primary w-100 mt-3">Sign in</button>
			</div>

			<!-- <div class="text-center">
				<a href="#">Forgot password?</a>
			</div> -->
		</div>
	</div>
</form>
<!-- /login form -->
@endsection
