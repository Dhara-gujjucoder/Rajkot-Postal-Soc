
@extends('front.layouts.app')
@section('content')
<div class="container">
    <div class="dashboard-box-listing">
        <div class="desh-listbox skybluebg-box wow fadeInRight" data-wow-delay="0.4s">
            <a href="{{ route('user.profile') }}">
                <div class="dash-box-title">{{ __('My Profile') }}</div>
                <div class="dash-icon"><img src="{{ asset('front/images/user.svg') }}" alt=""></div>
            </a>
        </div>

        <div class="desh-listbox greenbg-box wow fadeInLeft" data-wow-delay="0.4s">
            <a href="my-account.html">
                <div class="dash-box-title">{{ __('My Account') }}</div>
                <div class="dash-icon"><img src="{{ asset('front/images/my-account.svg') }}" alt=""></div>
            </a>
        </div>
        <div class="desh-listbox bluebg-box wow fadeInRight" data-wow-delay="0.4s">
            <a href="loan-account.html">
                <div class="dash-box-title">{{ __('Loan Account') }}</div>
                <div class="dash-icon"><img src="{{ asset('front/images/pig-money.svg') }}" alt="">
                </div>
            </a>
        </div>
        <div class="desh-listbox orangebg-box wow fadeInLeft" data-wow-delay="0.4s">
            <a href="loan-calculator.html">
                <div class="dash-box-title">{{ __('Loan Calculator') }}</div>
                <div class="dash-icon"><img src="{{ asset('front/images/calculator.svg') }}" alt=""></div>
            </a>
        </div>

        <div class="applyloanbtn mt-5 wow fadeInUp" data-wow-delay="0.4s">
            <a href="" class="btn btn-primary w-100">
                {{ __('Apply for Loan') }}
            </a>
        </div>

    </div>
</div>
@endsection
