<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="
      avicon.png">
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
                    <h3>Enter Your Registration Number</h3>
                    <p>We will send you a Confirmation Code</p>
                </div>
                <div class="mob-login-form px-3">
                    <form action="{{ route('user.login') }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <input type="input" name="registration_no" class="form-control input-user-icon input-bottom-border @error('registration_no') is-invalid @enderror" value="{{ old('registration_no') }}" placeholder="Registration Number">

                            @error('registration_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-5">
                            <input type="password" name="password" class="form-control input-user-icon input-bottom-border @error('password') is-invalid @enderror" value="{{ old('password') }}" id="password" placeholder="Password"><i class="toggle-password fa fa-eye-slash" id="togglePassword" toggle="#password"></i>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <input type="submit" class="btn btn-primary w-100" value="Submit">
                    </form>
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
        // const icon = document.getElementById('togglePassword');
        // let password = document.getElementById('password');

        // /* Event fired when <i> is clicked */
        // icon.addEventListener('click', function() {
        //     if (password.type === "password") {
        //         password.type = "text";
        //         icon.classList.add("fa-eye-slash");
        //         icon.classList.remove("fa-eye");
        //     } else {
        //         password.type = "password";
        //         icon.classList.add("fa-eye");
        //         icon.classList.remove("fa-eye-slash");
        //     }
        // });
    </script>
</body>

</html>
