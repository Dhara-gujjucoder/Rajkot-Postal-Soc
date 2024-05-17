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
                <div class="login-title mb-4">
                    <h3>Reset your Password</h3>
                    
                </div>


                <div class="mob-login-form px-3">
                    @if (Session::has('message'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    <form action="{{ route('forget.password.post') }}" method="POST">
                        @csrf
                        {{-- <div class="mb-5">
                            <input type="text" id="email_address" name="email" class="form-control input-user-icon input-bottom-border " required autofocus placeholder="Email Address">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div> --}}
                        <div class="mb-5">
                            <input type="text" id="reg_no" name="reg_no" class="form-control input-user-icon input-bottom-border " required autofocus placeholder="Registration No">

                            @if ($errors->has('reg_no'))
                                <span class="text-danger">{{ $errors->first('reg_no') }}</span>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100">Send Link</button>
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
