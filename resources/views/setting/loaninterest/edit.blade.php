@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-body mt-3">
                    <form action="{{ route('loaninterest.update',$loan_interest->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-group mb-3 row">
                            <label class="col-4 col-form-label">{{ __('Edit Loan Interest') . ' (%)' }} </label>

                            <div class="col-5">
                                <input type="hidden" name="setting_name" value="loan_interest">
                                <input type="number" step="any" name="loan_interest"
                                    class="form-control @error('loan_interest') is-invalid @enderror"
                                    placeholder="{{ __('Loan Interest') }}"
                                    value="{{ old('loan_interest', isset($loan_interest->loan_interest) ? $loan_interest->loan_interest : '') }}">
                                @error('loan_interest')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group mb-3 mt-2 row">
                            <label class="col-4 col-form-label">{{__('Set as Current (Active) ?')}} </label>
                            <div class="col-md-5 col-lg-2 form-check">
                                <input class="form-check-input" type="radio" name="is_active" id="yes" value="1" {{ $loan_interest->is_active == '1' ? 'checked' :'' }}>
                                <label class="form-check-label" for="yes">
                                    {{ __('Yes') }}
                                </label>
                            </div>
                            <div class="col-md-5 col-lg-2 form-check">
                                <input class="form-check-input" type="radio" name="is_active"
                                    id="no" value="0" {{ $loan_interest->is_active == '0' ? 'checked' :'' }}>
                                <label class="form-check-label" for="no">
                                    {{ __('No') }}
                                </label>
                            </div>
                            @if ($errors->has('is_active'))
                                <span class="text-danger">{{ $errors->first('is_active') }}</span>
                            @endif
                        </div>
                        <div class="mb-3 row">
                            <input type="submit" class="col-md-3 offset-md-5 btn btn-primary"
                                value="{{ __('Update') }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
