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
                        <a href="{{ route('ledger_entries.index') }}" class="btn btn-primary btn-sm">&larr;
                            {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('ledger_entries.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        {{-- {{dd($prefill[0])}} --}}
                        {{-- for members of each department --}}
                        <div class="row mb-3">
                            <label for="month" class="col-md-4 col-form-label text-md-end">{{ __('Month') }}</label>
                            {{-- <label for="account_name"  class="col-md-4 col-form-label text-md-end">{{__('Department')}}</label> --}}
                            <div class="col-md-6">
                                <select class="choices form-select @error('month') is-invalid @enderror"
                                    aria-label="Permissions" id="month" name="month" style="height: 210px;">
                                    <option value="">{{ __('Select Month') }}</option>
                                    @foreach (getYearDropDown($current_year->id) as $item)
                                    <option value="{{ $item['value'] }}" {{ old('month',date('M-Y',strtotime('01-'.$prefill[0]->month))) ==  $item['month']  ? 'selected':''}}>{{  $item['month']  }}</option>
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
                            @foreach ($department->members as $member)
                                @if ($loop->first)
                                    <div class="row pb-4">
                                        <div class="col-md-3 col-3 col-lg-2">
                                            <b class="p-1 ">&nbsp;</b>
                                        </div>
                                        <div class="col-md-2 col-2">
                                            <b class="p-1 ">Principal</b>
                                        </div>
                                        <div class="col-md-2 col-2">
                                            <b class="p-3 ">Interest</b>
                                        </div>
                                        <div class="col-md-2 col-2">
                                            <b class="p-1 ">Fixed Saving</b>
                                        </div>
                                        <div class="col-md-2 col-2">
                                            <b class="p-1 ">MS</b>
                                        </div>
                                        <div class="col-md-2 col-2">
                                            <b class="p-1 ">Total</b>
                                        </div>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-md-3 col-3 col-lg-2">
                                        <div class="form-group">
                                            <label for="first-particular-column">{{ $member->user->name }}<span
                                                    class="text-danger">*</span></label>
                                            <input type="hidden" class="form-control" id="particular" name="user_id"
                                                value="{{ $member->user_id }}" placeholder="{{ __('particular') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-2">
                                        <div class="form-group">
                                            <input type="number" step="any"
                                                class="form-control @error('particular') is-invalid @enderror"
                                                id="particular" name="particular" value="{{ old('principal',$member->principal) }}"
                                                placeholder="{{ __('Principal') }}">
                                            @if ($errors->has('particular'))
                                                <span class="text-danger">{{ $errors->first('particular') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-2">
                                        <div class="form-group">
                                            <input type="number" step="any"
                                                class="form-control @error('interest') is-invalid @enderror"
                                                id="interest" name="interest" value="{{ old('interest',$member->interest) }}"
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
                                                id="fixed" name="fixed" value="{{ old('fixed',$member->fixed) }}"
                                                placeholder="{{ __('Fixed saving') }}">
                                            @if ($errors->has('fixed'))
                                                <span class="text-danger">{{ $errors->first('fixed') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-2">
                                        <div class="form-group">
                                            <input type="number" step="any"
                                                class="form-control @error('ms') is-invalid @enderror"
                                                id="ms" name="ms" value="{{ old('ms',$member->ms) }}"
                                                placeholder="{{ __('MS') }}">
                                            @if ($errors->has('ms'))
                                                <span class="text-danger">{{ $errors->first('ms') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-2">
                                        <div class="form-group">
                                            <input type="number" step="any" disabled
                                                class="form-control @error('total_amount') is-invalid @enderror"
                                                id="total_amount" name="total_amount" value="{{ old('total_amount',$member->total_amount) }}"
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
                                        <div class="col-md-2 col-2 text-end">
                                            <b class="p-1  ">{{$department->principal_total}}
                                            </b>
                                        </div>
                                        <div class="col-md-2 col-2 text-end">
                                            <b class="p-3 ">{{$department->interest_total}}</b>
                                        </div>
                                        <div class="col-md-2 col-2 text-end">
                                            <b class="p-1  ">{{$department->fixed_total}}</b>
                                        </div>
                                        <div class="col-md-2 col-2 text-end">
                                            <b class="p-1  ">{{$department->ms_total}}</b>
                                        </div>
                                        <div class="col-md-2 col-2 text-end">
                                            <b class="p-1  ">{{$department->sub_total}}</b>
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
                            <div class="row pb-4">
                                <div class="col-md-3 col-3 col-lg-2">
                                    <b class="p-1 ">{{ $department->department_name }}</b>
                                </div>
                                <div class="col-md-2 col-2">
                                    <input type="number" step="any" disabled
                                        class="form-control @error('principal_total') is-invalid @enderror" id="principal_total"
                                        name="principal_total" value="{{ old('principal_total',$department->principal_total) }}"
                                        placeholder="{{ __('Principal') }}">
                                </div>
                                <div class="col-md-2 col-2">
                                    <input type="number" step="any" disabled
                                        class="form-control @error('interest') is-invalid @enderror" id="interest"
                                        name="interest" value="{{ old('interest',$department->interest_total) }}"
                                        placeholder="{{ __('Interest') }}">
                                </div>
                                <div class="col-md-2 col-2">
                                    <input type="number" step="any" disabled
                                        class="form-control @error('fixed') is-invalid @enderror" id="fixed"
                                        name="fixed" value="{{ old('fixed',$department->fixed_total) }}"
                                        placeholder="{{ __('Fixed Saving') }}">
                                </div>
                                <div class="col-md-2 col-2">
                                    <input type="number" step="any" disabled
                                        class="form-control @error('ms_total') is-invalid @enderror" id="ms_total"
                                        name="ms_total" value="{{ old('ms_total',$department->ms_total) }}"
                                        placeholder="{{ __('MS') }}">
                                </div>
                                <div class="col-md-2 col-2">
                                    <input type="number" step="any" disabled
                                        class="form-control @error('sub_total') is-invalid @enderror" id="sub_total"
                                        name="sub_total" value="{{ old('sub_total',$department->sub_total) }}"
                                        placeholder="{{ __('Total') }}">
                                </div>
                            </div>
                            @if ($loop->last)
                            <div class="row pb-4">
                                <div class="col-md-3 col-3 col-lg-2">
                                    <b class="p-1  ">&nbsp;</b>
                                </div>
                                <div class="col-md-2 col-2 text-end">
                                    <b class="p-1  ">{{ $total['principal']}}
                                    </b>
                                </div>
                                <div class="col-md-2 col-2 text-end">
                                    <b class="p-3 ">{{ $total['interest']}}</b>
                                </div>
                                <div class="col-md-2 col-2 text-end">
                                    <b class="p-1  ">{{ $total['fixed']}}</b>
                                </div>
                                <div class="col-md-2 col-2 text-end">
                                    <b class="p-1  ">{{ $total['ms']}}</b>
                                </div>
                                <div class="col-md-2 col-2 text-end">
                                    <b class="p-1  ">{{ $total['sub_total']}}</b>
                                </div>
                            </div>
                        @endif
                        @endforeach
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
