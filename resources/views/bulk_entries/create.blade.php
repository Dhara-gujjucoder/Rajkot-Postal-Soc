@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header mb-4">
                    {{-- <div class="float-start">
                        <b>{{ __('Ledger Entry') }}</b>
                    </div> --}}
                    <div class="float-end">
                        <a href="{{ route('bulk_entries.index') }}" class="btn btn-primary btn-sm">&larr;
                            {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('bulk_entries.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        {{-- for members of each department --}}
                        <div class="row mb-3">
                            <label for="month"
                                class="col-md-4 col-form-label text-md-end">{{ __('Month') }}</label>
                            {{-- <label for="account_name"  class="col-md-4 col-form-label text-md-end">{{__('Department')}}</label> --}}
                            <div class="col-md-6">
                                {{-- {{dd($months)}} --}}
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
                                        <div class="col-md-1 col-1"> <b class="p-1 ">{{ __('Loan Settlement') }}</b>
                                        </div>
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
                                                    id="ms_value" name="ms_value"
                                                    value="{{ old('ms_value') }}"
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
                                            <label for="first-particular-column">{{ $member->user->name }}<span
                                                    class="text-danger" data-bs-html="true" data-bs-toggle="tooltip"
                                                    data-bs-original-title="<em>Loan Details</em> <p>Member Name : Test User</p><p>Amount : 200</p>">&nbsp;<i
                                                        class="bi bi-info-circle-fill"></i></span></label>
                                            <input type="hidden" class="form-control" id="particular" name="user_id[]"
                                                value="{{ $member->user_id }}" placeholder="{{ __('particular') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-1">
                                        <input class="form-check-input" type="checkbox"
                                            name="is_loan_settle_{{ $member->user_id }}" value="1"
                                            id="is_loan_settle_{{ $member->user_id }}"
                                            {{ old('is_loan_settle_' . $member->user_id) ? 'checked' : '' }}>
                                    </div>
                                    <div class="col-md-2 col-2">
                                        <div class="form-group">
                                            <input type="number" step="any"
                                                class="form-control @error('principal') is-invalid @enderror"
                                                id="principal_{{ $department->id }}_{{ $member->user_id }}"
                                                oninput="calculate_total()"
                                                name="principal_{{ $department->id }}_{{ $member->user_id }}"
                                                value="{{ old('principal', $member->principal) }}"
                                                placeholder="{{ __('Principal') }}">
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
                                        value="{{ old('exact_amount_' . $department->id) }}"
                                        placeholder="{{ __('Exact amount') }}">
                                </div>
                                <div class="col-md-2 col-2">
                                    <input type="number" step="any"
                                        class="form-control @error('cheque_no') is-invalid @enderror"
                                        id="cheque_no_{{ $department->id }}" name="cheque_no_{{ $department->id }}"
                                        value="{{ old('cheque_no_' . $department->id) }}"
                                        placeholder="{{ __('Cheque No.') }}">
                                </div>
                                <div class="col-md-1 col-1">
                                    <input type="number" step="any" readonly
                                        class="form-control @error('principal_total') is-invalid @enderror"
                                        id="summary_principal_total_{{ $department->id }}"
                                        name="summary_principal_total_{{ $department->id }}"
                                        value="{{ old('principal_total', $department->principal_total) }}"
                                        placeholder="{{ __('Principal') }}">
                                </div>
                                <div class="col-md-1 col-2">
                                    <input type="number" step="any" readonly
                                        class="form-control @error('interest') is-invalid @enderror"
                                        id="summary_interest_total_{{ $department->id }}"
                                        name="summary_interest_total_{{ $department->id }}"
                                        value="{{ old('summary_interest_total_', $department->interest_total) }}"
                                        placeholder="{{ __('Interest') }}">
                                </div>
                                <div class="col-md-1 col-1">
                                    <input type="number" step="any" readonly
                                        class="form-control @error('fixed') is-invalid @enderror"
                                        id="summary_fixed_total_{{ $department->id }}"
                                        name="summary_fixed_total_{{ $department->id }}"
                                        value="{{ old('summary_fixed', $department->fixed_total) }}"
                                        placeholder="{{ __('Fixed Saving') }}">
                                </div>
                                <div class="col-md-1 col-1">
                                    <input type="number" step="any" readonly
                                        class="form-control @error('ms_total') is-invalid @enderror"
                                        id="summary_ms_total_{{ $department->id }}"
                                        name="summary_ms_total_{{ $department->id }}"
                                        value="{{ old('ms_total', $department->ms_total) }}"
                                        placeholder="{{ __('MS') }}">
                                </div>
                                <div class="col-md-2 col-2">
                                    <input type="number" step="any" readonly
                                        class="form-control @error('total_amount_') is-invalid @enderror"
                                        id="summary_total_amount_total_{{ $department->id }}"
                                        name="summary_total_amount_total_{{ $department->id }}"
                                        value="{{ old('total_amount', $department->total_amount_total) }}"
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
                                            {{ 'Pending' }}</option>
                                        <option value="{{ '2' }}"
                                            {{ old('status') == '2' ? 'selected' : '' }}>
                                            {{ 'Completed' }}</option>
                                    </select>
                                </fieldset>
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
</div>
@endsection
@push('script')
<script>
    var departments = @json($departments);
    var previous = $("#status").val();

    $('#status').change(function() {
        if ($(this).val() == 2) {
            var sure = confirm("{{ __('Are you sure about compelete this month and its verification?') }}");
            if (!sure) {
                $('#status').val(previous);
            }
        }
    });

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
                    .user_id].toFixed(2));

                ledger_type.forEach(ledger => {

                    totals[ledger + department.id] += Number($('#' + ledger + department
                        .id + '_' + member.user_id).val());
                    // console.log('#' + ledger + 'total_' + department.id);
                    $('#' + ledger + 'total_' + department.id).text(totals[ledger + department
                        .id].toFixed(2));

                });
            });
            ledger_type.forEach(ledger2 => {
                $('#summary_' + ledger2 + 'total_' + department.id).val(totals[ledger2 +
                    department.id].toFixed(2));
                totals[ledger2 + 'final'] += totals[ledger2 + department.id];
            });
        });
        ledger_type.forEach(ledger3 => {
            $('#smr_' + ledger3 + 'total').text(totals[ledger3 + 'final'].toFixed(2));
        });

    }
</script>
@endpush
