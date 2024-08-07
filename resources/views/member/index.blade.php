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
                                <select class="choices filter-input form-select @error('uid') is-invalid @enderror" aria-label="Permissions" id="uid" data-column="0" name="uid" style="height: 210px;">
                                    <option value="">{{ __('Member') }}</option>
                                    @forelse ($members as $key => $member)
                                        <option value="{{ $member->uid }}" {{ $member->uid == old('uid') ? 'selected' : '' }}>
                                            {{ $member->fullname }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                @if ($errors->has('uid'))
                                    <span class="text-danger">{{ $errors->first('uid') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="department_id" class="col-form-label">{{ __('Department') }}</label>
                            <div class="col-md-12">
                                <select class="choices filter-input form-select @error('department_id') is-invalid @enderror" aria-label="Permissions" id="department_id" data-column="3" name="department_id" style="height: 210px;">
                                    <option value="">{{ __('Department') }}</option>
                                    @forelse ($departments as $key => $department)
                                        <option value="{{ $department->id }}" {{ $department->id == old('department_id') ? 'selected' : '' }}>
                                            {{ $department->department_name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="department_id" class="col-form-label">{{ __('') }}</label>
                            <div class="col-md-12">
                                {{-- <form method="post" action="{{ route('all_member_export') }}" id="export_member">
                                    @csrf
                                    <button type="submit" id="submitBtn1" class="btn btn-outline-success btn-md float-start my-3">{{ __('All Member Export') }}</button>
                                    <div id="loading1" style="display: none;" class="btn btn-outline-light btn-md float-start my-3 disabled">{{ __('Loading...') }}</div>
                                </form> --}}

                                    {{-- id="submitBtn1" --}}
                                <button type="button"  class="btn btn-outline-success btn-md float-start my-3" data-bs-toggle="modal" data-bs-target="#member_export_popup">{{ __('All Member Export') }}</button>
                                {{-- <div id="loading1" style="display: none;" class="btn btn-outline-light btn-md float-start my-3 disabled">{{ __('Loading...') }}</div> --}}
                            </div>
                        </div>

                    </div>
                </div>

                @can('create-member')
                    <a href="{{ route('members.create') }}" class="btn btn btn-outline-success btn-md mb-3"><i class="bi bi-plus-circle"></i> {{ __('Add New Member') }}</a>
                @endcan

            </div>
            <div class="pt-2 mt-2">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1">
                        <thead>
                            <tr>
                                {{-- <th scope="col">S#</th> --}}
                                <th scope="col">{{ __('M.no') }}</th>
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

    <div class="modal fade text-left" id="loan_settle" tabindex="-1" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <form id="loan_close" method="post" action="{{ route('member.resign', ['member' => 'id']) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel1">{{ __('Resignation') }}
                        </h5>
                        <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                                <line x1="18" y1="6" x2="6" y2="18">
                                </line>
                                <line x1="6" y1="6" x2="18" y2="18">
                                </line>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-body" id="moddl">
                        <table class="table table-bordered">
                            <tbody id="loan_details">

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">{{ __('Cancel') }}</span>
                        </button>
                        <button type="submit" class="btn btn-primary ms-1">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">{{ __('Submit') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Change Password pop-up --}}
    <div class="modal fade" id="changePassword" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('users.profile', 1) }}" method="post" enctype="multipart/form-data" id="change_pwd_form">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Change Password') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{-- <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Current Password') }}</label>
                            <div class="col-md-8">
                                <input id="password" type="password" placeholder="{{ __('Password') }}" class="form-control @error('password') is-invalid @enderror" name="current_password" autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('New Password') }}</label>
                            <div class="col-md-8">
                                <input id="password" type="password" placeholder="{{ __('Password') }}" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-8">
                                <input id="password-confirm" type="password" placeholder="{{ __('Confirm Password') }}" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <input type="checkbox" id="send_email" name="send_email" value="1">
                            <label for="send_email">Send Email to Member</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>

                        <button type="submit" id="submitBtn" class="btn btn-primary">{{ __('Save Password') }}</button>
                        <div id="loading" style="display: none;" class="btn btn-outline-light btn-md float-start my-3">
                            {{ __('Loading...') }}</div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End --}}

    {{-- Member Export Modal popup --}}
    <div class="modal fade" id="member_export_popup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">  {{-- modal-xl --}}
            <div class="modal-content">
                @include('member.export_member_detail')
            </div>
        </div>
    </div>
    {{-- End --}}


</section>
@endsection
@push('script')

<script>
    $(document).ready(function() {
        // Check/uncheck all checkboxes when master checkbox is clicked
        $('#select-all').click(function() {
            $('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
        });
    });

    $(document).ready(function() {
        $('#export_member').submit(function(event) {
            event.preventDefault();

            if ($("input[name='columns[]']:checked").length == 0) {
                $('#error-message').text('Please select at least one checkbox.');
            } else {
                this.submit();
                $('#error-message').text('');
            }
        });
    });
</script>

<script>
    var table = $('#table1').DataTable({
        "pageLength": 15,

        processing: true,
        serverSide: true,
        ajax: "{{ route('members.index') }}",
        columns: [
            // {
            //     data: 'DT_RowIndex',
            //     name: 'DT_RowIndex',
            //     orderable: false,
            //     searchable: false
            // },
            {
                data: 'uid',
                name: 'uid',
                searchable: true
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
    $(".dataTables_filter").hide();
    $(".dataTables_length").hide();

    $('#department_id').on('change', function() {
        table
            .columns($(this).data('column'))
            .search($(this).val())
            .draw();
    });
    $('#uid').on('change', function() {
        table
            .columns($(this).data('column'))
            .search($(this).val())
            .draw();
    });


    function load_member_details(member_id) {
        $('#loader').show();
        var url = "{{ route('member.history.get', ':id') }}";
        url = url.replace(':id', member_id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            url: url,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                if (data.success == true) {
                    $('#moddl').html(data.history);
                    $('.modal-content').css('overflow-y', 'scroll');

                    $('#loader').hide();
                    $('#member_id').val(member_id);
                    calculate();
                    // take_fixed_saving();
                }
            }
        });
    }

    function set_member_id(member_id) {
        var url = "{{ route('users.passwword', ':id') }}";
        url = url.replace(':id', member_id);
        $('#change_pwd_form').attr('action', url);
    }


    $('#export_member').submit(function() {

        $('#submitBtn1').hide();
        $('#loading1').show();
        var formData = $('#export_member').serialize();

        $.ajax({
            url: $('#export_member').attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#loading1').hide();
                $('#submitBtn1').show();
            },
            error: function(xhr) {
                $('#loading1').hide();
                $('#submitBtn1').show();
            }
        });

    });


    $('#change_pwd_form').on('submit', function(e) {
        $('#loading').show(); // Display the loading symbol
        $('#submitBtn').hide();

        e.preventDefault(); // prevent the form submit
        var url = $(this).attr('action');
        $('.invalid-feedback').remove();
        $('.form-control').removeClass('is-invalid');
        // alert(url);
        // create the FormData object from the form context (this),
        // that will be present, since it is a form event
        var formData = new FormData(this);
        // build the ajax call
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                // handle success response
                show_success(response.message);
                $('#changePassword').modal('hide');

                $('#loading').hide();
                $('#submitBtn').show();
                $('#change_pwd_form').trigger('reset');
            },
            error: function(xhr, status, error) {
                // handle error response
                if (xhr.status == 422) {
                    var errors = xhr.responseJSON;
                    // $('input[name=' + column_name + ']').val(old_value);
                    $.each(errors, function(key, value) {
                        console.log(key, value);
                        $('input[name=' + key + ']').closest('.form-control').addClass(
                            'is-invalid');
                        // console.log($('input[name=' + key + ']').find('.form-control').length);
                        $('input[name=' + key + ']').closest('.form-control').after(
                            '<div class="invalid-feedback" role="alert"><strong>' + value +
                            '</strong></div>');
                    });

                }

                $('#loading').hide();
                $('#submitBtn').show();
            },
            contentType: false,
            processData: false
        });

    })

    function change_extra_amt_type() {
        $('#extra_amt_principle').hide();
        $('#extra_amt_interest').hide();
        $('#extra_amt_other').hide();
        var payment_type = $('input[name="extra_amt"]:checked').val();
        if (payment_type == '1') {
            $('#extra_amt_principle').show();
            $('#extra_amt_interest').show();
            $('#extra_amt_other').show();
        }
    }


    function change_payment_type() {
        $('#payment_details').hide();
        var payment_type = $('input[name="payment_type"]:checked').val();
        if (payment_type == 'cheque') {
            $('#payment_details').show();
        }
    }

    function calculate() {
        var total = 0;
        var payment_fixed = $('input[name="fixed_saving_check"]:checked').val();
        var payment_share = $('input[name="share_amount_check"]:checked').val();

        var loan = Number($('#remaining_loan').val());
        var share = Number($('#share_amt').val()) ? Number($('#share_amt').val()) : 0;
        var fixed = Number($('#fixed_saving_amt').val()) ? Number($('#fixed_saving_amt').val()) : 0;

        console.log(loan, fixed, share, payment_fixed, payment_share);
        var subtotal = 0;
        if (payment_fixed == 'fixed_saving_check') {
            console.log(total);

            subtotal = subtotal + fixed;
        }
        if (payment_share == 'share_check') {
            console.log(total);

            subtotal = subtotal + share;
        }
        total = subtotal - loan;
        console.log(subtotal, total);

        // total = loan - fixed;<0 {}
        $('#total_amount').val(total.toFixed(0));
    }

    $(document).on('submit', '#loan_close', function(e) {
        e.preventDefault();
        var form_element = $(this);
        var action = $(form_element).attr('action');
        url = action.replace('id', $('#member_id').val());
        var form_data = new FormData($(form_element)[0]);
        $('.form-error', form_element).remove();
        $('#loader').show();
        $.ajax({
            type: "POST",
            url: url,
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            success: function(ajax_response) {
                $('#loader').hide();
                if (ajax_response.success == true) {
                    show_success(ajax_response.message);
                    $('#loan_settle').modal('hide');
                    table.ajax.reload();
                } else if (ajax_response.success == false) {
                    show_error(ajax_response.message);
                } else {
                    alert('Something went wrong!!');
                }
            },
            error: function(ajax_response) {
                $('body').addClass('loaded');
                $('#loader').hide();
                ajax_response = ajax_response.responseJSON;
                if (typeof(ajax_response.errors) != 'undefined') {
                    $.each(ajax_response.errors, function(index, value) {

                        if ($('[name="' + index + '"]', form_element).hasClass('select2-element')) {
                            $('[name="' + index + '"]', form_element).parent().append('<span class="invalid-feedback d-block form-error"><strong>' + value[0] + '</strong></div>');
                        } else if ($('[name="' + index + '"]', form_element).hasClass('form-check-input')) {
                            $('[name="' + index + '"]:first', form_element).parent().parent().append('<span class="invalid-feedback d-block form-error"><strong>' + value[0] + '</strong></div>');
                        } else {
                            $('[name="' + index + '"]', form_element).after('<span class="invalid-feedback d-block form-error"><strong>' + value[0] + '</strong></div>');
                        }
                    });
                    // setTimeout(() => {
                    //     $('html, body').animate({
                    //         scrollTop: $(".form-error", form_element).offset().top - 120
                    //     }, 500);
                    // }, 500);
                }
            }
        });
    });
</script>
@endpush
