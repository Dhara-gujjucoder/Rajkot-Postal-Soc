@extends('front.layouts.app')
@section('content')
    {{-- {{ dump(session('success')) }} --}}
    <div class="dashboard-detail-page calculator-detail-page">
        <div class="container">

            <div class="desh-listbox skybluebg-box wow fadeInDown" data-wow-delay="0.2s">
                <div class="dash-box-title">{{ __('Change Password') }}</div>
            </div>

            <form action="{{ route('user.password.change') }}" method="post">
                @csrf
                <div class="row mb-3">
                    <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Current Password') }}</label>
                    <div class="col-md-8">
                        <div class="eye-settle">
                            <input id="current_password" type="password" placeholder="{{ __('Current Password') }}" class="form-control @error('current_password') is-invalid @enderror" name="current_password" autocomplete="new-password">
                            <i class="toggle-password fa fa-eye-slash" id="togglePasswordCurrent" toggle="#current_password"></i>
                        </div>
                        @error('current_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('New Password') }}</label>
                    <div class="col-md-8">
                        <div class="eye-settle">

                            <input id="password" type="password" placeholder="{{ __('Password') }}" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                            <i class="toggle-password fa fa-eye-slash" id="togglePassword" toggle="#password"></i>
                        </div>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
                    <div class="col-md-8">
                        <div class="eye-settle">
                            <input id="password_confirm" type="password" placeholder="{{ __('Confirm Password') }}" class="form-control" name="password_confirmation" autocomplete="new-password">
                            <i class="toggle-password fa fa-eye-slash" id="togglePasswordConfirm" toggle="#password_confirm"></i>
                        </div>
                    </div>
                </div>

                <div class="row mb-3 float-end">
                    <button type="submit" class="btn btn-primary">{{ __('Save Password') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $(".toggle-password").click(function() {
                var input = $($(this).attr("toggle"));
                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                    $(this).removeClass('fa-eye-slash').addClass('fa-eye');
                } else {
                    input.attr("type", "password");
                    $(this).removeClass('fa-eye').addClass('fa-eye-slash');
                }
            });
        });
    </script>
@endpush
