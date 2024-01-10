@extends('layouts.app')
@section('content')
@section('title')
    {{ $page_title }}
@endsection
<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header border mb-4">
                    <div class="float-start">
                        <b>{{ __('Personal Details') }}</b>
                    </div>
                    <div class="float-end">
                        <a href="{{ route('members.index') }}" class="btn btn-primary btn-sm">&larr; {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('members.update', $user->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="registration_no">{{ __('Registration No') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="registration_no"
                                        class="form-control @error('registration_no') is-invalid @enderror"
                                        placeholder="{{ __('Registration No') }}" name="registration_no"
                                        value="{{ old('registration_no', $user->registration_no) }}">
                                    @if ($errors->has('registration_no'))
                                        <span class="text-danger">{{ $errors->first('registration_no') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="name" class="">{{ __('Name') }}</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->user->name) }}">
                                    @if ($errors->has('name'))
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="profile_picture">{{ __('Profile Picture') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="file" id="profile_picture" accept="image/jpg, image/png, image/gif, image/jpeg"
                                        class="form-control  @error('profile_picture') is-invalid @enderror"
                                        placeholder="{{ __('Profile Picture') }}" name="profile_picture">

                                        @if ($user->profile_picture)
                                            <a href="{{ asset($user->profile_picture) }}" class="float-end"
                                                target="_blank"><img src="{{ asset($user->profile_picture) }}" height="100" width="100"></a>
                                                {{-- {{ __('View') }} <i class="bi bi-eye-fill"></i>--}}
                                        @endif

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
                                    <div class="col-md-5 col-lg-3 form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="male"
                                            value="male"
                                            {{ old('gender', $user->gender) == 'male' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="male">
                                            {{ __('Male') }}
                                        </label>
                                    </div>
                                    <div class="col-md-5 col-lg-2  form-check">
                                        <input class="form-check-input" type="radio" name="gender"
                                            id="female"value="female"
                                            {{ old('gender', $user->gender) == 'female' ? 'checked' : '' }}>
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
                                    <label for="email-id-column">{{ __('Email') }}</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $user->user->email) }}"
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
                                    <input type="text" id="birthdate" class="date form-control @error('birthdate') is-invalid @enderror"
                                        placeholder="{{ __('Birth Date') }}" name="birthdate" value="{{ old('birthdate', $user->birthdate) }}">
                                        @if ($errors->has('birthdate'))
                                        <span class="text-danger">{{ $errors->first('birthdate') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="mobile_no">{{ __('Mobile No') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="mobile_no"
                                        class="form-control @error('mobile_no') is-invalid @enderror"
                                        placeholder="{{ __('Mobile No') }}" name="mobile_no"
                                        value="{{ old('mobile_no', $user->mobile_no) }}">
                                    @if ($errors->has('mobile_no'))
                                        <span class="text-danger">{{ $errors->first('mobile_no') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="whatsapp_no">{{ __('Whatsapp No') }}</label>
                                    <input type="number" id="whatsapp_no"
                                        class="form-control @error('whatsapp_no') is-invalid @enderror"
                                        placeholder="{{ __('Whatsapp No') }}" name="whatsapp_no"
                                        value="{{ old('whatsapp_no', $user->whatsapp_no) }}">
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
                                        placeholder="{{ __('Current Address') }}" name="current_address">{{ old('current_address', $user->current_address) }}</textarea>
                                    @if ($errors->has('current_address'))
                                        <span class="text-danger">{{ $errors->first('current_address') }}</span>
                                    @endif

                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="parmenant_address">{{ __('Parmenant Address') }}</label>
                                    <textarea id="parmenant_address" class="form-control  @error('parmenant_address') is-invalid @enderror"
                                        placeholder="{{ __('Parmenant Address') }}" name="parmenant_address">{{ old('parmenant_address', $user->parmenant_address) }}</textarea>
                                    @if ($errors->has('parmenant_address'))
                                        <span class="text-danger">{{ $errors->first('parmenant_address') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="signature">{{ __('Signature') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="file" id="signature" accept="image/jpg, image/png, image/gif, image/jpeg"
                                        class="form-control  @error('signature') is-invalid @enderror"
                                        placeholder="{{ __('Signature') }}" name="signature">

                                        @if ($user->signature)
                                            <a href="{{ asset($user->signature) }}" class="float-end"
                                                target="_blank"><img src="{{ asset($user->signature) }}" height="100" width="100"></a>
                                        @endif
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
                                    <label for="department">{{ __('Department') }}<span class="text-danger">*</span></label>
                                    <select class="choices form-select @error('department_id') is-invalid @enderror" aria-label="Permissions" id="department_id" name="department_id" style="height: 210px;">
                                        <option value="">{{__('Select Department')}}</option>

                                        @forelse ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ $department->id == old('department_id',$user->department_id)  ? 'selected' : '' }}>
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
                                    <label for="company">{{ __('Company') }}<span class="text-danger">*</span></label>
                                    <input type="text" id="company"
                                        class="form-control @error('company') is-invalid @enderror" name="company"
                                        placeholder="{{ __('Company') }}" value="{{ old('company', $user->company) }}">
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
                                        value="{{ old('designation', $user->designation) }}">
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
                                        value="{{ old('salary', $user->salary) }}">
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
                                        value="{{ old('da', $user->da) }}">
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
                            {{-- <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="age">{{ __('Age') }}<span class="text-danger">*</span></label>
                                    <input type="number" id="age"
                                        class="form-control @error('age') is-invalid @enderror"
                                        placeholder="{{ __('Age') }}" name="age"
                                        value="{{ old('age', $user->age) }}">
                                    @if ($errors->has('age'))
                                        <span class="text-danger">{{ $errors->first('age') }}</span>
                                    @endif
                                </div>
                            </div> --}}


                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="aadhar_card_no">{{ __('AadharCard No') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="aadhar_card_no" accept="image/jpg, image/png, image/gif, image/jpeg"
                                        class="form-control @error('aadhar_card_no') is-invalid @enderror"
                                        placeholder="{{ __('AadharCard No') }}" name="aadhar_card_no"
                                        value="{{ old('aadhar_card_no', $user->aadhar_card_no) }}">
                                    @if ($errors->has('aadhar_card_no'))
                                        <span class="text-danger">{{ $errors->first('aadhar_card_no') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="aadhar_card">{{ __('Aadhar card') }}</label>
                                    <input type="file" id="aadhar_card" accept="image/jpg, image/png, image/gif, image/jpeg"
                                        class="form-control @error('aadhar_card') is-invalid @enderror"
                                        placeholder="{{ __('aadhar_card') }}" name="aadhar_card">

                                        @if ($user->aadhar_card)
                                            <a href="{{ asset($user->aadhar_card) }}" class="float-end"
                                                target="_blank"><img src="{{ asset($user->aadhar_card) }}" height="100" width="180"></i></a>
                                        @endif

                                    @if ($errors->has('aadhar_card'))
                                        <span class="text-danger">{{ $errors->first('aadhar_card') }}</span>
                                    @endif

                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="pan_no">{{ __('PAN No') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="pan_no"
                                        class="form-control @error('pan_no') is-invalid @enderror"
                                        placeholder="{{ __('PAN No') }}" name="pan_no"
                                        value="{{ old('pan_no', $user->pan_no) }}">
                                    @if ($errors->has('pan_no'))
                                        <span class="text-danger">{{ $errors->first('pan_no') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="pan_card">{{ __('PAN Card') }}</label>
                                    <input type="file" id="pan_card" accept="image/jpg, image/png, image/gif, image/jpeg"
                                        class="form-control  @error('pan_card') is-invalid @enderror"
                                        placeholder="{{ __('PAN card') }}" name="pan_card">

                                        @if ($user->pan_card)
                                            <a href="{{ asset($user->pan_card) }}" class="float-end"
                                                target="_blank"><img src="{{ asset($user->pan_card) }}" height="100" width="180"></a>
                                        @endif

                                    @if ($errors->has('pan_card'))
                                        <span class="text-danger">{{ $errors->first('pan_card') }}</span>
                                    @endif

                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="department_id_proof">{{ __('Departmental ID Proof') }}</label>
                                    <input type="file" id="department_id_proof" accept="image/jpg, image/png, image/gif, image/jpeg"
                                        class="form-control @error('department_id_proof') is-invalid @enderror"
                                        placeholder="{{ __('Departmental ID Proof') }}" name="department_id_proof">

                                        @if (!empty($user->department_id_proof))
                                            <a href="{{ asset($user->department_id_proof) }}" class="float-end"
                                                target="_blank"><img src="{{ asset($user->department_id_proof) }}" height="100" width="180"></a>
                                        @endif

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
                                        value="{{ old('nominee_name', $user->nominee_name) }}">
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
                                        value="{{ old('nominee_relation', $user->nominee_relation) }}">
                                    @if ($errors->has('nominee_relation'))
                                        <span class="text-danger">{{ $errors->first('nominee_relation') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="witness_signature">{{ __('Witness Signature') }}</label>
                                    <input type="file" id="witness_signature" accept="image/jpg, image/png, image/gif, image/jpeg"
                                        class="form-control @error('witness_signature') is-invalid @enderror"
                                        placeholder="{{ __('Place') }}" name="witness_signature">

                                        @if ($user->witness_signature)
                                            <a href="{{ asset($user->witness_signature) }}" class="float-end"
                                                target="_blank"><img src="{{ asset($user->witness_signature) }}" height="100" width="100"></a>
                                        @endif

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
                                    <label for="saving_account_no">{{ __('Saving Account No') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="saving_account_no"
                                        class="form-control @error('saving_account_no') is-invalid @enderror"
                                        placeholder="{{ __('Saving Account No') }}" name="saving_account_no"
                                        value="{{ old('saving_account_no', $user->saving_account_no) }}">
                                    @if ($errors->has('saving_account_no'))
                                        <span class="text-danger">{{ $errors->first('saving_account_no') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="bank_name">{{ __('Bank Name') }}</label>
                                    <input type="text" id="bank_name" class="form-control @error('saving_account_no') is-invalid @enderror"
                                        placeholder="{{ __('Bank Name') }}" name="bank_name"  value="{{ old('bank_name', $user->bank_name) }}">
                                        @if ($errors->has('bank_name'))
                                        <span class="text-danger">{{ $errors->first('bank_name') }}</span>@endif
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="ifsc_code">{{ __('IFSC code') }}</label>
                                    <input type="text" id="ifsc_code" class="form-control @error('ifsc_code') is-invalid @enderror"
                                        placeholder="{{ __('IFSC code') }}" name="ifsc_code"  value="{{ old('ifsc_code', $user->ifsc_code) }}">
                                        @if ($errors->has('ifsc_code'))
                                        <span class="text-danger">{{ $errors->first('ifsc_code') }}</span>@endif
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="branch_address">{{ __('Branch Address') }}</label>
                                    <input type="text" id="branch_address" class="form-control @error('branch_address') is-invalid @enderror"
                                        placeholder="{{ __('Branch Address') }}" name="branch_address"  value="{{ old('branch_address', $user->branch_address) }}">
                                        @if ($errors->has('branch_address'))
                                        <span class="text-danger">{{ $errors->first('branch_address') }}</span>@endif
                                </div>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit"
                                    class="btn btn-primary me-1 mb-1">{{ __('Submit') }}</button>
                                <button type="reset"
                                    class="btn btn-light-secondary me-1 mb-1">{{ __('Reset') }}</button>
                            </div>
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
            altInput: true,
            altFormat:"d/m/Y",
            dateFormat: "Y-m-d",
         });
    </script>
@endpush
