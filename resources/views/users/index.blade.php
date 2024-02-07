@extends('layouts.app')
@section('content')
@section('title'){{ $page_title }}@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">
            <div class="header_add">

                <div class="form"></div>
            @can('create-user')
                <a href="{{ route('users.create') }}" class="btn btn btn-outline-success btn-md  float-end my-3"><i class="bi bi-plus-circle"></i> {{__('Add New User')}}</a>
            @endcan
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">S#</th>
                            <th scope="col">{{__('Name')}}</th>
                            <th scope="col">{{__('Email')}}</th>
                            <th scope="col">{{__('Roles')}}</th>
                            <th scope="col">{{__('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @forelse ($user->getRoleNames() as $role)
                                        <span class="badge bg-primary">{{ $role }}</span>
                                    @empty
                                    @endforelse
                                </td>
                                <td>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')

                                        <a href="{{ route('users.show', $user->id) }}" class="btn btn-outline-info btn-sm"><i
                                                class="bi bi-eye"></i> {{__('Show')}}</a>

                                        @if (in_array('Super Admin', $user->getRoleNames()->toArray() ?? []))
                                            {{-- @if (Auth::user()->hasRole('Admin'))
                                                <a href="{{ route('users.edit', $user->id) }}"
                                                    class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i>
                                                    Edit</a>
                                            @endif --}}

                                        @else
                                            @can('edit-user')
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-warning btn-sm"><i
                                                        class="bi bi-pencil-square"></i> {{__('Edit')}}</a>
                                            @endcan

                                            @can('delete-user')
                                                @if (Auth::user()->id != $user->id)
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm(`{{__('Do you want to delete this user?')}}`);"><i
                                                            class="bi bi-trash"></i> {{__('Delete')}}</button>
                                                @endif
                                            @endcan
                                        @endif

                                    </form>
                                </td>
                            </tr>
                        @empty
                            <td colspan="5">
                                <span class="text-danger">
                                    <strong>No User Found!</strong>
                                </span>
                            </td>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $users->links() }}

        </div>
    </div>
</section>
@endsection
