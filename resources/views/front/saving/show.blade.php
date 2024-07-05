@extends('front.layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<div class="dashboard-detail-page apply-detail-page">
    <div class="container">
        <div class="dashboard-box-listing">
            <div class="desh-listbox skybluebg-box wow fadeInDown" data-wow-delay="0.2s">
                <div class="dash-box-title">{{ __('Saving Details') }}</div>
            </div>
            <div class="row wow fadeInUp" data-wow-delay="0.2s">
                <div class="col-md-6">
                    <div class="dashboard-detail-data">
                        <label class="col-form-label"><strong>{{ __('Saving Amount') }} :</strong></label>
                        <div class="col-form-info">
                            {{ $saving_amount ?? 0 }}
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-6">
                    <div class="dashboard-detail-data">
                        <label class="col-form-label"><strong>{{ __('Amount') }} :</strong></label>
                        <div class="col-form-info">
                            &#8377; {{ $share_amount ?? 0 }}
                        </div>
                    </div>
                </div> --}}
            </div>

            <div class="desh-listbox skybluebg-box wow fadeInDown" data-wow-delay="0.2s">
                <div class="dash-box-title">{{ __('All Savings') }}</div>
            </div>
            <table class="table table-bordered" id="table1">
                <thead>
                    <tr>
                        <th>{{ __('No.') }}</th>
                        <th>{{ __('Month') }}</th>
                        <th>{{ __('Amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($savings))
                        @foreach ($savings as $key => $s)
                            <tr align="center">
                                <td data-label="No.">{{ $key+1 }}</td>
                                <td data-label="Month">{{ date('M-Y', strtotime('01-' .$s->month)) }}</td>
                                <td data-label="Amount">{{ $s->fixed_amount }}</td>
                            </tr>
                        @endforeach
                    @else
                        {{ __('Not Found') }}
                    @endif
            </table>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    $(function() {
        var table = $('#table1').DataTable({});
    });
</script>
@endpush
