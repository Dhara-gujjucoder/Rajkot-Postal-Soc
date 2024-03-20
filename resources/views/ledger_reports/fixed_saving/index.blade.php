@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">

            <div class="header_add">
                <form method="post" action="{{ route('all_fixed_saving_export') }}" id="export_saving" enctype="multipart/form-data">
                    @csrf
                    <div class="form">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="member_id_from" class="col-form-label">{{ __('Member From') }}</label>
                                <div class="col-md-12">
                                    <select class="choices filter-input form-select" aria-label="Permissions"
                                        id="member_id_from" data-column="3" name="member_id_from" style="height: 210px;">
                                        <option value="">{{ __('Member From') }}</option>
                                        @forelse ($members as $key => $member)
                                            <option value="{{ $member->uid }}"
                                                {{ $member->id == old('member_id_from') ? 'selected' : '' }}>
                                                {{ $member->name }} ({{ $member->uid }})
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="member_id_from" class="col-form-label">{{ __('Member To') }}</label>
                                <div class="col-md-12">
                                    <select class="choices filter-input form-select" aria-label="Permissions"
                                        id="member_id_to" data-column="3" name="member_id_to" style="height: 210px;">
                                        <option value="">{{ __('Member To') }}</option>
                                        @forelse ($members as $key => $member)
                                            <option value="{{ $member->uid }}"
                                                {{ $member->id == old('member_id_to') ? 'selected' : '' }}>
                                                {{ $member->name }} ({{ $member->uid }})
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- {{ dd(getMonthsOfYear($current_year->id)) }} --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="month_from" class="col-form-label">{{ __('Month From') }}</label>
                                <div class="col-md-12">
                                    <select class="choices filter-input form-select" aria-label="Permissions"
                                        id="month_from" data-column="3" name="month_from" style="height: 210px;">
                                        <option value="">{{ __('Month From') }}</option>
                                            @php $months = getMonthsOfYear($current_year->id); @endphp
                                            @foreach ($months as $key => $month)
                                                <option value="{{ $key }}">{{ $month['month'] }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="month_to" class="col-form-label">{{ __('Month To') }}</label>
                                <div class="col-md-12">
                                    <select class="choices filter-input form-select" aria-label="Permissions"
                                        id="month_to" data-column="3" name="month_to" style="height: 210px;">
                                        <option value="">{{ __('Month To') }}</option>
                                            @foreach ($months as $key => $month)
                                                <option value="{{ $key }}">{{ $month['month'] }}</option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="account_name" class="col-form-label"></label>
                                <div class="col-md-12">
                                    <button type="submit"
                                        class="btn btn-outline-success btn-md float-start my-3">{{ __('Export') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                {{-- @can('create-loan_matrix') --}}
                <form method="post" action="{{ route('all_fixed_saving_export') }}" id="export_saving">
                    @csrf
                    <button type="submit"
                        class="btn btn-outline-success btn-md float-start my-3">{{ __('All Export') }}</button>
                </form>
                {{-- @endcan --}}

            </div>

            <div class="pt-2 mt-2">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1">
                        <thead>
                            <tr>
                                {{-- <th scope="col">{{ __('No.') }}</th> --}}
                                <th scope="col">{{ __('M.no') }}</th>
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
                                    {{-- <td>{{ $key + 1 }}</td> --}}
                                    <td>{{ $fixed->member->uid }}</td>
                                    <td>{{ $fixed->member->fullname }}</td>
                                    <td>{{ $fixed->LedgerGroupId->ledger_group }}</td>
                                    <td>{{ $fixed->member->name }}</td>
                                    <td>{{ $fixed->opening_balance }}</td>
                                    <td>{{ $fixed->type }}</td>
                                    <td><a href="{{ route('ledger_fixed_saving_export', $fixed->id) }}"
                                            class="btn btn-outline-warning btn-sm">{{ __('Export') }}</a></td>
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
                "pageLength": 25,
            });

        });
    </script>
@endpush
