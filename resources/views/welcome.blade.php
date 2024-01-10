<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="images/favicon.png">
    <title>Rajkot Postal SOC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<style type="text/css">
    body {
        margin: 0;
        padding: 0;
        text-align: center;
        background: url('{{ asset('front/images/bodybg.png') }}') no-repeat center center;
        background-size: cover;
        font-family: 'Roboto', sans-serif;
    }

    .page-flex {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        text-align: center;
        padding: 15px;
    }

    img {
        max-width: 100%;
        border: none;
    }

    .coming-soon-text h1 {
        font-size: 70px;
        font-weight: 900;
        margin: 20px 0px 10px 0px;
        text-align: center;
        text-transform: uppercase;
        line-height: 110%;
        letter-spacing: 10px;
    }

    .coming-soon-text {
        font-size: 20px;
        text-align: center;
        letter-spacing: 1px;
        line-height: 30px;
    }
</style>
</head>

<body>
    <div class="page-flex">
        <div>
           
            <img src="{{ asset('front/images/logo.png') }}" alt="" />
            <div class="coming-soon-text">
                <p>Webportal Launching Soon</p>
                {{-- @if (Route::has('login'))
                <div class="">
                    @auth
                        <a href="{{ route('user.home') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Home</a>
                    @else
                        <a href="{{ route('user.login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                    @endauth
                </div>
            @endif --}}
            </div>
        </div>
    </div>
</body>

</html>
