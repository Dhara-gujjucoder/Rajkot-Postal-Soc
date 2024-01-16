@extends('front.layouts.app')
@section('content')
    <style>

    </style>
    <div class="dashboard-detail-page apply-detail-page">
    <div class="container">
        <div class="dashboard-box-listing">
            {{--<div class="row wow fadeInRight" data-wow-delay="0.2s">
                 <div class="col-md-12">
                    <div class="dashboard-detail-data mb-3 align-items-center">
                    <label class="col-form-label"><strong>{{ __('Loan Amount') }}</strong></label>
                    <div class="col-form-info pt-0"><input type="text" class="form-control" name="name"></div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="dashboard-detail-data mb-4 align-items-center">
                    <label class="col-form-label"><strong>{{ __('Tenure (months)') }}</strong></label>
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
            </div>--}}

            <div class="desh-listbox skybluebg-box wow fadeInDown" data-wow-delay="0.2s">
                <div class="dash-box-title">{{ __('Personal Details') }}</div>
            </div>

            <div class="row wow fadeInUp" data-wow-delay="0.2s">
                <div class="col-md-6">
                    <div class="dashboard-detail-data">
                    <label class="col-form-label"><strong>{{ __('Name') }}:</strong></label>
                    <div class="col-form-info">{{ $user->name }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-detail-data">
                    <label class="col-form-label"><strong>{{ __('PAN Number') }}:</strong></label>
                    <div class="col-form-info">{{ $member->pan_no }}</div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="dashboard-detail-data">
                    <label class="col-form-label"><strong>{{ __('Present Address') }}:</strong></label>
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
                    <label class="col-form-label"><strong>{{ __('Current loan') }}:</strong></label>
                    <div class="col-form-info">&#8377; 0</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-detail-data">
                    <label class="col-form-label"><strong>{{ __('Remaining Loan') }}:</strong></label>
                    <div class="col-form-info">&#8377; 0</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-detail-data">
                    <label class="col-form-label"><strong>{{ __('Share') }}:</strong></label>
                    <div class="col-form-info">&#8377; 0</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-detail-data">
                    <label class="col-form-label"><strong>{{ __('Required share') }}:</strong></label>
                    <div class="col-form-info" id="required_share">&#8377; 0</div>
                    </div>
                </div>

            </div>

            <div class="desh-listbox skybluebg-box wow fadeInRight" data-wow-delay="0.2s">
                <div class="dash-box-title">Your Loan Detail</div>
            </div>
            <div class="row wow fadeInLeft" data-wow-delay="0.2s">
                coming soon ....
            </div>

            {{-- <div class="mt-5 justify-content-center skybluebg-box wow fadeInUp" data-wow-delay="0.2s">
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
                    <div class="text-center"><a href="#" class="btn btn-primary w-100">{{ __('Eligible for Loan') }}</a></div>
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
                    <div class="text-center"><a href="#" class="btn btn-primary w-100">{{ __('Not Eligible for Loan') }}</a></div>
                </div>
            </div> --}}
        </div>
    </div>
    </div>
@endsection
