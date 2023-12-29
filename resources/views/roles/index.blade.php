@extends('layouts.app')
@section('content')
@section('title'){{ $page_title }}@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">
            @can('create-role')
                <a href="{{ route('roles.create') }}" class="btn btn-outline-success btn-md  float-end my-3"><i class="bi bi-plus-circle"></i> {{__('Add New Role')}}</a>
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
                    @forelse ($roles as $role)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $role->name }}</td>
                            <td>
                                <form action="{{ route('roles.destroy', $role->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <a href="{{ route('roles.show', $role->id) }}" class="btn btn-outline-info btn-sm"><i
                                            class="bi bi-eye"></i> {{__('Show')}}</a>

                                    @if ($role->name != 'Super Admin')
                                        @can('edit-role')
                                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-outline-warning btn-sm"><i
                                                    class="bi bi-pencil-square"></i> {{__('Edit')}}</a>
                                        @endcan

                                        @can('delete-role')
                                            @if ($role->name != Auth::user()->hasRole($role->name))
                                                {{-- <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Do you want to delete this role?');"><i
                                                        class="bi bi-trash"></i> {{__('Delete')}}</button> --}}
                                            @endif
                                        @endcan
                                    @endif

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

            {{ $roles->links() }}

        </div>
    </div>
</section>
@endsection
