@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form action="{{ route('double_entries.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header border mb-4">
                        <div class="float-start">
                            <b>{{ __('Double Entry') }}</b>
                        </div>
                        <div class="float-end">
                            <a href="{{ route('double_entries.index') }}" class="btn btn-primary btn-sm">&larr;
                                {{ __('Back') }}</a>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-3 col-3">
                                <div class="form-group">
                                    <label for="first-amount-column">{{ __('Entry ID') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('entry_id') is-invalid @enderror"
                                        id="entry_id" name="entry_id" value="{{ '#D001'}}" readonly
                                        placeholder="{{ __('ID') }}">
                                    @if ($errors->has('entry_id'))
                                        <span class="text-danger">{{ $errors->first('entry_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-3">
                                <div class="form-group">
                                    <label for="first-amount-column">{{ __('Date') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror"
                                        id="date" name="date" value="<?php echo date('Y-m-d'); ?>"
                                        placeholder="{{ __('Date') }}">
                                    @if ($errors->has('cr_date'))
                                        <span class="text-danger">{{ $errors->first('date') }}</span>
                                    @endif
                                </div>
                            </div>
                            {{-- <div class="col-md-3 col-3">
                                <div class="form-group">
                                    <label for="cr_ledger_acc_id">{{ __('Ledger Account') }}<span
                                            class="text-danger">*</span></label>
                                    <select class="select2 @error('cr_ledger_acc_id') is-invalid @enderror"
                                        aria-label="Permissions" id="cr_ledger_acc_id" name="cr_ledger_acc_id"
                                        data-column="2" style="height: 210px;">
                                        <option value="">{{ __('Select Ledger Account') }}</option>
                                        @forelse ($ledger_accounts as $ac)
                                            <option value="{{ $ac->id }}"
                                                {{ $ac->id == old('ledger_ac_id') ? 'selected' : '' }}>
                                                {{ $ac->account_name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('cr_ledger_acc_id'))
                                        <span class="text-danger">{{ $errors->first('ledger_acc_id') }}</span>
                                    @endif
                                </div>
                            </div> --}}
                            <div class="col-md-6 col-6">
                                <div class="form-group">
                                    <label for="first-amount-column">{{ __('Description') }}<span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" placeholder="{{ __('Description') }}" name="description"></textarea>
                                    @if ($errors->has('description'))
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-3" id="cheque_div" style="display:none">
                                <div class="form-group">
                                    <label for="first-amount-column">{{ __('Cheque No.') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="any"
                                        class="form-control @error('cheque_no') is-invalid @enderror"
                                        id="cheque_no" name="cheque_no" value="{{ old('cheque_no') }}"
                                        placeholder="{{ __('Amount') }}">
                                    @if ($errors->has('cheque_no'))
                                        <span class="text-danger">{{ $errors->first('cheque_no') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card">
                    <div class="card-header border mb-4">
                        <div class="float-start">
                            {{-- <b>{{ __('Debit Entry') }}</b> --}}
                        </div>
                        <div class="float-end">
                            <button type="button" class="btn btn-outline-warning"
                                onclick="add_ledger_entry()">Add</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row ledger_entry">
                            <div class="col-md-4 col-3">
                                <div class="form-group">
                                    <label for="ledger_acc_id">{{ __('Ledger Account') }}<span
                                            class="text-danger">*</span></label>
                                    <select class="select2 @error('ledger_acc_id') is-invalid @enderror"
                                        aria-label="Permissions" name="ledger_acc_id[]" data-column="2"
                                        style="height: 210px;">
                                        <option value="">{{ __('Select Ledger Account') }}</option>
                                        @forelse ($ledger_accounts as $ac)
                                            <option value="{{ $ac->id }}"
                                                {{ $ac->id == old('ledger_ac_id') ? 'selected' : '' }}>
                                                {{ $ac->account_name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('ledger_acc_id.*'))
                                        <span class="text-danger">{{ $errors->first('ledger_acc_id.*') }}</span>
                                    @endif
                                </div>
                            </div>
                            {{-- <div class="col-md-3 col-3">
                                    <div class="form-group">
                                        <label for="ledger_ac_id">{{ __('Ledger Group') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="select2 @error('ledger_ac_id') is-invalid @enderror"
                                            aria-label="Permissions" id="ledger_ac_id" name="ledger_ac_id" data-column="2"
                                            style="height: 210px;">
                                            <option value="">{{ __('Select Ledger Group') }}</option>
                                            {!! getLedgerGroupDropDown() !!}
                                        </select>
                                        @if ($errors->has('ledger_ac_id'))
                                            <span class="text-danger">{{ $errors->first('ledger_ac_id') }}</span>
                                        @endif
                                    </div>
                                </div> --}}

                            <div class="col-md-4 col-3">
                                <div class="form-group">
                                    <label for="first-particular-column">{{ __('Particular') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('particular.0') is-invalid @enderror" id="particular"
                                        name="particular" value="{{ old('particular.0') }}"
                                        placeholder="{{ __('Particular') }}">
                                    @if ($errors->has('particular.*'))
                                        <span class="text-danger">{{ $errors->first('particular.*') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2 col-2">
                                <div class="form-group">
                                    <div class="row pt-4">
                                        <div class="col-md-6 col-lg-6 form-check">
                                            <input class="form-check-input" type="radio" name="type[0]"
                                                id="yes" value="1" checked>
                                            <label class="form-check-label" for="yes">
                                                {{ __('Debit') }}
                                            </label>
                                        </div>
                                        <div class="col-md-6 col-lg-6 form-check">
                                            <input class="form-check-input" type="radio" name="type[0]"
                                                id="no" value="2">
                                            <label class="form-check-label" for="no">
                                                {{ __('Credit') }}
                                            </label>
                                        </div>
                                        @if ($errors->has('type.*'))
                                            <span class="text-danger">{{ $errors->first('type.*') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 col-2">
                                <div class="form-group">
                                    <label for="first-amount-column">{{ __('Amount') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="any"
                                        class="form-control @error('amount') is-invalid @enderror" id="amount"
                                        name="amount" value="{{ old('amount') }}"
                                        placeholder="{{ __('Amount') }}">
                                    @if ($errors->has('amount'))
                                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- <div class="col-md-3 col-3">
                                <div class="form-group">
                                    <label for="first-amount-column">{{ __('Cheque No.') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="any"
                                        class="form-control @error('amount') is-invalid @enderror" id="amount"
                                        name="amount" value="{{ old('amount') }}" placeholder="{{ __('Amount') }}">
                                    @if ($errors->has('amount'))
                                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                                    @endif
                                </div>
                            </div> --}}

                        </div>
                        {{-- <div class="row mt-5 ps-5 justify-content-center">
                            Total Credit (₹) : 0
                        </div>
                        <div class="row  ps-5 justify-content-center">
                            Total Debit (₹) : 0
                        </div> --}}
                        <div class="mb-3 row justify-content-end">
                            <button type="submit" class="col-md-1 offset-md-5 btn btn-primary"><i
                                    class="fa fa-lock"></i>{{ __('Submit') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    $('.select2').select2();
    $('input[name="ctype"]').on('change', function() {
        if ($(this).val() == 2) {
            $('#cheque_div').show();
        } else {
            $('#cheque_div').hide();

        }

    });

    function lockinput() {
        $("#cr_ledger_acc_id").select2({
            disabled: 'readonly'
        });
        $("#cr_particular").attr('readonly', true);
        $("#cr_date").attr('readonly', true);
        $("#cr_amount").attr('readonly', true);
        $("#cr_cheque_no").attr('readonly', true);
    }
var count = 0;
    function add_ledger_entry() {
        count = count+1;
        var html = `<div class="row ledger_entry">
                            <div class="col-md-4 col-3">
                                <div class="form-group">
                                    <label for="ledger_acc_id">{{ __('Ledger Account') }}<span
                                            class="text-danger">*</span></label>
                                    <select class="select2 @error('ledger_acc_id') is-invalid @enderror"
                                        aria-label="Permissions" name="ledger_acc_id[]" data-column="2"
                                        style="height: 210px;">
                                        <option value="">{{ __('Select Ledger Account') }}</option>
                                        @forelse ($ledger_accounts as $ac)
                                            <option value="{{ $ac->id }}"
                                                {{ $ac->id == old('ledger_ac_id') ? 'selected' : '' }}>
                                                {{ $ac->account_name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('ledger_acc_id'))
                                        <span class="text-danger">{{ $errors->first('ledger_acc_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 col-3">
                                <div class="form-group">
                                    <label for="first-particular-column">{{ __('Particular') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('particular') is-invalid @enderror" id="particular"
                                        name="particular[]" value="{{ old('particular') }}"
                                        placeholder="{{ __('Particular') }}">
                                    @if ($errors->has('particular'))
                                        <span class="text-danger">{{ $errors->first('particular') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2 col-2">
                                <div class="form-group">
                                    <div class="row pt-4">
                                        <div class="col-md-6 col-lg-6 form-check">
                                            <input class="form-check-input" type="radio" name="type[`+count+`]"
                                                id="yes" value="1" checked>
                                            <label class="form-check-label" for="yes">
                                                {{ __('Debit') }}
                                            </label>
                                        </div>
                                        <div class="col-md-6 col-lg-6 form-check">
                                            <input class="form-check-input" type="radio" name="type[`+count+`]"
                                                id="no" value="0">
                                            <label class="form-check-label" for="no">
                                                {{ __('Credit') }}
                                            </label>
                                        </div>
                                        @if ($errors->has('is_current'))
                                            <span class="text-danger">{{ $errors->first('is_current') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-2">
                                <div class="form-group">
                                    <label for="first-amount-column">{{ __('Amount') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="any"
                                        class="form-control @error('amount') is-invalid @enderror" id="amount"
                                        name="amount" value="{{ old('amount') }}"
                                        placeholder="{{ __('Amount') }}">
                                    @if ($errors->has('amount'))
                                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>`;
        $('.ledger_entry').last().after(html);
        $('.select2').select2();
    }
</script>
@endpush
