@extends('layouts.app')
@section('content')
    <link href="{{ asset('assets/compiled/css/loan.css') }}" rel="stylesheet">
@section('title')
    {{ $page_title }}
@endsection
<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">


                <div class="card-header clearfix mb-1">
                    <div class=" float-start">
                        <h5 id="header_75" class="form-header" data-component="header">
                            {{ __('Loan Information') }}
                        </h5>
                    </div>
                    <div class="float-end">
                        <a href="{{ route('loan.index') }}" class="btn btn-primary btn-sm">&larr; {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('loan.store') }}" method="post" enctype="multipart/form-data" id="myForm">
                        @csrf
                        <div class="">

                            <div class="mb-3 row">
                                <label for="amount" class="col-md-2 col-form-label text-md-end text-start">{{ __('Select Loan') }}</label>
                                <div class="col-md-10">
                                    <div class="row">
                                        @foreach ($loans as $key => $loan)
                                            <div class="col-xl-3 col-lg-4 col-md-6">
                                                <input type="radio" class="btn-check" name="loan_id" data-content="{{ $loan }}" onchange="setloan({{ $loan }})" id="loan{{ $loan->id }}" autocomplete="off" {{ $key == 0 ? 'checked' : '' }} value="{{ $loan->id }}">
                                                <label class="btn btn-outline-primary w-100 text-sm my-2 p-3" for="loan{{ $loan->id }}">&#8377;
                                                    {{ number_format($loan->amount) }}<br>EMI
                                                    {{ $loan->minimum_emi }}<br>Share {{ $loan->required_share }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="amount" class="col-md-2 col-form-label text-md-end text-start">{{ __('EMI amount') }}</label>
                                <div class="col-md-10">
                                    <input type="number" class="form-control @error('emi_amount') is-invalid @enderror" id="emi_amount" name="emi_amount" value="{{ old('emi_amount') }}" onchange="loadEMI()" placeholder="{{ __('Amount') }}">
                                    @if ($errors->has('emi_amount'))
                                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="amount" class="col-md-2 col-form-label text-md-end text-start">{{ __('EMI Details') }}</label>
                                <div class="col-md-10" id="emi_details">
                                </div>

                            </div>
                            <div class="mb-3 row">
                                <label for="amount" class="col-md-2 col-form-label text-md-end text-start">{{ __('Member') }}</label>
                                <div class="col-md-10">
                                    <select class="choices form-select @error('member_id') is-invalid @enderror" aria-label="Permissions" id="member_id" name="member_id" style="height: 210px;" onchange="load_member_details()">
                                        <option value="">{{ __('Select Member') }}</option>
                                        @foreach ($members as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('member_id'))
                                        <span class="text-danger">{{ $errors->first('member_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div id="old_details">
                                <div class="mb-3 row">
                                    <label for="amount" class="col-md-2 col-form-label text-md-end text-start">{{ __('Member Information') }}</label>

                                    <div class="col-md-10" id="old_loan">
                                        <table class="table table-bordered">
                                            <thead>

                                                <tr>
                                                    <th>{{ __('Subject') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="bg-body-secondary">
                                                    <th colspan="4">{{ __('Current Loan') }}</th>
                                                </tr>
                                                <tr>
                                                    {{-- <td>&nbsp;</td> --}}
                                                    <td>{{ __('Principal Amount') }}</td>
                                                    <td><span class="badge bg-light-info" id="old_principal_amt">0
                                                        </span></td>
                                                </tr>
                                                <tr>
                                                    {{-- <td>&nbsp;</td> --}}
                                                    <td>{{ __('EMI') }} (&#8377;)</td>
                                                    <td><span class="badge bg-light-info" id="old_emi_amt">0
                                                        </span></td>
                                                </tr>
                                                <tr>
                                                    {{-- <td>&nbsp;</td> --}}
                                                    <td>{{ __('Remaining Loan') }} (&#8377;)</td>
                                                    <td><span class="badge bg-light-info" id="remaining_emi_amt">0
                                                        </span></td>
                                                </tr>
                                                <tr class="bg-body-secondary">
                                                    <th colspan="3">{{ __('Total Share') }} </th>
                                                </tr>
                                                <tr>
                                                    {{-- <td>&nbsp;</td> --}}
                                                    {{-- <td>Total Share </td><td><span class="badge bg-light-info"
                                                            id="old_principal_amt">7000
                                                        </span></td> --}}
                                                    <td>{{ __('Required Share') }}</td>
                                                    <td><span class="badge bg-light-info" id="remaining_share_label">0
                                                        </span></td>
                                                </tr>
                                                <tr>
                                                    {{-- <td>&nbsp;</td> --}}
                                                    <td>{{ __('Share amount') }}</td>
                                                    <td><span class="badge bg-light-info" id="remaining_share_amt_label">0
                                                        </span></td>
                                                </tr>
                                                <tr class="bg-body-secondary">
                                                    <th colspan="3">{{ __('Fixed saving') }} </th>
                                                </tr>
                                                <tr>
                                                    {{-- <td>&nbsp;</td> --}}
                                                    <td>{{ __('Total fixed saving') }}</td>
                                                    <td><span class="badge bg-light-info" id="old_fixed_saving">0
                                                        </span></td>
                                                </tr>
                                                <tr>
                                                    {{-- <td>&nbsp;</td> --}}
                                                    <td>{{ __('Remaining fixed saving') }}</td>
                                                    <td><span class="badge bg-light-info" id="remaining_fixed_saving_label">0
                                                        </span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" class="form-control" id="month" name="month" value="{{ date('Y-m-d') }}">   {{-- , strtotime('14-03-2024') --}}

                            <input type="hidden" class="form-control" id="total_share_amt" name="total_share_amt" value="{{ old('total_share_amt') }}">
                            <input type="hidden" class="form-control" id="remaining_share_amount" name="remaining_share_amount" value="{{ old('remaining_share_amount') }}">
                            <input type="hidden" class="form-control" id="remaining_share" name="remaining_share" value="{{ old('remaining_share') }}">

                            <input type="hidden" class="form-control" id="remaining_loan_amount" name="remaining_loan_amount" value="{{ old('remaining_loan_amount') }}">


                            <input type="hidden" class="form-control" id="fixed_saving" name="fixed_saving" value="{{ old('fixed_saving') }}">
                            <input type="hidden" class="form-control" id="remaining_fixed_saving" name="remaining_fixed_saving" value="{{ old('fixed_saving') }}">
                            <input type="hidden" class="form-control" id="total_required_amt" name="total_required_amt" value="{{ old('total_required_amt') }}">

                            <div class="mb-3 row">
                                <label for="amount" class="col-md-2 col-form-label text-md-end text-start">{{ __('Stamp Duty') }}</label>
                                <div class="col-md-10">
                                    <input type="number" class="form-control @error('stamp_duty') is-invalid @enderror" id="stamp_duty" name="stamp_duty" value="{{ old('stamp_duty', 0) }}" placeholder="{{ __('Stamp Duty') }}" oninput="calculate()">
                                    @if ($errors->has('stamp_duty'))
                                        <span class="text-danger">{{ $errors->first('stamp_duty') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="amount" class="col-md-2 col-form-label text-md-end text-start">{{ __('Total Amount') }}</label>
                                <div class="col-md-10">
                                    <input type="number" class="form-control @error('total_amt') is-invalid @enderror" id="total_amt" name="total_amt" value="{{ old('total_amt', 0) }}" placeholder="{{ __('Total Amount') }}">
                                    @if ($errors->has('total_amt'))
                                        <span class="text-danger">{{ $errors->first('total_amt') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="amount" class="col-md-2 col-form-label text-md-end text-start">{{ __('Payment Type') }}</label>
                                <div class="col-md-10 row ms-0">
                                    <div class="form-check col-lg-2">
                                        <input class="form-check-input" type="radio" name="payment_type" id="cash" checked="" value="cash" {{ old('payment_type') == 'cash' ? 'checked' : '' }} onchange="change_payment_type()">
                                        <label class="form-check-label" for="cash">
                                            {{ __('Cash') }}
                                        </label>
                                    </div>
                                    <div class="form-check col-lg-2">
                                        <input class="form-check-input" type="radio" name="payment_type" id="cheque" value="cheque" onchange="change_payment_type()" {{ old('payment_type') == 'cheque' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cheque">
                                            {{ __('Cheque') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="payment_details" style="display: {{ old('payment_type') == 'cheque' ? 'block' : 'none' }};">
                                {{-- <div class="mb-3 row">
                                    <label for="amount" class="col-md-2 col-form-label text-md-end text-start">{{ __('Bank Name') }}</label>
                                    <div class="col-md-10">
                                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" placeholder="{{ __('Bank Name') }}">
                                        @if ($errors->has('bank_name'))
                                            <span class="text-danger">{{ $errors->first('bank_name') }}</span>
                                        @endif
                                    </div>
                                </div> --}}
                                <div class="mb-3 row">
                                    <label for="amount" class="col-md-2 col-form-label text-md-end text-start">{{ __('Cheque No.(RDC Bank)') }}</label>
                                    <div class="col-md-10">
                                        <input type="number" class="form-control @error('cheque_no') is-invalid @enderror" id="cheque_no" name="cheque_no" value="{{ old('cheque_no') }}" placeholder="{{ __('Cheque No.') }}">
                                        @if ($errors->has('cheque_no'))
                                            <span class="text-danger">{{ $errors->first('cheque_no') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4 mt-5">
                                <h5 id="header_75" class="form-header" data-component="header">
                                    {{ __('Guarantee Details') }}</h5>
                            </div>
                            <div class="mb-3 row">
                                <label for="amount" class="col-md-2 col-form-label text-md-end text-start">{{ __('Guarantor 1') }}</label>
                                <div class="col-md-10">
                                    <div class="gexist">
                                        <select class="form-select @error('g1_member_id') is-invalid @enderror" aria-label="Permissions" id="g1_member_id" name="g1_member_id" style="height: 210px;" onchange="checkGuarantor($(this).val(),$('#g1_member_id'))">
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
                                        <select class="form-select @error('g2_member_id') is-invalid @enderror" aria-label="Permissions" id="g2_member_id" name="g2_member_id" style="height: 210px;" onchange="checkGuarantor($(this).val(),$('#g2_member_id'))">
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
                            <div class="mb-3 row">
                                <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" id="submitButton" value="{{ __('Submit') }}">
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    $('#myForm').submit(function() {
        $('#submitButton').prop('disabled', true);
    });

    $('#g2_member_id').select2();
    $('#g1_member_id').select2();

    var required_share = 0;
    var minimum_emi = 0;
    var loan_amount = 0;
    var share_amount = '{{ current_share_amount()->share_amount }}';
    var member_share = 0;
    var remaining_saving = 0;
    var month = $('#month').val();
    let dt = new Date(month);


    $('input[name="loan_id"]:checked').trigger('change');

    function disableoption(input, member_id) {
        // console.log(input.attr('id'));
        $('#g2_member_id option').attr('disabled', false);
        $('#g2_member_id').select2();
        $('#g1_member_id option').attr('disabled', false);
        $('#g1_member_id').select2();
        if (input.attr('id') == 'member_id') {
            $("#g1_member_id").val('').select2();
            $("#g2_member_id").val('').select2();
            $('.guarantor_existg1_member_id').remove();
            $('.guarantor_existg2_member_id').remove();
            $('#g2_member_id option[value="' + member_id + '"]').attr('disabled', true);
            $('#g2_member_id').select2();
            $('#g1_member_id option[value="' + member_id + '"]').attr('disabled', true);
            $('#g1_member_id').select2();
        } else if (input.attr('id') == 'g1_member_id') {
            $('#g2_member_id option[value="' + member_id + '"]').attr('disabled', true);
            $('#g2_member_id').select2();
        } else if (input.attr('id') == 'g2_member_id') {
            // console.log(member_id);
            $('#g1_member_id option[value="' + member_id + '"]').attr('disabled', true);
            $('#g1_member_id').select2();
        }
    }

    function setloan(loan) {
        JSON.stringify(loan);
        required_share = loan.required_share;
        minimum_emi = loan.minimum_emi;
        loan_amount = loan.amount;
        $('#emi_amount').val(minimum_emi);

        $('#emi_amount').attr('min', loan.minimum_emi);
        if ($('#member_id').val()) {
            load_member_details();
        } else {
            $('#loader').hide();
        }
        loadEMI();
        // console.log(required_share);
    }

    function loadEMI() {

        var emi_amount = Number($('#emi_amount').val());
        var no_of_emi = Number(loan_amount / emi_amount);
        var loan_amt = loan_amount;
        var emi_c = '{{ getLoanParam()[0] }}';
        var emi_d = '{{ getLoanParam()[1] }}';
        var rate = Number('{{ current_loan_interest()->loan_interest }}');
        // rate = 9.5;
        var emi_html = `<table class="table table-borderd"><tr><th>No.</th><th>Month</th><th>Rest Principal (&#8377;)</th><th>Principal (&#8377;)</th><th>Interest (&#8377;)</th><th>EMI amount (&#8377;)</th></tr>`;
        // console.log(emi_amount, minimum_emi, loan_amount);
        if (emi_amount < minimum_emi || emi_amount > loan_amount) {
            $('#emi_amount').val(minimum_emi);
            return false;
        } else {
            no_of_emi = Math.ceil(no_of_emi);
            let display_month = dt.getMonth();
            let year = dt.getFullYear();
            let dmonth = '';
            index = 1;
            while (loan_amt > 0) {
                // for (let index = 1; index <= no_of_emi; index++) {
                var emi_interest = ((loan_amt * rate) / 100) * (emi_c / emi_d);
                // var emi_interest = Number((loan_amt * (rate * 0.01))/no_of_emi);
                console.log((loan_amt * rate) / 100);
                console.log((emi_c / emi_d));
                if (display_month === 12) {
                    display_month = 0;
                    year++;
                }
                dmonth = new Date((display_month + 1) + '-1-' + year).toLocaleString('en-US', {
                    month: 'short'
                });
                dmonth = (dmonth + '-' + year);
                if (loan_amt > 0) {
                    if (loan_amt < emi_amount) {
                        emi_amount = loan_amt;
                        loan_amt = 0;
                    } else {
                        loan_amt = (loan_amt - (emi_amount - emi_interest)).toFixed(0);

                    }
                    // console.log(emi_amount, emi_interest);
                    emi_html += `<tr><td>` + index + `</td><input type="hidden" value="` + ((display_month + 1).toString().padStart(2, '0') + '-' + year) + `" name="emi_month[]"><td>` + dmonth + `</td><input type="hidden" value="` + loan_amt + `" name="rest_principal[]"><td>` + loan_amt + `</td><input type="hidden" value="` + (emi_amount - emi_interest).toFixed(0) +
                        `" name="principal[]"><td>` + (emi_amount - emi_interest).toFixed(0) + `</td><input type="hidden" value="` + emi_interest.toFixed(0) + `" name="emi_interest[]"><td>` + emi_interest.toFixed(0) + `</td><input type="hidden" value="` + emi_amount + `" name="emi_amt[]"><td>` + emi_amount + `</td></tr>`;
                }
                display_month++;
                index++;
            }
            emi_html += `</table>`;
            $('#emi_details').html(emi_html);
        }
    }

    function checkGuarantor(member_id, input) {
        $('.guarantor_exist' + input.attr('id')).remove();
        var url = "{{ route('guarantor_count.get', ':id') }}";
        url = url.replace(':id', member_id);
        disableoption(input, member_id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            url: url,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                if (data.success == true) {
                    if (data.guarantor_exist.length > 0) {
                        var html = `<table class="table table-bordered guarantor_exist` + input.attr('id') +
                            `"><tbody>`;
                        data.guarantor_exist.forEach(element => {
                            // console.log(element.member);
                            html +=
                                `<tr><td colspan="3"><span class="text-danger"><b>{{ __('Note') }}:</b>&nbsp;{{ __('This member is already exist as guarantor in following loan account.') }}</span></td></tr><tr><td class="">` +
                                "{{ __('Member') }}" +
                                `&nbsp;&nbsp;<span class="badge bg-light-danger">` +
                                element.member.user.name +
                                `</span></td><td class="">` +
                                "{{ __('Principal') }}" +
                                `&nbsp;&nbsp;<span class="badge bg-light-danger">&#8377;` +
                                element.principal_amt + `</span></td><td class="">` +
                                "{{ __('Loan EMI') }}" +
                                `&nbsp;&nbsp;<span class="badge bg-light-danger">&#8377;` +
                                element.emi_amount + `</span></td></tr></tbody></table>`;
                        });
                        // $('#g2_member_id_exist').show();

                        $(input).parent().after(html);
                    }

                }
            }
        });
    }

    function calculate() {
        var total = 0;
        var rshare = required_share - member_share;
        var remaining_loan_amount = Number($('#remaining_loan_amount').val());
        if (rshare > 0) {
            total += share_amount * rshare;
        }
        if (remaining_saving > 0) {
            total += remaining_saving;
        }
        if (remaining_loan_amount > 0) {
            total += remaining_loan_amount;
        }
        // total = total-($('#fixed_saving').val()+$('#total_share_amt').val());
        $('#total_required_amt').val(total);
        $('#total_amt').attr('min', total);
        total += Number($('#stamp_duty').val());
        $('#total_amt').val(total);
    }

    function load_member_details() {
        $('#loader').show();
        var member_id = $('#member_id').val();

        disableoption($('#member_id'), member_id);
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
                    remaining_saving = member.member_fixed_saving.remaining_fixed_saving;
                    member_share = member.shares.length;
                    var remaining_share = (required_share - member_share) > 0 ? (required_share -
                        member_share) : 0;
                    var member_loan = member.loan;
                    console.log('remaining_share' + remaining_share, member_share);
                    $('#old_principal_amt').html('&#8377;' + (member_loan ? member_loan.principal_amt : 0));
                    $('#remaining_share_label').html('' + remaining_share);
                    $('#remaining_share_amt_label').html('&#8377;' + remaining_share * share_amount);
                    $('#old_fixed_saving').html('&#8377;' + member.member_fixed_saving.member_fixed_saving);
                    $('#old_emi_amt').html('&#8377;' + (member_loan ? member_loan.emi_amount : 0));
                    $('#remaining_emi_amt').html('&#8377;' + (member_loan ? member.loan_remaining_amount : 0));
                    $('#remaining_fixed_saving_label').html('&#8377;' + member.member_fixed_saving
                        .remaining_fixed_saving);
                    $('#fixed_saving').val(member.member_fixed_saving.member_fixed_saving);
                    $('#total_share_amt').val(member_share * share_amount);
                    $('#remaining_fixed_saving').val(member.member_fixed_saving.remaining_fixed_saving);
                    $('#remaining_loan_amount').val(member_loan ? member.loan_remaining_amount : 0);
                    $('#remaining_share').val(remaining_share);
                    $('#remaining_share_amount').val(remaining_share * share_amount);
                    calculate();
                    $('#loader').hide();
                }
            }
        });
    }

    function change_payment_type() {
        $('#payment_details').hide();
        var payment_type = $('input[name="payment_type"]:checked').val();
        if (payment_type == 'cheque') {
            $('#payment_details').show();
        }
    }
</script>
@endpush
