<header class="header-bottom-borader position-fixed w-100">
    <div class="container">
        <div class="header-height d-flex align-items-center justify-content-between">
            <div class="tab-logo"><a href="dashboard.html"><img src="{{ asset('front/images/logo.png') }}"
                        alt="" /></a></div>

            <div class="topbar-right-sec">
                <div class="language">
                    <input type="checkbox" data-toggle="switchbutton" id="switch_language"  data-onlabel="English"
                        data-offlabel="Gujarati" data-style="ios" {{ app()->getLocale() == 'en' ? 'checked' : ''}}>
                </div>
                <div class="user-image">
                    <a href="{{ route('user.profile') }}"><img src="{{ asset('front/images/avtar-image.jpg') }}"
                            alt=""></a>
                </div>
                <div id="cssmenu">
                    <ul>
                        <li><a href="{{ route('user.home') }}">{{__('Dashboard')}}</a></li>
                        <li><a href="{{ route('user.profile') }}">{{__('My Profile')}}</a></li>
                        <li><a href="my-account.html">{{__('My Account')}}</a></li>
                        <li><a href="loan-account.html">{{__('Loan Account')}}</a></li>
                        <li><a href="loan-calculator.html">{{__('Loan Calculator')}}</a></li>
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