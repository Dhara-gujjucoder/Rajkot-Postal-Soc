@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
    <div class="page-wrapper page-wrapper-gc">
        <!-- Page body -->
        <div class="page-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">

                        {{-- @php print_r($errors); @endphp --}}

                        <form method="POST" action="{{ route('setting.update',1) }}" enctype="multipart/form-data"
                            class="user-create-form">
                            @csrf

                            <div class="card">
                                <div class="card-header justify-content-between">
                                    <h4 class="card-title"><b>{{ __('Site Setting') }}</b></h4>
                                </div>


                                <div class="card-body">
                                    <div class="form-group mb-3 row">
                                        <label class="col-2 col-form-label">{{ __('Favicon') }}</label>

                                        <div class="col-5">
                                            <input type="file" name="favicon" accept="image/png, image/gif, image/jpeg"
                                                class="form-control @error('favicon') is-invalid @enderror">

                                            {{-- <input type="hidden" name="old_favicon" value="{{ $setting->favicon }}"
                                                class="form-control @error('favicon') is-invalid @enderror"> --}}

                                            @if ($setting->favicon)
                                                <img src="{{ asset($setting->favicon) }}" height="100" width="100">
                                            @endif

                                                @error('favicon')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                        </div>
                                    </div>

                                    <div class="form-group mb-3 row">
                                        <label class="col-2 col-form-label">{{ __('Site Title') }}</label>

                                        <div class="col-5">
                                            <input type="text" name="title" id="title"
                                                class="form-control @error('title') is-invalid @enderror"
                                                value="{{ old('title', isset($setting->title) ? $setting->title : '') }}">

                                            @error('title')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group mb-3 row">
                                        <label class="col-2 col-form-label">{{ __('Logo') }}</label>

                                        <div class="col-5">
                                            <input type="file" name="logo" accept="image/png, image/gif, image/jpeg"
                                                class="form-control @error('logo') is-invalid @enderror"
                                                value="{{ old('logo') }}">
                                            {{-- <input type="hidden" name="logo" value="{{ $setting->logo }}"
                                                class="form-control @error('logo') is-invalid @enderror"> --}}

                                            @if ($setting->logo)
                                                <img src="{{ asset($setting->logo) }}" height="100" width="100">
                                            @endif

                                            @if (empty($setting->logo))
                                                @error('logo')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            @else
                                                @error('logo')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">{{ __('Save')}}</button>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header justify-content-between">
                                    <h4 class="card-title"><b>{{ __('Contact Details') }}</b></h4>
                                </div>
                                <div class="card-body">



                                    <div class="form-group mb-3 row">
                                        <label class="col-2 col-form-label">{{ __('Phone No') }}</label>
                                        <div class="col-5">
                                            <input type="number" name="phone"
                                                class="form-control @error('phone') is-invalid @enderror"
                                                value="{{ old('phone', isset($setting->phone) ? $setting->phone : '') }}">
                                            @error('phone')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group mb-3 row">
                                        <label class="col-2 col-form-label">{{ __('Email') }}</label>

                                        <div class="col-5">
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', isset($setting->email) ? $setting->email : '') }}">
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group mb-3 row">
                                        <label class="col-2 col-form-label">{{ __('Address') }}</label>

                                        <div class="col-5">
                                            <textarea type="address" name="address" class="form-control @error('address') is-invalid @enderror">{{ old('address', isset($setting->address) ? $setting->address : '') }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">{{ __('Save') }}</button>
                                    </div>
                                </div>
                            </div>
                             <div class="card">
                                <div class="card-header justify-content-between">
                                    <h4 class="card-title"><b>{{ __('Member Setting') }}</b></h4>
                                </div>
                                <div class="card-body">

                                    <div class="form-group mb-3 row">
                                        <label class="col-2 col-form-label">{{ __('Registration Fee') }} </label>

                                        <div class="col-5">
                                            <input type="number" name="registration_fee"
                                                class="form-control @error('registration_fee') is-invalid @enderror" placeholder="{{__('Registration Fees')}}"
                                                value="{{ old('registration_fee', isset($setting->registration_fee) ? $setting->registration_fee : '') }}">
                                            @error('registration_fee')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">{{ __('Save')}}</button>
                                    </div>
                                </div>
                            </div>


                                   {{-- <div class="form-group mb-3 row">
                                        <label class="col-2 col-form-label">{{ __('Monthly Saving') }}</label>
                                        <div class="col-5">
                                            <input type="number" name="monthly_saving"
                                                class="form-control @error('monthly_saving') is-invalid @enderror" placeholder="{{__('Monthly Saving')}}"
                                                value="{{ old('monthly_saving', isset($setting->monthly_saving) ? $setting->monthly_saving : '') }}">
                                            @error('monthly_saving')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group mb-3 row">
                                        <label class="col-2 col-form-label">{{ __('Share amount') }}</label>
                                        <div class="col-5">
                                            <input type="number" name="share_amount"
                                                class="form-control @error('share_amount') is-invalid @enderror" placeholder="{{ __('Share amount') }}"
                                                value="{{ old('share_amount', isset($setting->share_amount) ? $setting->share_amount : '') }}">
                                            @error('share_amount')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">{{ __('Save') }}</button>
                                    </div>
                                </div>
                            </div> --}}

                            {{-- <div class="card">
                                <div class="card-header justify-content-between">
                                    <h4 class="card-title"><b>{{ __('Copyright Message') }}</b></h4>
                                </div>
                                <div class="card-body">

                                    <div class="form-group mb-3 row">
                                        <label class="col-2 col-form-label">{{ __('Message') }}</label>

                                        <div class="col-7">
                                            <textarea type="text" class="form-control @error('copyright_msg') is-invalid @enderror" name="copyright_msg">{{ old('copyright_msg', isset($setting->copyright_msg) ? $setting->copyright_msg : '') }}</textarea>

                                            @error('copyright_msg')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">{{ __('Save') }}</button>
                                    </div>
                                </div>
                            </div> --}}


                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection

