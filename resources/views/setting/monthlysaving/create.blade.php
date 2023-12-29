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
                    <form action="{{ route('monthlysaving.store') }}" method="POST">
                        @csrf
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
                        <div class="mb-3 mt-4 row">
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
