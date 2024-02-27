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
            </div>

            <div class="pt-2 mt-2">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1">
                        <thead>
                            <tr>
                                <th>{{ __('Member') }}</th>
                                <th>{{ __('Opening Balance') }}</th>
                                <th>{{ __('Total Purchase') }} </th>
                                <th>{{ __('Total Sold') }} </th>
                                <th>{{ __('Net Balance') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($members as $key => $member)

                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->share_ledger_account->opening_balance}}</td>
                                    <td>{{ $member->purchased_share->sum('share_sum_share_amount') }}</td>
                                    <td>{{ $member->sold_share->sum('share_sum_share_amount') }}</td>
                                    <td>{{ $member->share_ledger_account->opening_balance+$member->purchased_share->sum('share_sum_share_amount')-$member->sold_share->sum('share_sum_share_amount')  }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

{{-- @push('script')
<script>
    $(function() {
        var table = $('#table1').DataTable({

            // "iDisplayLength": 25,
            "pageLength": 25,

            processing: true,
            serverSide: true,
            ajax: "{{ route('member_share.index') }}",
            columns: [{
                    data: 'member_id',
                    name: 'member_id',
                    searchable: true
                },
                {
                    data: 'share_code',
                    name: 'share_code',
                    searchable: true
                },
                {
                    data: 'share_amount',
                    name: 'share_amount'
                },
                {
                    data: 'purchase_on',
                    name: 'purchase_on'
                },
                {
                    data: 'status',
                    name: 'status'
                },
            ],
        });

        // $('#table1_filter').after('<div class="ms-5">Total Active share</div>');

        $('#member_id').on('change', function() {
            table
                .columns($(this).data('column'))
                .search($(this).val())
                .draw();
        });
        $('#status').on('change', function() {
            table
                .columns($(this).data('column'))
                .search($(this).val())
                .draw();
        });
    });

    function changeStatus(share_id) {
        var cc = confirm(`{{ __('Do you really want to Sold the share?') }}`);

        if (cc) {
            var url = "{{ route('member_share.update', ':id') }}";
            url = url.replace(':id', share_id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: url,
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: '0'
                },
                dataType: 'JSON',
                success: function(data) {
                    if(data.status){
                        location.reload();
                    }
                }
            });
        }
    }

    // $('#table1').dataTable({
    //     "iDisplayLength": 25,
    // });
</script>
@endpush --}}
