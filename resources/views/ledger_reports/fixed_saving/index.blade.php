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
            {{-- @can('create-loan_matrix') --}}
                <a href="#" class="btn btn-outline-success btn-md  float-end my-3">{{ __('All Export') }}</a>
            {{-- @endcan --}}
            </div>
            <div class="pt-2 mt-2">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1">
                        <thead>
                            <tr>
                                <th scope="col">{{ __('No.') }}</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Ledger Group') }}</th>
                                <th scope="col">{{ __('Member') }}</th>
                                <th scope="col">{{ __('Opening Balance') }}</th>
                                <th scope="col">{{ __('Type') }}</th>
                                <th scope="col" style="width: 250px;">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody align="center">
                            @foreach ($fixed_savings as $key => $fixed)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $fixed->member->fullname }}</td>
                                <td>{{ $fixed->LedgerGroupId->ledger_group }}</td>
                                <td>{{ $fixed->member->name }}</td>
                                <td>{{ $fixed->opening_balance }}</td>
                                <td>{{ $fixed->type }}</td>
                                <td><a href="{{ route('ledger_fixed_saving_export',$fixed->id) }}" class="btn btn-outline-warning btn-sm">Export</a></td>
                            </tr>
                            @endforeach
                        </tbody>

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
