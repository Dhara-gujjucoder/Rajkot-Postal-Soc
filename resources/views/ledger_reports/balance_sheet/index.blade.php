@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">

            <div class="header_add">
                @can('edit-balance_sheet')
                    <div class="col-md-12">
                        <a href="{{ route('balance_sheet.edit', currentYear()->id) }}" class="btn btn-outline-success btn-md float-end my-3">
                            <i class="bi bi-pencil-fill"></i> {{ __('Edit') }}
                        </a>
                @endif

                <div class="@cannot('edit-balance_sheet') col-md-12 @else col-md-11 @endcannot">
                        <div class="mb-3 form-group opening_balance">
                            <label for="email">
                                <span>{{ __('Total Saving') }}:</span>
                                <b>{{ $balance_financial_year->total_saving }}</b>

                            </label>
                            <label for="email">
                                <span>{{ __('Total Interest') }}:</span>
                                <b>{{ $balance_financial_year->total_interest }}</b>
                            </label>
                            <label for="email">
                                <span>{{ __('Total Share') }}:</span>
                                <b>{{ $balance_financial_year->total_share }}({{ $balance_financial_year->total_share_amount }})</b>
                            </label>
                            <label for="email">
                                <span>{{ __('Balance') }}:</span>
                                <b>{{ $balance_financial_year->balance }}</b>
                            </label>
                        </div>

                    </div>

                </div>
            </div>

            <div class="pt-0 mt-0">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1">
                        <thead>
                            <tr>
                                {{-- <th scope="col">{{ __('Sr') }}</th> --}}
                                <th scope="col">{{ __('Ledger Ac Name') }}</th>
                                <th scope="col">{{ __('Balance') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalBalance = 0;
                            @endphp

                            @foreach ($balance_sheet as $key => $sheet)
                                <tr>
                                    {{-- <td>{{ $key + 1 }}</td> --}}
                                    <td>{{ $sheet->ledger_ac_name }}</td>
                                    <td>{{ $sheet->balance }}</td>
                                </tr>
                                @php
                                    $totalBalance += $sheet->balance;
                                @endphp
                            @endforeach
                                <tr>
                                    <b><td scope="col"></td></b>
                                    <th>{{ __('Total Provision') }}: {{$totalBalance}}</th>
                                </tr>
                        </tbody>
                    </table>

                    {{-- <table>
                        <tr align="right">
                            <b><th scope="col">{{ __('Total Balance:') }}</th></b>
                            <td>{{100}}</td>
                        </tr>
                    </table> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(function() {
            var table = $('#table1').DataTable({
                "pageLength": 25,
                "lengthChange": false,
                "searching": false,
                "ordering": false
                "bPaginate": false,
            });
        });
    </script>


@endpush
