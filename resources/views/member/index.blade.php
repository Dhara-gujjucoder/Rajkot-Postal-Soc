@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">
            @can('create-member')
                <a href="{{ route('members.create') }}" class="btn btn btn-outline-success btn-md  float-end my-3"><i
                        class="bi bi-plus-circle"></i> {{ __('Add New Member') }}</a>
            @endcan
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">S#</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Email') }}</th>
                        <th scope="col">{{ __('Roles') }}</th>
                        <th scope="col">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($members as $member)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td>
                                @forelse ($member->getRoleNames() as $role)
                                    <span class="badge bg-primary">{{ $role }}</span>
                                @empty
                                @endforelse
                            </td>
                            <td>
                                <form action="{{ route('members.destroy', $member->member->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('members.show', $member->member->id) }}"
                                        class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i>
                                        {{ __('Show') }}</a>

                                    @if (in_array('Super Admin', $member->getRoleNames()->toArray() ?? []))
                                        {{-- @if (Auth::member()->hasRole('Admin'))
                                            <a href="{{ route('members.edit', $member->id) }}"
                                                class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i>
                                                Edit</a>
                                        @endif --}}
                                    @else
                                        @can('edit-member')
                                            <a href="{{ route('members.edit', $member->member->id) }}"
                                                class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil-square"></i>
                                                {{ __('Edit') }}</a>
                                        @endcan

                                        @can('delete-member')
                                            @if (Auth::user()->id != $member->id)
                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm(`{{ __('Do you want to delete this members?') }}`);"><i
                                                        class="bi bi-trash"></i> {{ __('Delete') }}</button>
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

            {{ $members->links() }}

        </div>
    </div>
</section>
@endsection
