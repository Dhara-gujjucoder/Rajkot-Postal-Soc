@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">

            <div class="header_add">
                <form method="post" action="{{ route('journel_report_export') }}" id="export_saving" enctype="multipart/form-data">
                    @csrf
                    <div class="form">

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="month_from" class="col-form-label">{{ __('Month From') }}</label>
                                <div class="col-md-12">
                                    <select class="choices filter-input form-select" aria-label="Permissions"
                                        id="month_from" data-column="3" name="month_from" style="height: 210px;">
                                        <option value="">{{ __('Month From') }}</option>
                                            @php $months = getMonthsOfYear($current_year->id); @endphp
                                            @foreach ($months as $key => $month)
                                                <option value="{{ $key }}">{{ $month['month'] }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="month_to" class="col-form-label">{{ __('Month To') }}</label>
                                <div class="col-md-12">
                                    <select class="choices filter-input form-select" aria-label="Permissions"
                                        id="month_to" data-column="3" name="month_to" style="height: 210px;">
                                        <option value="">{{ __('Month To') }}</option>
                                            @foreach ($months as $key => $month)
                                                <option value="{{ $key }}">{{ $month['month'] }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="account_name" class="col-form-label"></label>
                                <div class="col-md-12">
                                    <form method="post" action="{{ route('journel_report_export') }}" id="export_saving">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-outline-success btn-md float-start my-3">{{ __('Export') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- <form method="post" action="{{ route('journel_report_export') }}" id="export_saving">
                    @csrf
                    <button type="submit"
                        class="btn btn-outline-success btn-md float-start my-3">{{ __('All Export') }}</button>
                </form> --}}

            </div>
        </div>
    </div>
    </div>
@endsection

