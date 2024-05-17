<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="avicon.png">
    <title>{{ $setting->title ?? 'Rajkot Postal SOC' }}</title>
    <link rel="shortcut icon" href="{{ asset($setting->favicon) }}">
    <link href="{{ asset('front/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/responsive.css') }}" rel="stylesheet">
</head>

<body class="login-page">
    <div class="main-wrapper d-flex align-items-center w-100 h-100 login-PB">
        <div class="cor-img-pos top-vec-img"></div>
        <div class="main-middle-section">
            <div class="container text-center">
                <div class="login-logo mb-4 wow fadeInDown" data-wow-delay="0.4s"><img src="{{ asset('front/images/logo.png') }}" alt=""></div>


                <div class="mob-login-form px-3">
                    <form action="{{ route('reset.password.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        {{-- <div class="mb-5">
                            <input type="text" id="email_address" name="email" class="form-control input-user-icon input-bottom-border " required autofocus placeholder="E-Mail Address">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div> --}}
                        <div class="mb-4">
                            <input type="text" id="reg_no" name="reg_no" class="form-control input-user-icon input-bottom-border " required autofocus placeholder="Registration No">

                            @if ($errors->has('reg_no'))
                                <span class="text-danger">{{ $errors->first('reg_no') }}</span>
                            @endif
                        </div>

                        <div class="mb-4 eye-settle">
                            <input type="password" id="password" name="password" class="form-control input-user-icon input-bottom-border " required autofocus placeholder="Password"><i class="toggle-password fa fa-eye-slash" id="togglePassword" toggle="#password"></i>

                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>

                        <div class="mb-5 eye-settle">
                            <input type="password" id="password_confirm" name="password_confirmation" class="form-control input-user-icon input-bottom-border " required autofocus placeholder="Confirm Password"><i class="toggle-password fa fa-eye-slash" id="togglePasswordConfirm" toggle="#password_confirm"></i>

                            @if ($errors->has('password_confirmation'))
                                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100">
                                Reset Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <div class="cor-img-pos bottom-vec-img"></div>
    </div>
    <script src="{{ asset('front/js/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('front/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('front/js/wow.js') }}"></script>
    <script src="{{ asset('front/js/main.js') }}"></script>
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
</body>

</html>
