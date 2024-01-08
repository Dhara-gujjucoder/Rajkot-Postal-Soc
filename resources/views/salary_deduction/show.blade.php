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
                    <div class="float-start">
                        {{-- User Information --}}
                    </div>
                    <div class="float-end">
                        <a href="{{ route('salary_deduction.index') }}" class="btn btn-primary btn-sm">&larr; {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3 row">
                        <label for="name"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Name') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $salary_deduction->user->name }}
                        </div>
                    </div>
                    
                    
                    
                   
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Month-Year') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ date('M-Y',strtotime('01-'.$salary_deduction->month.'-'.$salary_deduction->year)) }}
                        </div>
                    </div>

                  
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Department') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $salary_deduction->account_type_id }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Rec No.') }}:</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $salary_deduction->rec_no }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Principal') }} (Rs.):</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $salary_deduction->principal }}
                        </div>
                        
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                        class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Interest') }} (Rs.):</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $salary_deduction->interest }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                        class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Fixed Monthly Saving') }} (Rs.):</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $salary_deduction->fixed }}
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="email"
                        class="col-md-4 col-form-label text-md-end text-start"><strong>{{ __('Amount') }} (Rs.):</strong></label>
                        <div class="col-md-6" style="line-height: 35px;">
                            {{ $salary_deduction->total_amount }}
                        </div>
                    </div>
                    
                    

                    
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
