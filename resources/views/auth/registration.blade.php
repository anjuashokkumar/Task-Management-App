@extends('layout')

@section('content')

<div class="login-wrap">
	<div class="login-html">
		<input id="tab-1" type="radio" name="tab" class="sign-in"><label for="tab-1" class="tab"><a class="nav-link text-dark" href="{{ route('login') }}">Login</a></label>
		<input id="tab-2" type="radio" name="tab" class="for-pwd" checked><label for="tab-2" class="tab"><a class="nav-link text-white" href="{{ route('register') }}">Register</a></label>
		<div class="login-form">
            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="for-pwd-htm">
                    <div class="group">
                        <label for="user" class="label">Name</label>
                        <input id="user" type="text" class="input" name="name" value="{{ old('name') }}">
                        @if ($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                    </div>
                    <div class="group">
                        <label for="user" class="label">E-Mail Address</label>
                        <input id="user" type="text" name="email" class="input" value="{{ old('email') }}">
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="group position-relative">
                        <label for="pass" class="label">Password</label>
                        <input id="password" type="password" name="password" class="input" data-type="password">
                        <i class="fas fa-eye toggle-password" data-target="password"></i>
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="group position-relative">
                        <label for="password_confirmation" class="label">Confirm Password</label>
                        <input id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            class="input"
                            data-type="password">
                            <i class="fas fa-eye toggle-password" data-target="password_confirmation"></i>

                        @if ($errors->has('password_confirmation'))
                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </div>
                    <div class="group">
                        <input type="submit" class="button" value="Register">
                    </div>
                    <div class="hr"></div>
                </div>
            </form>
		</div>
	</div>
</div>

@endsection

@section('scripts')
<script>
    $(document).on('click', '.toggle-password', function () {
        let target = $(this).data('target');
        let input = $('#' + target);

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
</script>
@endsection