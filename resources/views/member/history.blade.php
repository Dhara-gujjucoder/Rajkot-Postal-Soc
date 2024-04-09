<div class="mb-3 row">
    @php
        // dd($member->member_fixed['member_fixed_saving']);
        $no_of_month = count(getMonthsOfYear(currentYear()->id));
        $required_saving = $no_of_month * current_fixed_saving()->monthly_saving;
    @endphp
    <input type="hidden" class="form-control" id="month" name="month" value="{{ date('m-d-Y', strtotime(currentYear()->start_month . '-01-' . currentYear()->start_year)) }}">

    <input type="hidden" class="form-control" id="total_share_amt" name="total_share_amt" value="{{ old('total_share_amt', $member->shares->count() * current_share_amount()->share_amount) }}">

    <input type="hidden" class="form-control" id="remaining_loan_amount" name="remaining_loan_amount" value="{{ $member->loan_remaining_amount }}">


    <input type="hidden" class="form-control" id="total_required_amt" name="total_required_amt" value="{{ old('total_required_amt') }}">
    <input type="hidden" class="form-control" id="member_id" name="member_id" value="{{ old('member_id') }}">

    <label for="amount" class="col-md-5 col-form-label text-md-end text-start"><b>{{ __('Member Information') }}</b></label>
</div>
<div class="mb-3 row">
    <div class="col-md-12 p-4" id="old_loan">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ __('Subject') }}</th>
                    <th>{{ __('Amount') }}</th>
                </tr>
            </thead>
            <tbody>
                <form action="{{ route('member.resign', $member->id) }}" method="post" id="form_submit">
                    <tr class="bg-body-secondary">
                        <th colspan="4">{{ __('Current Loan') }}</th>
                    </tr>
                    <tr>
                        {{-- <td>&nbsp;</td> --}}
                        <td>{{ __('Principal Amount') }}</td>
                        <td><span class="badge bg-light-info" id="old_principal_amt">&#8377;{{ $member->loan ? $member->loan->principal_amt : 0 }}
                            </span></td>
                    </tr>
                    <tr>
                        {{-- <td>&nbsp;</td> --}}
                        <td>{{ __('EMI') }} (&#8377;)</td>
                        <td><span class="badge bg-light-info" id="old_emi_amt">&#8377;{{ $member->loan ? $member->loan->emi_amount : 0 }}
                            </span></td>
                    </tr>
                    <tr>
                        {{-- <td>&nbsp;</td> --}}
                        <td>{{ __('Remaining Loan') }} (&#8377;)</td>
                        <td><span class="badge bg-light-info" id="remaining_emi_amt">&#8377;{{ $member->loan ? $member->loan_remaining_amount : 0 }}
                            </span></td>
                    </tr>
                    <tr class="bg-body-secondary">
                        <th colspan="3">{{ __('Total Share') }} </th>
                    </tr>
                    <tr>
                        <td>{{ __('Share') }}</td>
                        <td><span class="badge bg-light-info" id="old_principal_amt">{{ $member->shares->count() }}
                            </span></td>
                    </tr>
                    <tr>
                        {{-- <td>&nbsp;</td> --}}
                        <td>{{ __('Share amount') }}</td>
                        <td><span class="badge bg-light-info" id="remaining_share_amt_label">&#8377;{{ $member->share_ledger_account->current_balance }}
                            </span></td>
                    </tr>
                    <tr class="bg-body-secondary">
                        <th colspan="3">{{ __('Fixed saving') }} </th>
                    </tr>
                    <tr>
                        {{-- <td>&nbsp;</td> --}}
                        <td>{{ __('Total fixed saving') }}</td>
                        <td><span class="badge bg-light-info" id="old_fixed_saving">&#8377;{{ $member->member_fixed['member_fixed_saving'] }}
                            </span></td>
                    </tr>
                    <tr>
                        <td>{{ __('Remaining fixed saving') }}</td>
                        <td><span class="badge bg-light-info" id="remaining_fixed_saving_label">&#8377;{{ $member->remaining_fixed_saving }}
                            </span></td>
                    </tr>

                    <input type="hidden" name="amount" placeholder="{{ __('Remaining Loan') }}" id="remaining_loan" min="{{ $member->loan_remaining_amount }}" value="{{ $member->loan_remaining_amount }}">
                    {{-- <tr>
                            <td> <b>{{ __('Loan Settlement amount') }}</b></td>
                            <td colspan="2">
                                <input type="number" class="form-control amount" name="amount"
                                    placeholder="{{ __('Remaining Loan') }}" id="remaining_loan"
                                    min="{{ $member->loan_remaining_amount }}"
                                    value="{{ $member->loan_remaining_amount }}">
                            </td>
                        </tr> --}}
                    {{-- @if ($member->loan_remaining_amount > $member->remaining_fixed_saving + $member->share_ledger_account->current_balance)
                    @else --}}
                    <tr>
                        <td colspan="2" class="text-center"><b>{{ __('Amount Settlement') }}</b></td>
                    </tr>

                    <tr>
                        <td> <input class="form-check-input payment_type ms-3" type="checkbox" name="share_amount_check" id="share_amount_check" value="share_check" onchange="calculate()" {{ $member->share_ledger_account->current_balance > 0 ? 'checked' : '' }}>
                            <label class="form-check-label pl-2" for="share_amount_check">
                                {{ __('Share Amount') }} : {{ $member->share_ledger_account->current_balance }}
                            </label>
                        </td>
                        <td colspan="2">
                            <input class="form-check-input payment_type ms-3" type="checkbox" name="fixed_saving_check" id="fixed_saving_check" value="fixed_saving_check" onchange="calculate()" {{ $member->member_fixed['member_fixed_saving'] > 0 ? 'checked' : '' }}>
                            <label class="form-check-label pl-2" for="fixed_saving_check">
                                {{ __('Fixed Saving') }} : {{ $member->fixed_saving_ledger_account->current_balance }}
                            </label>
                        </td>
                    </tr>
                    {{-- <tr id="fixed_saving_details"
                            style="display: {{ $member->member_fixed['member_fixed_saving'] > 0 ? 'contents' : 'none' }};">

                            <td> <label for="amount"
                                    class=" form-label text-md-end text-start"><b>{{ __('Fixed Saving Amount') }}</b></label>
                            </td>
                            <td colspan="2">
                                <input type="number" class="form-control fixed_saving" id="fixed_saving"
                                    max="{{ $member->member_fixed['member_fixed_saving'] }}" name="fixed_saving"
                                    value="{{ old('fixed_saving', $member->member_fixed['member_fixed_saving']) }}"
                                    onchange="calculate()" placeholder="{{ __('Fixed Saving') }}">
                            </td>
                        </tr> --}}
                    {{-- @elseif($member->member_fixed['member_fixed_saving']) --}}
                    {{-- <tr>
                        <td colspan="2" class="text-center">{{ __('Fixed Amount Settlement') }}</td>
                    </tr>
                        <tr>
                            <td><label for="amount"
                                class=" form-label text-md-end text-start"><b>{{ __('Total return amount') }}</b></label>
                        </td>
                            <td colspan="2">
                                <input type="number" class="form-control fixed_saving" id="return_fixed_saving" readonly
                                    max="{{ $member->member_fixed['member_fixed_saving'] }}" name="return_fixed_saving"
                                    value="{{ old('fixed_saving', $member->member_fixed['member_fixed_saving']) }}" placeholder="{{ __('Fixed Saving') }}">

                            </td>
                        </tr> --}}
                    {{-- @endif --}}

                    <tr id="final_total">
                        <td><label for="amount" class=" form-label text-md-end text-start"><b>{{ __('Total amount') }}</b></label>
                        </td>
                        <td colspan="2">
                            <div class="row">
                                <div class="col-md-12" {{ $member->loan_remaining_amount }} {{ $member->id }}>
                                    <input type="hidden" id="share_amt" name="share_amt" value="{{ $member->share_ledger_account->current_balance }}">
                                    <input type="hidden" id="fixed_saving_amt" name="fixed_saving_amt" value="{{ $member->fixed_saving_ledger_account->current_balance }}">
                                    <input type="number"  class="form-control total_amount @error('total_amount') is-invalid @enderror" id="total_amount" name="total_amount" value="{{ $member->fixed_saving_ledger_account->current_balance + $member->share_ledger_account->current_balance - $member->loan_remaining_amount }}" placeholder="{{ __('Total amount') }}">
                                    @if ($errors->has('total_amount'))
                                        <span class="text-danger">{{ $errors->first('total_amount') }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><b>{{ __('Payment Type') }}</b></td>
                        <td colspan="2">
                            <input class="form-check-input payment_type ms-3" type="radio" name="payment_type" id="cash" checked="" value="cash" {{ old('payment_type') == 'cash' ? 'checked' : '' }} onchange="change_payment_type()">
                            <label class="form-check-label pl-2" for="cash">
                                {{ __('Cash') }}
                            </label>
                            <input class="form-check-input payment_type ms-3" type="radio" name="payment_type" id="cheque" value="cheque" onchange="change_payment_type()" {{ old('payment_type') == 'cheque' ? 'checked' : '' }}>
                            <label class="form-check-label pl-2" for="cheque">
                                {{ __('Cheque') }}
                            </label>
                        </td>
                    </tr>
                    <tr id="payment_details" style="display: {{ old('payment_type') == 'cheque' ? 'block' : 'none' }};">
                        <td colspan="2">
                            <div>
                                {{-- <div class="mb-3 row">
                                        <label for="amount"
                                            class="col-md-2 col-form-label text-md-end text-start">{{ __('Bank Name') }}</label>
                                        <div class="col-md-10">
                                            <input type="text"
                                                class="form-control @error('bank_name') is-invalid @enderror" id="bank_name"
                                                name="bank_name" value="{{ old('bank_name') }}"
                                                placeholder="{{ __('Bank Name') }}">
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
                        </td>
                    </tr>
                    {{-- @if ($member->fixed_saving_ledger_account->current_balance + $member->share_ledger_account->current_balance - $member->loan_remaining_amount < 0)
                        <tr>
                            <td colspan="2" class="text-danger">Note : You are resigning in nagative amount</td>
                        </tr>
                    @endif --}}

                </form>

            </tbody>
        </table>
    </div>
</div>
