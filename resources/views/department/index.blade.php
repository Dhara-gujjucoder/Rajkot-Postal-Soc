@extends('layouts.app')
@section('content')
@section('title'){{ $page_title }}@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">
            <div class="header_add">
                <div class="form">

                </div>
                @can('create-department')
                <a href="{{ route('department.create') }}" class="btn btn-outline-success btn-md  float-end mb-3"><i class="bi bi-plus-circle"></i> {{__('Add New Department')}}</a>
                @endcan
            </div>
            <div class="pt-2 mt-2">
            <table class="table table-bordered" id="table1">
                <thead>
                    <tr>
                        <th scope="col">{{ __('No.') }}</th>
                        <th scope="col">{{__('Name')}}</th>
                        {{-- <th scope="col">{{__('Ledger Group')}}</th> --}}
                        <th scope="col" style="width: 250px;">{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($departments as $key => $department)
                        <tr>
                            <th scope="row">{{  ($key+1) }}</th>
                            <td>{{ $department->department_name }}</td>
                            {{-- <td>{{ $department->LedgerGroupId->ledger_group ?? '' }}</td> --}}
                            <td>
                                <form action="{{ route('department.destroy', $department->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    {{-- <a href="{{ route('department.show', $department->id) }}" class="btn btn-outline-info btn-sm"><i
                                            class="bi bi-eye"></i> {{__('Show')}}</a> --}}

                                        @can('edit-department')
                                            <a href="{{ route('department.edit', $department->id) }}" class="btn btn-outline-warning btn-sm"><i
                                                    class="bi bi-pencil-square"></i> {{__('Edit')}}</a>
                                        @endcan

                                        @can('delete-department')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm(`{{__('Do you want to delete this Department?')}}`);"><i
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
