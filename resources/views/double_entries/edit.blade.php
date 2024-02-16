@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <form action="{{ route('double_entries.update', $master_entry->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
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
                                    <input type="text" class="form-control @error('entry_id') is-invalid @enderror" id="entry_id" name="entry_id" value="{{ $master_entry->entry_id }}" readonly placeholder="{{ __('ID') }}">
                                    @if ($errors->has('entry_id'))
                                        <span class="text-danger">{{ $errors->first('entry_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3 col-3">
                                <div class="form-group">
                                    <label for="first-amount-column">{{ __('Date') }}<span class="text-danger">*</span></label>
                                    <input type="date" class="form-control date required @error('date') is-invalid @enderror" id="date" name="date" value="{{ $master_entry->date }}" placeholder="{{ __('Date') }}">
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
                                    <textarea class="form-control required @error('description') is-invalid @enderror" placeholder="{{ __('Description') }}" name="description">{{ $master_entry->description }}</textarea>
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
                            <button type="button" class="btn btn-outline-warning" onclick="add_ledger_entry()">Add</button>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach ($master_entry->meta_entry as $key => $meta_entry)
                            <div class="row ledger_entry">
                                <div class="col-md-4 col-3">
                                    <div class="form-group">
                                        <label for="ledger_ac_id">{{ __('Ledger Account') }}<span class="text-danger">*</span></label>
                                        <select id="ledgerAccountId" class="select2 ledgerAccountId required @error('ledger_ac_id') is-invalid @enderror" row="{{$key}}" aria-label="Permissions" name="ledger_ac_id[]" data-column="2" style="height: 210px;">
                                            <option value="">{{ __('Select Ledger Account') }}</option>
                                            @forelse ($ledger_accounts as $ac)
                                                <option value="{{ $ac->id }}" ledger_grp_id="{{ $ac->ledger_group_id }}" {{ $ac->id == $meta_entry->ledger_ac_id ? 'selected' : '' }}>
                                                    {{ $ac->account_name }}
                                                </option>
                                            @empty
                                            @endforelse
                                        </select>
                                        @if ($errors->has('ledger_ac_id.*'))
                                            <span class="text-danger">{{ $errors->first('ledger_ac_id.*') }}</span>
                                        @endif
                                    </div>
                                    <div class="share_input{{$key}}"  style="display:none;" ><input type="number" name="share[]"  placeholder="Add Share"></div>
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
                                        <input type="text" class="form-control required @error('particular') is-invalid @enderror" id="particular" name="particular[]" value="{{ $meta_entry->particular }}" placeholder="{{ __('Particular') }}">
                                        @if ($errors->has('particular.*'))
                                            <span class="text-danger">{{ $errors->first('particular.*') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-2 col-2">
                                    <div class="form-group transaction">
                                        <div class="row pt-4">
                                            <div class="col-md-6 col-lg-6 form-check">
                                                <input class="form-check-input" type="radio" name="type[{{ $key }}]" value="debit" {{ $meta_entry->type == 'debit' ? 'checked' : '' }} onchange="calculate()">
                                                <label class="form-check-label" for="yes">
                                                    {{ __('Debit') }}
                                                </label>
                                            </div>
                                            <div class="col-md-6 col-lg-6 form-check">
                                                <input class="form-check-input" type="radio" name="type[{{ $key }}]" value="credit" {{ $meta_entry->type == 'credit' ? 'checked' : '' }} onchange="calculate()">
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
                                        <input type="number" step="any" class="form-control required @error('amount') is-invalid @enderror" id="amount" name="amount[]" value="{{ $meta_entry->amount }}" oninput="calculate()" placeholder="{{ __('Amount') }}">
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
                        @endforeach

                        <div class="text-end">
                            <span class="text-danger" id="not_count_match"></span>
                        </div>
                        <div class="text-end">
                            Total Debit : <p id="debit_total"><span>&#8377; 0</span></p>
                            {{-- (₹) : 0 --}}
                        </div>
                        <div class="text-end">
                            Total Credit : <p id="credit_total"><span>&#8377; 0</span></p>
                        </div>

                        <div class="mb-3 row justify-content-end">
                            <button type="submit" id="submit" class="col-md-1 offset-md-5 btn btn-primary" onclick="return checktotal()"><i class="fa fa-lock"></i>{{ __('Update') }}</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
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
        //     $('#not_count_match').html('Debit and Credit must be Equal.');
        // }
    }
    calculate();
    function checktotal() {
    // all fields check not any blank if blank then error msg show
        var valid = 0;
        $('.required').each(function(){
            if(!this.value){
                valid = 1;
            }
        });
        if(valid){
             $('#not_count_match').html('All fields are Required.');
            return false;
        }
    //end

        if (cr_total != dr_total) {
            // alert('Debit and Credit must be Equal.');
            $('#not_count_match').html('Debit and Credit must be Equal.');
            return false;
        } else {
            $('#not_count_match').html('');
            return true;
        }
    }

    //end


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

     // *****Ledger Account select dropdown*****
        // $(document).ready(function () {
            $(document).on('change','.ledgerAccountId',function(e){
                // $(this).parent().find('.share_input'+$(this).val()).remove();
                // console.log($(this).parent());
                var selectedValue = $(this).find('option:selected').attr('ledger_grp_id');
                var row =  $(this).attr('row');
                $(".share_input"+row).hide();

                $(".share_input"+row).find('[name="share[]"]').length;

                $(this).val();
                console.log(row);
                if (selectedValue == "2") {
                    $(".share_input"+row).show();
                } else {
                    $(".share_input"+row).hide();
                }
            });
        // });
    // *****End*****

    var count = Number('{{ $master_entry->meta_entry()->count() }}');

    function add_ledger_entry() {

        var html = `<div class="row ledger_entry">
                            <div class="col-md-4 col-3">
                                <div class="form-group">
                                    <label for="ledger_ac_id">{{ __('Ledger Account') }}<span
                                            class="text-danger">*</span></label>
                                    <select class="select2 ledgerAccountId required @error('ledger_ac_id') is-invalid @enderror"
                                        aria-label="Permissions" name="ledger_ac_id[]" row="`+count+`" data-column="2"
                                        style="height: 210px;">
                                        <option value="">{{ __('Select Ledger Account') }}</option>
                                        @forelse ($ledger_accounts as $ac)
                                            <option value="{{ $ac->id }}" ledger_grp_id="{{ $ac->ledger_group_id }}"
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
                                <div class="share_input`+count+`"  style="display:none;" ><input type="number" name="share[]"  placeholder="Add Share"></div>
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
                                        class="form-control required @error('amount') is-invalid @enderror" id="amount"
                                        name="amount[]" value="{{ old('amount[]') }}" oninput="calculate()"
                                        placeholder="{{ __('Amount') }}">
                                    @if ($errors->has('amount'))
                                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>`;
        count = count + 1;
        $('.ledger_entry').last().after(html);
        $('.select2').select2();
    }
</script>
@endpush
