@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form action="{{ route('double_entries.store') }}" method="post" id="myForm" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header border mb-4">
                        <div class="float-start">
                            <b>{{ __('Double Entry') }}</b>
                        </div>
                        <div class="float-end">
                            <a href="{{ route('double_entries.index') }}" class="btn btn-primary btn-sm">&larr;
                                {{ __('Back') }}</a>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-3 col-3">
                                <div class="form-group">
                                    <label for="first-amount-column">{{ __('Entry ID') }}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('entry_id') is-invalid @enderror" id="entry_id" name="entry_id" value="{{ $no }}" readonly placeholder="{{ __('ID') }}">
                                    @if ($errors->has('entry_id'))
                                        <span class="text-danger">{{ $errors->first('entry_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-3">
                                <div class="form-group">
                                    <label for="first-amount-column">{{ __('Date') }}<span class="text-danger">*</span></label>
                                    <input type="date" class="form-control date required @error('date') is-invalid @enderror" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" placeholder="{{ __('Date') }}" onchange="changeDate(this)">
                                    @if ($errors->has('cr_date'))
                                        <span class="text-danger">{{ $errors->first('date') }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- <div class="col-md-3 col-3">
                                <div class="form-group">
                                    <label for="cr_ledger_acc_id">{{ __('Ledger Account') }}<span
                                            class="text-danger">*</span></label>
                                    <select class="select2 @error('cr_ledger_acc_id') is-invalid @enderror"
                                        aria-label="Permissions" id="cr_ledger_acc_id" name="cr_ledger_acc_id"
                                        data-column="2" style="height: 210px;">
                                        <option value="">{{ __('Select Ledger Account') }}</option>
                                        @forelse ($ledger_accounts as $ac)
                                            <option value="{{ $ac->id }}"
                                                {{ $ac->id == old('ledger_ac_id') ? 'selected' : '' }}>
                                                {{ $ac->account_name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('cr_ledger_acc_id'))
                                        <span class="text-danger">{{ $errors->first('ledger_ac_id') }}</span>
                                    @endif
                                </div>
                            </div> --}}

                            <div class="col-md-6 col-6">
                                <div class="form-group">
                                    <label for="first-amount-column">{{ __('Description') }}<span class="text-danger">*</span></label>
                                    <textarea class="form-control required @error('description') is-invalid @enderror" placeholder="{{ __('Description') }}" name="description"></textarea>
                                    @if ($errors->has('description'))
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-3" id="cheque_div" style="display:none">
                                <div class="form-group">
                                    <label for="first-amount-column">{{ __('Cheque No.') }}<span class="text-danger">*</span></label>
                                    <input type="number" step="any" class="form-control @error('cheque_no') is-invalid @enderror" id="cheque_no" name="cheque_no" value="{{ old('cheque_no') }}" placeholder="{{ __('Amount') }}">
                                    @if ($errors->has('cheque_no'))
                                        <span class="text-danger">{{ $errors->first('cheque_no') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header border mb-4">
                        <div class="float-start">
                            {{-- <b>{{ __('Debit Entry') }}</b> --}}
                        </div>
                        <div class="float-end">
                            <button type="button" class="btn btn-outline-warning" onclick="add_ledger_entry()">{{ __('Add') }}</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row ledger_entry">
                            <div class="col-md-4 col-3">
                                <div class="form-group">
                                    <label for="ledger_ac_id">{{ __('Ledger Account') }}<span class="text-danger">*</span></label>
                                    <select id="ledgerAccountId" class="select2 ledgerAccountId required @error('ledger_ac_id') is-invalid @enderror" row="0" aria-label="Permissions" name="ledger_ac_id[]" data-column="2" style="height: 210px;">
                                        <option value="">{{ __('Select Ledger Account') }}</option>
                                        @forelse ($ledger_accounts as $ac)
                                            <option value="{{ $ac->id }}" ledger_grp_id="{{ $ac->ledger_group_id }}" member_id="{{ $ac->member_id }}" {{ $ac->id == old('ledger_ac_id.0') ? 'selected' : '' }}>
                                                {{ $ac->account_name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('ledger_ac_id.*'))
                                        <span class="text-danger">{{ $errors->first('ledger_ac_id.*') }}</span>
                                    @endif
                                </div>

                                <div class="share_input0" style="display:none;"><input type="number" name="share[]" class="required_share0" placeholder="{{ __('Add Share') }}"></div>

                                <div class="col-md-12 col-3 text-danger" style="display:none;" id="loan_details0">
                                    {{-- <input type="text" name="loan_emi[]" class="required_loan_emi0" placeholder="{{ __('Loan Details') }}" readonly> --}}
                                    <div  >
                                        {{-- @if ($ac->id == 3)
                                            Member  : {{ dd$ac->member->name }}<br>
                                        @endif --}}
                                        {{-- member name :
                                        Loan ID : 27/2022-23 <br>
                                        Principal Amount : 191141 <br>
                                        EMI Amount : 6000 <br>
                                        Pending EMI : 22<br>
                                        Paid EMI : 15<br>
                                        Note : Here you are only able to paid EMI of Loan no. #(1234) for month june (prin: 100 , Int : 10)<br> --}}
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="col-md-3 col-3">
                                    <div class="form-group">
                                        <label for="ledger_ac_id">{{ __('Ledger Group') }}<span
                                                class="text-danger">*</span></label>
                                        <select class="select2 @error('ledger_ac_id') is-invalid @enderror"
                                            aria-label="Permissions" id="ledger_ac_id" name="ledger_ac_id" data-column="2"
                                            style="height: 210px;">
                                            <option value="">{{ __('Select Ledger Group') }}</option>
                                            {!! getLedgerGroupDropDown() !!}
                                        </select>
                                        @if ($errors->has('ledger_ac_id'))
                                            <span class="text-danger">{{ $errors->first('ledger_ac_id') }}</span>
                                        @endif
                                    </div>
                            </div> --}}

                            <div class="col-md-4 col-3">
                                <div class="form-group">
                                    <label for="first-particular-column">{{ __('Particular') }}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control required @error('particular') is-invalid @enderror" id="particular" name="particular[]" value="{{ old('particular.*') }}" placeholder="{{ __('Particular') }}">
                                    @if ($errors->has('particular.*'))
                                        <span class="text-danger">{{ $errors->first('particular.*') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2 col-2">
                                <div class="form-group transaction">
                                    <div class="row pt-4">
                                        <div class="col-md-6 col-lg-6 form-check">
                                            <input class="form-check-input" type="radio" name="type[0]" id="debit" value="debit" checked onchange="calculate()">
                                            <label class="form-check-label" for="yes">
                                                {{ __('Debit') }}
                                            </label>
                                        </div>
                                        <div class="col-md-6 col-lg-6 form-check">
                                            <input class="form-check-input" type="radio" name="type[0]" id="credit" value="credit" onchange="calculate()">
                                            <label class="form-check-label" for="no">
                                                {{ __('Credit') }}
                                            </label>
                                        </div>
                                        @if ($errors->has('type.*'))
                                            <span class="text-danger">{{ $errors->first('type.*') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 col-2">
                                <div class="form-group transaction">
                                    <label for="first-amount-column">{{ __('Amount') }}<span class="text-danger">*</span></label>
                                    <input type="number" step="any" class="form-control required @error('amount') is-invalid @enderror" id="amount0" name="amount[]" value="{{ old('amount.0') }}" placeholder="{{ __('Amount') }}" oninput="calculate()">
                                    @if ($errors->has('amount.*'))
                                        <span class="text-danger">{{ $errors->first('amount.*') }}</span>
                                    @endif
                                </div>
                            </div>

                            {{-- <div class="col-md-3 col-3">
                                <div class="form-group">
                                    <label for="first-amount-column">{{ __('Cheque No.') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="any"
                                        class="form-control @error('amount') is-invalid @enderror" id="amount"
                                        name="amount" value="{{ old('amount') }}" placeholder="{{ __('Amount') }}">
                                    @if ($errors->has('amount'))
                                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                                    @endif
                                </div>
                            </div> --}}
                        </div>
                        <div class="text-end">
                            <span class="text-danger" id="not_count_match"></span>
                        </div>
                        <div class="text-end">
                            {{ __('Total Debit') }} : <p id="debit_total"><span>&#8377;0</span></p>
                            {{-- (₹) : 0 --}}
                        </div>
                        <div class="text-end">
                            {{ __('Total Credit') }} : <p id="credit_total"><span>&#8377;0</span></p>
                        </div>
                        <div class="mb-3 row justify-content-end">
                            {{-- data-bs-toggle="modal" data-bs-target="#bd-example-modal-lg" <button type="submit" id="submit" class="col-md-1 offset-md-5 btn btn-primary" onclick="return checktotal()"><i class="fa fa-lock"></i>{{ __('Submit') }}</button> --}}
                            <button type="button" id="submitButton" class="col-md-1 offset-md-5 btn btn-primary" onclick="return checktotal()"><i class="fa fa-lock"></i>{{ __('Submit') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- MODEL SHOW --}}
<div class="modal fade text-left" id="confirmation" tabindex="-1" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h5 class="modal-title" id="myModalLabel1">{{ __('Double Entry Details') }}
                </h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <svg xmlns="https://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                        <line x1="18" y1="6" x2="6" y2="18">
                        </line>
                        <line x1="6" y1="6" x2="18" y2="18">
                        </line>
                    </svg>
                </button>
            </div>
            <div class="modal-body" id="tableBody">
                {{-- All Entry Show hear --}}
            </div>
        </div>
    </div>
</div>
{{-- END --}}
@endsection
@push('script')
<script>
    var valid = true;
    var cr_total = 0;
    var dr_total = 0;

    function calculate() {
        cr_total = 0;
        dr_total = 0;

        var inps = $("input[name='amount[]']");
        for (var i = 0; i < inps.length; i++) {
            if ($("input[name='type[" + i + "]']:checked").val() == 'credit') {
                cr_total += Number(inps[i].value);
            }
            if ($("input[name='type[" + i + "]']:checked").val() == 'debit') {
                dr_total += Number(inps[i].value);
            }
        }

        $('#debit_total').html('<span>&#8377;' + dr_total + '</span>')
        $('#credit_total').html('<span>&#8377;' + cr_total + '</span>');

        // $('#not_count_match').html('');
        // if (cr_total != dr_total) {
        //     $('#not_count_match').html('{{ __('Debit and Credit must be Equal.') }}');
        // }
    }

    function checktotal() {
        var valid = 0;
        $('.required').each(function() {
            if (!this.value) {
                valid = 1;
            }
        });

        $('.ledgerAccountId').each(function() {
            var selectedValue = $(this).find('option:selected').attr('ledger_grp_id');
            var row = $(this).attr('row');
            console.log(row);
            if (selectedValue == "2") {

                if (!$('.required_share' + row).val()) {
                    valid = 1;
                };
            } else {
                $('.required_share' + row).val('');
            }

            // if (selectedValue == "3") {

            //     if (!$('.required_loan_emi' + row).val()) {
            //         valid = 1;
            //     };
            // } else {
            //     $('.required_loan_emi' + row).val('');
            // }
        });

        if (valid) {
            // $('#not_count_match').html('All fields are required.');
            $('#not_count_match').html('{{ __('All fields are required.') }}');
            return false;
        }

        if (cr_total != dr_total) {
            // alert('Debit and Credit must be Equal.');
            $('#not_count_match').html('{{ __('Debit and Credit must be Equal.') }}');
            return false;
        } else {
            $('#not_count_match').html('');

            confirm();
            return true;
        }

    }
    //end

    // ***************All Form request data show in model popup***************

    function confirm() {
        $.ajax({
            type: "POST",
            url: "{{ route('double_entries.confirm') }}",
            data: $('#myForm').serialize(),
            success: function(response) {
                $('#confirmation').modal('show');
                $('#tableBody').empty();
                $('#tableBody').append(response.confirm);
                // $('#confirmation').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    };
    // **********end**********

    $('.date').flatpickr({
        allowInput: true,
        altInput: true,
        altFormat: "d/m/Y",
        dateFormat: "Y-m-d",
    });

    $('.select2').select2();
    $('input[name="ctype"]').on('change', function() {
        if ($(this).val() == 2) {
            $('#cheque_div').show();
        } else {
            $('#cheque_div').hide();
        }
    });

    function lockinput() {
        $("#cr_ledger_acc_id").select2({
            disabled: 'readonly'
        });
        $("#cr_particular").attr('readonly', true);
        $("#cr_date").attr('readonly', true);
        $("#cr_amount").attr('readonly', true);
        $("#cr_cheque_no").attr('readonly', true);
    }


    // $(document).on('change','input[name="share[]"]',function(e){
    //     $(this).val();
    //     $(this).attr('row');
    //     var share_amount = $(this).val()*100;
    //     $('amount').val(share_amount);
    // });

    // *****Ledger Account select dropdown*****
    // $(document).ready(function () {

    $(document).on('change', '.ledgerAccountId', function(e) {
        // $(this).parent().find('.share_input'+$(this).val()).remove();
        // console.log($(this).parent());
        var selectedValue = $(this).find('option:selected').attr('ledger_grp_id');
        var member_id = $(this).find('option:selected').attr('member_id');

        var row = $(this).attr('row');
        $(".share_input" + row).hide();
        $(".share_input" + row).find('[name="share[]"]').length;

        $(".loan_details" + row).hide();
        $("#loan_emi_id" + row).remove();

        $(".loan_details" + row).find('[name="loan_emi[]"]').length;

        $(this).val();
        console.log(row);

        if (selectedValue == "2") {
            $(".share_input" + row).show();
        } else {
            $(".share_input" + row).hide();
        }

        if (selectedValue == "3") {
            load_member_loan(member_id,row);
            $("#loan_details" + row).show();
        } else {
            $("#loan_details" + row).hide();
        }



        // ***********************************************************************************
        // if (selectedValue == "2") {
        //     $(".share_input" + row).show();

        //     var valid = 0;
        //     if (!('.required_share' + row).val()) {
        //         $('.required_share' + row).after('this field is required');

        //         // if (!this.value.trim()) {
        //         //     valid = 1;
        //         // }
        //     };
        //     if (valid) {
        //         $('#not_count_match').html('All fields are required.');
        //         return false;
        //     }
        // } else {
        //     $(".share_input" + row).hide();
        // }


        // ***********************************************************************************
        // if (selectedValue == "2") {
        //     $(".share_input" + row).show();

        //     var valid = 0;
        //     $('.required_share').each(function() {
        //         if (!this.value.trim()) {
        //             valid = 1;
        //         }
        //     });
        //     if (valid) {
        //         $('#not_count_match').html('All fields are required.');
        //         return false;
        //     }
        // } else {
        //     $(".share_input" + row).hide();
        // }
        // ***********************************************************************************


    });
    // });
    // *****End*****

    var count = 0;

    function add_ledger_entry() {
        count = count + 1;
        var html = `<div class="row ledger_entry">
                            <div class="col-md-4 col-3">
                                <div class="form-group">
                                    <label for="ledger_ac_id">{{ __('Ledger Account') }}<span
                                            class="text-danger">*</span></label>
                                    <select class="select2 ledgerAccountId required @error('ledger_ac_id') is-invalid @enderror"
                                        aria-label="Permissions" name="ledger_ac_id[]" row="` + count + `"
                                        style="height: 210px;">
                                        <option value="">{{ __('Select Ledger Account') }}</option>
                                        @forelse ($ledger_accounts as $ac)
                                            <option value="{{ $ac->id }}" ledger_grp_id="{{ $ac->ledger_group_id }}" member_id="{{ $ac->member_id }}"
                                                {{ $ac->id == old('ledger_ac_id') ? 'selected' : '' }}>
                                                {{ $ac->account_name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('ledger_ac_id'))
                                        <span class="text-danger">{{ $errors->first('ledger_ac_id') }}</span>
                                    @endif
                                </div>
                                <div class="share_input` + count + `"  style="display:none;" ><input type="number" name="share[]" class="required_share` + count + `"  placeholder="{{ __('Add Share') }}"></div>

                                <div class="col-md-12 col-3 text-danger" style="display:none;" id="loan_details` + count + `">

                                </div>


                            </div>
                            <div class="col-md-4 col-3">
                                <div class="form-group">
                                    <label for="first-particular-column">{{ __('Particular') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control required @error('particular') is-invalid @enderror" id="particular"
                                        name="particular[]" value="{{ old('particular[]') }}"
                                        placeholder="{{ __('Particular') }}">
                                    @if ($errors->has('particular'))
                                        <span class="text-danger">{{ $errors->first('particular') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-2 col-2">
                                <div class="form-group transaction">
                                    <div class="row pt-4">
                                        <div class="col-md-6 col-lg-6 form-check">
                                            <input class="form-check-input" type="radio" name="type[` + count + `]"
                                                id="debit" value="debit" checked onchange="calculate()">
                                            <label class="form-check-label" for="yes">
                                                {{ __('Debit') }}
                                            </label>
                                        </div>
                                        <div class="col-md-6 col-lg-6 form-check">
                                            <input class="form-check-input" type="radio" name="type[` + count + `]"
                                                id="credit" value="credit" onchange="calculate()">
                                            <label class="form-check-label" for="no">
                                                {{ __('Credit') }}
                                            </label>
                                        </div>
                                        @if ($errors->has('is_current'))
                                            <span class="text-danger">{{ $errors->first('is_current') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-2">
                                <div class="form-group transaction">
                                    <label for="first-amount-column">{{ __('Amount') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" step="any"
                                        class="form-control required @error('amount') is-invalid @enderror" id="amount` + count + `"
                                        name="amount[]" value="{{ old('amount[]') }}" oninput="calculate()"
                                        placeholder="{{ __('Amount') }}">
                                    @if ($errors->has('amount'))
                                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>`;
        $('.ledger_entry').last().after(html);
        $('.select2').select2();
    }

    function changeDate(input) {
        var dateValue = input.value;
        const date = new Date(dateValue);
        const formattedDate = formatDate(date);
        // $('#output').text(formattedDate);
        $('#loan_emi_date').html(formattedDate);
    }

    function formatDate(date) {
        const options = { year: 'numeric', month: 'long'};
        return date.toLocaleDateString('en-US', options);
    }

    function load_member_loan(member_id,row) {
        var url = "{{ route('member.get', ':id') }}";
        url = url.replace(':id', member_id);

        var dateValue = $('#date').val();

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
                    // console.log(member_loan);

                    var emi_date = new Date($('#date').val());
                    const options = { month: '2-digit', year: 'numeric'};
                    emi_month = emi_date.toLocaleDateString('en-US', options).replace('/', '-');
                    // console.log(emi_month);

                    var loan_emiss = member_loan.loan_emiss;
                    // console.log(loan_emiss[0].interest_amt);
                    // var emi_month = '05-2024';

                    var filteredEmis = $.grep(loan_emiss, function(emi) {
                        return emi.month === emi_month;
                    });
                    if(filteredEmis){
                        $('#amount'+row).val(filteredEmis[0].emi);
                    }
                    // console.log(filteredEmis[0].id);

                    const date = new Date($('#date').val());
                    const formattedDate = formatDate(date);

                    var html =`<div>
                        <p><b>{{ __('Loan A/c') }}</b>  : ` + member_loan.loan_no + `&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <b>{{ __('Amount') }}</b>  : ` + member_loan.principal_amt + `
                        </p>
                        <p><b>{{ __('EMI Amount') }}</b> &nbsp; : ` + member_loan.emi_amount + `&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <b>{{ __('Paid Loan') }}</b> &nbsp; : ` + (member_loan.principal_amt - member.loan_remaining_amount) + `
                            <b>{{ __('Interest') }}</b> &nbsp; : ` + (filteredEmis[0].interest_amt) + `
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </p>
                        <p><b>{{ __('Paid EMI') }}</b> &nbsp; : ` + ((member_loan.loan_emis.length)) + `&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <b>{{ __('Remaining Loan') }}</b> &nbsp; : ` + member.loan_remaining_amount + `
                        </p>
                        <p>
                            <b>{{ __('Here you are only able to paid EMI of loan #') }}</b>` + member_loan.loan_no + `&nbsp;&nbsp;<b>{{ __('for this month') }}</b>&nbsp;<label id="loan_emi_date">` + formattedDate + `</label>
                        </p>
                        <input type="hidden"  id="loan_emi_id`+row+`" value="`+filteredEmis[0].id+`" name="loan_emi_id[]">
                    </div>`;

                    // if(filteredEmis){
                    // }
                    // alert(row);
                    $('#loan_details'+row).html(html);
                    $('#loan_id').val(member_loan.id);
                    $('#loader').hide();

                }
            }
        });

    }
</script>
@endpush
