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
            @can('create-bulk_entries')
                <a href="{{ route('bulk_entries.create') }}" class="btn btn-outline-success btn-md  float-end my-3"><i
                    class="bi bi-plus-circle"></i> {{ __('Add New Bulk Entry') }}
                </a>
            @endcan
            </div>
            <div class="pt-4 mt-5">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1">
                        <thead>
                            <tr>
                                <th>{{ __('No.') }}</th>
                                <th>{{ __('Month') }}</th>
                                <th>{{ __('Total') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Action') }} </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bulk_entries as $key => $entry)
                                <tr>
                                    <td>{{ $entry->id }}</td>
                                    <td>{{ date('M-Y',strtotime('01-'.$entry->month)) }}</td>
                                    <td>{{ $entry->total }}</td>
                                    <td>{{ $entry->status }}</td>
                                    <td>
                                        <form action="{{ route('bulk_entries.destroy', $entry->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')

                                            @can('edit-bulk_entries')
                                                @if($entry->getRawOriginal('status') != '2')
                                                <a href="{{ route('bulk_entries.edit', $entry->id) }}"
                                                    class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil-square"></i>
                                                    {{ __('Edit') }}</a>
                                                @endif
                                            @endcan
                                            @can('export-bulk_entries-report')
                                                <a href="{{ route('bulk_entries.export', $entry->id) }}"
                                                    class="btn btn-outline-info btn-sm"><i class="bi bi-file-earmark-excel"></i>
                                                    {{ __('Export') }}</a>
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
                order: [[0, 'desc']]
            });
        });
    </script>
@endpush
