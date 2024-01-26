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

                <div class="card-header mb-4">
                    <div class="float-end">
                        <a href="{{ route('loan.index') }}" class="btn btn-primary btn-sm">&larr; {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('loan.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-4 mt-5">
                                <h5 id="header_75" class="form-header" data-component="header">
                                    {{ __('Loan Information') }}
                                </h5>
                            </div>
                            <div class="mb-3 row">
                                <label for="amount"
                                    class="col-md-2 col-form-label text-md-end text-start">{{ __('Select Loan') }}</label>
                                <div class="col-md-9">
                                    @foreach ($loans as $key => $loan)
                                        <input type="radio" class="btn-check" name="loan_id"
                                            data-content="{{ $loan }}" onchange="setloan({{ $loan }})"
                                            id="loan{{ $loan->id }}" autocomplete="off"
                                            {{ $key == 0 ? 'checked' : '' }} value="{{ $loan->id }}">
                                        <label class="btn btn-outline-primary w-25 m-2 text-sm p-3"
                                            for="loan{{ $loan->id }}">&#8377;
                                            {{ number_format($loan->amount) }}<br>EMI
                                            {{ $loan->minimum_emi }}<br>Share {{ $loan->required_share }}</label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="amount"
                                    class="col-md-2 col-form-label text-md-end text-start">{{ __('EMI amount') }}</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control @error('emi_amount') is-invalid @enderror"
                                        id="emi_amount" name="emi_amount" value="{{ old('emi_amount') }}"
                                        placeholder="{{ __('Amount') }}">
                                    @if ($errors->has('emi_amount'))
                                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="amount"
                                    class="col-md-2 col-form-label text-md-end text-start">{{ __('Member') }}</label>
                                <div class="col-md-9">
                                    <select class="choices form-select @error('member_id') is-invalid @enderror"
                                        aria-label="Permissions" id="member_id" name="member_id" style="height: 210px;"
                                        onchange="load_member_details()">
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
                                    <label for="amount"
                                        class="col-md-2 col-form-label text-md-end text-start">{{ __('Member Information') }}</label>

                                    <div class="col-md-9" id="old_loan">
                                        <table class="table table-bordered">
                                            <thead>

                                                <tr>
                                                    <th>{{ __('Subject') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th colspan="4">{{ __('Current Loan') }}</th>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>{{ __('Principal Amount') }}</td>
                                                    <td><span class="badge bg-light-info" id="old_principal_amt">0
                                                        </span></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>{{ __('EMI') }}</td>
                                                    <td><span class="badge bg-light-info" id="old_emi_amt">0
                                                        </span></td>
                                                </tr>
                                                <tr>
                                                    <th colspan="3">{{ __('Total Share') }} </th>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    {{-- <td>Total Share </td><td><span class="badge bg-light-info"
                                                            id="old_principal_amt">7000
                                                        </span></td> --}}
                                                    <td>{{ __('Required Share') }}</td>
                                                    <td><span class="badge bg-light-info" id="remaining_share">0
                                                        </span></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>{{ __('Share amount') }}</td>
                                                    <td><span class="badge bg-light-info" id="remaining_share_amt">0
                                                        </span></td>
                                                </tr>
                                                <tr>
                                                    <th colspan="3">{{ __('Fixed saving') }} </th>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>{{ __('Total fixed saving') }}</td>
                                                    <td><span class="badge bg-light-info" id="old_fixed_saving">0
                                                        </span></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>{{ __('Remaining fixed saving') }}</td>
                                                    <td><span class="badge bg-light-info" id="remaining_fixed_saving">0
                                                        </span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>



                            {{-- <div class="mb-4 mt-5">
                                <h5 id="header_75" class="form-header" data-component="header">
                                    {{ __('Amount Information') }}
                                </h5>
                            </div> --}}
                            {{-- <div class="mb-3 row">

                                 <label for="amount"
                                    class="col-md-2 col-form-label text-md-end text-start">{{ __('Share') }}</label> --}}
                            {{-- <div class="col-md-4">
                                    <input type="number"
                                        class="form-control @error('required_share') is-invalid @enderror"
                                        id="required_share" name="required_share" value="{{ old('required_share') }}"
                                        placeholder="{{ __('Required Share') }}">
                                    @if ($errors->has('required_share'))
                                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                                    @endif
                                </div>
                                <div class="col-md-5">
                                    <input type="number"
                                        class="form-control @error('total_share') is-invalid @enderror" id="total_share"
                                        name="total_share" value="{{ old('total_share') }}"
                                        placeholder="{{ __('Total Share') }}">
                                    @if ($errors->has('total_share'))
                                        <span class="text-danger">{{ $errors->first('total_share') }}</span>
                                    @endif
                                </div> 
                                
                            </div> --}}
                            {{-- <div class="mb-3 row">
                                <label for="amount"
                                    class="col-md-2 col-form-label text-md-end text-start">{{ __('Loan Settlement Amount') }}</label>
                                <div class="col-md-9">
                                    <input type="number"
                                        class="form-control @error('loan_settlement_amt') is-invalid @enderror"
                                        id="loan_settlement_amt" name="loan_settlement_amt"
                                        value="{{ old('loan_settlement_amt') }}"
                                        placeholder="{{ __('Loan Settlement amount') }}" readonly>
                                    @if ($errors->has('loan_settlement_amt'))
                                        <span class="text-danger">{{ $errors->first('loan_settlement_amt') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="amount"
                                    class="col-md-2 col-form-label text-md-end text-start">{{ __('Total Share amount') }}</label>
                                <div class="col-md-9">
                                    <input type="number"
                                        class="form-control @error('total_share_amt') is-invalid @enderror"
                                        id="total_share_amt" name="total_share_amt"
                                        value="{{ old('total_share_amt') }}"
                                        placeholder="{{ __('Total Share Amount') }}" readonly>
                                    @if ($errors->has('total_share_amt'))
                                        <span class="text-danger">{{ $errors->first('total_share_amt') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="amount"
                                    class="col-md-2 col-form-label text-md-end text-start">{{ __('Remianing Fixed Saving') }}</label>
                                <div class="col-md-9">
                                    <input type="number"
                                        class="form-control @error('fixed_saving') is-invalid @enderror"
                                        id="fixed_saving" name="fixed_saving" value="{{ old('fixed_saving') }}"
                                        placeholder="{{ __('Remianing Fixed Saving') }}" oninput="calculate()"
                                        readonly>
                                    @if ($errors->has('fixed_saving'))
                                        <span class="text-danger">{{ $errors->first('fixed_saving') }}</span>
                                    @endif
                                </div>
                            </div> --}}
                            <input type="hidden" class="form-control" id="total_share_amt" name="total_share_amt"
                                value="{{ old('total_share_amt') }}">
                            <input type="hidden" class="form-control" id="loan_settlement_amt"
                                name="loan_settlement_amt" value="{{ old('loan_settlement_amt') }}">
                            <input type="hidden" class="form-control" id="fixed_saving" name="fixed_saving"
                                value="{{ old('fixed_saving') }}">
                            <div class="mb-3 row">
                                <label for="amount"
                                    class="col-md-2 col-form-label text-md-end text-start">{{ __('Stamp Duty') }}</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control @error('stamp_duty') is-invalid @enderror"
                                        id="stamp_duty" name="stamp_duty" value="{{ old('stamp_duty', 0) }}"
                                        placeholder="{{ __('Stamp Duty') }}" oninput="calculate()">
                                    @if ($errors->has('stamp_duty'))
                                        <span class="text-danger">{{ $errors->first('stamp_duty') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label for="amount"
                                    class="col-md-2 col-form-label text-md-end text-start">{{ __('Total Amount') }}</label>
                                <div class="col-md-9">
                                    <input type="number"
                                        class="form-control @error('total_amt') is-invalid @enderror" id="total_amt"
                                        name="total_amt" value="{{ old('total_amt', 0) }}"
                                        placeholder="{{ __('Total Amount') }}">
                                    @if ($errors->has('total_amt'))
                                        <span class="text-danger">{{ $errors->first('total_amt') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="amount"
                                    class="col-md-2 col-form-label text-md-end text-start">{{ __('Payment Type') }}</label>
                                <div class="col-md-9 row ms-0">
                                    <div class="form-check col-lg-2">
                                        <input class="form-check-input" type="radio" name="payment_type"
                                            id="cash" checked="" value="cash"
                                            {{ old('payment_type') == 'cash' ? 'checked' : '' }}
                                            onchange="change_payment_type()">
                                        <label class="form-check-label" for="cash">
                                            {{ __('Cash') }}
                                        </label>
                                    </div>
                                    <div class="form-check col-lg-2">
                                        <input class="form-check-input" type="radio" name="payment_type"
                                            id="cheque" value="cheque" onchange="change_payment_type()"
                                            {{ old('payment_type') == 'cheque' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cheque">
                                            {{ __('Cheque') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div id="payment_details"
                                style="display: {{ old('payment_type') == 'cheque' ? 'block' : 'none' }};">
                                <div class="mb-3 row">
                                    <label for="amount"
                                        class="col-md-2 col-form-label text-md-end text-start">{{ __('Bank name') }}</label>
                                    <div class="col-md-9">
                                        <input type="text"
                                            class="form-control @error('bank_name') is-invalid @enderror"
                                            id="bank_name" name="bank_name" value="{{ old('bank_name') }}"
                                            placeholder="{{ __('Bank name') }}">
                                        @if ($errors->has('bank_name'))
                                            <span class="text-danger">{{ $errors->first('bank_name') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="amount"
                                        class="col-md-2 col-form-label text-md-end text-start">{{ __('Cheque No.') }}</label>
                                    <div class="col-md-9">
                                        <input type="number"
                                            class="form-control @error('cheque_no') is-invalid @enderror"
                                            id="cheque_no" name="cheque_no" value="{{ old('cheque_no') }}"
                                            placeholder="{{ __('Cheque No.') }}">
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
                                <label for="amount"
                                    class="col-md-2 col-form-label text-md-end text-start">{{ __('Guarantor 1') }}</label>
                                <div class="col-md-9">
                                    <div class="gexist">
                                        <select class="form-select @error('g1_member_id') is-invalid @enderror"
                                            aria-label="Permissions" id="g1_member_id" name="g1_member_id"
                                            style="height: 210px;"
                                            onchange="checkGuarantor($(this).val(),$('#g1_member_id'))">
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
                                <label for="amount"
                                    class="col-md-2 col-form-label text-md-end text-start">{{ __('Guarantor 2') }}</label>
                                <div class="col-md-9">
                                    <div class="gexist">
                                        <select class="form-select @error('g2_member_id') is-invalid @enderror"
                                            aria-label="Permissions" id="g2_member_id" name="g2_member_id"
                                            style="height: 210px;"
                                            onchange="checkGuarantor($(this).val(),$('#g2_member_id'))">
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
                                <label for="amount"
                                    class="col-md-2 col-form-label text-md-end text-start">{{ __('Guarantee Payee Cheque No') }}</label>
                                <div class="col-md-9">
                                    <input type="number"
                                        class="form-control @error('gcheque_no') is-invalid @enderror" id="gcheque_no"
                                        name="gcheque_no" value="{{ old('gcheque_no') }}"
                                        placeholder="{{ __('Guarantee Payee Cheque No') }}">
                                    @if ($errors->has('gcheque_no'))
                                        <span class="text-danger">{{ $errors->first('gcheque_no') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label for="amount"
                                    class="col-md-2 col-form-label text-md-end text-start">{{ __('Guarantee Payee Bank Name') }}</label>
                                <div class="col-md-9">
                                    <input type="text"
                                        class="form-control @error('gbank_name') is-invalid @enderror" id="gbank_name"
                                        name="gbank_name" value="{{ old('gbank_name') }}"
                                        placeholder="{{ __('Guarantee Payee Bank Name') }}">
                                    @if ($errors->has('gbank_name'))
                                        <span class="text-danger">{{ $errors->first('gbank_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <input type="submit" class="col-md-3 offset-md-5 btn btn-primary"
                                    value="{{ __('Submit') }}">
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
    function disableoption(input, member_id) {
        console.log(input.attr('id'));
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
            console.log(member_id);
            $('#g1_member_id option[value="' + member_id + '"]').attr('disabled', true);
            $('#g1_member_id').select2();
        }
    }
    $('#g2_member_id').select2();
    $('#g1_member_id').select2();
    var required_share = 0;
    var minimum_emi = 0;
    var share_amount = '{{ current_share_amount()->share_amount }}';
    var member_share = 0;
    $('input[name="loan_id"]:checked').trigger('change');
    // if(default_loan){
    //     JSON.stringify(default_loan)
    //     console.log(   default_loan);
    //     setloan(default_loan);
    // }
    // console.log(default_loan);
    function setloan(loan) {
        JSON.stringify(loan);
        required_share = loan.required_share;
        minimum_emi = loan.minimum_emi;
        $('#emi_amount').val(minimum_emi);

        $('#emi_amount').attr('min', loan.minimum_emi);
        if ($('#member_id').val()) {
            load_member_details();
        } else {
            $('#loader').hide();
        }
    }

    function checkGuarantor(member_id, input) {
        $('.guarantor_exist' + input.attr('id')).remove();
        var url = "{{ route('guarantor_count.get', ':id') }}";
        url = url.replace(':id', member_id);
        // console.log(input.attr('id'));
        // console.log(input == $('#g1_member_id'));
        // console.log($('#g1_member_id'));
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
                                `<tr><td colspan="3"><b>"{{ __('Note') }}":</b>&nbsp;"{{ __('This member is already exist as guarantor in following loan account.') }}"</td></tr><tr><td class="">` +
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
        var rshare = required_share - member_share
        if (rshare > 0) {
            total += share_amount * rshare;
        }
        $('#total_share_amt').val(total);
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

                    member_share = member.shares.length;
                    var remaining_share = (required_share - member_share) > 0 ? (required_share -
                        member_share) : 0;
                    var member_loan = member.loans[0];
                    // console.log(remaining_share);
                    $('#old_principal_amt').html('&#8377;' + (member_loan ? member_loan.principal_amt : 0));
                    $('#remaining_share').html('' + remaining_share);
                    $('#old_share_amt').html('&#8377;' + member_share);
                    $('#remaining_share_amt').html('&#8377;' + remaining_share * share_amount);
                    $('#old_fixed_saving').html('&#8377;' + member.member_fixed_saving.member_fixed_saving);
                    $('#old_emi_amt').html('&#8377;' + (member_loan ? member_loan.emi_amount : 0));
                    $('#remaining_fixed_saving').html('&#8377;' + member.member_fixed_saving
                        .remaining_fixed_saving);
                    $('#fixed_saving').val(member.member_fixed_saving.remaining_fixed_saving);
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
