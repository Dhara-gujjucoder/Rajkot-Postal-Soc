@extends('layouts.app')
@section('content')


@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header border mb-4">
                    <div class="float-start">
                        <b>{{ __('Personal Details') }}</b>
                    </div>
                    <div class="float-end">
                        <a href="{{ route('members.index') }}" class="btn btn-primary btn-sm">&larr; {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- <div class="mb-3">
                        <label for="email"
                        class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Fixed Saving Opening Balance') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->fixed_saving_ledger_account->opening_balance ?? ''}}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email"
                        class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Share Opening Balance') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->share_ledger_account->opening_balance ?? ''}}
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email"
                        class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Loan Opening Balance') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->loan_ledger_account->opening_balance ?? ''}}
                        </div>
                    </div> -->
                    <div class="mb-3 form-group opening_balance">
                        <label for="email">
                            <span>{{ __('Fixed Saving Opening Balance') }}:</span>
                            <b>{{ $user->fixed_saving_ledger_account->opening_balance ?? ''}}</b>
                        </label>
                        <label for="email">
                            <span>{{ __('Share Opening Balance') }}:</span>
                            <b>{{ $user->share_ledger_account->opening_balance ?? ''}}</b>
                        </label>
                        <label for="email">
                            <span>{{ __('Loan Opening Balance') }}:</span>
                            <b>{{ $user->loan_ledger_account->opening_balance ?? ''}}</b>
                        </label>
                    </div>

                    <div class="mb-3 row">
                        <label for="email"
                        class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Registration No') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->registration_no }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="name"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Name') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->user->name }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="name"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Profile Picture') }}:</strong></label>

                            @if($user->profile_picture)
                                <div class="col-md-6" style="line-height: 35px;">
                                    <a href="{{ asset($user->profile_picture) }}" target="_blank" ><img src="{{ asset($user->profile_picture) }}" height="100" width="100"></a>
                                </div>
                            @endif
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Gender') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ __($user->gender) }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Email Address') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->user->email }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Birth Date') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{-- {{ $user->birthdate }} --}}
                            {{ date('d/m/Y', strtotime($user->birthdate)) }}

                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Mobile No') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->mobile_no }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Whatsapp No') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->whatsapp_no }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Current Address') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->current_address }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Parmenant Address') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->parmenant_address }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Signature') }}:</strong></label>
                        @if($user->signature)
                            <div class="col-md-6" style="line-height: 35px;">
                                <a href="{{ asset($user->signature) }}" target="_blank" ><img src="{{ asset($user->signature) }}" height="100" width="100"></a>
                            </div>
                        @endif
                    </div>
                    <div class="float-start card-header-design mt-4">
                        <b>{{ __('Work Details') }}</b>
                    </div>
                    <br> <br>

                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Department') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->department->department_name ?? '' }}
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Company') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->company }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Designation') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->designation }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Salary') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->salary }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('DA') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->da }}
                        </div>
                    </div>
                    <div class="float-start mt-4">
                        <b>{{ __('Document Details') }}</b>
                    </div>
                    <br> <br>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Aadhar Card No') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->aadhar_card_no }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Aadhar card') }}:</strong></label>
                        @if($user->aadhar_card)
                            <div class="col-md-6" style="line-height: 35px;">
                            <a href="{{ asset($user->aadhar_card) }}" target="_blank" ><img src="{{ asset($user->aadhar_card) }}" height="100" width="180"> </a>
                        </div>
                        @endif
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('PAN No') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->pan_no }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('PAN Card') }}:</strong></label>
                            @if($user->pan_card)
                            <div class="col-md-6" style="line-height: 35px;">
                            <a href="{{ asset($user->pan_card) }}" target="_blank" ><img src="{{ asset($user->pan_card) }}" height="100" width="180"> </a>
                        </div>
                        @endif
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Departmental ID Proof') }}:</strong></label>

                            @if($user->department_id_proof)
                            <div class="col-md-6" style="line-height: 35px;">
                            <a href="{{ asset($user->department_id_proof) }}" target="_blank" ><img src="{{ asset($user->department_id_proof) }}" height="100" width="180"> </a>
                        </div>
                        @endif
                    </div>
                    <div class="float-start mt-4">
                        <b>{{ __('Other Details') }}</b>
                    </div>
                    <br> <br>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Nominee Name') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->nominee_name }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Nominee Relation') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->nominee_relation }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Witness Signature') }}:</strong></label>
                        @if($user->witness_signature)
                            <div class="col-md-6" style="line-height: 35px;">
                            <a href="{{ asset($user->witness_signature) }}" target="_blank" ><img src="{{ asset($user->witness_signature) }}" height="100" width="100"></a>
                        </div>
                        @endif
                    </div>

                    <div class="float-start mt-4">
                        <b>{{ __('Bank Details') }}</b>
                    </div>
                    <br> <br>

                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Bank Name') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->bank_name }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Branch Address') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->branch_address }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('Saving Account No') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->saving_account_no }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-5 col-form-label text-md-end text-start"><strong>{{ __('IFSC Code') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $user->ifsc_code }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

