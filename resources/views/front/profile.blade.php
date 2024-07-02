@extends('front.layouts.app')
@section('content')
    <style>

    </style>
    <div class="dashboard-detail-page">
        <div class="container">
            <div class="dashboard-box-listing">
                <div class="desh-listbox skybluebg-box wow fadeInRight" data-wow-delay="0.2s">
                    <div class="dash-box-title">{{ __('Personal Details') }}</div>
                </div>
                <div class="row wow fadeInRight" data-wow-delay="0.2s">
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Name') }}:</strong></label>
                            <div class="col-form-info">{{ $user->name }}</div>
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Gender') }}:</strong></label>
                            <div class="col-form-info">{{ $member->gender }}</div>
                        </div>
                    </div> --}}
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Email Address') }}:</strong></label>
                            <div class="col-form-info">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Birth Date') }}:</strong></label>
                            <div class="col-form-info">{{ $member->birthdate }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Mobile No') }}:</strong></label>
                            <div class="col-form-info">{{ $member->mobile_no }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Whatsapp No') }}:</strong></label>
                            <div class="col-form-info">{{ $member->whatsapp_no }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Current Address') }}:</strong></label>
                            <div class="col-form-info">{{ $member->current_address }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Parmenant Address') }}:</strong></label>
                            <div class="col-form-info">{{ $member->parmenant_address }}</div>
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Profile Picture') }}:</strong></label>
                            @if (!empty($member->profile_picture))
                                <div class="col-form-info">
                                    <img src="{{ asset($member->profile_picture) }}" alt="">
                                </div>
                            @endif
                        </div>
                    </div>--}}

                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Signature') }}:</strong></label>
                            @if (!empty($member->signature))
                                <div class="col-form-info">
                                    <a href="{{ asset($member->signature) }}" target="_blank"><img src="{{ asset($member->signature) }}" alt=""></a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="desh-listbox skybluebg-box pt-2 mt-4 wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="dash-box-title">{{ __('Work Details') }}</div>
                </div>
                <div class="row wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Company') }}:</strong></label>
                            <div class="col-form-info">{{ $member->company }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Designation') }}:</strong></label>
                            <div class="col-form-info">{{ $member->designation }}</div>
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                    <div class="dashboard-detail-data">
                    <label class="col-form-label"><strong>{{ __('Email Address') }}:</strong></label>
                    <div class="col-form-info">coming soon...</div>
                    </div>
                </div> --}}
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Salary') }}:</strong></label>
                            <div class="col-form-info">{{ $member->salary }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('DA') }}:</strong></label>
                            <div class="col-form-info">{{ $member->da }}</div>
                        </div>
                    </div>

                </div>

                <div class="desh-listbox skybluebg-box pt-2 mt-4 wow fadeInRight" data-wow-delay="0.2s">
                    <div class="dash-box-title">{{ __('Other Details') }}</div>
                </div>
                <div class="row wow fadeInRight" data-wow-delay="0.2s">
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Nominee Name') }}:</strong></label>
                            <div class="col-form-info">{{ $member->nominee_name }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Nominee Relation') }}:</strong></label>
                            <div class="col-form-info">{{ $member->nominee_relation }}</div>
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Witness Signature') }}:</strong></label>
                            @if (!empty($member->witness_signature))
                                <div class="col-form-info">
                                    <img src="{{ asset($member->witness_signature) }}" alt="">
                                </div>
                            @endif
                        </div>
                    </div> --}}
                </div>

                <div class="desh-listbox skybluebg-box pt-2 mt-4 wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="dash-box-title">{{ __('Bank Details') }}</div>
                </div>
                <div class="row wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Saving Account No') }}:</strong></label>
                            <div class="col-form-info">{{ $member->saving_account_no }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Bank Name') }}:</strong></label>
                            <div class="col-form-info">{{ $member->bank_name }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Branch Address') }}:</strong></label>
                            <div class="col-form-info">{{ $member->branch_address }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('IFSC Code') }}:</strong></label>
                            <div class="col-form-info">{{ $member->ifsc_code }}</div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
