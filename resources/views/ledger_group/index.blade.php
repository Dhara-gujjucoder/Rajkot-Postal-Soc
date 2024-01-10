@extends('layouts.app')
@section('content')
@section('title'){{ $page_title }}@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">
            @can('create-ledger_group')
                <a href="{{ route('ledger_group.create') }}" class="btn btn-outline-success btn-md  float-end my-3"><i class="bi bi-plus-circle"></i> {{__('Add New Ledger Group')}}</a>
            @endcan
            <div class="pt-4 mt-5">
                <div class="form">
                    <div class="row mb-3" id="filter">
                        <div class="col-md-4">
                            <label for="parent_id" class="col-md-6 col-form-label">{{ __('Parent Ledger Group') }}</label>
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
                        {{-- @forelse ($ledger_groups as $key => $ledger_group)
                            <tr>
                                <th scope="row">{{ ($ledger_groups->currentPage()>1) ? ($key+1)+$ledger_groups->perPage() : $key+1 }}</th>
                                <td>{{ $ledger_group->ledger_group }}</td>
                                <td>{{ $ledger_group->ParentLedgerGroup->ledger_group ?? '' }}</td>
                                <td>
                                    <form action="{{ route('ledger_group.destroy', $ledger_group->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                            @can('edit-ledger_group')
                                                <a href="{{ route('ledger_group.edit', $ledger_group->id) }}" class="btn btn-outline-warning btn-sm"><i
                                                        class="bi bi-pencil-square"></i> {{__('Edit')}}</a>
                                            @endcan

                                            @can('delete-ledger_group')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        onclick="return confirm(`{{__('Do you want to delete this Ledger Group?')}}`);"><i
                                                            class="bi bi-trash"></i> {{__('Delete')}}</button>
                                            @endcan
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <td colspan="3">
                                <span class="text-danger">
                                    <strong>No Role Found!</strong>
                                </span>
                            </td>
                        @endforelse --}}
                    </tbody>
                </table>

                {{ $ledger_groups->links() }}
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

