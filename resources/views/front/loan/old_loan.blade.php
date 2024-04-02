@extends('front.layouts.app')
@section('content')
    <style>
    </style>
    <div class="dashboard-detail-page apply-detail-page">
        <div class="container">
            <div class="dashboard-box-listing">
                <div class="desh-listbox skybluebg-box wow fadeInDown" data-wow-delay="0.2s">
                    <div class="dash-box-title">{{ __('Personal Details') }}</div>
                </div>

                <div class="row wow fadeInUp" data-wow-delay="0.2s">
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Name') }}:</strong></label>
                            <div class="col-form-info">{{ $user->name }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('PAN Number') }}:</strong></label>
                            <div class="col-form-info">{{ $member->pan_no }}</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>{{ __('Present Address') }}:</strong></label>
                            <div class="col-form-info">{{ $member->parmenant_address }}</div>
                        </div>
                    </div>
                </div>

                <div class="desh-listbox skybluebg-box wow fadeInRight" data-wow-delay="0.2s">
                    <div class="dash-box-title">{{ __('Your Old Loan Detail') }}</div>
                </div>
                <div class="wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="loan-table">
                        @if ($loan->loan_emis)
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        {{-- <th>{{ __('No.')}}</th> --}}
                                        <th>{{ __('Month')}}</th>
                                        <th>{{ __('EMI Amount')}}</th>
                                        <th>{{ __('Interest')}}</th>
                                        <th>{{ __('Principal')}}</th>
                                        <th>{{ __('Payment')}}</th>
                                        <th>{{ __('Status')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach ($loan->loan_emis as $key => $emi)
                                            <tr>
                                                {{-- <td data-label="No.">{{ $key+1 }}</td> --}}
                                                <td data-label="Month">{{ date('M-Y',strtotime('01-'.$emi->month)) }}</td>
                                                <td data-label="EMI Amount">{{ $emi->emi }}</td>
                                                <td data-label="Interest">{{ $emi->interest_amt }}</td>
                                                <td data-label="Principal">{{ $emi->rest_principal }}</td>
                                                <td data-label="Payment">{{ $emi->installment }}</td>
                                                <td data-label="Status">{{ $emi->status }}</td>
                                            </tr>
                                        @endforeach
                                </tbody>
                            </table>
                        @else
                            {{ 'no any loan' }}
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
