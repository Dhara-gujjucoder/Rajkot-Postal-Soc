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
                    </div>
                    <div class="float-end">
                        <a href="{{ route('member_share.index') }}" class="btn btn-primary btn-sm">&larr;
                            {{ __('Back') }}</a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('member_share.store') }}" method="post">
                        @csrf

                        <div class="row mb-3">
                            <label for="member_id" class="col-md-4 col-form-label text-md-end">{{ __('Member') }}</label>
                            <div class="col-md-6">
                                <select class="choices form-select @error('member_id') is-invalid @enderror"  aria-label="Permissions" id="member_id" name="member_id" style="height: 210px;" onchange="load_member_details()">
                                    <option value="">{{ __('Select Member') }}</option>
                                    @forelse ($members as $key => $member)
                                        <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                            {{ $member->fullname }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                {{ __('Member total share') }} : <label id="member_share">{{ '0' }}</label>
                                                                 <label id="member_share_amt"></label>
                                @if ($errors->has('member_id'))
                                    <span class="text-danger">{{ $errors->first('member_id') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="opening_balance" class="col-md-4 col-form-label text-md-end text-start">{{ __('New share') }}</label>
                            <div class="col-md-6">
                            {{-- {{ dd($members); }} --}}
                                <input type="number" id="share" class="form-control @error('share') is-invalid @enderror" placeholder="{{ __('0') }}" name="share" value="">
                                {{ __('Amount') }} : <label id="display_s">0</label>

                                @if ($errors->has('share'))
                                    <span class="text-danger">{{ $errors->first('share') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="first-amount-column"
                                class="col-md-4 col-form-label text-md-end text-start">{{ __('Payment type') }}<span
                                    class="text-danger">*</span></label>
                            <div class="col-md-6 row p-2 ps-4">
                                <div class="col-md-4 col-lg-4 form-check">
                                    <input class="form-check-input" type="radio" name="form_type" id="yes"
                                        value="1" checked>
                                    <label class="form-check-label" for="yes">
                                        {{ __('Case') }}
                                    </label>
                                </div>
                                <div class="col-md-4 col-lg-4 form-check">
                                    <input class="form-check-input" type="radio" name="form_type" id="no"
                                        value="2" >
                                    <label class="form-check-label" for="no">
                                        {{ __('Bank') }}
                                    </label>
                                </div>
                            </div>
                            @if ($errors->has('form_type'))
                                <span class="text-danger">{{ $errors->first('form_type') }}</span>
                            @endif
                        </div>

                        <div id="cheque_number_form">
                            <div class="mb-3 row">
                                <label for="opening_balance" class="col-md-4 col-form-label text-md-end text-start">{{ __('Cheque number') }}</label>
                                <div class="col-md-6">
                                    <input type="number" id="cheque_number" class="form-control" placeholder="{{ __('Cheque number') }}" name="cheque_number" value="">

                                    @if ($errors->has('cheque_number'))
                                        <span class="text-danger">{{ $errors->first('cheque_number') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="{{ __('Add Share') }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('script')
<script>

    //*****Member selection change total share*****
    function load_member_details() {
        // $('#loader').show();
        var member_id = $('#member_id').val();
            console.log(member_id);
        // disableoption($('#member_id'), member_id);
        var url = "{{ route('member.get', ':id') }}";
        url = url.replace(':id', member_id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            url: url,
            type: 'GET',
            dataType: 'JSON',
            success: function(data) {
                if (data.success == true) {
                    var member = data.member;
                    $('#member_share').html(member.shares.length);
                    var sum = 0;
                    if(member.shares.length){

                        for (let i = 0; i < member.shares.length; i++ ) {
                            sum +=  member.shares[i].share_amount;
                        }
                    }
                    $('#member_share_amt').html(sum);
                    // $('#loader').hide();
                }
            }
        });
    }



    // ***** New share total purpose *****
        let ShareAmount = Number('{{ current_share_amount()->share_amount }}');

        $(document).ready(function() {
            $('#share').on('input', function() {
                var totalShare = $(this).val();
                var totalValue = totalShare * ShareAmount;
                $('#display_s').html('&#8377; ' + totalValue);
            });
        });



    // ***** Payment type Bank *****
        $('#cheque_number_form').hide();
        $('input[name="form_type"]').on('change', function() {
            $('#cheque_number_form').hide();
            $('#acc_form').hide();
            if ($(this).val() == 2) {
                $('#cheque_number_form').show();
            } else {
                $('#acc_form').hide();
            }
        });



</script>
@endpush
