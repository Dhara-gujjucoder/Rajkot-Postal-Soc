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

                    <div class="col-md-4">
                        <label for="account_name" class="col-md-6 col-form-label"></label>
                        <div class="col-md-12">
                            {{ __('Total Active share') }}: {{ $active_share_count }} ({{ $share_amount }})
                        </div>
                    </div>

                    <div class="row mb-3" id="filter">
                        <div class="col-md-5">
                            <label for="member_id" class="col-md-2 col-form-label">{{ __('Member') }}</label>
                            <div class="col-md-12">
                                <select class="choices filter-input form-select" aria-label="Permissions" id="member_id" data-column="1" name="member_id" style="height: 210px;">
                                    <option value="">{{ __('Member') }}</option>
                                    @forelse ($members as $key => $member)
                                        <option value="{{ $member->id }}" {{ $member->id == old('id') ? 'selected' : '' }}>
                                            {{ $member->fullname }}
                                        </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        <div class="col-md-4">
                            <label for="account_name" class="col-md-2 col-form-label">{{ __('Status') }}</label>
                            <div class="col-md-12">
                                <select class="choices filter-input form-select" aria-label="Permissions" id="status" data-column="5" name="status" style="height: 210px;">
                                    <option value="">{{ __('Select Status') }}</option>
                                    <option value="1">{{ __('Sell') }}</option>
                                    <option value="0">{{ __('Sold') }}</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- <a href="{{ route('member_share.create') }}" class="btn btn btn-outline-success btn-md mb-3"><i
                    class="bi bi-plus-circle"></i> {{ __('Add New Share') }}</a> --}}
            </div>

            <div class="pt-2 mt-2">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1">
                        <thead>
                            <tr>
                                <th>{{ __('M.no') }}</th>
                                <th>{{ __('Member') }}</th>
                                <th>{{ __('Code') }}</th>
                                <th>{{ __('Amount') }} </th>
                                <th>{{ __('Date') }} </th>
                                <th>{{ __('Status') }} </th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($shares as $key => $share)
                                <tr>
                                    <td>{{ $share->member->name }}</td>
                                    <td>{{ $share->share_code }}</td>
                                    <td>{{ $share->share_amount }}</td>
                                    <td>{{ date('d-M-Y', strtotime($share->created_date)) }}</td>
                                    <td>
                                        @if ($share->status == 1)
                                            <b>{{ __('Active') }}</b>
                                        @else
                                            {{ __('Closed') }}
                                        @endif
                                    </td>
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

            // "iDisplayLength": 25,
            "pageLength": 20,
            // "lengthChange": false,
            // "searching": false,
            // "bFilter": false,

            processing: true,
            serverSide: true,
            ajax: "{{ route('member_share.index') }}",
            columns: [
                {
                    data: 'uid',
                    name: 'uid'
                },
                {
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
        $(".dataTables_filter").hide();
        $(".dataTables_length").hide();
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
@endpush
