@extends('layouts.app')
@push('style')
    <style>
        .custom-tooltip {
            --bs-tooltip-min-width: 1000px !important;
            --bs-tooltip-max-width: 90000px !important;
        }
    </style>
@endpush
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header clearfix mb-1">
                    {{-- <div class="float-start">
                        <b>{{ __('Double Entry') }}</b>
                    </div> --}}


                    <div class="float-end">
                        <a href="{{ route('bulk_entries.index') }}" class="btn btn-primary btn-sm">&larr;
                            {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('bulk_entries.store') }}" method="post" enctype="multipart/form-data"
                        id="bulkform">
                        @csrf
                        <div class="w-100 float-start mb-5">
                            <div class="row mb-1">
                                <label for="month"
                                    class="col-md-2 col-form-label text-md-end">{{ __('Month') }}</label>
                                {{-- <label for="account_name" class="col-md-4 col-form-label text-md-end">{{__('Department')}}</label> --}}
                                <div class="col-md-6">
                                    <select class="choices form-select @error('month') is-invalid @enderror"
                                        aria-label="Permissions" id="month" name="month" style="height: 210px;">
                                        <option value="">{{ __('Select Month') }}</option>
                                        @foreach ($months as $item)
                                            <option value="{{ $item['value'] }}"
                                                {{ $item['is_disable'] == 1 ? 'disabled' : '' }}
                                                {{ old('month', $next_month) == $item['value'] ? 'selected' : '' }}>
                                                {{ $item['month'] }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('month'))
                                        <span class="text-danger">{{ $errors->first('month') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- for members of each department --}}

                        @foreach ($departments as $mainkey => $department)
                            <div class="row">
                                <div class="card-header border mb-4">
                                    <div class="float-start">
                                        <b>{{ $department->department_name }}</b>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="departments[]" value="{{ $department->id }}">
                            @foreach ($department->members as $member)
                                @if ($loop->first)
                                    <div class="row pb-4">
                                        <div class="col-md-3 col-3 col-lg-2">
                                            <b class="p-1 ">&nbsp;</b>
                                        </div>

                                        {{-- <div class="col-md-1 col-1">
                                            <b class="p-1 ">{{ __('Loan Settlement') }}</b>
                                        </div> --}}


                                        <div class="col-md-2 col-2">
                                            <b class="p-1 ">{{ __('Principal') }}</b>
                                        </div>
                                        <div class="col-md-2 col-2">
                                            <b class="p-3 ">{{ __('Interest') }}</b>
                                        </div>
                                        <div class="col-md-2 col-2">
                                            <b class="p-1 ">{{ __('Fixed Saving') }}</b>
                                        </div>
                                        <div class="col-md-1 col-1">
                                            <b class="p-1 pe-3">{{ __('MS') }}</b>
                                            @if ($loop->parent->first)
                                                <input class="form-check-input" type="checkbox" name="is_ms_applicable"
                                                    value="1" id="is_ms_applicable"
                                                    {{ old('is_ms_applicable') ? 'checked' : '' }}
                                                    onchange="apply_ms()"> <label class="form-check-label"
                                                    for="ms_available">
                                                    &nbsp;{{ __('Is applicable ?') }}
                                                </label>

                                                <input type="number" step="any"
                                                    style="display: {{ old('ms_value') ? 'block' : 'none' }};"
                                                    class="form-control mt-3 @error('ms_value') is-invalid @enderror"
                                                    id="ms_value" name="ms_value" value="{{ old('ms_value') }}"
                                                    oninput="apply_ms()" placeholder="{{ __('MS Amount') }}">
                                            @endif
                                        </div>
                                        <div class="col-md-2 col-2">
                                            <b class="p-1 ">{{ __('Total') }}</b>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">

                                    <div class="col-md-3 col-3 col-lg-2">
                                        <div class="form-group">
                                            <label for="first-particular-column">{{ $member->name }}
                                                @if ($member->loan)
                                                    <div class="ctooltip text-danger">&nbsp;<i
                                                            class="bi bi-info-circle-fill"></i>
                                                        <span class="tooltiptext text-start p-3">
                                                            <p class="text-info text-xl">{{ __('Loan Detail') }}</p>
                                                            <div class="row">
                                                                <div class="col-md-4">{{ __('Member') }}</div>
                                                                <div class="col-md-8">{{ $member->name }}</div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4">{{ __('Loan ID') }}</div>
                                                                <div class="col-md-8">
                                                                    {{ $member->loan->loan_no ?? '' }}</div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-4">{{ __('Principal Amount') }}
                                                                </div>
                                                                <div class="col-md-8">
                                                                    {{ $member->loan->principal_amt ?? 0 }}</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">{{ __('EMI Amount') }}</div>
                                                                <div class="col-md-8">
                                                                    {{ $member->loan->emi_amount ?? 0 }}</div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">{{ __('Pending EMI') }}</div>
                                                                <div class="col-md-8">
                                                                    {{ $member->loan->loan_emis()->pending()->get()->count() ?? 0 }}
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-4">{{ __('Paid EMI') }}</div>
                                                                <div class="col-md-8">
                                                                    {{ $member->loan->loan_emis()->paid()->get()->count() }}
                                                                </div>
                                                            </div>
                                                        </span>
                                                    </div>
                                                @endif
                                            </label>
                                            <input type="hidden" class="form-control" id="particular" name="user_id[]"
                                                value="{{ $member->user_id }}" placeholder="{{ __('particular') }}">
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-1 col-1">
                                        @if ($member->loan)
                                            <input class="form-check-input" type="checkbox"
                                                name="is_loan_settle_{{ $member->user_id }}" value="1"
                                                id="is_loan_settle_{{ $member->user_id }}"
                                                onclick="if(this.checked){getLoan({{ $member->id }})}"
                                                {{ old('is_loan_settle_' . $member->user_id) ? 'checked' : '' }}>
                                        @endif
                                    </div> --}}

                                    <div class="col-md-2 col-2">
                                        <div class="form-group">
                                            <input type="number" step="any"
                                                class="form-control @error('principal') is-invalid @enderror"
                                                id="principal_{{ $department->id }}_{{ $member->user_id }}"
                                                oninput="calculate_total()"
                                                name="principal_{{ $department->id }}_{{ $member->user_id }}"
                                                value="{{ old('principal', $member->principal) }}"
                                                placeholder="{{ __('Principal') }}" >
                                            @if ($errors->has('principal'))
                                                <span class="text-danger">{{ $errors->first('principal') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-2">
                                        <div class="form-group">
                                            <input type="number" step="any"
                                                class="form-control @error('interest') is-invalid @enderror"
                                                id="interest_{{ $department->id }}_{{ $member->user_id }}"
                                                oninput="calculate_total()"
                                                name="interest_{{ $department->id }}_{{ $member->user_id }}"
                                                value="{{ old('interest', $member->interest) }}"
                                                placeholder="{{ __('Interest') }}">
                                                <input type="hidden"
                                                name="emi_id_{{ $department->id }}_{{ $member->user_id }}"
                                                value="{{ $member->emi_id }}">
                                            @if ($errors->has('interest'))
                                                <span class="text-danger">{{ $errors->first('interest') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-2">
                                        <div class="form-group">
                                            <input type="number" step="any"
                                                class="form-control @error('fixed') is-invalid @enderror"
                                                id="fixed_{{ $department->id }}_{{ $member->user_id }}"
                                                oninput="calculate_total()"
                                                name="fixed_{{ $department->id }}_{{ $member->user_id }}"
                                                value="{{ old('fixed', $member->fixed) }}"
                                                placeholder="{{ __('Fixed saving') }}">
                                            @if ($errors->has('fixed'))
                                                <span class="text-danger">{{ $errors->first('fixed') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-1">
                                        <div class="form-group">
                                            <input type="number" step="any" tabindex="-1"
                                                class="form-control ms_value @error('ms') is-invalid @enderror"
                                                id="ms_{{ $department->id }}_{{ $member->user_id }}"
                                                oninput="calculate_total()"
                                                name="ms_{{ $department->id }}_{{ $member->user_id }}"
                                                value="{{ old('ms', $member->ms) }}" readonly
                                                placeholder="{{ __('MS') }}">
                                            @if ($errors->has('ms_{{ $department->id }}_{{ $member->id }}'))
                                                <span
                                                    class="text-danger">{{ $errors->first('ms_' . $department->id . '_' . $member->id) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-2">
                                        <div class="form-group">
                                            <input type="number" step="any" readonly
                                                class="form-control @error('total_amount') is-invalid @enderror"
                                                id="total_amount_{{ $department->id }}_{{ $member->user_id }}"
                                                name="total_amount_{{ $department->id }}_{{ $member->user_id }}"
                                                value="{{ old('total_amount', $member->total_amount) }}"
                                                placeholder="{{ __('Total') }}">
                                            @if ($errors->has('total_amount'))
                                                <span class="text-danger">{{ $errors->first('total_amount') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if ($loop->last)
                                    <div class="row pb-4">
                                        <div class="col-md-3 col-3 col-lg-2">
                                            <b class="p-1  ">&nbsp;</b>
                                        </div>
                                        <div class="col-md-1 col-1">
                                            <b class="p-1  ">&nbsp;</b>
                                        </div>

                                        <div class="col-md-2 col-2 text-end">
                                            <b class="p-1"
                                                id="principal_total_{{ $department->id }}">{{ $department->principal_total }}
                                            </b>
                                        </div>
                                        <div class="col-md-2 col-2 text-end">
                                            <b class="p-3"
                                                id="interest_total_{{ $department->id }}">{{ $department->interest_total }}</b>
                                        </div>
                                        <div class="col-md-2 col-2 text-end">
                                            <b class="p-1"
                                                id="fixed_total_{{ $department->id }}">{{ $department->fixed_total }}</b>
                                        </div>
                                        <div class="col-md-1 col-1 text-end">
                                            <b class="p-1"
                                                id="ms_total_{{ $department->id }}">{{ $department->ms_total }}</b>
                                        </div>
                                        <div class="col-md-2 col-2 text-end">
                                            <b class="p-1"
                                                id="total_amount_total_{{ $department->id }}">{{ $department->total_amount_total }}</b>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach

                        {{-- for summry of each department --}}
                        <div class="row">
                            <div class="card-header border mb-4">
                                <div class="float-start">
                                    <b>{{ __('Summary') }}</b>
                                </div>
                            </div>
                        </div>
                        @foreach ($departments as $department)
                            @if ($loop->first)
                                <div class="row pb-4">
                                    <div class="col-md-3 col-3 col-lg-2">
                                        <b class="p-1 ">&nbsp;</b>
                                    </div>
                                    <div class="col-md-2 col-2 col-lg-2">
                                        <b class="p-1 ">{{ __('Exact Amount') }}</b>
                                    </div>
                                    <div class="col-md-2 col-2 col-lg-2">
                                        <b class="p-1 ">{{ __('Cheque No.') }}</b>
                                    </div>
                                    <div class="col-md-1 col-1">
                                        <b class="p-1 ">{{ __('Principal') }}</b>
                                    </div>
                                    <div class="col-md-1 col-1">
                                        <b class="p-3 ">{{ __('Interest') }}</b>
                                    </div>
                                    <div class="col-md-1 col-1">
                                        <b class="p-1 ">{{ __('Fixed Saving') }}</b>
                                    </div>
                                    <div class="col-md-1 col-1">
                                        <b class="p-1 ">{{ __('MS') }}</b>
                                    </div>
                                    <div class="col-md-2 col-2">
                                        <b class="p-1 ">{{ __('Total') }}</b>
                                    </div>
                                </div>
                            @endif
                            <div class="row pb-4">
                                <div class="col-md-3 col-3 col-lg-2">
                                    <b class="p-1 ">{{ $department->department_name }}</b>
                                </div>
                                <div class="col-md-2 col-2">
                                    <input type="number" step="any"
                                        class="form-control @error('amount') is-invalid @enderror"
                                        id="exact_amount_{{ $department->id }}"
                                        name="exact_amount_{{ $department->id }}"
                                        value="{{ old('exact_amount_'.$department->id, $department->total_amount_total) }}"
                                        placeholder="{{ __('Exact amount') }}">
                                </div>
                                <div class="col-md-2 col-2">
                                    <input type="number" step="any"
                                        class="form-control @error('cheque_no') is-invalid @enderror"
                                        id="cheque_no_{{ $department->id }}"
                                        name="cheque_no_{{ $department->id }}"
                                        value="{{ old('cheque_no_'.$department->id) }}"
                                        placeholder="{{ __('Cheque No.') }}">
                                </div>
                                <div class="col-md-1 col-1">
                                    <input type="number" step="any" readonly
                                        class="form-control @error('principal_total') is-invalid @enderror"
                                        id="summary_principal_total_{{ $department->id }}"
                                        name="summary_principal_total_{{ $department->id }}"
                                        value="{{ old('summary_principal_total_'.$department->id, $department->principal_total) }}"
                                        placeholder="{{ __('Principal') }}">
                                </div>
                                <div class="col-md-1 col-2">
                                    <input type="number" step="any" readonly
                                        class="form-control @error('interest') is-invalid @enderror"
                                        id="summary_interest_total_{{ $department->id }}"
                                        name="summary_interest_total_{{ $department->id }}"
                                        value="{{ old('summary_interest_total_'.$department->id, $department->interest_total) }}"
                                        placeholder="{{ __('Interest') }}">
                                </div>
                                <div class="col-md-1 col-1">
                                    <input type="number" step="any" readonly
                                        class="form-control @error('fixed') is-invalid @enderror"
                                        id="summary_fixed_total_{{ $department->id }}"
                                        name="summary_fixed_total_{{ $department->id }}"
                                        value="{{ old('summary_fixed_total_'.$department->id, $department->fixed_total) }}"
                                        placeholder="{{ __('Fixed Saving') }}">
                                </div>
                                <div class="col-md-1 col-1">
                                    <input type="number" step="any" readonly
                                        class="form-control @error('ms_total') is-invalid @enderror"
                                        id="summary_ms_total_{{ $department->id }}"
                                        name="summary_ms_total_{{ $department->id }}"
                                        value="{{ old('summary_ms_total_'.$department->id, $department->ms_total) }}"
                                        placeholder="{{ __('MS') }}">
                                </div>
                                <div class="col-md-2 col-2">
                                    <input type="number" step="any" readonly
                                        class="form-control @error('total_amount_') is-invalid @enderror"
                                        id="summary_total_amount_total_{{ $department->id }}"
                                        name="summary_total_amount_total_{{ $department->id }}"
                                        value="{{ old('summary_total_amount_total_'.$department->id, $department->total_amount_total) }}"
                                        placeholder="{{ __('Total') }}">
                                </div>
                            </div>
                            @if ($loop->last)
                                <div class="row pb-4">
                                    <div class="col-md-3 col-3 col-lg-2">
                                        <b class="p-1  ">&nbsp;</b>
                                    </div>
                                    <div class="col-md-2 col-2 text-end">
                                        <b class="p-1 ">&nbsp;</b>
                                    </div>
                                    <div class="col-md-2 col-2 text-end">
                                        <b class="p-1 ">&nbsp;</b>
                                    </div>
                                    <div class="col-md-1 col-1 text-end">
                                        <b class="p-1 " id="smr_principal_total">{{ $total['principal'] }}</b>
                                    </div>
                                    <div class="col-md-1 col-1 text-end">
                                        <b class="p-3 " id="smr_interest_total">{{ $total['interest'] }}</b>
                                    </div>
                                    <div class="col-md-1 col-1 text-end">
                                        <b class="p-1  " id="smr_fixed_total">{{ $total['fixed'] }}</b>
                                    </div>
                                    <div class="col-md-1 col-1 text-end">
                                        <b class="p-1  " id="smr_ms_total">{{ $total['ms'] }}</b>
                                    </div>
                                    <div class="col-md-2 col-2 text-end">
                                        <b class="p-1  "
                                            id="smr_total_amount_total">{{ $total['total_amount'] }}</b>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        <div class="row mb-3">
                            <label for="status"
                                class="col-md-2 col-form-label text-md-end"><b>{{ __('Status') }}</b></label>
                            <div class="col-md-6 mb-4">
                                <fieldset class="form-group">
                                    <select class="form-select" id="status" name="status"
                                        @error('status') is-invalid @enderror>
                                        <option value="">{{ __('Select Status') }}</option>
                                        <option value="{{ '1' }}" selected
                                            {{ old('status') == '1' ? 'selected' : '' }}>
                                            {{ 'Draft' }}</option>
                                        <option value="{{ '2' }}"
                                            {{ old('status') == '2' ? 'selected' : '' }}>
                                            {{ 'Completed' }}</option>
                                    </select>
                                </fieldset>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <input type="button" class="col-md-3 offset-md-5 btn btn-primary"
                                value="{{ __('Submit') }}" id="submitbtn" onclick="validateStatus()">
                        </div>
                        <button type="button" class="btn btn-outline-primary block" style="display:none"
                            data-bs-toggle="modal" data-bs-target="#exampleModalCenter" id="notmatch"></button>
                        <div class="modal fade text-left" id="exampleModalCenter" tabindex="-1"
                            aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="myModalLabel1">{{ __('Amount Confirmation') }}
                                        </h5>
                                        <button type="button" class="close rounded-pill" data-bs-dismiss="modal"
                                            aria-label="Close">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-x">
                                                <line x1="18" y1="6" x2="6" y2="18">
                                                </line>
                                                <line x1="6" y1="6" x2="18" y2="18">
                                                </line>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p id="not_matched_dept">

                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn" data-bs-dismiss="modal">
                                            <i class="bx bx-x d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">{{ __('No') }}</span>
                                        </button>
                                        <button type="button" id="still_continue" class="btn btn-primary ms-1"
                                            data-bs-dismiss="modal">
                                            <i class="bx bx-check d-block d-sm-none"></i>
                                            <span class="d-none d-sm-block">{{ __('Yes') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade text-left" id="loan_settle" tabindex="-1"
                            aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                                <div class="modal-content">
                                    <form id="loan_close">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="myModalLabel1">{{ __('Loan Settlement') }}
                                            </h5>
                                            <button type="button" class="close rounded-pill" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-x">
                                                    <line x1="18" y1="6" x2="6"
                                                        y2="18">
                                                    </line>
                                                    <line x1="6" y1="6" x2="18"
                                                        y2="18">
                                                    </line>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <table class="table table-bordered">
                                                <input type="hidden" id="loan_id">
                                                <tbody id="loan_details_settle">
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn" data-bs-dismiss="modal">
                                                <i class="bx bx-x d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">{{ __('Cancel') }}</span>
                                            </button>
                                            <button type="button" class="btn btn-primary ms-1"
                                                onclick="close_loan()">
                                                <i class="bx bx-check d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">{{ __('Close Loan') }}</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@push('script')
<script>
    var departments = @json($departments);
    $('#still_continue').on('click', function() {
        $('#bulkform').trigger('submit');
    });

    function validateStatus() {
        var status = $("#status").val();
        if (status == 2) {
            var not_matched_dept =
                `<div>{{ __('Exact amount of following deparments') }}<br><br><table class="table table-bordered"><tr><th>{{ __('Department') }}</th><th>{{ __('Total') }}</th><th>{{ __('Exact Amount') }}</th></tr>`;
            departments.forEach(department => {
                var dept_total = $('#summary_total_amount_total_' + department.id).val();
                var exact_amt = $('#exact_amount_' + department.id).val();
                if (dept_total > exact_amt) {
                    not_matched_dept += `<tr><td><span >` + department.department_name +
                        `&nbsp;</span></td><td>` + dept_total + `</td><td><span class="text-danger">` +
                        exact_amt + `</span></td></tr>`;
                }
            });
            not_matched_dept += `</table><br>` +
                `{{ __('are not matched with its total, still are you sure want to continue ?') }}`;

            $('#not_matched_dept').html(not_matched_dept);
            $('#notmatch').trigger('click');

            return false;
        } else {
            $('#bulkform').trigger('submit');
        }
    }

    function apply_ms() {
        $('#ms_value').hide();
        $('.ms_value').attr('readonly', true);
        if ($('#is_ms_applicable').is(":checked")) {
            $('#ms_value').show();
            $('.ms_value').attr('readonly', false);
            $('.ms_value').val($('#ms_value').val());
        } else {
            $('.ms_value').attr('readonly', true);
            $('.ms_value').val(0);
        }
        calculate_total();
    }

    function calculate_total() {
        var totals = [];
        var ledger_type = ['ms_', 'fixed_', 'principal_', 'interest_', 'total_amount_'];
        ledger_type.forEach(lg => {
            totals[lg + 'final'] = 0;
        });

        departments.forEach(department => {
            totals['ms_' + department.id] = 0;
            totals['fixed_' + department.id] = 0;
            totals['principal_' + department.id] = 0;
            totals['interest_' + department.id] = 0;
            totals['total_amount_' + department.id] = 0;
            department.members.forEach(member => {

                // for member total
                totals['member_' + member.user_id] = 0;

                totals['member_' + member.user_id] += (Number($('#ms_' + department.id + '_' + member
                    .user_id).val()) + Number($('#fixed_' + department.id + '_' + member
                    .user_id).val()) + Number($('#principal_' + department.id + '_' + member
                    .user_id).val())) + Number($('#interest_' + department.id + '_' + member
                    .user_id).val());
                $('#total_amount_' + department.id + '_' + member.user_id).val(totals['member_' + member
                    .user_id].toFixed(0));

                ledger_type.forEach(ledger => {

                    totals[ledger + department.id] += Number($('#' + ledger + department
                        .id + '_' + member.user_id).val());
                    // console.log('#' + ledger + 'total_' + department.id);
                    $('#' + ledger + 'total_' + department.id).text(totals[ledger + department
                        .id].toFixed(0));

                });
            });
            ledger_type.forEach(ledger2 => {
                $('#summary_' + ledger2 + 'total_' + department.id).val(totals[ledger2 +
                    department.id].toFixed(0));

                    $('#exact_amount_'+department.id).val(totals[ledger2 +
                    department.id].toFixed(0));
                totals[ledger2 + 'final'] += totals[ledger2 + department.id];
            });
        });
        ledger_type.forEach(ledger3 => {
            $('#smr_' + ledger3 + 'total').text(totals[ledger3 + 'final'].toFixed(0));
        });

    }

    function getLoan(member_id) {

            $('#loader').show();
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
                        var html = `<tr>
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
                                    <td>` + ((member_loan.loan_emis.length)) +
                            `</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr>    <td colspan="6">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="2"> <b>{{ __('Remaining Loan') }}</b></td>
                                    <td colspan="4"><input type="number" class="form-control amount" name="amount_settle" placeholder="{{ __('Remaining Loan') }}" id="remaining_loan_settle" min="` +
                            member.loan_remaining_amount + `"  value="` +
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
                                `;
                        $('#loan_details_settle').html(html);
                        $('#loan_settle').modal('show');
                        $('#loan_id').val(member_loan.id);
                        // console.log(remaining_share);
                        $('#loader').hide();
                        $('#remaining_loan').focus();

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

    function close_loan() {
        var loan_id = $('#loan_id').val();
        var amount = $('#remaining_loan_settle').val();
        var bank_name = $('#bank_name').val();
        var cheque_no = $('#cheque_no').val();
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
                "payment_type": payment_type
            },
            success: function(data) {
                show_success(data.msg);
                // table.ajax.reload();
                location.reload();
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
</script>
@endpush
