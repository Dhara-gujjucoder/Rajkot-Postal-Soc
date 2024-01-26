@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">
            @can('create-role')
                <a href="{{ route('ledger_account.create') }}" class="btn btn-outline-success btn-md  float-end my-3"><i
                        class="bi bi-plus-circle"></i> {{ __('Add New Ledger Account') }}</a>
            @endcan
            <div class="pt-4 mt-5">
                <div class="form">
                    <div class="row mb-3" id="filter">

                        <div class="col-md-4">
                            <label for="account_name" class="col-md-2 col-form-label">{{ __('Member') }}</label>
                            <div class="col-md-12">
                                <select class="choices filter-input form-select @error('user_id') is-invalid @enderror"
                                    aria-label="Permissions" id="user_id" data-column="3" name="user_id"
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

                        <div class="col-md-4">
                            <label for="ledger_ac_id"
                                class="col-md-4 col-form-label text-md-end">{{ __('Ledger Group') }}</label>
                            <div class="col-md-12">
                                <select class="choices filter-input form-select @error('ledger_ac_id') is-invalid @enderror"
                                    aria-label="Permissions" id="ledger_ac_id" name="ledger_ac_id" data-column="2"
                                    style="height: 210px;">
                                    <option value="">{{ __('Select Ledger Group') }}</option>
                                    {!! getLedgerGroupDropDown() !!}
                                     {{-- @foreach ($ledger_group as $ledger)
                                    <option value="{{ $ledger->id }}" @if($ledger->parent_id > 0) data-pup="{{ $ledger->parent_id }}"  class="l1 non-leaf" @endif
                                        {{ $ledger->id == old('ledger_ac_id') ? 'selected' : '' }}>
                                        {{ $ledger->ledger_group }}</option>
                                    @endforeach --}}
                                </select>
                                @if ($errors->has('ledger_ac_id'))
                                    <span class="text-danger">{{ $errors->first('ledger_ac_id') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="type"
                                class="col-md-4 col-form-label text-md-end">{{ __('Type') }}</label>
                            <div class="col-md-12">
                                <select class="choices filter-input form-select @error('type') is-invalid @enderror"
                                    aria-label="Permissions" id="type" name="type"  data-column="5"
                                    style="height: 210px;">
                                    <option value="">{{ __('Select Type') }}</option>
                                    <option value="CR"
                                        {{ 'CR' == old('type') ? 'selected' : '' }}>
                                        {{ __('Credit') }}</option>
                                        <option value="DR"
                                            {{ 'DR' == old('type') ? 'selected' : '' }}>
                                            {{ __('Debit') }}</option>
                                </select>
                                @if ($errors->has('type'))
                                    <span class="text-danger">{{ $errors->first('type') }}</span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                <div class="pt-4 mt-5">
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
            ajax: "{{ route('ledger_account.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'account_name',
                    name: 'account_name',
                    searchable: true
                },
                {
                    data: 'ledger_group_id',
                    name: 'ledger_group_id',
                    searchable: true
                },
                {
                    data: 'user_id',
                    name: 'user_id',
                    searchable: true
                },
                {
                    data: 'opening_balance',
                    name: 'opening_balance'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],

        });
        $('#ledger_ac_id').on('change', function() {
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
        $('#type').on('change', function() {
            table
                .columns($(this).data('column'))
                .search($(this).val())
                .draw();
        });
    });
</script>
@endpush
