@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">

            <div class="header_add">
                <form method="post" action="{{ route('rojmel_report_export') }}" id="rojmel_export" enctype="multipart/form-data">
                    @csrf
                    <div class="form">

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="month_from" class="col-form-label">{{ __('Month From') }}</label>
                                <div class="col-md-12">
                                    <select class="choices filter-input form-select" aria-label="Permissions"
                                        id="month_from"  name="month_from" style="height: 210px;">
                                        <option value="">{{ __('Month From') }}</option>
                                        @php $months = getMonthsOfYear($current_year->id); @endphp
                                        @foreach ($months as $key => $month)
                                            <option value="{{ $key }}">{{ $month['month'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('month_from'))
                                        <span class="text-danger">{{ $errors->first('month_from') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="month_to" class="col-form-label">{{ __('Month To') }}</label>
                                <div class="col-md-12">
                                    <select class="choices filter-input form-select" aria-label="Permissions" id="month_to"  name="month_to" style="height: 210px;">
                                        <option value="">{{ __('Month To') }}</option>
                                        @foreach ($months as $key => $month)
                                            <option value="{{ $key }}">{{ $month['month'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('month_to'))
                                        <span class="text-danger">{{ $errors->first('month_to') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="account_name" class="col-form-label"></label>
                                <div class="col-md-12">
                                    <div method="post" action="{{ route('rojmel_report_export') }}" id="rojmel_export">
                                        @csrf
                                        <button type="submit" id="submitBtn" class="btn btn-outline-success btn-md float-start my-3">{{ __('Export') }}</button>
                                        <div id="loading" style="display: none;" class="btn btn-outline-light btn-md float-start my-3">{{ __('Loading...') }}</div>
                                    </div>
                </form>

            </div>
        </div>
    </div>
    </div>
    </form>
    </div>
    </div>
    </div>
    </div>
@endsection
@push('script')
    <script>
        $('#submitBtn').on('click', function() {
            $('#loading').show(); // Display the loading symbol
            $('#submitBtn').hide();
            // Serialize the form data
            var formData = $('#rojmel_export').serialize();

            // Submit form data using AJAX
            $.ajax({
                url: $('#rojmel_export').attr('action'),
                type: 'POST',
                data: formData,

                success: function(response) {
                    // Hide loading symbol after successful submission
                    $('#loading').hide();
                    $('#submitBtn').show();
                    //$('#month_from').val('');
                },

                error: function(xhr) {
                    $('#loading').hide();
                    $('#submitBtn').show();
                    // Handle errors if needed
                }
            });
        });
    </script>
@endpush
