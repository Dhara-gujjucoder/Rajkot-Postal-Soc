@extends('layouts.app')
@section('content')
@section('title'){{ $page_title }}@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">
            @can('create-role')
                <a href="{{ route('ledger_account.create') }}" class="btn btn-outline-success btn-md  float-end my-3"><i class="bi bi-plus-circle"></i> {{__('Add New Ledger Account')}}</a>
            @endcan
            <div class="pt-4 mt-5">
            <table class="table table-bordered" id="table1">
                <thead>
                    <tr>
                        <th scope="col">{{ __('No.') }}</th>
                        <th scope="col">{{__('Name')}}</th>
                        <th scope="col">{{__('Ledger Group')}}</th>
                        <th scope="col" style="width: 250px;">{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ledger_accounts as $key => $ledger_account)
                        <tr>
                            <th scope="row">{{  ($key+1) }}</th>
                            <td>{{ $ledger_account->account_name }}</td>
                            <td>{{ $ledger_account->LedgerGroupId->type_name ?? '' }}</td>
                            <td>
                                <form action="{{ route('ledger_account.destroy', $ledger_account->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    {{-- <a href="{{ route('ledger_account.show', $ledger_account->id) }}" class="btn btn-outline-info btn-sm"><i
                                            class="bi bi-eye"></i> {{__('Show')}}</a> --}}

                                        @can('edit-ledger_account')
                                            <a href="{{ route('ledger_account.edit', $ledger_account->id) }}" class="btn btn-outline-warning btn-sm"><i
                                                    class="bi bi-pencil-square"></i> {{__('Edit')}}</a>
                                        @endcan

                                        @can('delete-ledger_account')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm(`{{__('Do you want to delete this ledger account?')}}`);"><i
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
                    @endforelse
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
        });
    });
</script>
@endpush
