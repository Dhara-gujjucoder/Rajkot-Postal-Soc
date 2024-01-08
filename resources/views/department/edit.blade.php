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
                        <a href="{{ route('department.index') }}" class="btn btn-primary btn-sm">&larr; {{__('Back')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('department.update', $department->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3 row">
                            <label for="department_name" class="col-md-4 col-form-label text-md-end text-start">{{__('Department Name')}}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('department_name') is-invalid @enderror"
                                    id="department_name" name="department_name" value="{{ $department->department_name }}">
                                @if ($errors->has('department_name'))
                                    <span class="text-danger">{{ $errors->first('department_name') }}</span>
                                @endif
                            </div>
                        </div>
                       
                        <div class="mb-3 row">
                            <label for="ledger_group_id" class="col-md-4 col-form-label text-md-end text-start">{{__('Ledger Group')}}</label>
                            <div class="col-md-6">
                                <select class="choices form-select @error('ledger_group_id') is-invalid @enderror"
                                    aria-label="Permissions" id="ledger_group_id" name="ledger_group_id"
                                    style="height: 210px;">
                                    @forelse ($ledger_groups as $ledger_group)
                                        <option value="{{ $ledger_group->id }}"
                                            {{ $ledger_group->id == old('ledger_group_id',$department->ledger_group_id)  ? 'selected' : '' }}>
                                            {{ $ledger_group->type_name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                @if ($errors->has('ledger_group_id'))
                                    <span class="text-danger">{{ $errors->first('ledger_group_id') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="{{__('Update Department')}}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
