@extends('layouts.app')
@section('content')
@section('title'){{ $page_title }}@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">
            @can('create-role')
                <a href="{{ route('ledger_group.create') }}" class="btn btn-outline-success btn-md  float-end my-3"><i class="bi bi-plus-circle"></i> {{__('Add New Ledger Group')}}</a>
            @endcan
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">{{ __('No.') }}</th>
                        <th scope="col">{{__('Name')}}</th>
                        <th scope="col" style="width: 250px;">{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ledger_groups as $key => $ledger_group)
                        <tr>
                            <th scope="row">{{ ($ledger_groups->currentPage()>1) ? ($key+1)+$ledger_groups->perPage() : $key+1 }}</th>
                            <td>{{ $ledger_group->type_name }}</td>
                            <td>
                                <form action="{{ route('ledger_group.destroy', $ledger_group->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    {{-- <a href="{{ route('ledger_group.show', $ledger_group->id) }}" class="btn btn-outline-info btn-sm"><i
                                            class="bi bi-eye"></i> {{__('Show')}}</a> --}}

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
                    @endforelse
                </tbody>
            </table>

            {{ $ledger_groups->links() }}

        </div>
    </div>
</section>
@endsection
