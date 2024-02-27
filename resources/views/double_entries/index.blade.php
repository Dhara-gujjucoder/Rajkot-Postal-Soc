@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">
            <div class="header_add">

                <div class="form"></div>
            @can('create-role')
                <a href="{{ route('double_entries.create') }}" class="btn btn-outline-success btn-md  float-end my-3"><i class="bi bi-plus-circle"></i> {{ __('Add New Double Entry') }}</a>
            @endcan
            </div>
            <div class="pt-2 mt-2">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1">
                        <thead>
                            <tr>
                                <th>{{ __('Entry ID') }}</th>
                                <th>{{ __('Credit Amount') }} (&#8377;)</th>
                                <th>{{ __('Debit Amount') }} (&#8377;)</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Action') }} </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($double_entries as $key => $entry)
                            <tr>
                                <td>{{ $entry->entry_id}}</td>
                                <td>{{ $entry->credit_amount }}</td>
                                <td>{{ $entry->debit_amount }}</td>
                                {{-- <td>{{ $entry->date }}</td> --}}
                               <td>{{ date('d-M-Y', strtotime($entry->date)); }}</td>
                                <td>
                                    <form action="{{ route('double_entries.destroy', $entry->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')

                                            {{-- @can('edit-double_entries')
                                                <a href="{{ route('double_entries.edit', $entry->id) }}" class="btn btn-outline-warning btn-sm"><i
                                                        class="bi bi-pencil-square"></i> {{__('Edit')}}</a>
                                            @endcan --}}

                                            @can('show-double_entries')
                                                <a href="{{ route('double_entries.show', $entry->id) }}" class="btn btn-outline-info btn-sm"><i
                                                        class="bi bi-eye-fill"></i> {{__('Show')}}</a>
                                            @endcan

                                            @can('delete-double_entries')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                        onclick="return confirm(`{{__('Do you want to delete this Double Entry?')}}`);"><i
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
