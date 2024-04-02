@extends('layouts.app')
@section('content')
@section('title'){{ $page_title }}@endsection
<section class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if($errors->any())
            {{ implode('', $errors->all('<div>:message</div>')) }}
        @endif
            <div class="card">
                <div class="card-header">
                    <div class="float-start">

                    </div>
                    <div class="float-end">
                        <a href="{{ route('balance_sheet.index') }}" class="btn btn-primary btn-sm">&larr; {{__('Back')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3 form-group opening_balance">
                        <label for="email">
                            <span>{{ __('Total Saving') }}:</span>
                            <b>{{ 0 }}</b> {{-- <b>{{ $user->fixed_saving_ledger_account->opening_balance }}</b> --}}
                        </label>
                        <label for="email">
                            <span>{{ __('Total Interest') }}:</span>
                            {{ 0 }} {{-- <b>{{ $user->share_ledger_account->opening_balance }}</b> --}}
                        </label>
                        <label for="email">
                            <span>{{ __('Total Share') }}:</span>
                            {{ 0 }} {{-- <b>{{ $user->loan_ledger_account->opening_balance }}</b> --}}
                        </label>
                        <label for="email">
                            <span>{{ __('Balance') }}:</span>
                            {{ 0 }} {{-- <b>{{ $user->loan_ledger_account->opening_balance }}</b> --}}
                        </label>
                    </div>

                    <form action="{{ route('balance_sheet.update',currentYear()->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        @foreach ($balance_sheet as $bal)
                            <div class="mb-2 row">
                                <label for="ledger" class="col-md-4 col-form-label text-md-end text-start">{{ $bal->ledger_ac_name }}</label>
                                <div class="col-md-7">
                                    <input type="number" class="form-control @error( $bal->id ) is-invalid @enderror"
                                        id="ledger" name="acc_{{ $bal->id }}" value="{{ $bal->balance }}">
                                    @if ($errors->has( $bal->id ))
                                        <span class="text-danger">{{ $errors->first($bal->id) }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="mb-3 row">
                            <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="{{__('Update') }}">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
