@extends('front.layouts.app')
@section('content')
    <style>

    </style>
    <div class="dashboard-detail-page calculator-detail-page">
        <div class="container">
            <div class="dashboard-box-listing">
                <div class="desh-listbox skybluebg-box wow fadeInDown" data-wow-delay="0.2s">
                    <div class="dash-box-title">Calculate your Personal Loan EMIs</div>
                </div>
                <div class="row wow fadeInUp" data-wow-delay="0.2s">
                    <div class="col-md-12">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>Loan Amount :</strong></label>
                            <div class="col-form-info">
                                <form action="/">
                                    <div id="comission" class="range-slider">
                                        <label class="final-value">&#8377; <span id="comissionLabel"></span></label>
                                        <input class="custom-range" id="loan_range" type="range" min="{{ $min_amount }}" max="{{ $max_amount }}" step="50000" oninput="cal()">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-12">
                    <div class="dashboard-detail-data">
                        <label class="col-form-label"><strong>Loan Duration :</strong></label>
                        <div class="col-form-info">
                            <form action="/">
                                <div id="mothsinput" class="range-slider">
                                    <label class="final-value"><span id="mothsinputLabel"></span> Months</label>
                                    <input class="custom-range" type="range" min="12" max="72" step="6">
                                </div>
                            </form>
                        </div>
                    </div>
                </div> --}}
                    <div class="col-md-12">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>EMI Amount :</strong></label>
                            <div class="col-form-info pt-0">
                                <div class="range-slider">
                                    <input type="number" class="emi-account" id="emi_amount" name="emi_amount">
                                </div>
                                <div class="min-amount" id="amt_min" style="color: red; display:none;">
                                    <label>*Minimum Amount:</label><input class="w3-input w3-border-0" type="text" id="min_amt" style="border: none;color: red;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="dashboard-detail-data">
                            <label class="col-form-label"><strong>Interest Rate :</strong></label>
                            <div class="col-form-info pt-0">
                                <div class="range-slider">
                                    <label class="final-value">{{ $loan_interest }} %</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5 justify-content-center skybluebg-box wow fadeInUp" data-wow-delay="0.2s">
                    <div class="col-md-8">
                        <div class="calculator-details">
                            <div class="calculator__info d-flex justify-content-between blue-text-dark">
                                <div class="calculator__info-text radius-12 text-center range-slider">
                                    <p class="f-12 mb-0">EMI Amount</p>
                                    <p id="emi_amount_label"><span>&#8377; 4,645</span>*</p>
                                </div>
                                <div class="calculator__info-text text-center">
                                    <p class="f-12 mb-0">Interest Payable</p>
                                    <p id="interest_payable"><span>&#8377; 11,469</span>*</p>
                                </div>
                            </div>
                            <div class="text-center"><a href="#" class="btn btn-primary w-100">Apply for Loan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        // RangeSlider Workaround
        const comission = document.querySelector('#comission input');
        const comissionLabel = document.getElementById('comissionLabel');
        const comissionLabelPrefix = comissionLabel.innerHTML;
        const comissionRange = document.createElement('label');

        comissionRange.id = 'rangeLimits';
        comissionRange.className = 'row';
        comissionRange.innerHTML =
            `<span class="col-6 text-start">&#8377; ${comission.getAttribute('min')}</span><span class="col-6 text-end">&#8377; ${comission.getAttribute('max')}</span>`;
        document.querySelector('#comission').appendChild(comissionRange);

        comissionLabel.innerHTML = `${comissionLabelPrefix}${comission.value}`;

        comission.addEventListener('input', function() {
            comissionLabel.innerHTML = `${comissionLabelPrefix}${Number(comission.value)}`;
        }, false);

        // mothsinputSlider Workaround
        const mothsinput = document.querySelector('#mothsinput input');
        const mothsinputLabel = document.getElementById('mothsinputLabel');
        const mothsinputLabelPrefix = mothsinputLabel.innerHTML;
        const mothsinputRange = document.createElement('label');

        mothsinputRange.id = 'rangeLimits';
        mothsinputRange.className = 'row';
        mothsinputRange.innerHTML =
            `<span class="col-6 text-start">${mothsinput.getAttribute('min')} Months</span><span class="col-6 text-end">${mothsinput.getAttribute('max')} Months</span>`;
        document.querySelector('#mothsinput').appendChild(mothsinputRange);

        mothsinputLabel.innerHTML = `${mothsinputLabelPrefix}${mothsinput.value}`;

        mothsinput.addEventListener('input', function() {
            mothsinputLabel.innerHTML = `${mothsinputLabelPrefix}${Number(mothsinput.value)}`;
        }, false);

        function numberWithCommas(number) {
            var parts = number.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        }
        $(document).ready(function() {
            $("#rangeLimits span, #comissionLabel").each(function() {
                var num = $(this).text();
                var commaNum = numberWithCommas(num);
                $(this).text(commaNum);
            });
        });
    </script>

    {{-- <script>
        document.getElementById("loan_range").addEventListener('input', function() {
            let min_val = Number(this.value, 10);
            var matrix = @json($matrix);
            let interestRate =Number('{{ $loan_interest}}');   // Interest rate
            let min_value;
            console.log(min_val);

            matrix.forEach(element => {
                if (min_val == element.amount) {
                    min_value = element.minimum_emi;

                    interestAmount = (element.amount * interestRate) / 100;

                    console.log(interestAmount);



                    console.log(element.amount + '-->' + element.minimum_emi);
                }
            });

            // if (min_val === 50000) {
            //     min_value = 1500;
            // } else if (min_val === 100000) {
            //     min_value = 3000;
            // } else if (min_val === 150000) {
            //     min_value = 3500;
            // } else {
            //     min_value = 6000;
            // }

            document.getElementById("emi_amount").setAttribute('value', min_value);
            document.getElementById("emi_amount_label").innerHTML ='<span>&#8377;'+min_value+'</span>*';
            document.getElementById("interest_payable").innerHTML ='<span>&#8377;'+interestAmount+'</span>*';
            document.getElementById("min_amt").setAttribute('value', min_value);
            $('#amt_min').css("display", "block");
        });
    </script> --}}




    <script>
        let interestRate = Number('{{ $loan_interest }}'); // Interest rate
        function cal() {
            let min_val = Number($('#loan_range').val());
            var matrix = @json($matrix);
            let min_value;
            console.log(min_val);

            matrix.forEach(element => {
                if (min_val == element.amount) {
                    min_value = element.minimum_emi;
                    interestAmount = (element.amount * interestRate) / 100;
                    // interestAmount = Number((element.amount * interestRate) / 100).toFixed(2);

                    console.log(interestAmount);
                    console.log(element.amount + '-->' + element.minimum_emi);
                }
            });

            // if (min_val === 50000) {
            //     min_value = 1500;
            // } else if (min_val === 100000) {
            //     min_value = 3000;
            // } else if (min_val === 150000) {
            //     min_value = 3500;
            // } else {
            //     min_value = 6000;
            // }

            document.getElementById("emi_amount").setAttribute('value', min_value);
            document.getElementById("emi_amount_label").innerHTML = '<span>&#8377;' + min_value + '</span>*';
            document.getElementById("interest_payable").innerHTML = '<span>&#8377;' + interestAmount + '</span>*';
            document.getElementById("min_amt").setAttribute('value', min_value);
            $('#amt_min').css("display", "block");
        };
        // A $( document ).ready() block.
        $(document).ready(function() {
            cal();
        });
    </script>

@endpush
