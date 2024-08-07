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
                @can('create-loan')
                    <a href="{{ route('loan.create') }}" class="btn btn-outline-success btn-md  float-end my-3"><i class="bi bi-plus-circle"></i> {{ __('Add New Loan') }}</a>
                @endcan
            </div>
            <div class="pt-2 mt-2">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table1">
                        <thead>
                            <tr>
                                <th>{{ __('No.') }}</th>
                                <th>{{ __('Loan A/c') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('EMI Amount') }}</th>
                                <th>{{ __('Status') }}</th>
                                {{-- <th>{{ __('Temp Loan Status') }}</th> only temporary add this column in db --}}
                                <th>{{ __('Action') }} </th>
                            </tr>
                        </thead>
                        <input type="hidden" id="loan_id">
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-outline-primary block" style="display:none" id="notmatch"></button>
    <div class="modal fade text-left" id="loan_settle" tabindex="-1" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <form id="loan_close">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel1">{{ __('Loan Settlement') }}
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
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <tbody id="loan_details_settle">
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">{{ __('Cancel') }}</span>
                        </button>
                        <button type="button" class="btn btn-primary ms-1" onclick="close_loan()">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">{{ __('Close Loan') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="loan_pay" tabindex="-1" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <form id="loan_close">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loan_payModalLabel1">{{ __('Loan Payment') }}
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
                    <div class="modal-body">
                        <table class="table table-bordered">

                            <tbody id="loan_details_pay">
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">
                            <i class="bx bx-x d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">{{ __('Cancel') }}</span>
                        </button>
                        <button type="button" class="btn btn-primary ms-1" onclick="pay_loan()">
                            <i class="bx bx-check d-block d-sm-none"></i>
                            <span class="d-none d-sm-block">{{ __('Submit') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="guarentor_add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('loan.guarantor_store', 1) }}" method="post" enctype="multipart/form-data" id="guarentor_store">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ __('Guarantee Details') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label for="amount" class="col-md-2 col-form-label text-md-end text-start">{{ __('Guarantor 1') }}</label>
                            <div class="col-md-10">
                                <div class="gexist">
                                    <select class="form-control @error('g1_member_id') is-invalid @enderror" aria-label="Permissions" name="g1_member_id" onchange="checkGuarantor($(this).val(),$('#g1_member_id'))">
                                        <option value="">{{ __('Select Member') }}</option>
                                        @foreach ($members as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('g1_member_id'))
                                        <span class="text-danger">{{ $errors->first('g1_member_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="amount" class="col-md-2 col-form-label text-md-end text-start">{{ __('Guarantor 2') }}</label>
                            <div class="col-md-10">
                                <div class="gexist">
                                    <select class="form-control @error('g2_member_id') is-invalid @enderror" aria-label="Permissions" name="g2_member_id" onchange="checkGuarantor($(this).val(),$('#g2_member_id'))">
                                        <option value="">{{ __('Select Member') }}</option>
                                        @foreach ($members as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('g2_member_id'))
                                        <span class="text-danger">{{ $errors->first('g2_member_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="amount" class="col-md-2 col-form-label text-md-end text-start">{{ __('Guarantee Payee Cheque No') }}</label>
                            <div class="col-md-10">
                                <input type="number" class="form-control @error('gcheque_no') is-invalid @enderror" id="gcheque_no" name="gcheque_no" value="{{ old('gcheque_no') }}" placeholder="{{ __('Guarantee Payee Cheque No') }}">
                                @if ($errors->has('gcheque_no'))
                                    <span class="text-danger">{{ $errors->first('gcheque_no') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="amount" class="col-md-2 col-form-label text-md-end text-start">{{ __('Guarantee Payee Bank Name') }}</label>
                            <div class="col-md-10">
                                <input type="text" class="form-control @error('gbank_name') is-invalid @enderror" id="gbank_name" name="gbank_name" value="{{ old('gbank_name') }}" placeholder="{{ __('Guarantee Payee Bank Name') }}">
                                @if ($errors->has('gbank_name'))
                                    <span class="text-danger">{{ $errors->first('gbank_name') }}</span>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <div class="mb-3 float-end">
                            <input type="submit" class="btn btn-primary" id="submitButton" value="{{ __('Submit') }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
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
                // {
                //     data: 'temp_loan_status',
                //     name: 'temp_loan_status'
                // },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],

        });

        $('#g2_member_id').select2();
        $('#g1_member_id').select2();



        function load_member_details(member_id, div) {

            $('#loader').show();
            $('#loan_details_pay').html('');
            $('#loan_details_settle').html('');
            var url = "{{ route('member.get', ':id') }}";
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
                        var member = data.member;
                        var member_loan = member.loan;
                        console.log(member_loan);
                        var html =
                            `<tr>
                                <td><b>{{ __('Loan A/c') }}</b></td>
                                <td>` + member_loan.loan_no + `</td>
                                <td><b>{{ __('Amount') }}</b></td>
                                <td>&#8377; ` + member_loan.principal_amt + `</td>
                                <td><b>{{ __('EMI Amount') }}</b></td>
                                <td>&#8377; ` + member_loan.emi_amount + `</td>
                            </tr>

                            <tr>
                                <td><b>{{ __('Paid Loan') }}</b></td>
                                <td>&#8377; ` + (member_loan.principal_amt - member.loan_remaining_amount) + `</td>

                                <td><b>{{ __('Paid EMI') }}</b></td>
                                <td>` + ((member_loan.loan_emis.length)) + `</td>`;
                                    if (div == '_settle') {
                                        html += `<td><b>{{ __('Date') }}</b></td>
                                                <td><input type="text" class="form-control date_picker"
                                                    id="close_date" name="loan_settlment_month"  >
                                                </td>`;
                                    }

                        html += `</tr>

                            <tr>
                                <td colspan="6">&nbsp;</td>
                            </tr>

                            <tr>
                                <td colspan="2"> <b>{{ __('Remaining Loan') }}</b></td>
                                <td colspan="4"><input type="number" class="form-control amount" name="amount` + div + `" placeholder="{{ __('Remaining Loan') }}" id="remaining_loan` + div + `" min="` +
                            member.loan_remaining_amount + `" value="` +
                            member.loan_remaining_amount + `"></td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>{{ __('Payment Type') }}</b></td>
                                <td colspan="4"><input class="form-check-input payment_type" type="radio" name="payment_type"
                                    id="cash" checked="" value="cash"
                                    {{ old('payment_type') == 'cash' ? 'checked' : '' }}
                                    onchange="change_payment_type()">
                                <label class="form-check-label" for="cash">
                                    {{ __('Cash') }}
                                </label>
                                <input class="form-check-input payment_type" type="radio" name="payment_type"
                                    id="cheque" value="cheque" onchange="change_payment_type()"
                                    {{ old('payment_type') == 'cheque' ? 'checked' : '' }}>
                                <label class="form-check-label" for="cheque">
                                    {{ __('Cheque') }}
                                </label></td></tr>
                                <tr>
                                <td colspan="6"><div id="payment_details"
                                style="display: {{ old('payment_type') == 'cheque' ? 'block' : 'none' }};">

                                <div class="mb-3 row">
                                    <label for="amount"
                                        class="col-md-2 col-form-label text-md-end text-start">{{ __('Cheque No.(RDC Bank)') }}</label>
                                    <div class="col-md-10">
                                        <input type="number"
                                            class="form-control cheque_no"
                                            id="cheque_no" name="cheque_no" value="{{ old('cheque_no') }}"
                                            placeholder="{{ __('Cheque No.') }}">
                                    </div>
                                </div>
                            </div></td>
                            </tr>

                             <tr>
                                <td colspan="2"> <b>{{ __('Particular') }}</b></td>
                                <td colspan="4">
                                    <textarea class="form-control amount" name="payment_comment" id="payment_comment" placeholder="{{ __('Particular') }}" ></textarea>
                                </td>
                            </tr>

                            `;
                        $('#loan_details' + div).html(html);
                        $('#loan_id').val(member_loan.id);
                        // console.log(remaining_share);


                        $('.date_picker').flatpickr({
                            allowInput: true,
                            altInput: true,
                            altFormat: "d/m/Y",
                            dateFormat: "Y-m-d",
                            defaultDate: "{{ date('Y-m-d') }}"
                        });


                        $('#loader').hide();
                        $('#remaining_loan').focus();
                        // <div class="mb-3 row">
                        //     <label for="amount"
                        //         class="col-md-2 col-form-label text-md-end text-start">{{ __('Bank Name') }}</label>
                        //     <div class="col-md-10">
                        //         <input type="text"
                        //             class="form-control bank_name"
                        //             id="bank_name" name="bank_name" value="{{ old('bank_name') }}"
                        //             placeholder="{{ __('Bank Name') }}">

                        //     </div>
                        // </div>
                    }
                }
            });
        }

        function close_loan() {
            var loan_id = $('#loan_id').val();
            var amount = $('#remaining_loan_settle').val();
            var bank_name = $('#bank_name').val();
            var cheque_no = $('#cheque_no').val();
            var payment_comment = $('#payment_comment').val();
            var close_date = $('#close_date').val();
            var payment_type = $('input[name=payment_type]:checked').val()
            $(document).find('.is-invalid').removeClass('is-invalid');
            $(document).find('.text-danger').remove();


            var url = "{{ route('loan.destroy', ':id') }}";
            url = url.replace(':id', loan_id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: url,
                type: 'DELETE',
                dataType: 'json',
                data: {
                    "amount": amount,
                    "bank_name": bank_name,
                    "cheque_no": cheque_no,
                    "payment_type": payment_type,
                    "payment_comment": payment_comment,
                    "loan_settlment_month": close_date,

                },
                success: function(data) {
                    show_success(data.msg);
                    table.ajax.reload();
                    $("#loan_settle").modal('hide');

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    var err = eval("(" + xhr.responseText + ")");
                    $.each(xhr.responseJSON.errors, function(field_name, error) {
                        if (field_name == 'amount') {
                            field_name = 'amount_settle';
                        }
                        $(document).find('[name=' + field_name + ']').addClass("is-invalid");
                        $(document).find('[name=' + field_name + ']').after(
                            '<span class="text-danger">' + error + '</span>');
                    });
                }
            });
        }

        function pay_loan() {
            if (confirm(`{{ __('Are you sure to pay?') }}`)) {

                var loan_id = $('#loan_id').val();
                var amount = $('#remaining_loan_pay').val();
                var bank_name = $('#bank_name').val();
                var cheque_no = $('#cheque_no').val();
                var payment_comment = $('#payment_comment').val();

                var payment_type = $('input[name=payment_type]:checked').val()
                $(document).find('.is-invalid').removeClass('is-invalid');
                $(document).find('.text-danger').remove();

                var url = "{{ route('loan.partial_pay', ':id') }}";
                url = url.replace(':id', loan_id);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        "amount": amount,
                        "bank_name": bank_name,
                        "cheque_no": cheque_no,
                        "payment_type": payment_type,
                        "payment_comment": payment_comment,

                    },
                    success: function(data) {
                        show_success(data.msg);
                        table.ajax.reload();
                        $("#loan_pay").modal('hide');

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        var err = eval("(" + xhr.responseText + ")");

                        $.each(xhr.responseJSON.errors, function(field_name, error) {
                            if (field_name == 'amount') {
                                field_name = 'amount_pay';
                            }
                            $(document).find('[name=' + field_name + ']').addClass("is-invalid");
                            $(document).find('[name=' + field_name + ']').after(
                                '<span class="text-danger">' + error + '</span>');
                        });
                    }
                });
            }
        }

        function change_payment_type() {
            $('#payment_details').hide();
            var payment_type = $('input[name="payment_type"]:checked').val();
            if (payment_type == 'cheque') {
                $('#payment_details').show();
            }
        }

        function set_member_id(member_id) {
            var url = "{{ route('loan.guarantor_store', ':id') }}";
            url = url.replace(':id', member_id);
            $('#guarentor_store').attr('action', url);
        }

        $('#guarentor_store').on('submit', function(e) {
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
                    $('#guarentor_store').trigger('reset');
                    $('#guarentor_add').modal('hide');
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
                            $('select[name=' + key + ']').closest('.form-control').addClass(
                                'is-invalid');
                            // console.log($('input[name=' + key + ']').find('.form-control').length);
                            $('input[name=' + key + ']').closest('.form-control').after(
                                '<div class="invalid-feedback" role="alert"><strong>' + value +
                                '</strong></div>');
                            $('select[name=' + key + ']').closest('.form-control').after(
                                '<div class="invalid-feedback" role="alert"><strong>' + value +
                                '</strong></div>');
                        });
                    }
                },
                contentType: false,
                processData: false
            });

        })
    </script>
@endpush
