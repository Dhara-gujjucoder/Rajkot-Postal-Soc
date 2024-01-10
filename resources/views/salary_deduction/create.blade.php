@extends('layouts.app')
@section('content')

@section('title')
    {{ $page_title }}
@endsection
<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header border mb-4">
                    <div class="float-start">
                        <b>{{ __('Salary Deduction') }}</b>
                    </div>
                    <div class="float-end">
                        <a href="{{ route('salary_deduction.index') }}" class="btn btn-primary btn-sm">&larr;
                            {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('salary_deduction.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        {{-- <div class="row mb-3">
                            <label for="account_name" class="col-md-4 col-form-label text-md-end">{{ __('Account Type') }}</label>
                            <div class="col-md-6">
                                <select class="choices form-select @error('account_type_id') is-invalid @enderror" aria-label="Permissions" id="account_type_id" name="account_type_id" style="height: 210px;">
                                    <option value="">{{ __('Account Type') }}</option>
                                    @forelse ($departments as $key => $department)
                                        <option value="{{ $department->id }}" {{ $department->id == old('account_type_id') ? 'selected' : '' }}>
                                            {{ $department->type_name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                @if ($errors->has('account_type_id'))
                                    <span class="text-danger">{{ $errors->first('account_type_id') }}</span>
                                @endif
                            </div>
                        </div> --}}

                        <div class="row mb-3">
                            <label for="ledger_ac_id" class="col-md-4 col-form-label text-md-end">{{ __('Ledger') }}</label>
                            <div class="col-md-6">
                                <select class="choices form-select @error('ledger_ac_id') is-invalid @enderror" aria-label="Permissions" id="ledger_ac_id" name="ledger_ac_id" style="height: 210px;" ">
                                    <option value="">{{ __('Select Ledger') }}</option>
                                    @foreach ($ledgers as $ledger)
                                        <option value="{{ $ledger->id }}" {{ $ledger->id == old('ledger_ac_id') ? 'selected' : '' }}>{{ $ledger->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('ledger_ac_id'))
                                    <span class="text-danger">{{ $errors->first('ledger_ac_id') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <label for="user_id" class="col-md-4 col-form-label text-md-end">{{ __('Member') }}</label>
                            <div class="col-md-6">
                                <select class="choices form-select @error('user_id') is-invalid @enderror" aria-label="Permissions" id="user_id" name="user_id" style="height: 210px;">
                                    <option value="">{{ __('Select Member') }}</option>
                                    @forelse ($members as $key => $member)
                                        <option value="{{ $member->user_id }}" {{ $member->user_id == old('user_id') ? 'selected' : '' }}>
                                            {{ $member->fullname }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                @if ($errors->has('user_id'))
                                    <span class="text-danger">{{ $errors->first('user_id') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="month" class="col-md-4 col-form-label text-md-end">{{ __('Month') }}</label>
                            {{-- <label for="account_name"  class="col-md-4 col-form-label text-md-end">{{__('Department')}}</label> --}}
                            <div class="col-md-6">
                                <select class="choices form-select @error('month') is-invalid @enderror" aria-label="Permissions" id="month" name="month" style="height: 210px;">
                                    <option value="">{{ __('Select Month') }}</option>
                                    @foreach (getYearDropDown($current_year->id) as $item)
                                        <option value="{{ $item['value'] }}" {{ old('month') == $item['month'] ? 'selected' : '' }}>{{ $item['month'] }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('month'))
                                    <span class="text-danger">{{ $errors->first('month') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="rec_no" class="col-md-4 col-form-label text-md-end">{{ __('Rec No.') }}</label>
                            {{-- <label for="account_name"  class="col-md-4 col-form-label text-md-end">{{__('Department')}}</label> --}}
                            <div class="col-md-6">
                                <input type="number" id="rec_no" class="form-control @error('rec_no') is-invalid @enderror" placeholder="{{ __('Rec No.') }}" name="rec_no" value="{{ old('rec_no') }}">
                                @if ($errors->has('rec_no'))
                                    <span class="text-danger">{{ $errors->first('rec_no') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="principal" class="col-md-4 col-form-label text-md-end">{{ __('Principal') }}
                                (Rs.)</label>
                            {{-- <label for="account_name"  class="col-md-4 col-form-label text-md-end">{{__('Department')}}</label> --}}
                            <div class="col-md-6">
                                <input type="number" id="principal" class="form-control @error('principal') is-invalid @enderror" placeholder="{{ __('Principal') }}" name="principal" value="{{ old('principal', 0) }}" onchange="calculate()">
                                @if ($errors->has('principal'))
                                    <span class="text-danger">{{ $errors->first('principal') }}</span>
                                @endif
                            </div>
                        </div>



                        <div class="row mb-3">
                            <label for="interest" class="col-md-4 col-form-label text-md-end">{{ __('Interest') }}
                                (Rs.)</label>
                            {{-- <label for="account_name"  class="col-md-4 col-form-label text-md-end">{{__('Department')}}</label> --}}
                            <div class="col-md-6">
                                <input type="number" id="interest" class="form-control @error('interest') is-invalid @enderror" placeholder="{{ __('Interest') }}" name="interest" value="{{ old('interest', 0) }}" onchange="calculate()">
                                @if ($errors->has('interest'))
                                    <span class="text-danger">{{ $errors->first('interest') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="fixed" class="col-md-4 col-form-label text-md-end">{{ __('Fixed Monthly Saving') }} (Rs.)</label>
                            {{-- <label for="account_name"  class="col-md-4 col-form-label text-md-end">{{__('Department')}}</label> --}}
                            <div class="col-md-6">
                                <input type="number" id="fixed" class="form-control @error('fixed') is-invalid @enderror" placeholder="{{ __('Fixed Monthly Saving') }}" name="fixed" value="{{ old('fixed', 0) }}" onchange="calculate()">
                                @if ($errors->has('fixed'))
                                    <span class="text-danger">{{ $errors->first('fixed') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="total_amount" class="col-md-4 col-form-label text-md-end">{{ __('Total Amount') }} (Rs.)</label>
                            {{-- <label for="account_name"  class="col-md-4 col-form-label text-md-end">{{__('Department')}}</label> --}}
                            <div class="col-md-6">
                                <input type="number" id="total_amount" class="form-control @error('total_amount') is-invalid @enderror" placeholder="{{ __('Total Amount') }}" name="total_amount" value="0" disabled>
                                @if ($errors->has('total_amount'))
                                    <span class="text-danger">{{ $errors->first('total_amount') }}</span>
                                @endif
                            </div>
                        </div>
                </div>
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary me-1 mb-1">{{ __('Submit') }}</button>
                    <button type="reset" class="btn btn-light-secondary me-1 mb-1">{{ __('Reset') }}</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
@endsection
@push('script')
<script>
    function calculate() {
        $('#total_amount').val(0);
        var total = 0;
        var principal = $('#principal').val();
        var interest = $('#interest').val();
        var fixed = $('#fixed').val();
        var total_amount = $('#total_amount').val();
        total = Number(principal) + Number(interest) + Number(fixed) + Number(total_amount);
        $('#total_amount').val(total);
    }
</script>
@endpush
