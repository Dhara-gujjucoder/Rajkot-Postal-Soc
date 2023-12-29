<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="{{ asset('front/images/favicon.png') }}">
    <title>Rajkot Postal SOC</title>
    <link href="{{ asset('front/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/cssmenu-styles.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/responsive.css') }}" rel="stylesheet">
</head>

<body>
    <div class="menu-overlay"></div>
    <div class="main-wrapper">
        <!-- <div class="cor-img-pos top-vec-img"></div> -->
        <header class="header-bottom-borader position-fixed w-100">
            <div class="container">
                <div class="header-height d-flex align-items-center justify-content-between">
                    <div class="tab-logo"><a href="dashboard.html"><img src="{{ asset('front/images/logo.png') }}"
                                alt="" /></a></div>

                    <div class="topbar-right-sec">
                        <div class="language">
                            <input type="checkbox" data-toggle="switchbutton" checked data-onlabel="English"
                                data-offlabel="Gujarati" data-style="ios">
                        </div>
                        <div class="user-image">
                            <a href="myprofile.html"><img src="{{ asset('front/images/avtar-image.jpg') }}"
                                    alt=""></a>
                        </div>
                        <div id="cssmenu">
                            <ul>
                                <li><a href="dashboard.html">Dashboard</a></li>
                                <li><a href="myprofile.html">My Profile</a></li>
                                <li><a href="my-account.html">My Account</a></li>
                                <li><a href="loan-account.html">Loan Account</a></li>
                                <li><a href="loan-calculator.html">Loan Calculator</a></li>
                                <li>
                                    <a href="{{ route('user.logout') }}" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Logout"
                                        onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
            
                                    <form id="logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
            
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="main-middle-section inner-main-PTB">
            <div class="container">
                <div class="dashboard-box-listing">
                    <div class="desh-listbox skybluebg-box">
                        <a href="myprofile.html">
                            <div class="dash-box-title">My Profile</div>
                            <div class="dash-icon"><img src="{{ asset('front/images/user.svg') }}" alt=""></div>
                        </a>
                    </div>

                    <div class="desh-listbox greenbg-box">
                        <a href="my-account.html">
                            <div class="dash-box-title">My Account</div>
                            <div class="dash-icon"><img src="{{ asset('front/images/my-account.svg') }}"
                                    alt=""></div>
                        </a>
                    </div>
                    <div class="desh-listbox bluebg-box">
                        <a href="loan-account.html">
                            <div class="dash-box-title">Loan Account</div>
                            <div class="dash-icon"><img src="{{ asset('front/images/pig-money.svg') }}" alt="">
                            </div>
                        </a>
                    </div>
                    <div class="desh-listbox orangebg-box">
                        <a href="loan-calculator.html">
                            <div class="dash-box-title">Loan Calculator</div>
                            <div class="dash-icon"><img src="{{ asset('front/images/calculator.svg') }}"
                                    alt=""></div>
                        </a>
                    </div>

                    <div class="applyloanbtn mt-5">
                        <a href="" class="btn btn-primary w-100">
                            Apply for Loan
                        </a>
                    </div>
                   
                </div>
            </div>
        </div>
        <div class="cor-img-pos bottom-vec-img"></div>
    </div>
    <script src="{{ asset('front/js/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('front/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('front/js/cssmenu-script.js') }}"></script>
    <script src="{{ asset('front/js/wow.js') }}"></script>
    <script src="{{ asset('front/js/main.js') }}"></script>
    <script src="{{ asset('front/js/bootstrap-switch-button.min.js') }}"></script>
</body>

</html>
