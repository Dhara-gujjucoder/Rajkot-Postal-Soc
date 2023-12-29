@extends('front.layouts.app')
@section('content')
    <style>
      
    </style>
    <div class="container">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <div class="">
            <div class="row mb-3">
                <label for="name"
                    class="col-md-4 col-form-label text-md-end">{{ __('Registration No') }}</label>

                <div class="col-md-6">
                    <input id="name" type="text"
                        class="form-control @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name', $user->member->registration_no) }}" required autocomplete="name" autofocus>

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <label for="name"
                    class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                <div class="col-md-6">
                    <input id="name" type="text"
                        class="form-control @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            
            <div class="row mb-3">
                <label for="email"
                class="col-md-4 col-form-label text-md-end">{{ __('Email') }}</label>

                <div class="col-md-6">
                    <input id="email" type="email" placeholder="{{ __('Email') }}"
                    class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email', $user->email) }}" required autocomplete="email">
                    
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="name"
                        class="col-md-4 col-form-label text-md-end">{{ __('Mobile No') }}</label>
    
                    <div class="col-md-6">
                        <input id="mobile_no" type="text"
                            class="form-control @error('mobile_no') is-invalid @enderror" name="name"
                            value="{{ old('mobile_no', $user->member->mobile_no) }}" required autocomplete="name" autofocus>
    
                        @error('mobile_no')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="name"
                        class="col-md-4 col-form-label text-md-end">{{ __('Whatsapp No') }}</label>
    
                    <div class="col-md-6">
                        <input id="whatsapp_no" type="text"
                            class="form-control @error('whatsapp_no') is-invalid @enderror" name="name"
                            value="{{ old('whatsapp_no', $user->member->whatsapp_no) }}" required autocomplete="name" autofocus>
    
                        @error('whatsapp_no')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="name"
                        class="col-md-4 col-form-label text-md-end">{{ __('Birth Date') }}</label>
    
                    <div class="col-md-6">
                        <input id="birthdate" type="text"
                            class="form-control @error('birthdate') is-invalid @enderror" name="name"
                            value="{{ old('birthdate', $user->member->birthdate) }}" required autocomplete="name" autofocus>
    
                        @error('birthdate')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>

          

          

           
        </div>
    </div>
@endsection
