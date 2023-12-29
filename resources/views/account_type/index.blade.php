@extends('layouts.app')
@section('content')
@section('title'){{ $page_title }}@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">
            @can('create-role')
                <a href="{{ route('account_type.create') }}" class="btn btn-outline-success btn-md  float-end my-3"><i class="bi bi-plus-circle"></i> {{__('Add New Account Type')}}</a>
            @endcan
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">S#</th>
                        <th scope="col">{{__('Name')}}</th>
                        <th scope="col" style="width: 250px;">{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($account_types as $key => $account_type)
                        <tr>
                            <th scope="row">{{ ($account_types->currentPage()>1) ? ($key+1)+$account_types->perPage() : $key+1 }}</th>
                            <td>{{ $account_type->type_name }}</td>
                            <td>
                                <form action="{{ route('account_type.destroy', $account_type->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    {{-- <a href="{{ route('account_type.show', $account_type->id) }}" class="btn btn-outline-info btn-sm"><i
                                            class="bi bi-eye"></i> {{__('Show')}}</a> --}}

                                        @can('edit-account_type')
                                            <a href="{{ route('account_type.edit', $account_type->id) }}" class="btn btn-outline-warning btn-sm"><i
                                                    class="bi bi-pencil-square"></i> {{__('Edit')}}</a>
                                        @endcan

                                        @can('delete-account_type')
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm(`{{__('Do you want to delete this account type?')}}`);"><i
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

            {{ $account_types->links() }}

        </div>
    </div>
</section>
@endsection
