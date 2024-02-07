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
                        <b>{{ __('Double Entry Detail') }}</b>
                    </div>
                    <div class="float-end">
                        <a href="{{ route('double_entries.index') }}" class="btn btn-primary btn-sm">&larr; {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('double_entries.update', $ledger_entry->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">

                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="ledger_ac_id">{{ __('Ledger') }}<span class="text-danger">*</span></label>
                                    <select class="choices form-select @error('ledger_ac_id') is-invalid @enderror" aria-label="Permissions" id="ledger_ac_id" name="ledger_ac_id" style="height: 210px;" ">
                                             @forelse ($ledger_accounts as $ac)
                                                <option value="{{ $ac->id }}" {{ $ac->id == old('ledger_ac_id', $ledger_entry->ledger_ac_id) ? 'selected' : '' }}>
                                                    {{ $ac->account_name }}
                                                </option>
                                            @empty
                                            @endforelse
                                    </select>
                                    @if ($errors->has('ledger_ac_id'))
                                        <span class="text-danger">{{ $errors->first('ledger_ac_id') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="first-particular-column">{{ __('Particular') }}<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('particular') is-invalid @enderror" id="particular" name="particular" value="{{ old('particular', $ledger_entry->particular) }}" placeholder="{{ __('particular') }}">
                                    @if ($errors->has('particular'))
                                        <span class="text-danger">{{ $errors->first('particular') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="first-amount-column">{{ __('Amount') }}<span class="text-danger">*</span></label>
                                    <input type="number" step="any" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" value="{{ old('amount', $ledger_entry->amount) }}" placeholder="{{ __('Amount') }}">
                                    @if ($errors->has('amount'))
                                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="first-amount-column">{{ __('Date') }}<span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" placeholder="{{ __('Date') }}">
                                    @if ($errors->has('date'))
                                        <span class="text-danger">{{ $errors->first('date') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="{{ __('Submit') }}">
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
