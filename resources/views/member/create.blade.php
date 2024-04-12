@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header border mb-4">
                    <div class="float-start">
                        <b>{{ __('Personal Details') }}</b>
                    </div>
                    <div class="float-end">
                        <a href="{{ route('members.index') }}" class="btn btn-primary btn-sm">&larr;
                            {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('members.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="registration_no">{{ __('Registration No') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="registration_no"
                                        class="form-control @error('registration_no') is-invalid @enderror"
                                        placeholder="{{ __('Registration No') }}" name="registration_no"
                                        value="{{ old('registration_no') }}">
                                    @if ($errors->has('registration_no'))
                                        <span class="text-danger">{{ $errors->first('registration_no') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="first-name-column">{{ __('Name') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}"
                                        placeholder="{{ __('Name') }}">
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="profile_picture">{{ __('Profile Picture') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="file" id="profile_picture"
                                        class="form-control  @error('profile_picture') is-invalid @enderror"
                                        placeholder="{{ __('Profile Picture') }}" name="profile_picture"
                                        accept="image/jpg, image/png, image/gif, image/jpeg">
                                    @if ($errors->has('profile_picture'))
                                        <span class="text-danger">{{ $errors->first('profile_picture') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="row">
                                    <div class="col-md-2 form-check">
                                        <label for="Gender">{{ __('Gender') }}<span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="row ms-lg-5 p-2 ps-4">
                                    <div class="col-md-5 col-lg-2 form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="male"
                                            value="male" checked="">
                                        <label class="form-check-label" for="male">
                                            {{ __('Male') }}
                                        </label>
                                    </div>
                                    <div class="col-md-5 col-lg-2  form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="female"
                                            value="female">
                                        <label class="form-check-label" for="female">
                                            {{ __('Female') }}
                                        </label>
                                    </div>
                                    @if ($errors->has('gender'))
                                        <span class="text-danger">{{ $errors->first('gender') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="email-id-column">{{ __('Email') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}"
                                        placeholder="{{ __('Email') }}">
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="birthdate">{{ __('Birth Date') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="birthdate" value="{{ date('Y-m-d') }}"
                                        class="form-control date @error('birthdate') is-invalid @enderror"
                                        style="" placeholder="{{ __('Birth Date') }}">
                                    @if ($errors->has('birthdate'))
                                        <span class="text-danger">{{ $errors->first('birthdate') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mobile_no">{{ __('Mobile No') }}<span class="text-danger">*</span></label>
                                <input type="number" id="mobile_no"
                                    class="form-control @error('mobile_no') is-invalid @enderror"
                                    placeholder="{{ __('Mobile No') }}" name="mobile_no"
                                    value="{{ old('mobile_no') }}">
                                @if ($errors->has('mobile_no'))
                                    <span class="text-danger">{{ $errors->first('mobile_no') }}</span>
                                @endif
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="whatsapp_no">{{ __('Whatsapp No') }}</label>
                                    <input type="number" id="whatsapp_no"
                                        class="form-control @error('whatsapp_no') is-invalid @enderror"
                                        placeholder="{{ __('Whatsapp No') }}" name="whatsapp_no"
                                        value="{{ old('whatsapp_no') }}">
                                    @if ($errors->has('whatsapp_no'))
                                        <span class="text-danger">{{ $errors->first('whatsapp_no') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="current_address">{{ __('Current Address') }}<span
                                            class="text-danger">*</span></label>
                                    <textarea id="current_address" class="form-control  @error('current_address') is-invalid @enderror"
                                        placeholder="{{ __('Current Address') }}" name="current_address">{{ old('current_address') }}</textarea>
                                    @if ($errors->has('current_address'))
                                        <span class="text-danger">{{ $errors->first('current_address') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="parmenant_address">{{ __('Parmenant Address') }}<span
                                            class="text-danger">*</span></label>
                                    <textarea id="parmenant_address" class="form-control  @error('parmenant_address') is-invalid @enderror"
                                        placeholder="{{ __('Parmenant Address') }}" name="parmenant_address">{{ old('parmenant_address') }}</textarea>
                                    @if ($errors->has('parmenant_address'))
                                        <span class="text-danger">{{ $errors->first('parmenant_address') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="signature">{{ __('Signature') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="file" id="signature"
                                        class="form-control @error('signature') is-invalid @enderror"
                                        placeholder="{{ __('Signature') }}" name="signature"
                                        accept="image/jpg, image/png, image/gif, image/jpeg">
                                    @if ($errors->has('signature'))
                                        <span class="text-danger">{{ $errors->first('signature') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="card-header border mb-4 mt-4">
                                <div class="float-start">
                                    <b>{{ __('Work Details') }}</b>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="department">{{ __('Department') }}<span
                                            class="text-danger">*</span></label>
                                    <select class="choices form-select @error('department_id') is-invalid @enderror"
                                        aria-label="Permissions" id="department_id" name="department_id"
                                        style="height: 210px;">
                                        <option value="">{{ __('Select Department') }}</option>

                                        @forelse ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ $department->id == old('department_id') ? 'selected' : '' }}>
                                                {{ $department->department_name }}
                                            </option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @if ($errors->has('department_id'))
                                        <span class="text-danger">{{ $errors->first('department_id') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="company">{{ __('Company') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="company"
                                        class="form-control @error('company') is-invalid @enderror" name="company"
                                        value="{{ old('company') }}" placeholder="{{ __('Company') }}">
                                    @if ($errors->has('company'))
                                        <span class="text-danger">{{ $errors->first('company') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="designation">{{ __('Designation') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="designation"
                                        class="form-control @error('designation') is-invalid @enderror"
                                        placeholder="{{ __('Designation') }}" name="designation"
                                        value="{{ old('designation') }}">
                                    @if ($errors->has('designation'))
                                        <span class="text-danger">{{ $errors->first('designation') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="salary">{{ __('Salary') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="salary"
                                        class="form-control @error('salary') is-invalid @enderror"
                                        placeholder="{{ __('Salary') }}" name="salary"
                                        value="{{ old('salary') }}">
                                    @if ($errors->has('salary'))
                                        <span class="text-danger">{{ $errors->first('salary') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="da">{{ __('DA') }}</label>
                                    <input type="number" id="da"
                                        class="form-control @error('da') is-invalid @enderror"
                                        placeholder="{{ __('Salary DA') }}" name="da"
                                        value="{{ old('da') }}">
                                    @if ($errors->has('da'))
                                        <span class="text-danger">{{ $errors->first('da') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="card-header border mb-4 mt-4">
                                <div class="float-start">
                                    <b>{{ __('Document Details') }}</b>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="aadhar_card_no">{{ __('Aadhar Card No') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="aadhar_card_no"
                                        class="form-control @error('aadhar_card_no') is-invalid @enderror"
                                        placeholder="{{ __('AadharCard No') }}" name="aadhar_card_no"
                                        value="{{ old('aadhar_card_no') }}">
                                    @if ($errors->has('aadhar_card_no'))
                                        <span class="text-danger">{{ $errors->first('aadhar_card_no') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="aadhar_card">{{ __('Aadhar card') }}</label>
                                    <input type="file" id="aadhar_card"
                                        class="form-control @error('aadhar_card') is-invalid @enderror"
                                        placeholder="{{ __('Whatsapp No') }}" name="aadhar_card"
                                        accept="image/jpg, image/png, image/gif, image/jpeg">
                                    @if ($errors->has('aadhar_card'))
                                        <span class="text-danger">{{ $errors->first('aadhar_card') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="pan_no">{{ __('PAN No') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="pan_no"
                                        class="form-control @error('pan_no') is-invalid @enderror"
                                        placeholder="{{ __('PAN No') }}" name="pan_no"
                                        value="{{ old('pan_no') }}">
                                    @if ($errors->has('pan_no'))
                                        <span class="text-danger">{{ $errors->first('pan_no') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="pan_card">{{ __('PAN Card') }}</label>
                                    <input type="file" id="pan_card"
                                        class="form-control  @error('pan_card') is-invalid @enderror"
                                        placeholder="{{ __('PAN No') }}" name="pan_card"
                                        accept="image/jpg, image/png, image/gif, image/jpeg">
                                    @if ($errors->has('pan_card'))
                                        <span class="text-danger">{{ $errors->first('pan_card') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="department_id_proof">{{ __('Departmental ID Proof') }}</label>
                                    <input type="file" id="department_id_proof"
                                        class="form-control @error('department_id_proof') is-invalid @enderror"
                                        placeholder="{{ __('Departmental ID Proof') }}" name="department_id_proof"
                                        accept="image/jpg, image/png, image/gif, image/jpeg">
                                    @if ($errors->has('department_id_proof'))
                                        <span class="text-danger">{{ $errors->first('department_id_proof') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="card-header border mb-4 mt-4">
                                <div class="float-start">
                                    <b>{{ __('Other Details') }}</b>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="nominee_name">{{ __('Nominee Name') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="nominee_name"
                                        class="form-control @error('nominee_name') is-invalid @enderror"
                                        placeholder="{{ __('Nominee Name') }}" name="nominee_name"
                                        value="{{ old('nominee_name') }}">
                                    @if ($errors->has('nominee_name'))
                                        <span class="text-danger">{{ $errors->first('nominee_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="nominee_relation">{{ __('Nominee Relation') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="nominee_relation"
                                        class="form-control @error('nominee_relation') is-invalid @enderror"
                                        placeholder="{{ __('Nominee Relation') }}" name="nominee_relation"
                                        value="{{ old('nominee_relation') }}">
                                    @if ($errors->has('nominee_relation'))
                                        <span class="text-danger">{{ $errors->first('nominee_relation') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="witness_signature">{{ __('Witness Signature') }}</label>
                                    <input type="file" id="witness_signature"
                                        class="form-control @error('witness_signature') is-invalid @enderror"
                                        placeholder="{{ __('Place') }}" name="witness_signature"
                                        accept="image/jpg, image/png, image/gif, image/jpeg">
                                    @if ($errors->has('witness_signature'))
                                        <span class="text-danger">{{ $errors->first('witness_signature') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="card-header border mb-4 mt-4">
                                <div class="float-start">
                                    <b>{{ __('Bank Details') }}</b>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="saving_account_no">{{ __('Saving Account No') }}</label>
                                    <input type="number" id="saving_account_no"
                                        class="form-control @error('saving_account_no') is-invalid @enderror"
                                        placeholder="{{ __('Saving Account No') }}" name="saving_account_no"
                                        value="{{ old('saving_account_no') }}">
                                    @if ($errors->has('saving_account_no'))
                                        <span class="text-danger">{{ $errors->first('saving_account_no') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="bank_name">{{ __('Bank Name') }}</label>
                                    <input type="text" id="bank_name"
                                        class="form-control @error('saving_account_no') is-invalid @enderror"
                                        placeholder="{{ __('Bank Name') }}" name="bank_name"
                                        value="{{ old('bank_name') }}">
                                    @if ($errors->has('bank_name'))
                                        <span class="text-danger">{{ $errors->first('bank_name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="ifsc_code">{{ __('IFSC code') }}</label>
                                    <input type="text" id="ifsc_code"
                                        class="form-control @error('ifsc_code') is-invalid @enderror"
                                        placeholder="{{ __('IFSC code') }}" name="ifsc_code"
                                        value="{{ old('ifsc_code') }}">
                                    @if ($errors->has('ifsc_code'))
                                        <span class="text-danger">{{ $errors->first('ifsc_code') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="branch_address">{{ __('Branch Address') }}</label>
                                    <input type="text" id="branch_address"
                                        class="form-control @error('branch_address') is-invalid @enderror"
                                        placeholder="{{ __('Branch Address') }}" name="branch_address"
                                        value="{{ old('branch_address') }}">
                                    @if ($errors->has('branch_address'))
                                        <span class="text-danger">{{ $errors->first('branch_address') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="total_share">{{ __('Total Share') }}</label>
                                    <input type="number" id="total_share" onchange="calculate()"
                                        class="form-control @error('total_share') is-invalid @enderror"
                                        placeholder="{{ __('Total Share') }}" name="total_share"
                                        value="{{ old('total_share', $minimum_share) }}">
                                    <label style="color: red;">*{{ __('Minimum Share') }}:
                                        {{ $minimum_share }}</label>
                                    @if ($errors->has('total_share'))
                                        <span class="text-danger">{{ $errors->first('total_share') }}</span>
                                    @endif
                                </div>
                            </div>

                        

                        </div>
                        <div class="col-md-6 col-12">&nbsp;
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>{{ __('Register Fee') }}:</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input class="form-control ms-3" type="number" step="any" name="member_fee"
                                        value="{{ $setting->registration_fee }}" id="reg_fee" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>{{ __('Share Amount') }}:</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input class="form-control ms-3" type="number" step="any" id="share_amt" readonly name="share_amt">
                                </div>
                                <div class="col-md-4">
                                    <label>{{ __('Total') }}:</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input class="form-control ms-3" type="number" step="any" id="total" readonly name="total">

                                </div>

                                <div class="col-md-4">
                                    <label><b>{{ __('Payment Type') }}</b></label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input class="form-check-input payment_type ms-3" type="radio"
                                        name="payment_type" id="cash" checked="" value="cash"
                                        {{ old('payment_type') == 'cash' ? 'checked' : '' }}
                                        onchange="change_payment_type()">
                                    <label class="form-check-label pl-2" for="cash">
                                        {{ __('Cash') }}
                                    </label>
                                    <input class="form-check-input payment_type ms-3" type="radio"
                                        name="payment_type" id="cheque" value="cheque"
                                        onchange="change_payment_type()"
                                        {{ old('payment_type') == 'cheque' ? 'checked' : '' }}>
                                    <label class="form-check-label pl-2" for="cheque">
                                        {{ __('Cheque') }}
                                    </label>
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-8 form-group" id="payment_details"
                                    style="display: {{ old('payment_type') == 'cheque' ? 'block' : 'none' }};">
                                        <input type="number"
                                            class="form-control ms-3 @error('cheque_no') is-invalid @enderror"
                                            id="cheque_no" name="cheque_no" value="{{ old('cheque_no') }}"
                                            placeholder="{{ __('Cheque No.') }}">
                                        @if ($errors->has('cheque_no'))
                                            <span class="text-danger">{{ $errors->first('cheque_no') }}</span>
                                        @endif
                                </div>
                            </div>
                        </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit"
                                    class="btn btn-primary me-1 mb-1">{{ __('Submit') }}</button>
                                <button type="reset"
                                    class="btn btn-light-secondary me-1 mb-1">{{ __('Reset') }}</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script type="text/javascript">

    $('.date').flatpickr({
        allowInput: true,
        altInput: true,
        altFormat: "d/m/Y",
        dateFormat: "Y-m-d",
    });

    function change_payment_type() {
        $('#payment_details').hide();
        var payment_type = $('input[name="payment_type"]:checked').val();
        if (payment_type == 'cheque') {
            $('#payment_details').show();
        }
    }
    function calculate() {
        var share = $('#total_share').val();
        var amt = '{{current_share_amount()->share_amount}}';
        var total_share = share*amt;
        var reg_fee = Number($('#reg_fee').val());
        $('#share_amt').val(total_share);
        $('#total').val(reg_fee+total_share);
    }
    calculate();
</script>
@endpush
