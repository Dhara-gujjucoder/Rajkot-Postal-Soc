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

                        <div class="col-md-4">
                            <label for="department_id" class="col-form-label">{{ __('Department') }}</label>
                            <div class="col-md-12">
                                <select
                                    class="choices filter-input form-select @error('department_id') is-invalid @enderror"
                                    aria-label="Permissions" id="department_id" data-column="4" name="department_id"
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
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1">
                        <thead>
                            <tr>
                                <th scope="col">S#</th>
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
    <div class="modal fade text-left" id="loan_settle" tabindex="-1" aria-labelledby="myModalLabel1"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <form id="loan_close" method="post" action="{{ route('member.resign',['member' => 'id']) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel1">{{ __('Resignation') }}
                        </h5>
                        <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-x">
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
</section>
@endsection
@push('script')
<script>

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
                    // calculate();
                    take_fixed_saving();
                }
            }
        });
    }

    function take_fixed_saving() {
        // $('.payment_details').hide();
        $('#fixed_saving_details').hide();
        var payment_type = $('input[name="fixed_saving_check"]:checked').val();
        if (payment_type == 'fixed_saving_check') {
            $('#fixed_saving_details').show();
        }else{
            $('#fixed_saving').val(0);
        }
        calculate();
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
        var loan = Number($('#remaining_loan').val());
        var fixed = Number($('#fixed_saving').val()) ? Number($('#fixed_saving').val()) : 0;
        console.log(loan, fixed);
        total = loan - fixed;
        $('#total_amount').val(total.toFixed(0));
    }

    $(document).on('submit','#loan_close',function(e){
        e.preventDefault();
        var form_element = $(this);
        var action = $(form_element).attr('action');
        url = action.replace('id', $('#member_id').val());
        var form_data = new FormData($(form_element)[0]);
        $('.form-error',form_element).remove();
        $('#loader').show();
        $.ajax({
            type: "POST",
            url: url,
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            success: function (ajax_response) {
                $('#loader').hide();
                if(ajax_response.success == true)
                {
                    show_success(ajax_response.message);
                    $('#loan_settle').modal('hide');
                    table.ajax.reload();
                }
                else if(ajax_response.success == false)
                {
                    show_error(ajax_response.message);
                }
                else
                {
                    alert('Something went wrong!!');
                }
            },
            error:function(ajax_response){
                $('body').addClass('loaded');
                ajax_response = ajax_response.responseJSON;
                if(typeof(ajax_response.errors) != 'undefined')
                {
                    $.each(ajax_response.errors, function (index,value) {

                        if($('[name="'+index+'"]',form_element).hasClass('select2-element'))
                        {
                            $('[name="'+index+'"]',form_element).parent().append('<span class="invalid-feedback d-block form-error"><strong>'+value[0]+'</strong></div>');
                        }
                        else if($('[name="'+index+'"]',form_element).hasClass('form-check-input'))
                        {
                            $('[name="'+index+'"]:first',form_element).parent().parent().append('<span class="invalid-feedback d-block form-error"><strong>'+value[0]+'</strong></div>');
                        }
                        else
                        {
                            $('[name="'+index+'"]',form_element).after('<span class="invalid-feedback d-block form-error"><strong>'+value[0]+'</strong></div>');
                        }
                    });
                    setTimeout(() => {
                        $('html, body').animate({
                            scrollTop: $(".form-error",form_element).offset().top - 120
                        }, 500);
                    }, 500);
                }
            }
        });
    });
</script>
@endpush
