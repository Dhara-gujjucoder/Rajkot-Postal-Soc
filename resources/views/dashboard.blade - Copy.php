@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon purple mb-2">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">{{ __('Members') }}</h6>
                                    <h6 class="font-extrabold mb-0">{{ $total_members ?? '0' }}</h6>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">{{ __('Total Saving') }}</h6>
                                    <h6 class="font-extrabold mb-0">{{ $balance_financial_year->total_saving ?? '0' }}</h6>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">{{ __('Total Active Share') }}</h6>
                                    <h6 class="font-extrabold mb-0">{{ $balance_financial_year->total_share ?? '0' }}({{ $balance_financial_year->total_share_amount ?? '0' }})</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">{{ __('Total Interest') }}</h6>
                                    <h6 class="font-extrabold mb-0">{{ $balance_financial_year->total_interest ?? '0' }}</h6>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                {{-- {{ dd($active_loan); }} --}}
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">{{ __('Active loan') }}</h6>
                                    <h6 class="font-extrabold mb-0">{{ $active_loan ?? '0' }}</h6>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-6 col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon blue mb-2">
                                        <i class="iconly-boldProfile"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">{{ __('Balance') }}</h6>
                                    <h6 class="font-extrabold mb-0">{{ $balance_financial_year->balance ?? '0' }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




                {{-- <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon green mb-2">
                                        <i class="iconly-boldAdd-User"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Following</h6>
                                    <h6 class="font-extrabold mb-0">80.000</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start ">
                                    <div class="stats-icon red mb-2">
                                        <i class="iconly-boldBookmark"></i>
                                    </div>
                                </div>
                                <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                    <h6 class="text-muted font-semibold">Saved Post</h6>
                                    <h6 class="font-extrabold mb-0">112</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>

            <div class="pt-4 mt-5">
                <h3>{{ __('Bulk Entries') }}</h3>
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
                                    <td>{{ date('M-Y', strtotime('01-' . $entry->month)) }}</td>
                                    <td>{{ $entry->total }}</td>
                                    <td>{{ $entry->status }}</td>
                                    <td>
                                        <form action="{{ route('bulk_entries.destroy', $entry->id) }}" method="post">
                                            @csrf
                                            @method('DELETE')

                                            @can('edit-bulk_entries')
                                                @if ($entry->getRawOriginal('status') != '2')
                                                    <a href="{{ route('bulk_entries.edit', $entry->id) }}" target="_blank" class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil-square"></i>
                                                        {{ __('Edit') }}</a>
                                                @endif
                                            @endcan
                                            {{-- @can('export-bulk_entries-report')
                                                <a href="{{ route('bulk_entries.export', $entry->id) }}" class="btn btn-outline-info btn-sm"><i class="bi bi-file-earmark-excel"></i>
                                                    {{ __('Export') }}</a>
                                            @endcan --}}
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                    </table>
                </div>
            </div>

            @if (isset($loans))
            {{-- @if (!empty($loans)) --}}
                <div class="pt-2 mt-2">
                    <h3>{{ __('Pending Loan EMI') }}</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table1">
                            <thead>
                                <tr>
                                    <th>{{ __('No.') }}</th>
                                    <th>{{ __('Loan A/c') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('EMI Amount') }}</th>
                                    <th>{{ __('EMI Month') }}</th>
                                    <th>{{ __('Action') }} </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($loans as $key => $loan)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $loan->loan_no }}</td>
                                        <td>{{ $loan->member->name }}</td>
                                        <td>{{ $loan->principal_amt }}</td>
                                        <td>{{ $loan->emi_amount }}</td>
                                        <td>{{ date('M-Y',strtotime('01-' . $loan->loan_emis()->pending()->first()->month)) }}</td>
                                        <td>
                                            <a href="{{ route('loan.show', $loan->id).'?loan_show_dashboard=1' }}" target="_blank" class="btn btn-outline-warning btn-sm"><i class="bi bi-eye"></i>
                                                {{ __('Show') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

    </section>
</div>
@endsection
