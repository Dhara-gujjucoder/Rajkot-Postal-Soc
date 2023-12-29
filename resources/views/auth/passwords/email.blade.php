@extends('layouts.guest')

@section('content')
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo">
                    <a href="index.html"><img src="{{ asset('assets/images/logo.png') }}" alt="Logo"></a>
                    <div class="float-end">
                        <a href="{{ route('home') }}" class="btn btn-primary btn-sm">&larr; {{ __('Back') }}</a>
                    </div>
                </div>
                <h1 class="auth-title">{{ __('Reset Password') }}</h1>
                <p class="auth-subtitle mb-5">{{ __('Provide Email for send password reset link.') }}</p>

                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="row mb-3">
                        <input id="email" type="email"
                            class="form-control form-control-xl  @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" required autocomplete="email" autofocus
                            placeholder="{{ __('Email Address') }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button
                        class="btn btn-primary btn-block btn-lg shadow-lg mt-5">{{ __('Send Password Reset Link') }}</button>

                </form>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">

            </div>
        </div>
    </div>
@endsection
