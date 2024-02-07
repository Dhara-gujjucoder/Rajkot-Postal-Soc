@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body pt-4">
            <div class="header_add">

                <div class="form"></div>
            @can('create-member')
                <a href="{{ route('salary_deduction.create') }}" class="btn btn btn-outline-success btn-md  float-end my-3"><i
                        class="bi bi-plus-circle"></i> {{ __('Add New') }}</a>
            @endcan
            </div>
            <div class="pt-2 mt-2">
                <div class="form">
                    <div class="row mb-3" id="filter">
                        <div class="col-md-4">
                            <label for="account_name" class="col-md-2 col-form-label">{{ __('Member') }}</label>
                            <div class="col-md-12">
                                <select class="choices filter-input form-select @error('user_id') is-invalid @enderror"
                                    aria-label="Permissions" id="user_id" data-column="2" name="user_id"
                                    style="height: 210px;">
                                    <option value="">{{ __('Member') }}</option>
                                    @forelse ($members as $key => $member)
                                    <option value="{{ $member->user_id }}"
                                        {{ $member->user_id == old('user_id') ? 'selected' : '' }}>
                                        {{ $member->fullname }}
                                    </option>
                                @empty
                                @endforelse
                                </select>
                                @if ($errors->has('user_id'))
                                    <span class="text-danger">{{ $errors->first('user_id') }}</span>
                                @endif
                            </div>
                        </div>
                        {{-- <div class="col-md-4">
                            <label for="account_name" class="col-md-4 col-form-label">{{ __('Account Type') }}</label>
                            <div class="col-md-12">
                                <select class="choices filter-input form-select @error('account_type_id') is-invalid @enderror"
                                    aria-label="Permissions" id="account_type_id" data-column="3" name="account_type_id"
                                    style="height: 210px;">
                                    <option value="">{{ __('Account Type') }}</option>
                                    @forelse ($departments as $key => $department)
                                        <option value="{{ $department->id }}"
                                            {{ $department->id == old('account_type_id') ? 'selected' : '' }}>
                                            {{ $department->type_name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                @if ($errors->has('account_type_id'))
                                    <span class="text-danger">{{ $errors->first('account_type_id') }}</span>
                                @endif
                            </div>
                        </div> --}}

                        <div class="col-md-4">
                            <label for="ledger_ac_id" class="col-md-4 col-form-label text-md-end">{{ __('Ledger') }}</label>
                            <div class="col-md-12">
                                <select class="choices form-select @error('ledger_ac_id') is-invalid @enderror" aria-label="Permissions" id="ledger_ac_id" name="ledger_ac_id" style="height: 210px;" ">
                                    <option value="">{{ __('Select Ledger') }}</option>
                                    @foreach ($ledgers as $ledger)
                                        <option value="{{ $ledger->id }}" {{ $ledger->id == old('ledger_ac_id') ? 'selected' : '' }}>{{ $ledger->account_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('ledger_ac_id'))
                                    <span class="text-danger">{{ $errors->first('ledger_ac_id') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered mt-5" id="table1">
                    <thead>
                        <tr>
                            <th scope="col">S#</th>
                            <th scope="col">{{ __('Month Year') }}</th>
                            <th scope="col">{{ __('Name') }} </th>
                            <th scope="col">{{ __('Rec No.') }} </th>
                            <th scope="col">{{ __('Principal') }} (Rs.)</th>
                            <th scope="col">{{ __('Interest') }} (Rs.)</th>
                            <th scope="col">{{ __('Fixed Monthly Saving') }} (Rs.)</th>
                            <th scope="col">{{ __('Amount') }} (Rs.)</th>
                            <th scope="col">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>



                    </tbody>
                </table>
            </div>


        </div>
    </div>
</section>
@endsection
@push('script')
<script>
    $(function() {

        var table = $('#table1').DataTable({

            processing: true,
            serverSide: true,
            ajax: "{{ route('salary_deduction.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'month',
                    name: 'month',
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name',
                    searchable: true
                },
                {
                    data: 'rec_no',
                    name: 'rec_no',
                    searchable: false
                },
                {
                    data: 'principal',
                    name: 'principal'
                },
                {
                    data: 'interest',
                    name: 'interest'
                },
                {
                    data: 'fixed',
                    name: 'fixed'
                },
                {
                    data: 'total_amount',
                    name: 'total_amount'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],

        });
        $('#account_type_id').on('change', function() {
            table
                .columns($(this).data('column'))
                .search($(this).val())
                .draw();
        });
        $('#user_id').on('change', function() {
            table
                .columns($(this).data('column'))
                .search($(this).val())
                .draw();
        });

    });
</script>
@endpush
