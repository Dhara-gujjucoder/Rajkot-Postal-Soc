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
                        <a href="{{ route('ledger_account.index') }}" class="btn btn-primary btn-sm">&larr; {{__('Back')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('ledger_account.store') }}" method="post">
                        @csrf
                        <div class="mb-3 row">
                            <label for="account_name" class="col-md-4 col-form-label text-md-end text-start">{{__('Account Type')}}</label>
                            <div class="col-md-6">
                                <select class="choices form-select @error('account_type_id') is-invalid @enderror"
                                    aria-label="Permissions" id="account_type_id" name="account_type_id"
                                    style="height: 210px;">
                                    @forelse ($account_types as $account_type)
                                        <option value="{{ $account_type->id }}"
                                            {{ $account_type->id == old('account_type_id')  ? 'selected' : '' }}>
                                            {{ $account_type->type_name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                @if ($errors->has('account_type_id'))
                                    <span class="text-danger">{{ $errors->first('account_type_id') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="account_name" class="col-md-4 col-form-label text-md-end text-start">{{__('Ledger Account')}}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('account_name') is-invalid @enderror"
                                    id="account_name" name="account_name" value="{{ old('account_name') }}">
                                @if ($errors->has('account_name'))
                                    <span class="text-danger">{{ $errors->first('account_name') }}</span>
                                @endif
                            </div>
                        </div>


                        <div class="mb-3 row">
                            <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="{{__('Add Ledger Account')}}">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
