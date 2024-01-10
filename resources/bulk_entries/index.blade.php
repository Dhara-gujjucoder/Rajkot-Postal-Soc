@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">
            @can('create-role')
                <a href="{{ route('ledger_entries.create') }}" class="btn btn-outline-success btn-md  float-end my-3"><i class="bi bi-plus-circle"></i> {{ __('Add New Ledger Entry') }}</a>
            @endcan
            <div class="pt-4 mt-5">
                <table class="table table-bordered" id="table1">
                    <thead>
                        <tr>
                            <th>{{ __('No.') }}</th>
                            <th>{{ __('Ledger') }}</th>
                            <th>{{ __('Particular') }}</th>
                            <th>{{ __('Amount ') }}</th>
                            <th>{{ __('Date ') }}</th>
                            <th>{{ __('Action') }} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ledger_entries as $key => $entry)
                        <tr>
                            <td>{{ $entry->id }}</td>
                            <td>{{ $entry->LedgerAcountName->account_name }}</td>
                            <td>{{ $entry->particular }}</td>
                            <td>{{ $entry->amount }}</td>
                            <td>{{ $entry->date }}</td>
                            <td>
                                <form action="{{ route('ledger_entries.destroy', $entry->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                        @can('edit-ledger_entries')
                                            <a href="{{ route('ledger_entries.edit', $entry->id) }}" class="btn btn-outline-warning btn-sm"><i
                                                    class="bi bi-pencil-square"></i> {{__('Edit')}}</a>
                                        @endcan

                                        @can('delete-ledger_entries')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm(`{{__('Do you want to delete this ledger Entry?')}}`);"><i
                                                        class="bi bi-trash"></i> {{__('Delete')}}</button>
                                        @endcan

                                </form>
                            </td>
                        </tr>
                        @endforeach

                </table>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('script')
<script>
    $(function() {
        var table = $('#table1').DataTable({
        });
    });
</script>
@endpush
