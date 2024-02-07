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
                    <div class="float-end">
                        <a href="{{ route('ledger_account.index') }}" class="btn btn-primary btn-sm">&larr;
                            {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('ledger_account.update', $ledger_account->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3 row">
                            <label for="first-amount-column"
                                class="col-md-4 col-form-label text-md-end text-start">{{ __('Type') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-md-6 row p-2 ps-4">
                                <div class="col-md-4 col-lg-4 form-check">
                                    <input class="form-check-input" type="radio" name="form_type" id="yes" disabled
                                        value="1" {{ $ledger_account->is_member_account == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="yes">
                                        {{ __('Member') }}
                                    </label>
                                </div>
                                <div class="col-md-4 col-lg-4 form-check">
                                    <input class="form-check-input" type="radio" name="form_type" id="no" disabled
                                        value="0" {{ $ledger_account->is_member_account == 0 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="no">
                                        {{ __('Account') }}
                                    </label>
                                </div>
                            </div>
                            @if ($errors->has('form_type'))
                                <span class="text-danger">{{ $errors->first('form_type') }}</span>
                            @endif
                        </div>
                        <div id="member_form" {{$ledger_account->is_member_account ? 'style=display:block' : 'style=display:none' }}>
                            <div class="row mb-3">
                                <label for="member_id"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Member') }}</label>
                                <div class="col-md-6">
                                    <select class="choices form-select @error('member_id') is-invalid @enderror"
                                        aria-label="Permissions" id="member_id" name="member_id" style="height: 210px;">
                                        <option value="">{{ __('Select Member') }}</option>
                                        @forelse ($members as $key => $member)
                                            <option value="{{ $member->user_id }}"
                                                {{ $member->id == old('user_id', $ledger_account->member_id) ? 'selected' : '' }}>
                                                {{ $member->fullname }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('member_id'))
                                        <span class="text-danger">{{ $errors->first('member_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="account_name"
                                class="col-md-4 col-form-label text-md-end text-start">{{ __('Account Name') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('account_name') is-invalid @enderror"
                                    id="account_name" name="account_name" value="{{ $ledger_account->account_name }}">
                                @if ($errors->has('account_name'))
                                    <span class="text-danger">{{ $errors->first('account_name') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="ledger_group_id"
                                class="col-md-4 col-form-label text-md-end text-start">{{ __('Ledger Group') }}</label>
                            <div class="col-md-6">
                                <select class="choices form-select @error('ledger_group_id') is-invalid @enderror"
                                    aria-label="Permissions" id="ledger_group_id" name="ledger_group_id"
                                    style="height: 210px;">
                                    {!! getLedgerGroupDropDown(old('ledger_group_id', $ledger_account->ledger_group_id)) !!}
                                </select>
                                @if ($errors->has('ledger_group_id'))
                                    <span class="text-danger">{{ $errors->first('ledger_group_id') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="opening_balance"
                                class="col-md-4 col-form-label text-md-end text-start">{{ __('Opening Balance') }}</label>
                            <div class="col-md-6">
                                <input type="number" step="any"
                                    class="form-control @error('opening_balance') is-invalid @enderror"
                                    id="opening_balance" name="opening_balance"
                                    placeholder="{{ __('Opening Balance') }}"
                                    value="{{ old('opening_balance', $ledger_account->opening_balance) }}">
                                @if ($errors->has('opening_balance'))
                                    <span class="text-danger">{{ $errors->first('opening_balance') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="type"
                                class="col-md-4 col-form-label text-md-end">{{ __('Select Type') }}</label>
                            <div class="col-md-6">
                                <select class="choices form-select @error('type') is-invalid @enderror" id="type"
                                    name="type" style="height: 210px;">
                                    <option value="">{{ __('Select Credit Or Debit') }}</option>
                                    <option value="CR"
                                        {{ 'CR' == old('type', $ledger_account->type) ? 'selected' : '' }}>
                                        {{ __('Credit') }}
                                    </option>
                                    <option value="DR"
                                        {{ 'DR' == old('type', $ledger_account->type) ? 'selected' : '' }}>
                                        {{ __('Debit') }}
                                    </option>
                                </select>
                                @if ($errors->has('type'))
                                    <span class="text-danger">{{ $errors->first('type') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <input type="submit" class="col-md-3 offset-md-5 btn btn-primary"
                                value="{{ __('Update Ledger Account') }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script>
    $('input[name="form_type"]').on('change', function() {
        $('#member_form').hide();
        $('#acc_form').hide();
        if ($(this).val() == 1) {
            $('#member_form').show();
        } else {
            $('#acc_form').hide();
        }
    });
</script>
@endpush
