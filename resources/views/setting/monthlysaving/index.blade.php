@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">
            @can('create-role')
                <a href="{{ route('monthlysaving.create') }}" class="btn btn-outline-success btn-md  float-end my-3"><i
                        class="bi bi-plus-circle"></i> {{ __('Add New Monthly Saving') }}</a>
            @endcan
            @if (!empty($monthly_saving))
                <table class="table table-bordered ">
                    <tr>
                        <th>{{ __('No.') }}</th>
                        <th>{{ __('Monthly Saving') }}</th>
                        <th>{{ __('Start Date') }}</th>
                        <th>{{ __('End Date') }}</th>
                        <th>{{ __('Is current ?') }} </th>
                        <th>{{ __('Action') }} </th>
                    </tr>
                    @foreach ($monthly_saving as $key => $amount)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $amount->monthly_saving }}</td>
                            <td>{{ $amount->start_date ? date('d-m-Y H:i:s', strtotime($amount->start_date)) : '' }}
                            </td>
                            <td>{{ $amount->end_date && $amount->is_active == 0 ? date('d-m-Y H:i:s', strtotime($amount->end_date)) : '' }}
                            </td>
                            <td>{{ $amount->is_active == 1 ?  __('Yes') : __('No') }} </td>
                            <td>  <a href="{{ route('monthlysaving.edit', $amount->id) }}" class="btn btn-outline-warning btn-sm"><i
                                        class="bi bi-pencil-square"></i> {{__('Edit')}}</a></td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>
    </div>
</section>
@endsection
