@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header mb-4">
                    <div class="float-end">
                        <a href="{{ route('ledger_sharecapital.index') }}" class="btn btn-primary btn-sm">&larr;
                            {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="#" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="all_share">{{ __('Upload File') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="file" id="all_share"
                                        class="form-control @error('all_share') is-invalid @enderror"
                                         name="all_share"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                    @if ($errors->has('all_share'))
                                        <span class="text-danger">{{ $errors->first('all_share') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">{{ __('Submit') }}</button>
                                <button type="reset"
                                    class="btn btn-light-secondary me-1 mb-1">{{ __('Reset') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
