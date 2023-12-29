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
                    <form action="{{ route('shareamount.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3 row">
                            <label class="col-2 col-form-label">{{ __('New Share Amount') }}</label>
                            <div class="col-5">
                                <input type="hidden" name="setting_name" value="share_amount">
                                <input type="number" name="share_amount"
                                    class="form-control @error('share_amount') is-invalid @enderror"
                                    placeholder="{{ __('Share Amount') }}"
                                    value="{{ old('share_amount', isset($setting->share_amount) ? $setting->share_amount : '') }}">
                                @error('share_amount')
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
