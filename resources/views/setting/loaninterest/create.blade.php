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
                    <form action="{{ route('loaninterest.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3 row">
                            <label class="col-2 col-form-label">{{ __('New Loan Interest') . ' (%)' }} </label>

                            <div class="col-5">
                                <input type="hidden" name="setting_name" value="loan_interest">
                                <input type="number" step="any" name="loan_interest"
                                    class="form-control @error('loan_interest') is-invalid @enderror"
                                    placeholder="{{ __('Loan Interest') }}"
                                    value="{{ old('loan_interest', isset($setting->loan_interest) ? $setting->loan_interest : '') }}">
                                @error('loan_interest')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <input type="submit" class="col-md-3 offset-md-5 btn btn-primary"
                                value="{{ __('Add') }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
