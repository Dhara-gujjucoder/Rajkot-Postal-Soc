@extends('front.layouts.app')
@section('content')
    <style>

    </style>
    <div class="dashboard-detail-page apply-detail-page">
    <div class="container">
        <div class="dashboard-box-listing">
            <div class="row wow fadeInRight" data-wow-delay="0.2s">
                <div class="col-md-12">
                    <div class="dashboard-detail-data mb-3 align-items-center">
                    <label class="col-form-label"><strong>Loan Amount</strong></label>
                    <div class="col-form-info pt-0"><input type="text" class="form-control" name="name"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="dashboard-detail-data mb-4 align-items-center">
                    <label class="col-form-label"><strong>Tenure (months)</strong></label>
                    <div class="col-form-info pt-0">
                        <select class="form-select" aria-label="Default select example">
                            <option selected value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>
                    </div>
                </div>
            </div>

            <div class="desh-listbox skybluebg-box wow fadeInRight" data-wow-delay="0.2s">
                <div class="dash-box-title">Personal Details</div>
            </div>
            <div class="row wow fadeInRight" data-wow-delay="0.2s">
                <div class="col-md-6">
                    <div class="dashboard-detail-data">
                    <label class="col-form-label"><strong>Name:</strong></label>
                    <div class="col-form-info">Calvin Talley</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-detail-data">
                    <label class="col-form-label"><strong>PAN Number:</strong></label>
                    <div class="col-form-info">CNEPS6645W</div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="dashboard-detail-data">
                    <label class="col-form-label"><strong>Present Address:</strong></label>
                    <div class="col-form-info">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Non provident hic saepe aliquid velit quaerat!</div>
                    </div>
                </div>                
            </div>

            <div class="desh-listbox skybluebg-box pt-2 mt-4 wow fadeInLeft" data-wow-delay="0.2s">
                <div class="dash-box-title">Details of other Liabilities</div>
            </div>
            <div class="row wow fadeInLeft" data-wow-delay="0.2s">
                <div class="col-md-6">
                    <div class="dashboard-detail-data">
                    <label class="col-form-label"><strong>Loans from Employer:</strong></label>
                    <div class="col-form-info">&#8377; 50,000</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-detail-data">
                    <label class="col-form-label"><strong>Materials/Assets purchased from the seller on credit:</strong></label>
                    <div class="col-form-info">&#8377; 30,000</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-detail-data">
                    <label class="col-form-label"><strong>Other liabilities:</strong></label>
                    <div class="col-form-info">&#8377; 30,000</div>
                    </div>
                </div>
                       
            </div>

            <div class="mt-5 justify-content-center skybluebg-box wow fadeInUp" data-wow-delay="0.2s">
                <div class="loan-details">
                    <div class="calculator__info d-flex justify-content-between blue-text-dark">
                        <div class="calculator__info-text radius-12 w-100">
                            <p class="info-text"><span id="emi_amt_pl">Lorem ipsum </span></p>
                            <ul>
                                <li>
                                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tempora, rem.</p>
                                </li>
                                <li>
                                    <p>Eveniet cupiditate blanditiis unde et inventore amet debitis pariatur porro facilis deserunt dolores officia, veritatis praesentium animi nobis sed. Ratione voluptates deleniti nihil, autem veniam ea?</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="text-center"><a href="#" class="btn btn-primary w-100">Eligible for Loan</a></div>
                </div>
            </div>

            <div class="mt-5 justify-content-center skybluebg-box wow fadeInUp" data-wow-delay="0.2s">
                <div class="loan-details loan-declined">
                    <div class="calculator__info d-flex justify-content-between blue-text-dark">
                        <div class="calculator__info-text radius-12 w-100">
                            <p class="info-text"><span id="emi_amt_pl">Lorem ipsum </span></p>
                            <ul>
                                <li>
                                    <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tempora, rem.</p>
                                </li>
                                <li>
                                    <p>Eveniet cupiditate blanditiis unde et inventore amet debitis pariatur porro facilis deserunt dolores officia, veritatis praesentium animi nobis sed. Ratione voluptates deleniti nihil, autem veniam ea?</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="text-center"><a href="#" class="btn btn-primary w-100">Not Eligible for Loan</a></div>
                </div>
            </div>
        </div>    
    </div>   
    </div>
@endsection
