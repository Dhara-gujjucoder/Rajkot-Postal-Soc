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
                                    <input class="custom-range" type="range" min="50000" max="5000000" step="25000">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
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
                </div>
                <div class="col-md-12">
                    <div class="dashboard-detail-data">
                        <label class="col-form-label"><strong>Interest Rate :</strong></label>
                        <div class="col-form-info pt-0">
                            <div class="range-slider">
                                <label class="final-value">8 %</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5 justify-content-center skybluebg-box wow fadeInUp" data-wow-delay="0.2s">
                <div class="col-md-8">
                    <div class="calculator-details">
                        <div class="calculator__info d-flex justify-content-between blue-text-dark">
                            <div class="calculator__info-text radius-12 text-center">
                                <p class="f-12 mb-0">EMI Amount</p>
                                <p><span>&#8377; 4,645</span>*</p>
                            </div>
                            <div class="calculator__info-text text-center">
                                <p class="f-12 mb-0">Interest Payable</p>
                                <p><span>&#8377; 11,469</span>*</p>
                            </div>
                        </div>
                        <div class="text-center"><a href="#" class="btn btn-primary w-100">Apply for Loan</a></div>
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
        comissionRange.innerHTML = `<span class="col-6 text-start">&#8377; ${comission.getAttribute('min')}</span><span class="col-6 text-end">&#8377; ${comission.getAttribute('max')}</span>`;
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
        mothsinputRange.innerHTML = `<span class="col-6 text-start">${mothsinput.getAttribute('min')} Months</span><span class="col-6 text-end">${mothsinput.getAttribute('max')} Months</span>`;
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
    @endpush

