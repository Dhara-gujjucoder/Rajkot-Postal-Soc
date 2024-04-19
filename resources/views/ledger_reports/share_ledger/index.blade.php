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
                <form method="post" action="{{ route('all_share_ledger_export') }}" id="export_share_ledger">
                    @csrf
                    <button type="submit" id="submitBtn" class="btn btn-outline-success btn-md float-start my-3">{{ __('All Export') }}</button>
                    <div id="loading" style="display: none;" class="btn btn-outline-light btn-md float-start my-3 disabled">{{ __('Loading...') }}</div>
                </form>
            </div>

            <div class="pt-2 mt-2">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1" align="center">
                        <thead>
                            <tr>
                                <th>{{ __('M.no') }}</th>
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
            columns: [
                {
                    data: 'uid',
                    name: 'uid'
                },
                {
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


    $('#submitBtn').on('click', function() {
            $('#loading').show(); // Display the loading symbol
            $('#submitBtn').hide();
            // Serialize the form data
            var formData = $('#export_share_ledger').serialize();

            // Submit form data using AJAX
            $.ajax({
                url: $('#export_share_ledger').attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Hide loading symbol after successful submission
                    $('#loading').hide();
                    $('#submitBtn').show();
                    $('#month_from').val('');

                    // Clear the form fields or do any other necessary actions
                    // $('#export_share_ledger')[0].reset();
                    // const element = document.querySelector('#month_from');
                    // const element2 = document.querySelector('#month_to');
                    // const choices = new Choices('#month_from');
                    // const choices2 = new Choices('#month_to');

                },
                error: function(xhr) {
                       $('#loading').hide();
                    // Handle errors if needed
                }
            });
        });

</script>
@endpush
