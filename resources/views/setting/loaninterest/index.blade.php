@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">
            <div class="header_add">

                <div class="form"></div>
            @can('create-role')
                <a href="{{ route('loaninterest.create') }}" class="btn btn-outline-success btn-md  float-end my-3"><i class="bi bi-plus-circle"></i> {{__('Add New Loan Interest')}}</a>
            @endcan
            </div>
            @if (!empty($loan_interest))
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('No.') }}</th>
                                <th>{{ __('Loan Interest') }}</th>
                                <th>{{ __('Start Date') }}</th>
                                <th>{{ __('End Date') }}</th>
                                <th>{{ __('Is current ?') }} </th>
                                <th>{{ __('Action') }} </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($loan_interest as $key => $amount)
                                <tr >
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $amount->loan_interest }}</td>
                                    <td>{{ $amount->start_date ? date('d-m-Y H:i:s', strtotime($amount->start_date)) : '' }}
                                    </td>
                                    <td>{{ $amount->end_date && $amount->is_active == 0 ? date('d-m-Y H:i:s', strtotime($amount->end_date)) : '' }}
                                    </td>
                                    <td>{{ $amount->is_active == 1 ?  __('Yes') : __('No') }} </td>
                                    <td>  <a href="{{ route('loaninterest.edit', $amount->id) }}" class="btn btn-outline-warning btn-sm"><i
                                        class="bi bi-pencil-square"></i> {{__('Edit')}}</a></td>
                                </tr>
                            @endforeach
                    </table>
                </div>
            @endif

            {{-- <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title"><b>{{ __('Monthly Saving') }}</b></h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('loan.setting.store', 1) }}" method="POST">
                                @csrf
                                @if (!empty($monthly_saving))
                                    <div class="col-lg-8 col-md-10">
                                        <table class="table table-bordered ">
                                            <tr>
                                                <th>{{ __('No.') }}</th>
                                                <th>{{ __('Monthly Saving') }}</th>
                                                <th>{{ __('Start Date') }}</th>
                                                <th>{{ __('End Date') }}</th>
                                            </tr>
                                            @foreach ($monthly_saving as $key => $amount)
                                                <tr style="{{ $amount->is_active != 1 ? 'background-color:#e2e0e0;' : '' }}">
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $amount->monthly_saving }}</td>
                                                    <td>{{ $amount->start_date ? date('d-m-Y H:i:s', strtotime($amount->start_date)) : '' }}
                                                    </td>
                                                    <td>{{ $amount->end_date ? date('d-m-Y H:i:s', strtotime($amount->end_date)) :  __('Active') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                @endif
                                <div class="form-group mb-3 row">
                                    <label class="col-2 col-form-label">{{ __('New Monthly Saving') }}</label>
                                    <div class="col-5">
                                        <input type="hidden" name="setting_name" value="monthly_saving">
                                        <input type="number" name="monthly_saving"
                                            class="form-control @error('monthly_saving') is-invalid @enderror"
                                            placeholder="{{ __('Monthly Saving') }}"
                                            value="{{ old('monthly_saving', isset($setting->monthly_saving) ? $setting->monthly_saving : '') }}">
                                        @error('monthly_saving')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-2">
                                        <button type="submit"
                                            class="btn btn-primary me-1 mb-1">{{ __('Add') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h4 class="card-title"><b>{{ __('Share amount') }}</b></h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('loan.setting.store', 1) }}" method="POST">
                                @csrf

                                @if (!empty($share_amount))
                                    <div class="col-lg-8 col-md-10">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>{{ __('No.') }}</th>
                                                <th>{{ __('Share Amount') }}</th>
                                                <th>{{ __('Start Date') }}</th>
                                                <th>{{ __('End Date') }}</th>
                                            </tr>
                                            @foreach ($share_amount as $key => $amount)
                                                <tr style="{{ $amount->is_active != 1 ? 'background-color:#e2e0e0;' : '' }}">
                                                    <td >{{ $key + 1 }}</td>
                                                    <td>{{ $amount->share_amount }}</td>
                                                    <td>{{ $amount->start_date ? date('d-m-Y H:i:s', strtotime($amount->start_date)) : '' }}
                                                    </td>
                                                    <td>{{ $amount->end_date ? date('d-m-Y H:i:s', strtotime($amount->end_date)) :  __('Active') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                @endif
                                <div class="form-group mb-3 row">
                                    <label class="col-2 col-form-label">{{ __('New Share amount') }}</label>
                                    <div class="col-5">
                                        <input type="hidden" name="setting_name" value="share_amount">
                                        <input type="number" name="share_amount"
                                            class="form-control @error('share_amount') is-invalid @enderror"
                                            placeholder="{{ __('Share amount') }}"
                                            value="{{ old('share_amount', isset($setting->share_amount) ? $setting->share_amount : '') }}">
                                        @error('share_amount')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-2">
                                        <button type="submit"
                                            class="btn btn-primary me-1 mb-1">{{ __('Add') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> --}}



        </div>
    </div>
    </div>
@endsection
