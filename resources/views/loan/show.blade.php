@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="float-start">
                        {{-- User Information --}}
                    </div>
                    <div class="float-end">
                        <a href="{{ route('loan.index') }}" class="btn btn-primary btn-sm">&larr; {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <label for="name"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Loan A/c') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $loan->loan_no }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="name"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Name') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $loan->member->name }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Principal Amount') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            &#8377;{{ $loan->principal_amt }}
                        </div>
                    </div>


                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('EMI Amount') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            &#8377;{{ $loan->emi_amount }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Stamp Duty') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            &#8377;{{ $loan->stamp_duty }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Total Amount') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            &#8377;{{ $loan->total_amt }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Payment Type') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ __($loan->payment_type) }}
                        </div>
                    </div>
                    @if ($loan->payment_type == 'cheque')
                        <div class="mb-3 row">
                            <label for="email"
                                class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Bank Name') }}:</strong></label>
                            <div class="col-md-6" style="line-height: 35px;">
                                {{ __($loan->bank_name) }}
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="email"
                                class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Cheque No.') }}:</strong></label>
                            <div class="col-md-6" style="line-height: 35px;">
                                {{ __($loan->cheque_no) }}
                            </div>
                        </div>
                    @endif
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Guarantor 1') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $loan->guarantor1->name }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Guarantor 2') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $loan->guarantor2->name }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Guarantee Payee Cheque No') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $loan->gcheque_no }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Guarantee Payee Bank Name') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $loan->gbank_name }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Status') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $loan->status }}
                        </div>
                    </div>
                    <div class="mb-3 mt-5 row" >
                        <h5 class="form-header"><b>{{ __('Loan EMI') }}:</b></h5>
                        {{-- <label for="email"
                            class="col-md-12 col-form-label text-md-end text-start"><strong>{{ __('Loan EMI') }}:</strong></label> --}}
                        <div class="col-md-12" style="line-height: 35px;">
                            <div class="table-responsive">
                                <table class="table table-borderd">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Month</th>
                                            <th>EMI amount</th>
                                            <th>Interest</th>
                                            <th>Principal</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($loan->loan_emis_d as $key => $emi)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ date('M-Y',strtotime('01-'.$emi->month)) }}</td>
                                                <td>{{ $emi->emi }}</td>
                                                <td>{{ $emi->interest_amt }}</td>
                                                <td>{{ $emi->rest_principal }}</td>
                                                <td>{{ $emi->installment }}</td>
                                                <td>{{ $emi->status }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
