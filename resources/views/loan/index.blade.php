@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">
            @can('create-loan')
                <a href="{{ route('loan.create') }}" class="btn btn-outline-success btn-md  float-end my-3"><i
                        class="bi bi-plus-circle"></i> {{ __('Add New Loan') }}</a>
            @endcan
            <div class="pt-4 mt-5">
                <table class="table table-bordered" id="table1">
                    <thead>
                        <tr>
                            <th>{{ __('No.') }}</th>
                            <th>{{ __('Loan A/c') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Amount') }}</th>
                            <th>{{ __('EMI Amount') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }} </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('script')
    <script>
        $(function() {
            var table = $('#table1').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('loan.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'loan_no',
                        name: 'loan_no',
                        searchable: true
                    },
                    {
                        data: 'member_id',
                        name: 'member_id',
                        searchable: true
                    },
                    {
                        data: 'principal_amt',
                        name: 'principal_amt',
                        searchable: true
                    },
                    {
                        data: 'emi_amount',
                        name: 'emi_amount',
                        searchable: true
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],

            });
        });
    </script>
@endpush
