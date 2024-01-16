@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header border mb-4">
                    <div class="float-start">
                        <b>{{ __('Loan Detail') }}</b>
                    </div>
                    <div class="float-end">
                        <a href="{{ route('loan_matrix.index') }}" class="btn btn-primary btn-sm">&larr; {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('loan_matrix.update', $loan->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="row">

                            <div class="mb-3 row">
                                <label for="amount" class="col-md-2 col-form-label text-md-end text-start">{{__('Amount')}}</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror"
                                        id="amount" name="amount" value="{{ old('amount', $loan->amount) }}" placeholder="{{ __('Amount') }}">
                                    @if ($errors->has('amount'))
                                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="minimum_emi" class="col-md-2 col-form-label text-md-end text-start">{{__('Minimum EMI')}}</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control @error('minimum_emi') is-invalid @enderror"
                                        id="minimum_emi" name="minimum_emi" value="{{ old('minimum_emi', $loan->minimum_emi) }}" placeholder="{{ __('Amount') }}">
                                    @if ($errors->has('minimum_emi'))
                                        <span class="text-danger">{{ $errors->first('minimum_emi') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="required_share" class="col-md-2 col-form-label text-md-end text-start">{{__('Required share')}}</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control @error('required_share') is-invalid @enderror"
                                        id="required_share" name="required_share" value="{{ old('required_share', $loan->required_share) }}" placeholder="{{ __('Required share') }}">
                                    @if ($errors->has('required_share'))
                                        <span class="text-danger">{{ $errors->first('required_share') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="Status" class="col-md-2 col-form-label text-md-end text-start">{{ __('Status') }}</label>
                                    <div class="col-md-9">
                                        <input class="form-check-input" type="radio" name="status" value="1" {{ old('status', $loan->status) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="active">
                                            {{ __('Active') }}
                                        </label>

                                        <input class="form-check-input" type="radio" name="status" value="0" {{ old('status', $loan->status) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="deactive">
                                            {{ __('Deactive') }}
                                        </label>
                                    </div>
                                    @if ($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="{{ __('Submit') }}">
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
