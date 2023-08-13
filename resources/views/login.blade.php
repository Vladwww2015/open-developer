<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>{{config('developer.title')}} | {{ __('developer.login') }}</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

		@if(!is_null($favicon = Developer::favicon()))
		<link rel="shortcut icon" href="{{$favicon}}">
		@endif

		<link rel="stylesheet" href="{{ Developer::asset("open-developer/css/styles.css")}}">
		<script src="{{ Developer::asset("bootstrap5/bootstrap.bundle.min.js")}}"></script>

	</head>
	<body class="bg-light" @if(config('developer.login_background_image'))style="background: url({{config('developer.login_background_image')}}) no-repeat;background-size: cover;"@endif>
		<div class="d-flex justify-content-center align-items-center h-100">
			<div class="container m-4" style="max-width:400px;">
				<h1 class="text-center mb-3 h2"><a class="text-decoration-none text-dark" href="{{ developer_url('/') }}">{{config('developer.name')}}</a></h1>
				<div class="bg-body p-4 shadow-sm rounded-3">

					@if($errors->has('attempts'))
						<div class="alert alert-danger m-0 text-center">{{$errors->first('attempts')}}</div>
					@else

					<form action="{{ developer_url('auth/login') }}" method="post">

						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<div class="mb-3">

							@if($errors->has('username'))
								<div class="alert alert-danger">{{$errors->first('username')}}</div>
							@endif

							<label for="username" class="form-label">{{ __('developer.username') }}</label>
							<div class="input-group mb-3">
								<span class="input-group-text"><i class="icon-user"></i></span>
								<input type="text" class="form-control" placeholder="{{ __('developer.username') }}" name="username" id="username" value="{{ old('username') }}" required>
							</div>
						</div>

						<div class="mb-3">
							<label for="password" class="form-label">{{ __('developer.password') }}</label>
							<div class="input-group mb-3">
								<span class="input-group-text"><i class="icon-eye"></i></span>
								<input type="password" class="form-control" placeholder="{{ __('developer.password') }}" name="password" id="password" required>
							</div>

							@if($errors->has('password'))
								<div class="alert alert-danger">{{$errors->first('password') }}</div>
							@endif
						</div>

						@if(config('developer.auth.remember'))
						<div class="mb-3 form-check">
							<input type="checkbox" class="form-check-input" name="remember" id="remember" value="1"  {{ (old('remember')) ? 'checked="checked"' : '' }}>
							<label class="form-check-label" for="remember">{{ __('developer.remember_me') }}</label>
						</div>
						@endif

						<div class="clearfix">
							<button type="submit" class="btn float-end btn-secondary">{{ __('developer.login') }}</button>
						</div>

					</form>
					@endif
				</div>
			</div>
		</div>
	</body>
</html>
