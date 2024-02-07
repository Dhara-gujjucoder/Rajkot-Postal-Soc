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
                    <label class="col-4 col-form-label">{{ __('Export Member Fixed Saving') }}</label>
                    <div class="col-5">
                        <a href="{{ route('fixed_saving_export') }}" class="btn btn-primary"><b>
                            {{ __('Export') }}</b>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
