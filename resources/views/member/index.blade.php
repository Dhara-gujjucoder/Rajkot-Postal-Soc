@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<section class="page-content">
    <div class="card">
        <div class="card-body">

            <div class="header_add">

                <div class="form">
                    <div class="row mb-3" id="filter">

                        <div class="col-md-4">
                            <label for="account_name" class="col-form-label">{{ __('Member') }}</label>
                            <div class="col-md-12">
                                <select class="choices filter-input form-select @error('user_id') is-invalid @enderror"
                                    aria-label="Permissions" id="user_id" data-column="1" name="user_id"
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
                            <label for="department_id" class="col-form-label">{{ __('Department') }}</label>
                            <div class="col-md-12">
                                <select class="choices filter-input form-select @error('department_id') is-invalid @enderror"
                                    aria-label="Permissions" id="department_id" data-column="3" name="department_id"
                                    style="height: 210px;">
                                    <option value="">{{ __('Department') }}</option>
                                    @forelse ($departments as $key => $department)
                                        <option value="{{ $department->id }}"
                                            {{ $department->id == old('department_id') ? 'selected' : '' }}>
                                            {{ $department->department_name }}
                                        </option>
                                        @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                @can('create-member')
                    <a href="{{ route('members.create') }}" class="btn btn btn-outline-success btn-md mb-3"><i
                            class="bi bi-plus-circle"></i> {{ __('Add New Member') }}</a>
                @endcan

            </div>
            <div class="pt-2 mt-2">
                {{-- <div class="form">
                    <div class="row mb-3" id="filter">

                        <div class="col-md-4">
                            <label for="account_name" class="col-md-2 col-form-label">{{ __('Member') }}</label>
                            <div class="col-md-12">
                                <select class="choices filter-input form-select @error('user_id') is-invalid @enderror"
                                    aria-label="Permissions" id="user_id" data-column="1" name="user_id"
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
                            <label for="department_id" class="col-md-4 col-form-label">{{ __('Department') }}</label>
                            <div class="col-md-12">
                                <select class="choices filter-input form-select @error('department_id') is-invalid @enderror"
                                    aria-label="Permissions" id="department_id" data-column="3" name="department_id"
                                    style="height: 210px;">
                                    <option value="">{{ __('Department') }}</option>
                                    @forelse ($departments as $key => $department)
                                        <option value="{{ $department->id }}"
                                            {{ $department->id == old('department_id') ? 'selected' : '' }}>
                                            {{ $department->department_name }}
                                        </option>
                                        @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1">
                        <thead>
                            <tr>
                                <th scope="col">S#</th>
                                <th scope="col">{{ __('Name') }}</th>
                                <th scope="col">{{ __('Email') }}</th>
                                <th scope="col">{{ __('Department') }}</th>
                                {{-- <th scope="col">{{ __('Opening Balance') }}</th> --}}
                                <th scope="col">{{ __('Registration No.') }}</th>
                                <th scope="col">{{ __('Roles') }}</th>
                                <th scope="col">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
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
            ajax: "{{ route('members.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name',
                    searchable: true
                },
                {
                    data: 'email',
                    name: 'email',
                    searchable: true
                },
                {
                    data: 'department_id',
                    name: 'department_id',
                    searchable: true
                },
                // {
                //     data: 'share_total_price',
                //     name: 'share_total_price',
                //     searchable: true
                // },
                {
                    data: 'registration_no',
                    name: 'registration_no'
                },
                {
                    data: 'roles',
                    name: 'roles',
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],

        });

        // $('#table1_length').before('<div class="ms-5">ddsdf</div>');

        $('#department_id').on('change', function() {
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

