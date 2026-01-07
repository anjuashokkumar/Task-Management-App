@extends('layout')

@section('content')

<div class="login-wrap">
	<div class="login-html">
		<input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab"><a class="nav-link text-white" href="{{ route('login') }}">Login</a></label>
		<input id="tab-2" type="radio" name="tab" class="for-pwd"><label for="tab-2" class="tab"><a class="nav-link text-dark" href="{{ route('register') }}">Register</a></label>
		<div class="login-form">
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="sign-in-htm">
                    @if ($errors->any())
                        <div class="alert alert-danger text-center">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <div class="group">
                        <label for="user" class="label">Email</label>
                        <input id="user" type="text" name="email" class="input">
                    </div>
                    <div class="group">
                        <label for="pass" class="label">Password</label>
                        <input id="pass" type="password" name="password" class="input" data-type="password">
                    </div>
                    <div class="group">
                        <input type="submit" class="button" value="Login">
                    </div>
                    <div class="hr"></div>
                </div>
            </form>
		</div>
	</div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999">
    <div id="successToast" class="toast fade align-items-center text-bg-success border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function showToast(message) {
        const toastEl = document.getElementById('successToast');
        if (!toastEl) return;

        document.getElementById('toastMessage').innerText = message;

        const toast = new bootstrap.Toast(toastEl, {
            delay: 3000
        });

        toast.show();
    }
</script>
@if(session('success'))
<script>
    showToast("{{ session('success') }}");
</script>
@endif
@endsection