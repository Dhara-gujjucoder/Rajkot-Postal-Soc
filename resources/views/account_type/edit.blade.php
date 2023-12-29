@extends('layouts.app')
@section('content')
@section('title')
{{ $page_title }}
@endsection
<section class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">
                    <div class="float-end">
                        <a href="{{ route('account_type.index') }}" class="btn btn-primary btn-sm">&larr; {{__('Back')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('account_type.update', $account_type->id) }}" method="post">
                        @csrf
                        @method('PUT')

                      
                        <div class="mb-3 row">
                            <label for="type_name" class="col-md-4 col-form-label text-md-end text-start">{{__('Account Type')}}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('type_name') is-invalid @enderror"
                                    id="type_name" name="type_name" value="{{ $account_type->type_name }}">
                                @if ($errors->has('type_name'))
                                    <span class="text-danger">{{ $errors->first('type_name') }}</span>
                                @endif
                            </div>
                        </div>
                    

                        <div class="mb-3 row">
                            <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="{{__('Update Account Type')}}">
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
