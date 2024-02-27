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
                <a href="{{ route('financial_year.create') }}" class="btn btn-outline-success btn-md  float-end my-3"><i class="bi bi-plus-circle"></i> {{ __('Add New Financial Year') }}</a>
            @endcan
            </div>
            <div class="pt-2 mt-2">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1">
                        <thead>
                            <tr>
                                <th>{{ __('No.') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Start Year') }}</th>
                                <th>{{ __('Start Month') }}</th>
                                <th>{{ __('End Year') }}</th>
                                <th>{{ __('End Month') }}</th>
                                <th>{{ __('Is active?') }} </th>
                                {{-- <th>{{ __('Is current?') }} </th> --}}
                                <th>{{ __('Status') }} </th>
                                <th>{{ __('Action') }} </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($financial_years as $key => $finance)
                                <tr>
                                    <td>{{ $finance->id }}</td>
                                    <td>{{ $finance->title }}</td>
                                    <td>{{ $finance->start_year }}</td>
                                    <td>{{  __(date('F', mktime(0, 0, 0, $finance->start_month, 10)));  }}</td>
                                    <td>{{ $finance->end_year }}</td>
                                    <td>{{ __(date('F', mktime(0, 0, 0, $finance->end_month, 10))) }}</td>
                                    
                                    <td>{{ $finance->is_active == 1 ? __('Yes') : __('No') }} </td>
                                    {{-- <td>{{ $finance->is_current == 1 ? __('Yes') : __('No') }} </td> --}}
                                    <td>{{ $finance->status == 1 ? __('Active') : __('Deactive') }} </td>
                                    <td>@can('edit-general-setting')<a href="{{ route('financial_year.edit', $finance->id) }}" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil-square"></i> {{ __('Edit') }}</a>@endcan</td>
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
