@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">

    <div class="card">
        <div class="card-body">
            <div class="form">
                <div class="row mb-3" id="filter">

                    <div class="col-md-4">
                        <label for="member_id" class="col-md-2 col-form-label">{{ __('Member') }}</label>
                        <div class="col-md-12">
                            <select class="choices filter-input form-select" aria-label="Permissions" id="member_id" data-column="0" name="member_id" style="height: 210px;">
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
                            <select class="choices filter-input form-select" aria-label="Permissions" id="status" data-column="4" name="status" style="height: 210px;">
                                <option value="">{{ __('Select Status') }}</option>
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Closed') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="account_name" class="col-md-6 col-form-label"></label>
                        <div class="col-md-12">
                            {{ __('Total Active share') }}: {{ $active_share_count }} ({{ $share_amount }})
                        </div>
                    </div>

                </div>
            </div>
            <div class="pt-2 mt-2">
                <table class="table table-bordered" id="table1">
                    <thead>
                        <tr>
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
</section>
@endsection
@push('script')
<script>

    $(function() {
        var table = $('#table1').DataTable({

            processing: true,
            serverSide: true,
            ajax: "{{ route('ledger_sharecapital.index') }}",
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
                    data: 'created_date',
                    name: 'created_date'
                },
                {
                    data: 'status',
                    name: 'status'
                },
            ],
        });

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
        var cc = confirm(`{{ __('Do you really want to close the share?') }}`);

        if (cc) {
            var url = "{{ route('ledger_sharecapital.update', ':id') }}";
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
</script>
@endpush
