@extends('layouts.app')
@section('content')
@section('title'){{ $page_title }}@endsection
<section class="page-content">

    <div class="card">
        <div class="card-body">

            <div class="header_add">
                <div class="form">
                    <div class="row mb-3" id="filter">
                        <div class="col-md-4">
                            <label for="parent_id" class="col-form-label">{{ __('Parent Ledger Group') }}</label>
                            <div class="col-md-12">
                                <select class="choices filter-input form-select"
                                    aria-label="Permissions" id="parent_id" data-column="2" name="parent_id"
                                    style="height: 210px;">
                                    <option value="">{{ __('Parent Ledger Group') }}</option>
                                    @forelse ($ledgers as $key => $ledger)
                                        <option value="{{ $ledger->id }}"
                                            {{ $ledger->id == old('parent_id') ? 'selected' : '' }}>
                                            {{ $ledger->ledger_group }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                @can('create-ledger_group')
                    <a href="{{ route('ledger_group.create') }}" class="btn btn-outline-success btn-md  float-end my-3"><i class="bi bi-plus-circle"></i> {{__('Add New Ledger Group')}}</a>
                @endcan

            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="table1">
                    <thead>
                        <tr>
                            {{-- <th scope="col">{{ __('No.') }}</th> --}}
                            <th scope="col">S#</th>
                            <th scope="col">{{__('Name')}}</th>
                            <th scope="col">{{__('Parent Ledger Group')}}</th>

                            <th scope="col" style="width: 250px;">{{__('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>

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
            ajax: "{{ route('ledger_group.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'ledger_group',
                    name: 'ledger_group',
                    searchable: true
                },{
                    data: 'parent_id',
                    name: 'parent_id',
                    searchable: true
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],

        });
        $('#parent_id').on('change', function() {
            table
                .columns($(this).data('column'))
                .search($(this).val())
                .draw();
        });
    });
</script>
@endpush

