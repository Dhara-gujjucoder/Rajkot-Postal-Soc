@extends('layouts.app')
@section('content')
@section('title'){{ $page_title }}@endsection
<section class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">
                    <div class="float-start">

                    </div>
                    <div class="float-end">
                        <a href="{{ route('ledger_group.index') }}" class="btn btn-primary btn-sm">&larr; {{__('Back')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('ledger_group.store') }}" method="post">
                        @csrf

                        <div class="mb-3 row">
                            <label for="parent_id" class="col-md-4 col-form-label text-md-end text-start">{{ __('Parent Ledger Group') }}</label>
                            <div class="col-md-6">
                                <select class="choices form-select" aria-label="Permissions" id="parent_id" name="parent_id" style="height: 210px;" ">
                                    <option value="0">{{ __('Select Parent Ledger Group') }}</option>
                                    @foreach ($ledgers as $ledger)
                                        <option value="{{ $ledger->id }}">{{ $ledger->ledger_group }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="ledger_group" class="col-md-4 col-form-label text-md-end text-start">{{__('Ledger Group')}}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('ledger_group') is-invalid @enderror"
                                    id="ledger_group" name="ledger_group" value="{{ old('ledger_group') }}">
                                @if ($errors->has('ledger_group'))
                                    <span class="text-danger">{{ $errors->first('ledger_group') }}</span>
                                @endif
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="{{__('Add Ledger Group')}}">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
