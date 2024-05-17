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
    <div class="main-wrapper d-flex align-items-center w-10 h-10 login-PB">
        <div class="cor-img-pos top-vec-img"></div>
        <div class="main-middle-section">
            <div class="container text-center">

                <h3>Hii, {{$member_name}}</h3>
                Please <a href="{{ route('reset.password.get', $token) }}">click here </a>for reset your password.
                <br> <br>
                Thanks<br>
                Support Rajkotpostalsoc.
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
