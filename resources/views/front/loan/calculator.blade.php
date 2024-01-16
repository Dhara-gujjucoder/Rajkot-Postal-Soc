@extends('front.layouts.app')
@section('content')
    <style>

    </style>
    <div class="dashboard-detail-page calculator-detail-page">
        <div class="container">

            <form action="{{ route('user.loan.sendmail') }}" method="post">
                @csrf
                <div class="dashboard-box-listing">
                    <div class="desh-listbox skybluebg-box wow fadeInDown" data-wow-delay="0.2s">
                        <div class="dash-box-title">{{ __('Calculate your Personal Loan EMIs') }}</div>
                    </div>
                    <div class="row wow fadeInUp" data-wow-delay="0.2s">
                        <div class="col-md-12">
                            <div class="dashboard-detail-data">
                                <label class="col-form-label"><strong>{{ __('Loan Amount') }} :</strong></label>
                                <div class="col-form-info">
                                    <form action="/">
                                        <div id="comission" class="range-slider">
                                            <label class="final-value"><span id="comissionLabel"></span></label>
                                            <input type="hidden" id="loan_amount" name="loan_amount">
                                            <input type="text" id="sampleSlider" oninput="cal()">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="dashboard-detail-data">
                                <label class="col-form-label"><strong>{{ __('EMI Amount') }} :</strong></label>
                                <div class="col-form-info pt-0">
                                    <div class="range-slider">
                                        <input type="number" class="emi-account" id="emi_amount" name="emi_amount">
                                    </div>
                                    <div class="min-amount" id="amt_min" style="color: red; display:none;">
                                        <label>*{{ __('Minimum Amount') }}:</label><input class="w3-input w3-border-0" type="text" id="min_amt" style="border: none;color: red;" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="dashboard-detail-data">
                                <label class="col-form-label"><strong>{{ __('Interest Rate') }} :</strong></label>
                                <div class="col-form-info pt-0">
                                    <div class="range-slider">
                                        <label class="final-value">{{ $loan_interest }} %</label>
                                        <input type="hidden" value="{{$loan_interest}}%" name="loan_interest">
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="desh-listbox skybluebg-box wow fadeInDown" data-wow-delay="0.2s">
                            <div class="dash-box-title">{{ __('Personal Details') }}</div>
                        </div>

                        <div class="row wow fadeInUp" data-wow-delay="0.2s">
                            <div class="col-md-6">
                                <div class="dashboard-detail-data">
                                <label class="col-form-label"><strong>{{ __('Name') }} :</strong></label>
                                <div class="col-form-info">{{ $user->name }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="dashboard-detail-data">
                                <label class="col-form-label"><strong>{{ __('PAN Number') }} :</strong></label>
                                <div class="col-form-info">{{ $member->pan_no }}</div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="dashboard-detail-data">
                                <label class="col-form-label"><strong>{{ __('Present Address') }} :</strong></label>
                                <div class="col-form-info">{{ $member->parmenant_address }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="desh-listbox skybluebg-box pt-2 mt-4 wow fadeInDown" data-wow-delay="0.2s">
                            <div class="dash-box-title">{{ __('Details of other Liabilities') }}</div>
                        </div>
                        <div class="row wow fadeInUp" data-wow-delay="0.2s">
                            <div class="col-md-6">
                                <div class="dashboard-detail-data">
                                <label class="col-form-label"><strong>{{ __('Current loan') }} :</strong></label>
                                <div class="col-form-info">&#8377; 0</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="dashboard-detail-data">
                                <label class="col-form-label"><strong>{{ __('Remaining Loan') }} :</strong></label>
                                <div class="col-form-info">&#8377; 0</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="dashboard-detail-data">
                                <label class="col-form-label"><strong>{{ __('Share') }} :</strong></label>
                                <div class="col-form-info">&#8377; 0</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="dashboard-detail-data">
                                <label class="col-form-label"><strong>{{ __('Required share') }} :</strong></label>
                                <div class="col-form-info" id="required_share">&#8377; data placed hear</div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="row mt-5 justify-content-center skybluebg-box wow fadeInUp" data-wow-delay="0.2s">
                        <div class="col-md-8">
                            <div class="calculator-details">
                                <div class="calculator__info d-flex justify-content-between blue-text-dark">
                                    <div class="calculator__info-text radius-12 text-center range-slider">
                                        <p class="f-12 mb-0">{{ __('EMI Month(Around)') }}</p>
                                        <p id="emi_month"><span>12</span>*</p>
                                        <input type="hidden" id="emi_month_around" name="emi_month_around">
                                    </div>
                                    <div class="calculator__info-text radius-12 text-center range-slider">
                                        <p class="f-12 mb-0">{{ __('EMI Amount') }}</p>
                                        <p id="emi_amount_label"><span>&#8377; 4,645</span>*</p>
                                        <input type="hidden" id="emi_amount_lab" name="emi_amount_lab">
                                    </div>
                                    <div class="calculator__info-text text-center">
                                        <p class="f-12 mb-0">{{ __('Interest Payable') }}</p>
                                        <p id="interest_payable"><span>&#8377; 11,469</span>*</p>
                                        <input type="hidden" id="interest_pay" name="interest_pay">
                                    </div>
                                </div>
                                <button id="myButton" class="btn btn-primary w-100">{{ __('Apply for Loan') }}</button>
                                {{-- <div class="text-center" id="myButton"><a href="#" class="btn btn-primary w-100">Apply for Loan</a>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('script')
    <script>
        var slider2 = new rSlider({
            target: '#sampleSlider',
            values: [{{ implode(',', $amount) }}],
            range: false,
            tooltip: false,
            scale: true,
            step: 0.1,
            width: 480,
            labels: true,
            set: [0],

            onChange: function(vals) {
                cal();
            }
        });
    </script>

    <script>
        let interestRate = Number('{{ $loan_interest }}'); // Interest rate
        let min_value;
        let amount;
        let emi_month;

        $('#emi_amount').on('input', function() {
            console.log($('#emi_amount').val() + '__' + min_value);

            if ($('#emi_amount').val() < min_value) {
                $('#myButton').attr('disabled', true);
            } else {
                $('#myButton').attr('disabled', false);
                calculate_emi();
            }
        });

        function calculate_emi() {
            // console.log(emi_month);
            emi_month = (amount /  $('#emi_amount').val());
            $('#emi_month').html('<span>' + Math.trunc(emi_month) + '</span>*');
            $('#emi_month_around').val(Math.trunc(emi_month));
            $('#emi_amount_label').html('<span>&#8377;' + $('#emi_amount').val() + '</span>*');
        }

        function cal() {
            let min_val = Number($('#sampleSlider').val());
            var loan = @json($loan);

            // console.log(min_val);

            loan.forEach(element => {
                if (min_val == element.amount) {

                    amount = element.amount;
                    min_value = element.minimum_emi;
                    required_share = element.required_share;
                    interestAmount = (element.amount * interestRate) / 100;
                    emi_month = (element.amount / element.minimum_emi);

                    // console.log(emi_month+'<----EmiMonth');
                    // console.log(element.amount + '-->' + element.minimum_emi);
                }
            });
            console.log('min_value__' + min_value);

            $('#emi_amount').val(min_value);   //EMI Amount
            $('#required_share').html('<span>&#8377;' + required_share + '</span>*');   //required_share
            $('#myButton').attr('disabled', false);

            $('#comissionLabel').html('<span>&#8377;' + amount + '</span>*');        // Loan Amount
            $('#emi_month').html('<span>' + Math.trunc(emi_month) + '</span>*');     // EMI Month(Around)
            $('#emi_amount_label').html('<span>&#8377;' + min_value + '</span>*');   // EMI Amount
            $('#interest_payable').html('<span>&#8377;' + interestAmount + '</span>*');  // Interest Payable
            $('#min_amt').val(min_value);

            //hidden values
            $('#loan_amount').val(amount);
            $('#emi_month_around').val(Math.trunc(emi_month));
            $('#emi_amount_lab').val(min_value);
            $('#interest_pay').val(interestAmount);



            $('#amt_min').css("display", "block");


            // document.getElementById("emi_amount").setAttribute('value', min_value);
            // document.getElementById("comissionLabel").innerHTML = '<span>&#8377;' + amount + '</span>*';
            // document.getElementById("emi_month").innerHTML = '<span>' + Math.trunc(emi_month) + '</span>*';
            // document.getElementById("emi_amount_label").innerHTML = '<span>&#8377;' + min_value + '</span>*';
            // document.getElementById("interest_payable").innerHTML = '<span>&#8377;' + interestAmount + '</span>*';
            // document.getElementById("min_amt").setAttribute('value', min_value);
        };

        $(document).ready(function() {
            cal();
        });
    </script>
@endpush
