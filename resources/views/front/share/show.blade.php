@extends('front.layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<div class="dashboard-detail-page apply-detail-page">
    <div class="container">
        <div class="dashboard-box-listing">
            <div class="desh-listbox skybluebg-box wow fadeInDown" data-wow-delay="0.2s">
                <div class="dash-box-title">{{ __('Share Details') }}</div>
            </div>
            <div class="row wow fadeInUp" data-wow-delay="0.2s">
                <div class="col-md-6">
                    <div class="dashboard-detail-data">
                        <label class="col-form-label"><strong>{{ __('Active Share') }}:</strong></label>
                        <div class="col-form-info">{{ $active_share_count ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-detail-data">
                        <label class="col-form-label"><strong>{{ __('Active Share Amount') }}:</strong></label>
                        <div class="col-form-info">&#8377; {{ $share_amount ?? 0 }}</div>
                    </div>
                </div>
            </div>
            
            <div class="desh-listbox skybluebg-box wow fadeInDown" data-wow-delay="0.2s">
                <div class="dash-box-title">{{ __('All Share') }}</div>
            </div>
            <table class="table table-bordered" id="table1">
                <thead>
                    <tr>
                        <th>{{ __('Code') }}</th>
                        <th>{{ __('Amount') }} </th>
                        <th>{{ __('Date') }} </th>
                        <th>{{ __('Status') }} </th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($shares))
                        @foreach ($shares as $key => $share)
                            <tr align="center">
                                <td data-label="Code">{{ $share->share_code }}</td>
                                <td data-label="Amount">{{ $share->share_amount }}</td>
                                <td data-label="Date">{{ date('d-M-Y', strtotime($share->purchase_on)) }}</td>
                                <td data-label="Status">
                                    @if ($share->status == 1)
                                        {{-- <button type="button" class="btn btn-outline-warning btn-sm" id="share_acc{{ $share->id }}" onclick="changeStatus('{{ $share->id }}')">{{ __('Sell') }}</button> --}}
                                        {{-- <a href="{{ route('member_share.edit', $share->id) }}" class="btn btn-outline-warning btn-sm">{{__('Close')}}</a> --}}
                                        {{ __('Active') }}
                                    @else
                                        <b>{{ __('Sold') }}</b>
                                    @endif
                                </td>
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
