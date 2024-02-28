@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">

            <div class="header_add">
                <div class="form">

                </div>
                <form method="post" action="{{ route('all_share_ledger_export') }}" id="export_saving">
                    @csrf
                    <button type="submit" class="btn btn-outline-success btn-md float-start my-3">{{ __('All Export') }}</button>
                </form>
            </div>

            <div class="pt-2 mt-2">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1" align="center">
                        <thead>
                            <tr>
                                <th>{{ __('Member') }}</th>
                                <th>{{ __('Opening Balance') }}</th>
                                <th>{{ __('Total Purchase') }}</th>
                                <th>{{ __('Total Sold') }}</th>
                                <th>{{ __('Net Balance') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($members as $key => $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->share_ledger_account->opening_balance }}</td>
                                    <td>{{ $member->purchased_share->sum('share_sum_share_amount') }}</td>
                                    <td>{{ $member->sold_share->sum('share_sum_share_amount') }}</td>
                                    <td>{{ $member->share_ledger_account->opening_balance + $member->purchased_share->sum('share_sum_share_amount') - $member->sold_share->sum('share_sum_share_amount') }}</td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')
<script>
    $(function() {
        var table = $('#table1').DataTable({
            "pageLength": 25,

            processing: true,
            serverSide: true,
            ajax: "{{ route('ledger_reports.share_ledger.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id',
                    searchable: true
                },
                {
                    data: 'opening_balance',
                    name: 'opening_balance',
                    searchable: true
                },
                {
                    data: 'purchased_share',
                    name: 'purchased_share'
                },
                {
                    data: 'sold_share',
                    name: 'sold_share'
                },
                {
                    data: 'net_balance',
                    name: 'net_balance'
                },
            ],

        });

    });
</script>
@endpush
