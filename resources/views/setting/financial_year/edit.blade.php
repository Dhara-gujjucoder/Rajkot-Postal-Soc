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
                        <b>{{ __('Edit Year Details') }}</b>
                    </div>
                    <div class="float-end">
                        <a href="{{ route('financial_year.index') }}" class="btn btn-primary btn-sm">&larr; {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('financial_year.update', $financial->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">

                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="first-title-column">{{ __('Title') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title', $financial->title) }}"
                                        placeholder="{{ __('Title') }}">
                                    @if ($errors->has('title'))
                                        <span class="text-danger">{{ $errors->first('title') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="financial_year">{{ __('Start Year') }}<span class="text-danger">*</span></label>
                                        <select class="choices form-select @error('start_year') is-invalid @enderror" aria-label="Permissions" id="start_year" name="start_year" style="height: 210px;" ">
                                            <option value="">{{ __('--Start Year--') }}</option>
                                            <option value="{{ date('Y', strtotime('-5 year')) }}" {{ $financial->start_year == date('Y', strtotime('-5 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('-5 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('-4 year')) }}" {{ $financial->start_year == date('Y', strtotime('-4 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('-4 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('-3 year')) }}" {{ $financial->start_year == date('Y', strtotime('-3 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('-3 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('-2 year')) }}" {{ $financial->start_year == date('Y', strtotime('-2 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('-2 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('-1 year')) }}" {{ $financial->start_year == date('Y', strtotime('-1 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('-1 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('0 year')) }}" {{ $financial->start_year == date('Y', strtotime('0 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('0 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('1 year')) }}" {{ $financial->start_year == date('Y', strtotime('1 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('1 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('2 year')) }}" {{ $financial->start_year == date('Y', strtotime('2 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('2 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('3 year')) }}" {{ $financial->start_year == date('Y', strtotime('3 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('3 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('4 year')) }}" {{ $financial->start_year == date('Y', strtotime('4 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('4 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('5 year')) }}" {{ $financial->start_year == date('Y', strtotime('5 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('5 year')) }}</option>
                                        </select>
                                    @if ($errors->has('start_year'))
                                        <span class="text-danger">{{ $errors->first('start_year') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="first-name-column">{{ __('End Year') }}<span class="text-danger">*</span></label>
                                        <select class="choices form-select @error('end_year') is-invalid @enderror" aria-label="Permissions" id="end_year" name="end_year" style="height: 210px;" ">
                                            <option value="">{{ __('--End Year--') }}</option>
                                            <option value="{{ date('Y', strtotime('-5 year')) }}" {{ $financial->end_year == date('Y', strtotime('-5 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('-5 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('-4 year')) }}" {{ $financial->end_year == date('Y', strtotime('-4 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('-4 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('-3 year')) }}" {{ $financial->end_year == date('Y', strtotime('-3 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('-3 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('-2 year')) }}" {{ $financial->end_year == date('Y', strtotime('-2 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('-2 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('-1 year')) }}" {{ $financial->end_year == date('Y', strtotime('-1 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('-1 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('0 year')) }}" {{ $financial->end_year == date('Y', strtotime('0 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('0 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('1 year')) }}" {{ $financial->end_year == date('Y', strtotime('1 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('1 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('2 year')) }}" {{ $financial->end_year == date('Y', strtotime('2 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('2 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('3 year')) }}" {{ $financial->end_year == date('Y', strtotime('3 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('3 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('4 year')) }}" {{ $financial->end_year == date('Y', strtotime('4 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('4 year')) }}</option>
                                            <option value="{{ date('Y', strtotime('5 year')) }}" {{ $financial->end_year == date('Y', strtotime('5 year')) ? 'selected' : '' }}> {{ date('Y', strtotime('5 year')) }}</option>
                                        </select>
                                    @if ($errors->has('end_year'))
                                        <span class="text-danger">{{ $errors->first('end_year') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="registration_no">{{ __('Start Month') }}<span class="text-danger">*</span></label>
                                        <select class="choices form-select @error('start_month') is-invalid @enderror" aria-label="Permissions" id="start_month" name="start_month" style="height: 210px;" ">
                                            <option value="">{{ __('--Start Month--') }}</option>
                                            <option value="01" {{ $financial->start_month == '01' ? 'selected' : '' }}> {{__('January')}}</option>
                                            <option value="02" {{ $financial->start_month == '02' ? 'selected' : '' }}> {{__('February')}}</option>
                                            <option value="03" {{ $financial->start_month == '03' ? 'selected' : '' }}> {{__('March')}}</option>
                                            <option value="04" {{ $financial->start_month == '04' ? 'selected' : '' }}> {{__('April')}}</option>
                                            <option value="05" {{ $financial->start_month == '05' ? 'selected' : '' }}> {{__('May')}}</option>
                                            <option value="06" {{ $financial->start_month == '06' ? 'selected' : '' }}> {{__('June')}}</option>
                                            <option value="07" {{ $financial->start_month == '07' ? 'selected' : '' }}> {{__('July')}}</option>
                                            <option value="08" {{ $financial->start_month == '08' ? 'selected' : '' }}> {{__('August')}}</option>
                                            <option value="09" {{ $financial->start_month == '09' ? 'selected' : '' }}> {{__('September')}}</option>
                                            <option value="10" {{ $financial->start_month == '10' ? 'selected' : '' }}> {{__('October')}}</option>
                                            <option value="11" {{ $financial->start_month == '11' ? 'selected' : '' }}> {{__('November')}}</option>
                                            <option value="12" {{ $financial->start_month == '12' ? 'selected' : '' }}> {{__('December')}}</option>
                                        </select>
                                    @if ($errors->has('start_month'))
                                        <span class="text-danger">{{ $errors->first('start_month') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="first-name-column">{{ __('End Month') }}<span class="text-danger">*</span></label>
                                        <select class="choices form-select @error('end_month') is-invalid @enderror" aria-label="Permissions" id="end_month" name="end_month" style="height: 210px;" ">
                                            <option value="">{{ __('--End Month--') }}</option>
                                            <option value="01" {{ $financial->end_month == '01' ? 'selected' : '' }}> {{__('January')}}</option>
                                            <option value="02" {{ $financial->end_month == '02' ? 'selected' : '' }}> {{__('February')}}</option>
                                            <option value="03" {{ $financial->end_month == '03' ? 'selected' : '' }}> {{__('March')}}</option>
                                            <option value="04" {{ $financial->end_month == '04' ? 'selected' : '' }}> {{__('April')}}</option>
                                            <option value="05" {{ $financial->end_month == '05' ? 'selected' : '' }}> {{__('May')}}</option>
                                            <option value="06" {{ $financial->end_month == '06' ? 'selected' : '' }}> {{__('June')}}</option>
                                            <option value="07" {{ $financial->end_month == '07' ? 'selected' : '' }}> {{__('July')}}</option>
                                            <option value="08" {{ $financial->end_month == '08' ? 'selected' : '' }}> {{__('August')}}</option>
                                            <option value="09" {{ $financial->end_month == '09' ? 'selected' : '' }}> {{__('September')}}</option>
                                            <option value="10" {{ $financial->end_month == '10' ? 'selected' : '' }}> {{__('October')}}</option>
                                            <option value="11" {{ $financial->end_month == '11' ? 'selected' : '' }}> {{__('November')}}</option>
                                            <option value="12" {{ $financial->end_month == '12' ? 'selected' : '' }}> {{__('December')}}</option>
                                        </select>
                                    @if ($errors->has('end_month'))
                                        <span class="text-danger">{{ $errors->first('end_month') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12 col-12">
                                <div class="row">
                                    <div class="col-md-4 form-check">
                                        <label for="current">{{ __('Is active?') }}
                                        <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="row ms-lg-5 p-2 ps-4">
                                    <div class="col-md-5 col-lg-2 form-check">
                                        <input class="form-check-input" type="radio" name="is_active"
                                            id="yes" value="1" {{ old('is_active', $financial->is_active) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="yes">
                                            {{ __('Yes') }}
                                        </label>
                                    </div>
                                    <div class="col-md-5 col-lg-2  form-check">
                                        <input class="form-check-input" type="radio" name="is_active"
                                            id="no" value="0" {{ old('is_active', $financial->is_active) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="no">
                                            {{ __('No') }}
                                        </label>
                                    </div>
                                    @if ($errors->has('is_current'))
                                        <span class="text-danger">{{ $errors->first('is_current') }}</span>
                                    @endif
                                </div>
                            </div>
                            @if(!$financial->is_active)
                            <div class="col-md-12 col-12">
                                <div class="row">
                                    <div class="col-md-3 form-check">
                                        <label for="Status">{{ __('Status') }}
                                        <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="row ms-lg-5 p-2 ps-4">
                                    <div class="col-md-5 col-lg-2 form-check">
                                        <input class="form-check-input" type="radio" name="status"
                                            value="1" {{ old('status', $financial->status) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="active">
                                            {{ __('Active') }}
                                        </label>
                                    </div>
                                    <div class="col-md-5 col-lg-2  form-check">
                                        <input class="form-check-input" type="radio" name="status"
                                            id="deactive"value="0" {{ old('status', $financial->status) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="deactive">
                                            {{ __('Deactive') }}
                                        </label>
                                    </div>
                                    @if ($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                            <div class="mb-3 row">
                                <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="{{__('Submit')}}">
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
