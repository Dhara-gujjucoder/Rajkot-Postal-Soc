<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{__('Login')}} - {{'Rajkot Postal Soc.'}}</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png')}}" type="image/x-icon">
    <link rel="shortcut icon"
        href="{{ asset('assets/images/favicon.png')}}"
        type="image/png">
    <link rel="stylesheet" href="{{asset('/assets/compiled/css/app.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/compiled/css/app-dark.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/compiled/css/auth.css')}}">

    <style>
        #auth #auth-right {
            background: url("{{ asset('assets/images/logo.png')}}");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 60%;
            opacity: 0.2;
        }
    </style>

</head>

<body>
    <script src="assets/static/js/initTheme.js"></script>
    <div id="auth">

       @yield('content')

    </div>
</body>

</html>
